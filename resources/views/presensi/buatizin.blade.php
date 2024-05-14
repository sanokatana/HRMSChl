@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal{
        max-height: 430px !important;
    }
    .datepicker-date-display{
        background-color: #4989EF !important;
    }
</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/presensi/izin" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Pengajuan Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="formizin">
            @csrf
            <div class="col">
                <div class="form-group">
                    <input type="text" id="tgl_izin" name="tgl_izin" class="datepicker form-control" placeholder="Tanggal">
                </div>
            </div>
            <div class="form-group">
                <label for="tipe" class="col-form-label">Tipe Absen</label>
                <select name="status" id="status" class="form-control">
                    <option disabled selected value> -- Pilih -- </option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipe" class="col-form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="10" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });

        $("#formizin").submit(function(event){
            event.preventDefault(); // Prevent default form submission
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();

            if (tgl_izin == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                });
            } else if (status == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Status Harus Diisi',
                    icon: 'warning',
                });
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin mengajukan izin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ajukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/presensi/storeizin',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                tgl_izin: tgl_izin,
                                status: status,
                                keterangan: keterangan
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Data Berhasil Di Simpan',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '/presensi/izin';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan, data tidak tersimpan',
                                    icon: 'error',
                                });
                            }
                        });
                    }
                });
            }
        });
    });
</script>
@endpush
