<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pegawai</th>
            <th>Jumlah Ritasi</th>
            <th>HM Ritasi</th>
            <th>Jumlah Non-Ritasi</th>
            <th>HM Non-Ritasi</th>
            <th>Jumlah General</th>
            <th>Total HM</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($rows as $r)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $r['pegawai']->nama }}</td>
                <td>{{ $r['ritasi'] }}</td>
                <td>{{ $r['ritasi_hm'] }}</td>
                <td>{{ $r['non_ritasi'] }}</td>
                <td>{{ $r['non_ritasi_hm'] }}</td>
                <td>{{ $r['general'] }}</td>
                <td>{{ number_format($r['ritasi_hm'] + $r['non_ritasi_hm'], 1) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
