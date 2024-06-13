<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        // Join cuti table with karyawan table on NIK
        $query = Cuti::query();
        $query->select('cuti.*', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'department.nama_dept');
        $query->join('karyawan', 'cuti.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        // Order by NIK
        $query->orderBy('nik', 'asc');

        // Filter by Nama if provided
        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->nik_req)) {
            $query->where('cuti.nik', 'like', '%' . $request->nik_req . '%');
        }

        if (!empty($request->tahun_req)) {
            $query->where('cuti.tahun', 'like', '%' . $request->tahun_req . '%');
        }

        // Paginate the results
        $cuti = $query->paginate(10);
        $department = DB::table('department')->get();
        // Return the view with the results
        return view("cuti.index", compact('cuti', 'department'));
    }



    public function store(Request $request)
    {
        $nik = $request->nik;
        $tahun = $request->tahun;
        $sisa_cuti = $request->sisa_cuti;
        $created_by = Auth::guard('user')->user()->nik;
        $created_at = Carbon::now();

        try {

            $nama_lengkap = DB::table('karyawan')->where('nik', $created_by)->value('nama_lengkap');

            $data = [
                'nik' => $nik,
                'tahun' => $tahun,
                'sisa_cuti' => $sisa_cuti,
                'created_at' => $created_at,
                'created_by' => $nama_lengkap,
            ];
            $simpan = DB::table('cuti')->insert($data);

            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit($id)
    {
        $cuti = Cuti::findOrFail($id);
        return view('cuti.edit', compact('cuti'));
    }

    public function update($id, Request $request)
    {

        $updated_by = Auth::guard('user')->user()->nik;

        try {

            $nama_lengkap = DB::table('karyawan')->where('nik', $updated_by)->value('nama_lengkap');

            $cuti = Cuti::findOrFail($id);
            $cuti->nik = $request->nik;
            $cuti->tahun = $request->tahun;
            $cuti->sisa_cuti = $request->sisa_cuti;
            $cuti->updated_at = Carbon::now();
            $cuti->updated_by = $nama_lengkap;
            $cuti->save();
            return Redirect::back()->with('success', 'Data Berhasil Di Update');
        } catch (\Exception $e) {
            return Redirect::back()->with('danger', 'Data Gagal Di Update: ' . $e->getMessage());
        }
    }




    public function delete($id)
    {
        $delete = DB::table('cuti')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
