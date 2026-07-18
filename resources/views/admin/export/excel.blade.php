<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        table { border-collapse: collapse; width: 100%; font-family: sans-serif; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Pemantauan Lapangan - Surface Mine Production</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Supervisor</th>
                <th>Area</th>
                <th>Alat Utama</th>
                <th>Progress (%)</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Kendala</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemantauans as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($item->shift) }}</td>
                    <td>{{ $item->spv->name ?? '-' }}</td>
                    <td>{{ $item->area->nama ?? '-' }}</td>
                    <td>{{ $item->alat->nama ?? '-' }}</td>
                    <td>{{ $item->progress_persen }}%</td>
                    <td>{{ ucfirst($item->progress_status) }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->kendala ?: '-' }}</td>
                    <td style="height: 100px; width: 150px; text-align: center; vertical-align: middle;">
                        @if($item->getMedia('pemantauan_fotos')->count() > 0)
                            @php
                                $media = $item->getMedia('pemantauan_fotos')->first();
                                $path = $media->getPath();
                                $base64 = '';
                                if(file_exists($path)) {
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                }
                            @endphp
                            @if($base64)
                                <img src="{{ $base64 }}" width="120" height="90" style="object-fit: cover;" />
                            @else
                                Error loading image
                            @endif
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
