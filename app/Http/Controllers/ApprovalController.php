<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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
        $currentDate = Carbon::now();

        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update([
                'status_approved_hrd' => $status_approved_hrd,
                'tgl_status_approved_hrd' => $currentDate
        ]);

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

    public function izinapproval(Request $request)
    {
        $nik = Auth::guard('user')->user()->nik;

        // Get the list of employee NIKs whose supervisor is the current user
        $employeeNiks = Karyawan::where('nik_atasan', $nik)->pluck('nik');

        // Begin query on pengajuan_izin table
        $query = Pengajuanizin::query();
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept')
            ->whereIn('pengajuan_izin.nik', $employeeNiks); // Filter to only include employees supervised by the current user

        // Apply date filter if provided
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        // Apply NIK filter if provided
        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        // Apply name filter if provided
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Apply approval status filter if provided
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '1' (Approved) if no status_approved is provided
            $query->where('status_approved', 0);
        }


        // Paginate the results
        $izinapproval = $query->paginate(10);
        $izinapproval->appends($request->all());

        // Return the view with the filtered results
        return view('approval.izinapproval', compact('izinapproval'));
    }


    public function approveizin(Request $request)
    {
        $id = $request->id_izin_form;
        $status_approved = $request->status_approved;
        $currentDate = Carbon::now();

        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update(['status_approved' => $status_approved,
            'tgl_status_approved' => $currentDate
        ]);

        if ($update) {
            return redirect('/approval/izinapproval')->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect('/approval/izinapproval')->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function batalapprove($id)
    {
        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update(['status_approved' => 0]);

        if ($update) {
            return response()->json(['success' => true, 'message' => 'Approval has been cancelled.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Gagal Di Update']);
        }
    }
}
