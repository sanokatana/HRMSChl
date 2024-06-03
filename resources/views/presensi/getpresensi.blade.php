@foreach ($presensi as $d)
@php
$foto_in = Storage::url('uploads/absensi/'.$d->foto_in);
$foto_out = Storage::url('uploads/absensi/'.$d->foto_out);

// Standard office start time
$startTime = strtotime('08:05:00');
$jamInTime = strtotime($d->jam_in);

// Calculate delay in hours and minutes
$delayHours = 0;
$delayMinutes = 0;
if ($jamInTime > $startTime) {
$delayInSeconds = $jamInTime - $startTime;
$delayHours = floor($delayInSeconds / 3600);
$delayMinutes = floor(($delayInSeconds % 3600) / 60);
}
@endphp
<tr style="text-align: center; ">
    <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
    <td style="vertical-align: middle;">{{ $d->nik }}</td>
    <td style="vertical-align: middle;">{{ $d->nama_lengkap }}</td>
    <td style="vertical-align: middle;">{{ $d->nama_dept }}</td>
    <td style="vertical-align: middle;">{{ $d->jam_in }}</td>
    <td style="vertical-align: middle;">
        @if ($d->foto_in != null)
        <img src="{{ url($foto_in) }}" class="avatar" alt="">
        @else
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-fingerprint">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
            <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
            <path d="M12 11v2a14 14 0 0 0 2.5 8" />
            <path d="M8 15a18 18 0 0 0 1.8 6" />
            <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
        </svg>
        @endif
    </td>
    <td style="vertical-align: middle;">
        {!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger" style="color: white;">Belum Absen</span>' !!}
    </td>
    <td style="vertical-align: middle;">
        @if ($d->jam_out == null)
        <img src="{{ asset('assets/img/ban.png') }}" class="avatar" alt="">
        @elseif ($d->foto_out != null)
        <img src="{{ url($foto_out) }}" class="avatar" alt="">
        @else
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-fingerprint">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
            <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
            <path d="M12 11v2a14 14 0 0 0 2.5 8" />
            <path d="M8 15a18 18 0 0 0 1.8 6" />
            <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
        </svg>
        @endif
    </td>
    <td style="vertical-align: middle;">
        @if ($jamInTime > $startTime)
        <div class="row">
            <span class="badge bg-yellow text-yellow-fg" style="color: white;">Terlambat</span>
            <span class="badge bg-yellow-lt" style="color: white;">
                {{ $delayHours > 0 ? $delayHours . ' Jam ' : '' }}{{ $delayMinutes > 0 ? $delayMinutes . ' Menit' : '' }}
            </span>
        </div>
        @else
        <div class="row">
            <span class="badge bg-green text-yellow-fg" style="color: white;">Tepat Waktu</span>
            <span class="badge bg-green-lt" style="color: white;">
                On Time
            </span>
        </div>
        @endif
    </td>
    <td style="vertical-align: middle;">
        <a href="#" class="btn btn-pill btn-primary tampilkanpeta" id="{{ $d->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map" style="margin:0;">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                <path d="M9 4v13" />
                <path d="M15 7v13" />
            </svg>
        </a>
    </td>
</tr>
@endforeach

<script>
    $(function() {
        $(".tampilkanpeta").click(function(e) {
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '/tampilkanpeta',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond) {
                    $("#loadmap").html(respond);
                }
            })
            $("#modal-tampilkanpeta").modal("show")
        });
    });
</script>
