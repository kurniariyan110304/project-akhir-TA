<div style="padding: 8px 0;">
    <div style="margin-bottom: 16px; padding: 14px; border: 1px solid #d1d5db; border-radius: 10px; background: #f9fafb;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 160px; font-weight: 600; padding: 6px 0;">Nama Project</td>
                <td style="padding: 6px 0;">: {{ $project?->nama_project ?? '-' }}</td>
            </tr>
            <tr>
                <td style="width: 160px; font-weight: 600; padding: 6px 0;">Nama Kelompok</td>
                <td style="padding: 6px 0;">: {{ $project?->nama_kelompok ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #9ca3af; font-size: 14px;">
            <thead>
                <tr style="background: #f3f4f6;">
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">No</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">NIM</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Nama Anggota</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Peran</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Status</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Nilai</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($anggotas as $anggota)
                    <tr>
                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $anggota->mahasiswa?->nim ?? '-' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $anggota->mahasiswa?->nama ?? '-' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $anggota->peran ?? '-' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $anggota->aktif ? 'Aktif' : 'Tidak Aktif' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $anggota->nilai ?? 0 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="border: 1px solid #9ca3af; padding: 16px; text-align: center; color: #6b7280;">
                            Belum ada anggota kelompok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>