<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Ultrasonic Testing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h3 {
            font-size: 14px;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #999;
            color: #000;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 35%;
            font-weight: bold;
            padding-right: 10px;
        }

        .info-value {
            width: 65%;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-validated {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-accepted {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #000;
            font-size: 11px;
            text-align: center;
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            font-size: 12px;
        }

        .signature-line {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>LAPORAN HASIL PENGUJIAN ULTRASONIC (UT)</h1>
            <p>Sistem Informasi Inspeksi Kapal berbasis NDT</p>
            <p>{{ now()->format('d Maret Y') }}</p>
        </div>

        <!-- INFO INSPEKSI -->
        <div class="info-section">
            <h3>I. INFORMASI INSPEKSI</h3>
            <div class="info-row">
                <div class="info-label">ID Inspeksi</div>
                <div class="info-value"><strong>{{ $inspeksi->id_inspeksi }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Kapal</div>
                <div class="info-value">{{ $inspeksi->jenis_kapal ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Area Kapal</div>
                <div class="info-value">{{ $inspeksi->area_kapal ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Validasi</div>
                <div class="info-value">
                    <span class="status-badge status-validated">✓ TERVALIDASI</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Validasi</div>
                <div class="info-value">
                    @if ($inspeksi->validated_at)
                        {{ \Carbon\Carbon::parse($inspeksi->validated_at)->format('d/m/Y H:i:s') }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>

        <!-- DATA PENGUJIAN ULTRASONIC -->
        <div class="info-section">
            <h3>II. DATA PENGUJIAN ULTRASONIC</h3>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ketebalan Desain Awal (t₀)</td>
                        <td>{{ $inspeksi->t_origin ?? '-' }}</td>
                        <td>mm</td>
                    </tr>
                    <tr>
                        <td>Nilai Ketebalan Hasil Ukur</td>
                        <td>{{ $inspeksi->nilai_ketebalan ?? '-' }}</td>
                        <td>mm</td>
                    </tr>
                    <tr>
                        <td>Batas Standar Minimum</td>
                        <td>{{ $inspeksi->batas_standar ?? '-' }}</td>
                        <td>mm</td>
                    </tr>
                    <tr>
                        <td>Metode Perhitungan</td>
                        <td>{{ $inspeksi->metode_perhitungan ?? '-' }}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Frekuensi UT</td>
                        <td>{{ $inspeksi->frekuensi_ut ?? '-' }}</td>
                        <td>MHz</td>
                    </tr>
                    <tr>
                        <td>Level Pengujian (ISO 17640)</td>
                        <td>{{ $inspeksi->level_pengujian ?? '-' }}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Kelas Area</td>
                        <td>{{ $inspeksi->kelas_area ?? '-' }}</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- HASIL ANALISIS -->
        <div class="info-section">
            <h3>III. HASIL ANALISIS</h3>
            <table>
                <thead>
                    <tr>
                        <th>Aspek</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jenis Cacat</td>
                        <td>{{ $inspeksi->jenis_cacat ?? 'Tidak ada cacat terdeteksi' }}</td>
                    </tr>
                    <tr>
                        <td>Kedalaman Cacat</td>
                        <td>{{ $inspeksi->kedalaman_cacat ? $inspeksi->kedalaman_cacat . ' mm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Panjang Cacat</td>
                        <td>{{ $inspeksi->panjang_cacat ? $inspeksi->panjang_cacat . ' mm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Amplitudo Gema</td>
                        <td>{{ $inspeksi->echo_amplitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Persentase Penipisan</td>
                        <td>{{ $inspeksi->persentase_penipisan ? $inspeksi->persentase_penipisan . '%' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status Ketebalan</td>
                        <td>
                            @if ($inspeksi->status_ketebalan === 'sesuai')
                                <span class="status-badge status-accepted">✓ SESUAI</span>
                            @elseif ($inspeksi->status_ketebalan === 'tidak_sesuai')
                                <span class="status-badge status-rejected">✗ TIDAK SESUAI</span>
                            @else
                                {{ $inspeksi->status_ketebalan ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Klasifikasi Cacat</td>
                        <td>{{ $inspeksi->klasifikasi_cacat ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Status Akseptansi</td>
                        <td>
                            <span class="status-badge status-accepted">
                                @if ($inspeksi->status_akseptansi === 'diterima')
                                    ✓ DITERIMA
                                @elseif ($inspeksi->status_akseptansi === 'ditolak')
                                    ✗ DITOLAK
                                @else
                                    {{ $inspeksi->status_akseptansi ?? 'PENDING' }}
                                @endif
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- CATATAN -->
        @if ($inspeksi->catatan_validasi)
        <div class="info-section">
            <h3>IV. CATATAN VALIDASI</h3>
            <p style="background-color: #f9f9f9; padding: 10px; border-left: 3px solid #999;">
                {{ $inspeksi->catatan_validasi }}
            </p>
        </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <p>Laporan ini adalah dokumen resmi hasil pengujian Non-Destructive Testing (NDT) menggunakan metode Ultrasonic Testing (UT).</p>
            <p style="margin-top: 15px; font-size: 10px; color: #999;">
                Dihasilkan otomatis oleh Sistem Informasi Inspeksi Kapal berbasis NDT<br>
                Tanggal Generate: {{ $tanggal_generate }}
            </p>
        </div>
    </div>
</body>
</html>
