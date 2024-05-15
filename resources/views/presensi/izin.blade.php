@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
</div>
<style>
    .rounded-custom {
        border-radius: 15px; /* Customize the radius as needed */
    }
</style>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 60px">
    <div class="col">
    @foreach ($dataizin as $d)
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
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom: 60px;">
    <a href="/presensi/buatizin" class="fab">
    <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@endsection

