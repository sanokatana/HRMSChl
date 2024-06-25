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

    public function tipecuti(){
        $tipecuti = DB::table('tipe_cuti')
        ->get();
        return view("konfigurasi.tipecuti", compact('tipecuti'));
    }

    public function tipecutistore(Request $request){
        $tipe_cuti = $request->tipe_cuti;
        $jumlah_hari = $request->jumlah_hari;
        $data = [
            'tipe_cuti' => $tipe_cuti,
            'jumlah_hari' => $jumlah_hari,
        ];

        $simpan = DB::table('tipe_cuti')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Tipe Cuti Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Lokasi Gagal Di Simpan']);
        }
    }
    public function tipecutiedit(Request $request){
        $id_tipe_cuti = $request->id_tipe_cuti;
        $tipecuti = DB::table('tipe_cuti')->where('id_tipe_cuti', $id_tipe_cuti)->first();
        return view('konfigurasi.tipecutiedit', compact('tipecuti'));
    }

    public function tipecutiupdate($id_tipe_cuti, Request $request){
        $id_tipe_cuti = $request->id_tipe_cuti;
        $tipe_cuti = $request->tipe_cuti;
        $jumlah_hari = $request->jumlah_hari;
        $data = [
            'id_tipe_cuti'=>$id_tipe_cuti,
            'tipe_cuti' => $tipe_cuti,
            'jumlah_hari' => $jumlah_hari,
        ];

        $update = DB::table('tipe_cuti')->where('id_tipe_cuti',$id_tipe_cuti)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }
}
