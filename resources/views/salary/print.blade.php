<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji - {{ $employee->name }} - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #0d6efd;
            --text-primary: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            /* Latar belakang halaman (non-print) */
            color: var(--text-primary);
            font-size: 14px;
            /* Ukuran font dasar */
        }

        .print-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        }

        /*
        * ==================
        * Desain Header
        * ==================
        */
        .slip-header {
            padding: 2.5rem;
            border-bottom: 2px dashed var(--border-color);
        }

        .slip-header .company-logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--brand-primary);
        }

        .slip-header .company-address {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .slip-header .slip-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /*
        * ==================
        * Detail Karyawan
        * ==================
        */
        .employee-details {
            padding: 2.5rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-item .label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .detail-item .value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /*
        * ==================
        * Detail Gaji (Tabel)
        * ==================
        */
        .salary-details {
            padding: 0 2.5rem 2.5rem;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 0.5rem;
            /* Sudut melengkung untuk tabel */
        }

        .table thead th {
            background-color: #f8f9fa;
            /* Latar header tabel */
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 2px solid var(--border-color);
        }

        .table tbody tr:not(:last-child) {
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table .text-end {
            font-family: monospace;
            /* Font konsisten untuk angka */
            font-size: 0.95rem;
        }

        .table tfoot tr {
            background-color: #f8f9fa;
        }

        .table tfoot .total-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .table tfoot .total-amount {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brand-primary);
            font-family: monospace;
        }

        /*
        * ==================
        * Footer Tanda Tangan
        * ==================
        */
        .slip-footer {
            padding: 2.5rem;
            border-top: 1px solid var(--border-color);
        }

        .signature-box {
            text-align: right;
        }

        .signature-box .date {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .signature-box .role {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            color: var(--text-muted);
        }

        .signature-box .signature-line {
            margin-top: 4rem;
            font-weight: 600;
            border-top: 1px solid var(--text-primary);
            display: inline-block;
            padding-top: 0.5rem;
        }

        /*
        * ==================
        * Aturan Cetak
        * ==================
        */
        @media print {
            body {
                background-color: #ffffff;
            }

            @page {
                size: A4 portrait;
                margin: 20mm 15mm 20mm 15mm;
            }

            .print-container {
                max-width: 100%;
                margin: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="slip-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="company-logo">CV. Rangga Soly</div>
                    <div class="company-address">Sistem Penggajian Karyawan</div>
                </div>
                <div class="col-6 text-end">
                    <h4 class="slip-title mb-0">Slip Gaji Karyawan</h4>
                </div>
            </div>
        </div>

        <div class="employee-details">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="label">Nama Karyawan</span>
                    <span class="value">{{ $employee->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">NIK / Nomor Pegawai</span>
                    <span class="value">{{ $employee->nomor_pegawai }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Penempatan</span>
                    <span class="value">{{ $employee->penempatan }}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Periode Gaji</span>
                    <span class="value">{{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="salary-details">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Deskripsi</th>
                        <th scope="col" class="text-center">Jumlah Hari</th>
                        <th scope="col" class="text-end">Tarif / Hari</th>
                        <th scope="col" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hadir</td>
                        <td class="text-center">{{ $count['hadir'] }}</td>
                        <td class="text-end">Rp {{ number_format($setting->present_rate, 0, ',', '.') }}</td>
                        <td class="text-end">Rp
                            {{ number_format($count['hadir'] * $setting->present_rate, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Sakit</td>
                        <td class="text-center">{{ $count['sakit'] }}</td>
                        <td class="text-end">Rp {{ number_format($setting->sick_rate, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($count['sakit'] * $setting->sick_rate, 0, ',', '.') }}
                        </td>
                    </tr>

                    @php
                        $nonHadirCount = $count['tidak_hadir'] + $count['cuti'] + $count['izin'] + $count['terlambat'];
                    @endphp
                    <tr>
                        <td>Tidak Hadir (Cuti/Izin/Lainnya)</td>
                        <td class="text-center">{{ $nonHadirCount }}</td>
                        <td class="text-end">Rp {{ number_format($setting->absent_rate, 0, ',', '.') }}</td>
                        <td class="text-end">Rp
                            {{ number_format($nonHadirCount * $setting->absent_rate, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end total-label">Total Gaji Diterima</td>
                        <td class="text-end total-amount">Rp {{ number_format($totalSalary, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="slip-footer">
            <div class="signature-box">
                <div class="date">Medan, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="role">Manager HRD</div>
                <div class="signature-line">( ______________________ )</div>
            </div>
        </div>
    </div>

    <script>
        // Otomatis memicu dialog cetak
        window.print();
    </script>
</body>

</html>
