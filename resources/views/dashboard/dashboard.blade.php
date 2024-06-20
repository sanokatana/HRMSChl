@extends('layouts.presensi')
@section('content')
@php
use App\Helpers\DateHelper;
@endphp

<style>
    .rounded-custom {
        border-radius: 10px;
        /* Customize the radius as needed */
    }

    .jam-row {
        display: flex;
        flex-direction: column;
    }

    .status-row {
        display: flex;
        flex-direction: column;
        align-items: end;
    }

    .jam-in {
        width: 100%;
        /* Make each badge occupy full width */
    }

    .jam-out {
        width: 100%;
        /* Make each badge occupy full width */
    }
</style>
<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            @if (!empty(Auth::guard('karyawan')->user()->foto))
            @php
            $path = Storage::url('uploads/karyawan/'.Auth::guard('karyawan')->user()->foto)
            @endphp
            <img src="{{ url($path)}}" alt="avatar" class="imaged w64" style="height:60px">
            @else
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap}}</h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan}}</span>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/editprofile" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="orange" style="font-size: 40px;">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Lokasi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null && $presensihariini->foto_in != null)
                                @php
                                $path = Storage::url('/uploads/absensi/'.$presensihariini->foto_in);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="finger-print-outline"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensihariini != null && $presensihariini->foto_out != null)
                                @php
                                $path = Storage::url('/uploads/absensi/'.$presensihariini->foto_out);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="finger-print-outline"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini]}} Tahun {{$tahunini}}</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir}}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlterlambat}}</span>
                        <ion-icon name="hourglass-outline" style="font-size: 1.6rem;" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Telat</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekapizin->jmlizin}}</span>
                        <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekapcuti->jmlcuti }}</span>
                        <ion-icon name="document-attach-outline" style="font-size: 1.6rem;" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Cuti</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Hadir
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#formView" role="tab">
                        Form
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cutiView" role="tab">
                        Cuti
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                @foreach ($historibulanini as $d)
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
                            <div class="icon-box bg-info">
                                <ion-icon name="finger-print-outline"></ion-icon>
                            </div>
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
                                        <span class="badge badge-success">{{ $d->jam_in }}</span>
                                    </div>
                                    <div class="jam-out">
                                        <span class="badge badge-danger">{{ $presensihariini != null && $d->jam_out != null ? $d->jam_out : "NoAbsen"}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                @endforeach
            </div>

            <div class="tab-pane fade" id="formView" role="tabpanel">
                @foreach ($historiizin as $d)
                @php
                // Format the date for each izin entry
                $izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_izin);
                $izinFormattedDateAkhir = DateHelper::formatIndonesianDate($d->tgl_izin_akhir);
                @endphp
                <ul class="listview image-listview rounded-custom">
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <b>{{ $izinFormattedDate }}</b><br>
                                    <b class="text-muted">Sampai</b><br>
                                    @if ($d->tgl_izin_akhir)
                                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                                    @endif
                                    <b style="color: red;">{{ DateHelper::getStatusText($d->status) }}</b><br>
                                    <b class="text-info">{{ $d->keterangan }}</b>
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
            </div>
            <div class="tab-pane fade" id="cutiView" role="tabpanel">
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
                                    <b class="text-muted">Sampai</b><br>
                                    @if ($d->tgl_cuti_sampai)
                                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                                    @endif
                                    <b style="color: red;">Cuti</b><br>
                                    <b class="text-info">{{ $d->note }}</b>
                                </div>
                                <div class="status-row">
                                    <div class="mb-1">
                                        @if ($d->status_approved == 0)
                                        <span class="badge bg-warning">Waiting Approval</span>
                                        @elseif ($d->status_approved == 1)
                                        <span class="badge bg-success">Cuti Approved</span>
                                        @else
                                        <span class="badge bg-danger">Cuti Declined</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($d->status_approved_hrd == 0)
                                        <span class="badge bg-warning">Waiting Approval</span>
                                        @elseif ($d->status_approved_hrd == 1)
                                        <span class="badge bg-success">Cuti Approved</span>
                                        @else
                                        <span class="badge bg-danger">Cuti Declined</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
