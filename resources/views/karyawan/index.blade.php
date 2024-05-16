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
            <table class="table table-boder">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Department</th>
                        <th>No. Hp</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>
</div>
@endsection
