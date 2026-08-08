<?php

namespace Tests\Feature;

use App\Models\Klien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KlienControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_only_sees_clients_created_by_them(): void
    {
        $salesA = User::factory()->create(['role' => 'sales']);
        $salesB = User::factory()->create(['role' => 'sales']);

        $clientA = Klien::create([
            'nama_klien' => 'Klien A',
            'email' => 'klien-a@example.com',
            'no_hp' => '081111111111',
            'alamat' => 'Alamat A',
            'password' => Hash::make('password123'),
            'sales_id' => $salesA->id,
        ]);

        $clientB = Klien::create([
            'nama_klien' => 'Klien B',
            'email' => 'klien-b@example.com',
            'no_hp' => '082222222222',
            'alamat' => 'Alamat B',
            'password' => Hash::make('password123'),
            'sales_id' => $salesB->id,
        ]);

        $response = $this->actingAs($salesA)->get(route('klien.index'));

        $response->assertOk();
        $response->assertSee($clientA->nama_klien);
        $response->assertDontSee($clientB->nama_klien);

        $response = $this->actingAs($salesA)->get(route('klien.show', $clientB));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_change_sales_owner_of_client(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $sales = User::factory()->create(['role' => 'sales']);
        $client = Klien::create([
            'nama_klien' => 'Klien C',
            'email' => 'klien-c@example.com',
            'no_hp' => '083333333333',
            'alamat' => 'Alamat C',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($superadmin)->put(route('klien.update', $client), [
            'nama_klien' => $client->nama_klien,
            'email' => $client->email,
            'telepon' => $client->no_hp,
            'alamat' => $client->alamat,
            'sales_id' => $sales->id,
        ]);

        $response->assertRedirect();
        $client->refresh();
        $this->assertSame($sales->id, $client->sales_id);
    }

    public function test_sales_only_sees_their_own_clients_when_creating_transaction(): void
    {
        $salesA = User::factory()->create(['role' => 'sales']);
        $salesB = User::factory()->create(['role' => 'sales']);

        $clientA = Klien::create([
            'nama_klien' => 'Klien A',
            'email' => 'klien-a2@example.com',
            'no_hp' => '081111111112',
            'alamat' => 'Alamat A',
            'password' => Hash::make('password123'),
            'sales_id' => $salesA->id,
        ]);

        Klien::create([
            'nama_klien' => 'Klien B',
            'email' => 'klien-b2@example.com',
            'no_hp' => '082222222223',
            'alamat' => 'Alamat B',
            'password' => Hash::make('password123'),
            'sales_id' => $salesB->id,
        ]);

        $response = $this->actingAs($salesA)->get(route('transaksi.create'));

        $response->assertOk();
        $response->assertSee($clientA->nama_klien);
        $response->assertDontSee('Klien B');
    }
}
