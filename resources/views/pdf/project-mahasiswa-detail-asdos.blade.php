<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Project Mahasiswa Asdos</title>

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
            padding: 6px;
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
            padding: 8px;
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
    <h2>Detail Project Mahasiswa</h2>

    <div class="subtitle">
        Asdos: {{ $asdos?->mahasiswa?->nama ?? $asdos?->user?->name ?? '-' }}
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Project</td>
            <td>: {{ $project?->nama_project ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Tipe Tugas</td>
            <td>: {{ $project?->tugas?->kategori ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Nama Kelompok</td>
            <td>: {{ $project?->nama_kelompok ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Kelas</td>
            <td>: {{ $project?->tugas?->kelas?->kode ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Mata Kuliah</td>
            <td>: {{ $project?->tugas?->kelas?->matakuliah?->nama ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Link URL</td>
            <td>: {{ $project?->link_url ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Link Video</td>
            <td>: {{ $project?->link_video ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Nilai Akhir Project</td>
            <td>: {{ $project?->nilai_akhir ?? 0 }}</td>
        </tr>
    </table>

    @if ($project?->tugas?->kategori === 'KELOMPOK')
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>NIM</th>
                    <th>Nama Anggota</th>
                    <th>Peran</th>
                    <th>Status</th>
                    <th>Nilai Anggota</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($anggotas as $anggota)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $anggota->mahasiswa?->nim ?? '-' }}</td>
                        <td>{{ $anggota->mahasiswa?->nama ?? '-' }}</td>
                        <td>{{ $anggota->peran ?? '-' }}</td>
                        <td>{{ $anggota->aktif ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>{{ $anggota->nilai ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            Belum ada anggota kelompok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Email</th>
                    <th>Nilai Project</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $project?->mahasiswa?->nim ?? '-' }}</td>
                    <td>{{ $project?->mahasiswa?->nama ?? '-' }}</td>
                    <td>{{ $project?->mahasiswa?->email ?? '-' }}</td>
                    <td>{{ $project?->nilai_akhir ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>