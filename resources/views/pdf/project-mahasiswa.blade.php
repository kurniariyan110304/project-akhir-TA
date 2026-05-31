<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Project Mahasiswa</title>
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
    <h2>Data Project Mahasiswa</h2>
    <p>Dosen: {{ $dosen->nama }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Mahasiswa</th>
                <th>Project</th>
                <th>Kelompok</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>Link URL</th>
                <th>Link Video</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->mahasiswa?->nim }}</td>
                    <td>{{ $item->mahasiswa?->nama }}</td>
                    <td>{{ $item->nama_project }}</td>
                    <td>{{ $item->nama_kelompok ?? '-' }}</td>
                    <td>{{ $item->tugas?->kelas?->kode }}</td>
                    <td>{{ $item->tugas?->kelas?->matakuliah?->nama }}</td>
                    <td>{{ $item->link_url ?? '-' }}</td>
                    <td>{{ $item->link_video ?? '-' }}</td>
                    <td class="text-center">{{ $item->nilai_akhir ?? '-' }}</td>
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