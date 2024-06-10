<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function izinapprovalhrd(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Handle the status_approved filter
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '1' (Approved) if no status_approved is provided
            $query->where('status_approved', 1);
        }

        // Handle the status_approved_hrd filter
        if ($request->has('status_approved_hrd')) {
            if ($request->status_approved_hrd === '0' || $request->status_approved_hrd === '1' || $request->status_approved_hrd === '2') {
                $query->where('status_approved_hrd', $request->status_approved_hrd);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('status_approved_hrd', 0);
        }

        $izinapproval = $query->paginate(10);
        $izinapproval->appends($request->all());

        return view('approval.approvalhr', compact('izinapproval'));
    }


    public function approveizinhrd(Request $request)
    {
        $id = $request->id_izin_form;
        $status_approved_hrd = $request->status_approved_hrd;
        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update(['status_approved_hrd' => $status_approved_hrd]);

        if ($update) {
            return redirect('/approval/izinapprovalhrd')->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect('/approval/izinapprovalhrd')->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function batalapprovehrd($id)
    {
        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update(['status_approved_hrd' => 0]);

        if ($update) {
            return response()->json(['success' => true, 'message' => 'Approval has been cancelled.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Gagal Di Update']);
        }
    }
}
