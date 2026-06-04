<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kelompok Project Asdos</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h2 {
            margin-bottom: 4px;
        }

        .subtitle {
            margin-bottom: 18px;
            color: #4b5563;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 5px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #6b7280;
            padding: 7px;
            text-align: left;
        }

        .main-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <h2>Data Kelompok Project</h2>

    <div class="subtitle">
        Panel Asdos
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Asdos</td>
            <td>: {{ $asdos?->mahasiswa?->nama ?? $asdos?->user?->name ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">NIM Asdos</td>
            <td>: {{ $asdos?->mahasiswa_nim ?? $asdos?->user?->nim ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Email</td>
            <td>: {{ $asdos?->user?->email ?? '-' }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Nama Project</th>
                <th>Tipe Tugas</th>
                <th>Nama Kelompok</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>NIM</th>
                <th>Nama Anggota</th>
                <th>Peran</th>
                <th>Status</th>
                <th>Nilai</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->project?->nama_project ?? '-' }}</td>
                    <td>{{ $item->project?->tugas?->kategori ?? '-' }}</td>
                    <td>{{ $item->project?->nama_kelompok ?? '-' }}</td>
                    <td>{{ $item->project?->tugas?->kelas?->kode ?? '-' }}</td>
                    <td>{{ $item->project?->tugas?->kelas?->matakuliah?->nama ?? '-' }}</td>
                    <td>{{ $item->mahasiswa?->nim ?? '-' }}</td>
                    <td>{{ $item->mahasiswa?->nama ?? '-' }}</td>
                    <td>{{ $item->peran ?? '-' }}</td>
                    <td>{{ $item->aktif ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>{{ $item->nilai ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">
                        Tidak ada data kelompok project.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>