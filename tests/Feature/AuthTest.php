<?php

namespace Tests\Feature;

use App\Models\Klien;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('sales');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('klien', function (Blueprint $table) {
            $table->id();
            $table->string('nama_klien');
            $table->string('email')->unique();
            $table->string('no_hp');
            $table->text('alamat');
            $table->string('nama_perusahaan')->nullable();
            $table->string('password');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('klien');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_client_can_login_from_main_login_page()
    {
        $klien = Klien::create([
            'nama_klien' => 'Client Test',
            'email' => 'client@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Bandung',
            'nama_perusahaan' => 'PT Test',
            'password' => Hash::make('password123'),
            'status_aktif' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard.klien'));
        $this->assertTrue(Auth::guard('klien')->check());
        $this->assertSame($klien->id, Auth::guard('klien')->id());
    }
}
