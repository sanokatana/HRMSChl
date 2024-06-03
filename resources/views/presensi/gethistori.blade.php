@if ($histori->isEmpty())
<div id="alert-div" class="alert alert-warning">
    <p style="text-align: center; height: 10px">Data Belum Ada</p>
</div>
@endif
@php
use App\Helpers\DateHelper;
@endphp
<!-- @foreach ($histori as $d)
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            @php
            $path = Storage::url('uploads/absensi/'.$d->foto_in);
            @endphp
            @if ($d->foto_in == null)
            <ion-icon name="finger-print-outline" style="font-size: 2em; padding-right: 5px;"></ion-icon>
            @else
            <img src="{{ url($path) }}" alt="image" class="image">
            @endif

            <div class="in">
                <div>{{ date("F j, Y", strtotime($d->tgl_presensi))}}</div>
                <div>
                    <span class="badge {{ $d->jam_in < "08:05" ? "bg-success" : "bg-warning"}}">{{ $d != null ? $d->jam_in : 'No Absen'}}</span>
                    <span class="badge {{ $d->jam_out < "17:00" ? "bg-warning" : "bg-success"}}">{{ $d->jam_out != null ? $d->jam_out : 'NoAbsen'}}</span>
                </div>

            </div>
        </div>
    </li>
</ul>
@endforeach -->
@foreach ($histori as $d)
<ul class="listview image-listview rounded-custom">

    @php
    $path = Storage::url('uploads/absensi/'.$d->foto_in);
    // Extract hours and minutes from the jam_in time
    $jam_in_time = strtotime($d->jam_in);
    $hours_diff = floor(($jam_in_time - strtotime("08:05")) / 3600);
    $minutes_diff = floor((($jam_in_time - strtotime("08:05")) % 3600) / 60);

    // Calculate lateness
    if ($hours_diff > 0) {
    if ($minutes_diff > 0) {
    $lateness = $hours_diff . " Jam " . $minutes_diff . " Menit";
    } else {
    $lateness = $hours_diff . " Jam";
    }
    } elseif ($minutes_diff > 0) {
    $lateness = $minutes_diff . " Menit";
    } else {
    $lateness = "On Time";
    }

    // Determine status based on lateness
    $status = ($lateness != "On Time") ? "Terlambat" : "On Time";
    @endphp
    <li>
        <div class="item">
            @if ($d->foto_in == null)
            <ion-icon name="finger-print-outline" style="font-size: 2em; padding-right: 5px;"></ion-icon>
            @else
            <img src="{{ url($path) }}" alt="image" class="image">
            @endif
            <div class="in">
                <div class="jam-row">
                    <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                    <div class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                        {{ $status }}
                    </div>
                    <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                    ({{ $lateness }})
                    </div>
                </div>
                <div class="jam-row">
                    <div class="jam-in mb-1">
                        <span class="badge {{ $d->jam_in < "08:05" ? "bg-success" : "bg-success"}}">{{ $d != null ? $d->jam_in : 'No Absen'}}</span>
                    </div>
                    <div class="jam-out">
                        <span class="badge {{ $d->jam_out < "17:00" ? "bg-danger" : "bg-danger"}}">{{ $d->jam_out != null ? $d->jam_out : 'NoAbsen'}}</span>
                    </div>
                </div>
            </div>
        </div>
    </li>

</ul>
@endforeach
