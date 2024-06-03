@if ($historiizin->isEmpty())
<div id="alert-div" class="alert alert-warning">
    <p style="text-align: center; height: 10px">Data Belum Ada</p>
</div>
@endif
@foreach ($historiizin as $d)
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            <div class="in">
                <div>
                    <b>{{ date("F j, Y", strtotime($d->tgl_izin))}}</b><br>
                    <small>{{ $d->status=="s" ? "Sakit" : "Izin"}}</small> -
                    <small class="text-muted">{{ $d->keterangan}}</small>
                </div>
                @if ($d->status_approved==0)
                <span class="badge bg-warning">Waiting Approval</span>
                @elseif($d->status_approved==1)
                <span class="badge bg-success">Approved</span>
                @else
                <span class="badge bg-danger">Declined</span>
                @endif
            </div>
        </div>
    </li>
</ul>
@endforeach
