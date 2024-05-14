@if ($histori->isEmpty())
<div class="alert alert-warning">
    <p style="text-align: center;">Data Belum Ada</p>
</div>
@endif
@foreach ($histori as $d)
<ul class="listview image-listview">
    <li>
        <div class="item">
            @php
                $path = Storage::url('uploads/absensi/'.$d->foto_in);
            @endphp
            <img src="{{ url($path) }}" alt="image" class="image">
            <div class="in">
                <div>Absen {{ date("F j, Y", strtotime($d->tgl_presensi))}}</div>
                <div>
                <span class="badge {{ $d->jam_in < "08:00" ? "bg-success" : "bg-danger"}}">{{ $d != null ? $d->jam_in : 'Belum Absen'}}</span>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
