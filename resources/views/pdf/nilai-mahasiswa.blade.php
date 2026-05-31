<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nilai Mahasiswa</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2, p {
            margin: 0 0 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Data Nilai Mahasiswa</h2>
    <p>Dosen: {{ $dosen->nama }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>Semester</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->mahasiswa?->nim }}</td>
                    <td>{{ $item->mahasiswa?->nama }}</td>
                    <td>{{ $item->kelas?->kode }}</td>
                    <td>{{ $item->kelas?->matakuliah?->nama }}</td>
                    <td>{{ $item->kelas?->semester }}</td>
                    <td class="text-center">{{ $item->nilai_akhir ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>