<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai Mahasiswa Dosen</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        h2, h3 {
            margin: 0;
            padding: 0;
        }

        .info {
            margin-bottom: 14px;
        }

        .info table {
            width: 100%;
            border: none;
        }

        .info td {
            border: none;
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Nilai Mahasiswa</h2>
        <h3>Panel Dosen</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="18%"><strong>Nama Dosen</strong></td>
                <td width="2%">:</td>
                <td>{{ $dosen->nama ?? auth()->user()->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Export</strong></td>
                <td>:</td>
                <td>{{ now()->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="11%">Kode Kelas</th>
                <th width="18%">Mata Kuliah</th>
                <th width="8%">Semester</th>
                <th width="12%">NIM</th>
                <th width="28%">Nama Mahasiswa</th>
                <th width="12%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kelas->kode ?? '-' }}</td>
                    <td>{{ $item->kelas->matakuliah->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->kelas->semester ?? '-' }}</td>
                    <td>{{ $item->mahasiswa_nim ?? '-' }}</td>
                    <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->nilai_akhir ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Data nilai mahasiswa tidak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>