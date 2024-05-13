@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i=1; $i<=12; $i++)
                            <option value="{{ $i }}">{{ $namabulan[$i]}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @php
                        @endphp
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-block">
                        <ion-icon name="search-outline"></ion-icon>Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
