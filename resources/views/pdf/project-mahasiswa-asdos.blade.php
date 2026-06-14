<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Project Mahasiswa Asdos</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h2, h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 15px;
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

        .text-right {
            text-align: right;
        }

        .project-title {
            margin-top: 12px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Project Mahasiswa</h2>
        <h3>Panel Asisten Dosen</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="18%"><strong>Nama Asdos</strong></td>
                <td width="2%">:</td>
                <td>
                    {{ $asdos->mahasiswa->nama ?? auth()->user()->name ?? '-' }}
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal Export</strong></td>
                <td>:</td>
                <td>{{ now()->format('d-m-Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="18%">Nama Project</th>
                <th width="10%">Tipe Tugas</th>
                <th width="15%">Nama Kelompok</th>
                <th width="13%">Kelas</th>
                <th width="15%">Mata Kuliah</th>
                <th width="10%">NIM</th>
                <th width="15%">Nama Mahasiswa</th>
                <th width="8%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $project)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $project->nama_project ?? '-' }}</td>
                    <td class="text-center">{{ $project->tugas->kategori ?? '-' }}</td>
                    <td>{{ $project->nama_kelompok ?? '-' }}</td>
                    <td>{{ $project->tugas->kelas->kode ?? '-' }}</td>
                    <td>{{ $project->tugas->kelas->matakuliah->nama ?? '-' }}</td>
                    <td>{{ $project->mahasiswa_nim ?? '-' }}</td>
                    <td>{{ $project->mahasiswa->nama ?? '-' }}</td>
                    <td class="text-center">{{ $project->nilai_akhir ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Data project mahasiswa tidak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Detail Anggota Kelompok</h3>

    @forelse ($data as $project)
        @if (($project->tugas->kategori ?? null) === 'KELOMPOK')
            <div class="project-title">
                Project: {{ $project->nama_project ?? '-' }}
                |
                Kelompok: {{ $project->nama_kelompok ?? '-' }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">NIM</th>
                        <th width="35%">Nama Mahasiswa</th>
                        <th width="18%">Peran</th>
                        <th width="12%">Nilai Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($project->anggotaKelompok as $i => $anggota)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $anggota->mahasiswa_nim ?? '-' }}</td>
                            <td>{{ $anggota->mahasiswa->nama ?? '-' }}</td>
                            <td class="text-center">{{ $anggota->peran ?? '-' }}</td>
                            <td class="text-center">{{ $anggota->nilai ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Anggota kelompok tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    @empty
        <p>Data anggota kelompok tidak tersedia.</p>
    @endforelse

</body>
</html>