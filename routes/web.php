<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function (){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin',[AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function (){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class,'updateprofile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class,'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class,'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class,'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class,'storeizin']);
});

Route::middleware(['auth:user'])->group(function (){
    Route::get('/panel/dashboardadmin', [DashboardController::class,'dashboardadmin']);
    Route::get('/panel/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);

    //Karyawan
    Route::get('/karyawan', [KaryawanController::class,'index']);
    Route::post('/karyawan/store', [KaryawanController::class,'store']);
    Route::post('/karyawan/edit', [KaryawanController::class,'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class,'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class,'delete']);

    //Department
    Route::get('/department', [DepartmentController::class,'index']);
    Route::post('/department/store', [DepartmentController::class,'store']);
    Route::post('/department/edit', [DepartmentController::class,'edit']);
    Route::post('/department/{kode_dept}/update', [DepartmentController::class,'update']);
    Route::post('/department/{kode_dept}/delete', [DepartmentController::class,'delete']);

    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class,'monitoring']);
    Route::post('/getpresensi', [PresensiController::class,'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class,'tampilkanpeta']);

    //Konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class,'index']);
    Route::post('/konfigurasi/lokasikantor/store', [KonfigurasiController::class,'store']);
    Route::post('/konfigurasi/lokasikantor/edit', [KonfigurasiController::class,'edit']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/update', [KonfigurasiController::class,'update']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/delete', [KonfigurasiController::class,'delete']);
});
