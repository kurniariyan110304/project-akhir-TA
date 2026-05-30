<div style="overflow-x: auto; width: 100%;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">NIM</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Nama</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Email</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Tahun Masuk</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Kelas</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Semester</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Hari</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Jam</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: left;">Ruang</th>
                <th style="border: 1px solid #d1d5db; padding: 10px; text-align: center;">Nilai Akhir</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($mahasiswas as $item)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->nim }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->nama }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->email ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->thn_masuk ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->kode_kelas ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->semester ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->hari ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->jam ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px;">
                        {{ $item->ruang ?? '-' }}
                    </td>

                    <td style="border: 1px solid #d1d5db; padding: 10px; text-align: center;">
                        {{ $item->nilai_akhir ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="border: 1px solid #d1d5db; padding: 20px; text-align: center; color: #6b7280;">
                        Belum ada mahasiswa pada data ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>