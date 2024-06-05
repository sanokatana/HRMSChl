@if ($historiizin->isEmpty())
<div id="alert-div" class="alert alert-warning">
    <p style="text-align: center; height: 10px">Data Belum Ada</p>
</div>
@endif
@php
use App\Helpers\DateHelper;
@endphp
@foreach ($historiizin as $d)
@php
// Format the date for each izin entry
$izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_izin);
@endphp
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            <div class="in">
                <div>
                    <b>{{ $izinFormattedDate }}</b><br>
                    <b style="color: red;">{{ DateHelper::getStatusText($d->status) }}</b><br>
                    <small class="text-muted">{{ $d->keterangan }}</small>
                </div>
                @if ($d->status_approved == 0)
                <span class="badge bg-warning">Waiting Approval</span>
                @elseif ($d->status_approved == 1)
                <span class="badge bg-success">Form Approved</span>
                @else
                <span class="badge bg-danger">Form Declined</span>
                @endif
            </div>
        </div>
    </li>
</ul>
@endforeach
