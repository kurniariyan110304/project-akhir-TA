<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kelompok Project</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
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
            padding: 5px;
            text-align: left;
            vertical-align: top;
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
    <h2>Data Kelompok Project</h2>
    <p>Dosen: {{ $dosen->nama }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Project</th>
                <th>Kelompok</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>NIM</th>
                <th>Mahasiswa</th>
                <th>Peran</th>
                <th>Status</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->project?->nama_project }}</td>
                    <td>{{ $item->project?->nama_kelompok ?? '-' }}</td>
                    <td>{{ $item->project?->tugas?->kelas?->kode }}</td>
                    <td>{{ $item->project?->tugas?->kelas?->matakuliah?->nama }}</td>
                    <td>{{ $item->mahasiswa?->nim }}</td>
                    <td>{{ $item->mahasiswa?->nama }}</td>
                    <td>{{ $item->peran }}</td>
                    <td>{{ $item->aktif ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td class="text-center">{{ $item->nilai ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>