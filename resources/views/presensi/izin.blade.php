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
<!-- * App Header -->
@endsection
@section('content')
<div class="row">
    <div class="col">
    @foreach ($dataizin as $d)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                <div class="in">
                    <div>Absen {{ date("F j, Y", strtotime($d->tgl_izin))}}</div>
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

