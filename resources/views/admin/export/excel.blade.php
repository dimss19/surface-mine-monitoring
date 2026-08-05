<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Shift</th>
            <th>Operator</th>
            <th>Unit</th>
            <th>Area</th>
            <th>Material</th>
            <th>HM Awal</th>
            <th>HM Akhir</th>
            <th>HM Total</th>
            <th>Ritasi</th>
            <th>Fuel (L)</th>
            <th>Lokasi</th>
            <th>Deskripsi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($ritasis as $r)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $r->tanggal->format('Y-m-d') }}</td>
                <td>{{ $r->shift }}</td>
                <td>{{ $r->pegawai->nama ?? '-' }}</td>
                <td>{{ $r->unit->kode ?? '-' }}</td>
                <td>{{ $r->area->nama ?? '-' }}</td>
                <td>{{ $r->material->nama ?? '-' }}</td>
                <td>{{ $r->hm_awal }}</td>
                <td>{{ $r->hm_akhir }}</td>
                <td>{{ $r->hm_total }}</td>
                <td>{{ $r->jumlah_ritasi }}</td>
                <td>{{ $r->fuel_consumption }}</td>
                <td>{{ $r->lokasi_pekerjaan }}</td>
                <td>{{ $r->deskripsi_pekerjaan }}</td>
                <td>{{ $r->status }}</td>
            </tr>
        @endforeach
        @foreach($nonRitasis as $nr)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $nr->tanggal->format('Y-m-d') }}</td>
                <td>{{ $nr->shift }}</td>
                <td>{{ $nr->pegawai->nama ?? '-' }}</td>
                <td>{{ $nr->unit->kode ?? '-' }}</td>
                <td>{{ $nr->area->nama ?? '-' }}</td>
                <td>-</td>
                <td>{{ $nr->hm_awal }}</td>
                <td>{{ $nr->hm_akhir }}</td>
                <td>{{ $nr->hm_total }}</td>
                <td>-</td>
                <td>{{ $nr->fuel_consumption }}</td>
                <td>{{ $nr->lokasi_pekerjaan }}</td>
                <td>{{ $nr->deskripsi_pekerjaan }}</td>
                <td>{{ $nr->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
