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
            margin-bottom: 20px;
        }

        .logo {
            float: left;
            width: 90px;
        }

        .company {
            margin-left: 110px;
        }

        .company h1 {
            color: #0d6b43;
            margin: 0;
            font-size: 28px;
        }

        .company p {
            margin: 2px 0;
            color: #666;
        }

        .title {
            text-align: center;
            margin: 30px 0;
        }

        .title h2 {
            margin: 0;
            color: #0d6b43;
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
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #0d6b43;
            color: white;
            padding: 10px;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .footer {
            margin-top: 70px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .clear {
            clear: both;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            color: white;
            border-radius: 20px;
            font-weight: bold;
        }

        .success {
            background: #16a34a;
        }

        .pending {
            background: #f59e0b;
        }

        .failed {
            background: #dc2626;
        }
    </style>

</head>

<body>

<div class="header">

    {{-- Logo --}}
    {{-- <img src="{{ public_path('assets/logo-nabilla.png') }}" class="logo"> --}}

    <div class="company">

            <h1>NABILLA CATERING</h1>

            <p>Wedding • Aqiqah • Prasmanan • Event</p>


        <p style="margin-top:8px;">
            Jl. Betung Raya No. A16, Pondok Bambu,<br>
            Jakarta Timur, Jakarta, Indonesia 13430
        </p>

        <p style="margin-top:8px;">
            <b>Telp / WhatsApp</b><br>
            0821-1361-2300 | 0813-1566-6613
        </p>

    </div>

</div>

<div class="clear"></div>

<div class="title">

    <h2>BUKTI PEMBAYARAN</h2>

    <hr>

</div>

<div class="info">

    <div class="left">

        <div class="box">

            <b>Data Klien</b>

            <hr>

            Nama :
            {{ $pembayaran->transaksi->klien->nama_klien }}

            <br><br>

            Email :
            {{ $pembayaran->transaksi->klien->email }}

            <br><br>

            No HP :
            {{ $pembayaran->transaksi->klien->no_hp }}

        </div>

    </div>

    <div class="right">

        <div class="box">

            <b>Informasi Pembayaran</b>

            <hr>

            No Pembayaran :
            {{ $pembayaran->kode_pembayaran }}

            <br><br>

            No Transaksi :
            {{ $pembayaran->transaksi->kode_transaksi }}

            <br><br>

            Tanggal Bayar :
            {{ optional($pembayaran->tanggal_bayar)->format('d F Y H:i') }}

            <br><br>

            Status :

            @php
                $class = 'pending';

                if($pembayaran->status_pembayaran=='berhasil'){
                    $class='success';
                }

                if($pembayaran->status_pembayaran=='gagal'){
                    $class='failed';
                }
            @endphp

            <span class="status {{ $class }}">

                {{ strtoupper($pembayaran->status_pembayaran) }}

            </span>

        </div>

    </div>

</div>

<div class="clear"></div>

<table class="table">

    <thead>

        <tr>

            <th>Jenis Pembayaran</th>

            <th>Metode</th>

            <th>Nominal</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td align="center">

                {{ strtoupper($pembayaran->jenis_pembayaran) }}

            </td>

            <td align="center">

                {{ $pembayaran->metode_pembayaran }}

            </td>

            <td align="right">

                Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}

            </td>

        </tr>

    </tbody>

</table>

<br>

<table width="100%" style="border-collapse:collapse">

    <tr>

        <td width="70%"></td>

        <td>

            <table style="width:100%;border-collapse:collapse">

                <tr>

                    <td><b>Total Dibayar</b></td>

                    <td align="right">

                        <b>

                            Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}

                        </b>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

@if($pembayaran->catatan)

<br><br>

<b>Catatan :</b>

<br>

{{ $pembayaran->catatan }}

@endif

<div class="footer">

    <hr>

    <h3>Terima Kasih</h3>

    <p>

        Bukti pembayaran ini dibuat secara otomatis oleh sistem
        <b>NABILLA CATERING</b>.

    </p>

    <br><br><br>

    __________________________

    <br>

    NABILLA CATERING

</div>

</body>

</html>