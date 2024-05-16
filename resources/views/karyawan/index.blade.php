@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Data Master
                </div>
                <h2 class="page-title">
                    Data Karyawan
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>No. Hp</th>
                                    <th>Foto</th>
                                    <th>Department</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $d)
                                @php
                                    $path = Storage::url('uploads/karyawan/'.$d->foto)
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $d->nik}}</td>
                                    <td>{{ $d->nama_lengkap}}</td>
                                    <td>{{ $d->jabatan}}</td>
                                    <td>{{ $d->no_hp}}</td>
                                    <td>
                                        @if (empty($d->foto))
                                        <img src="{{ asset('assets/img/nophoto.jpg')}}" class="avatar" alt="">
                                        @else
                                        <img src="{{ url($path )}}" class="avatar" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $d->nama_dept}}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
