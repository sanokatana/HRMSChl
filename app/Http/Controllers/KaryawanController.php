<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    public function index(Request $request){


        $query = Karyawan::query();
        $query->select('karyawan.*','nama_dept');
        $query->join('department','karyawan.kode_dept','=','department.kode_dept');
        $query->orderBy('nama_lengkap','asc');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap','like','%' .$request->nama_karyawan.'%');
        }

        if(!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept',$request->kode_dept);
        }
        $karyawan = $query->paginate(10);

        $department = DB::table('department')->get();
        return view("karyawan.index", compact('karyawan','department'));
    }

    public function store(Request $request){
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = Hash::make('chl12345');
        $kode_dept = $request->kode_dept;
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan'=> $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password'=> $password
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if($simpan){
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger'=>'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request){

        $nik = $request->nik;
        $department = DB::table('department')->get();
        $karyawan = DB::table('karyawan')
        ->where('nik', $nik)
        ->first();
        return view('karyawan.edit', compact('department','karyawan'));
    }
}
