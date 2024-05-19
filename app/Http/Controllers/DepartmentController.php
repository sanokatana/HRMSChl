<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{
    public function index(Request $request){

        // $department = DB::table('department')
        // ->orderBy('kode_dept')
        // ->get();
        $nama_dept = $request->nama_dept;
        $query = Department::query();
        $query ->select('*');
        if(!empty($nama_dept)){
            $query->where('nama_dept','like','%' . $nama_dept . '%');
        }
        $department = $query->get();
        return view('department.index', compact('department'));
    }

    public function store(Request $request){
        $kode_dept = $request->kode_dept;
        $nama_dept = $request->nama_dept;
        $data = [
            'kode_dept' => $kode_dept,
            'nama_dept' => $nama_dept
        ];

        $simpan = DB::table('department')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request){
        $kode_dept = $request->kode_dept;
        $department = DB::table('department')->where('kode_dept', $kode_dept)->first();
        return view('department.edit', compact('department'));
    }

    public function update($kode_dept, Request $request){
        $nama_dept = $request->nama_dept;
        $data = [
            'nama_dept'=>$nama_dept
        ];

        $update = DB::table('department')->where('kode_dept',$kode_dept)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }

    public function delete($kode_dept){
        $delete = DB::table('department')->where('kode_dept',$kode_dept)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
