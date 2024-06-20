@if ($historicuti->isEmpty())
<div id="alert-div" class="alert alert-warning">
    <p style="text-align: center; height: 10px">Data Belum Ada</p>
</div>
@endif
@php
use App\Helpers\DateHelper;
@endphp
@foreach ($historicuti as $d)
@php
// Format the date for each izin entry
$izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_cuti);
$izinFormattedDateAkhir = DateHelper::formatIndonesianDate($d->tgl_cuti_sampai);
@endphp
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            <div class="in">
                <div>
                    <b>{{ $izinFormattedDate }}</b><br>
                    @if ($d->tgl_cuti_sampai)
                    <b>- {{ $izinFormattedDateAkhir }}</b><br>
                    @endif
                    <b style="color: red;">Cuti</b><br>
                    <small class="text-muted">{{ $d->note }}</small>
                </div>
                <div class="status-row">
                    <div class="mb-1">
                        @if ($d->status_approved == 0)
                        <span class="badge bg-warning">Waiting Approval</span>
                        @elseif ($d->status_approved == 1)
                        <span class="badge bg-success">Form Approved</span>
                        @else
                        <span class="badge bg-danger">Form Declined</span>
                        @endif
                    </div>
                    <div>
                        @if ($d->status_approved_hrd == 0)
                        <span class="badge bg-warning">Waiting Approval</span>
                        @elseif ($d->status_approved_hrd == 1)
                        <span class="badge bg-success">Form Approved</span>
                        @else
                        <span class="badge bg-danger">Form Declined</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
