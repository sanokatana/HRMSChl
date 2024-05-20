<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function index(){
        $lokasi = DB::table('konfigurasi_lokasi')
        ->get();
        return view("konfigurasi.lokasikantor", compact('lokasi'));
    }

    public function store(Request $request){
        $nama_kantor = $request->nama_kantor;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;
        $data = [
            'nama_kantor' => $nama_kantor,
            'lokasi_kantor' => $lokasi_kantor,
            'radius'=> $radius
        ];

        $simpan = DB::table('konfigurasi_lokasi')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Lokasi Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Lokasi Gagal Di Simpan']);
        }
    }
    public function edit(Request $request){
        $nama_kantor = $request->nama_kantor;
        $lokasi = DB::table('konfigurasi_lokasi')->where('nama_kantor', $nama_kantor)->first();
        return view('konfigurasi.lokasiedit', compact('lokasi'));
    }

    public function update($nama_kantor, Request $request){
        $nama_kantor = $request->nama_kantor;
        $data = [
            'nama_kantor'=>$nama_kantor
        ];

        $update = DB::table('konfigurasi_lokasi')->where('nama_kantor',$nama_kantor)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }
}
