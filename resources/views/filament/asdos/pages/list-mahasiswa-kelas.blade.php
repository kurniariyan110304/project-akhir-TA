<div style="padding: 8px 0;">
    <div style="margin-bottom: 16px; padding: 14px; border: 1px solid #d1d5db; border-radius: 10px; background: #f9fafb;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 140px; font-weight: 600; padding: 6px 0;">Kelas</td>
                <td style="padding: 6px 0;">: {{ $kelas?->kode ?? '-' }}</td>
            </tr>

            <tr>
                <td style="width: 140px; font-weight: 600; padding: 6px 0;">Mata Kuliah</td>
                <td style="padding: 6px 0;">: {{ $kelas?->matakuliah?->nama ?? '-' }}</td>
            </tr>

            <tr>
                <td style="width: 140px; font-weight: 600; padding: 6px 0;">Dosen</td>
                <td style="padding: 6px 0;">: {{ $kelas?->dosen?->nama ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #9ca3af; font-size: 14px;">
            <thead>
                <tr style="background: #f3f4f6;">
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left; width: 60px;">No</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">NIM</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Nama Mahasiswa</th>
                    <th style="border: 1px solid #9ca3af; padding: 10px; text-align: left;">Nilai Akhir</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($mahasiswas as $item)
                    <tr>
                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $item->mahasiswa_nim ?? '-' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $item->mahasiswa?->nama ?? '-' }}
                        </td>

                        <td style="border: 1px solid #9ca3af; padding: 10px;">
                            {{ $item->nilai_akhir ?? 0 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="border: 1px solid #9ca3af; padding: 16px; text-align: center; color: #6b7280;">
                            Belum ada mahasiswa di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>