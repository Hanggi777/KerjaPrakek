<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .header {
            width: 100%;
            margin-bottom: 25px;
        }

        .logo {
            float: left;
            width: 90px;
        }

        .company {
            margin-left: 110px;
        }

        .company h1 {
            margin: 0;
            font-size: 28px;
            color: #0f6b3d;
        }

        .company p {
            margin: 3px 0;
            color: #555;
            line-height: 1.5;
        }

        .title {
            text-align: center;
            margin: 25px 0;
        }

        .title h2 {
            margin: 0;
            color: #0f6b3d;
            font-size: 22px;
        }

        .title hr {
            border: none;
            border-top: 2px solid #0f6b3d;
            margin-top: 10px;
        }

        .info {
            width: 100%;
            margin-bottom: 20px;
        }

        .left {
            width: 48%;
            float: left;
        }

        .right {
            width: 48%;
            float: right;
        }

        .box {
            border: 1px solid #cfd8d3;
            padding: 12px;
            border-radius: 5px;
        }

        .box b {
            color: #0f6b3d;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #0f6b3d;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .total {
            width: 40%;
            float: right;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .total td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .total tr:last-child {
            background: #eaf6ef;
            font-weight: bold;
        }

        .footer {
            margin-top: 70px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer hr {
            border: none;
            border-top: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .clear {
            clear: both;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            background: #198754;
            color: #fff;
            border-radius: 20px;
            font-size: 11px;
        }
    </style>

</head>

<body>

    <div class="header">

        {{-- Jika punya logo aktifkan ini --}}
        {{-- <img src="{{ public_path('assets/logo-nabilla.png') }}" class="logo"> --}}

        <div class="company">

            <h1>NABILLA CATERING</h1>

            <p><strong>Catering Only</strong></p>

            <p>
                Jl. Betung Raya No. A16, Pondok Bambu,<br>
                Jakarta Timur, Jakarta, Indonesia 13430
            </p>

            <p>
                <strong>Telp / WhatsApp :</strong><br>
                0821-1361-2300 &nbsp; | &nbsp;
                0813-1566-6613
            </p>

        </div>

    </div>

    <div class="clear"></div>

    <div class="title">

        <h2>INVOICE PENJUALAN</h2>

        <hr>

    </div>

    <div class="info">

        <div class="left">

            <div class="box">

                <b>DATA KLIEN</b>

                <hr>

                <strong>Nama</strong><br>
                {{ $transaksi->klien->nama_klien }}

                <br><br>

                <strong>Email</strong><br>
                {{ $transaksi->klien->email }}

                <br><br>

                <strong>No. HP</strong><br>
                {{ $transaksi->klien->no_hp }}

            </div>

        </div>

        <div class="right">

            <div class="box">

                <b>INFORMASI INVOICE</b>

                <hr>

                <strong>No. Invoice</strong><br>
                {{ $transaksi->kode_transaksi }}

                <br><br>

                <strong>Tanggal</strong><br>
                {{ $transaksi->tanggal_transaksi->format('d F Y') }}

                <br><br>

                <strong>Sales</strong><br>
                {{ $transaksi->sales->name }}

                <br><br>

                <strong>Status</strong><br>

                <span class="status">

                    {{ strtoupper($transaksi->status_label) }}

                </span>

            </div>

        </div>

    </div>

    <div class="clear"></div>

    <table class="table">

        <thead>

            <tr>

                <th width="6%">No</th>

                <th>Item</th>

                <th width="12%">Qty</th>

                <th width="20%">Harga</th>

                <th width="22%">Subtotal</th>

            </tr>

        </thead>

        <tbody>

            @foreach($transaksi->detail as $detail)

            <tr>

                <td align="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $detail->nama_item }}
                </td>

                <td align="center">
                    {{ $detail->qty }}
                </td>

                <td align="right">
                    Rp {{ number_format($detail->harga_satuan,0,',','.') }}
                </td>

                <td align="right">
                    Rp {{ number_format($detail->subtotal,0,',','.') }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <table class="total">

        <tr>

            <td>Subtotal</td>

            <td align="right">
                Rp {{ number_format($transaksi->subtotal,0,',','.') }}
            </td>

        </tr>

        <tr>

            <td>Diskon</td>

            <td align="right">
                Rp {{ number_format($transaksi->diskon,0,',','.') }}
            </td>

        </tr>

        <tr>

            <td>Total</td>

            <td align="right">
                Rp {{ number_format($transaksi->total_penawaran,0,',','.') }}
            </td>

        </tr>

        <tr>

            <td>DP</td>

            <td align="right">
                Rp {{ number_format($transaksi->nominal_dp,0,',','.') }}
            </td>

        </tr>

        <tr>

            <td>Sisa Pelunasan</td>

            <td align="right">
                Rp {{ number_format($transaksi->sisa_pelunasan,0,',','.') }}
            </td>

        </tr>

    </table>

    <div class="clear"></div>

    <div class="footer">

        <hr>

        <h3 style="color:#0f6b3d;">Terima Kasih</h3>

        <p>
            Terima kasih telah mempercayakan kebutuhan catering Anda kepada
            <strong>NABILLA CATERING</strong>.
        </p>

        <br><br><br>

        _______________________________

        <br>

        <strong>NABILLA CATERING</strong>

    </div>

</body>

</html>