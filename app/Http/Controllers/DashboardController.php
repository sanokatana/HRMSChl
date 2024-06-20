<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nik = Auth::guard('karyawan')->user()->nik;
        $namaUser = DB::table('karyawan')->where('nik', $nik)->first();
        $presensihariini = DB::table('presensi')->where('nik', $nik)
        ->where('tgl_presensi',$hariini)
        ->first();
        $historibulanini = DB::table('presensi')
        ->where('nik',$nik)->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahunini.'"')
        ->orderBy('tgl_presensi')
        ->get();

        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00",1,0)) as jmlterlambat')
        ->where('nik',$nik)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahunini.'"')
        ->first();

        $historiizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_izin')
            ->get();

        $historicuti = DB::table('pengajuan_cuti')
            ->whereRaw('MONTH(tgl_cuti)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_cuti)="' . $tahunini . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_cuti')
            ->get();

        $rekapizin = DB::table('pengajuan_izin')
        ->selectRaw('SUM(IF(status != "s",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_izin)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_izin)="'.$tahunini.'"')
        ->first();

        $rekapcuti = DB::table('pengajuan_cuti')
        ->selectRaw('count(id) as jmlcuti')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_cuti)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_cuti)="'.$tahunini.'"')
        ->first();

        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('dashboard.dashboard', compact('presensihariini','historibulanini', 'namabulan', 'bulanini', 'tahunini', 'namaUser', 'rekappresensi','historiizin', 'historicuti', 'rekapizin', 'rekapcuti'));
    }

    public function dashboardadmin(){
        $hariini = date("Y-m-d");
        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00",1,0)) as jmlterlambat')
        ->where('tgl_presensi',$hariini)
        ->first();

        $rekapizin = DB::table('pengajuan_izin')
        ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
        ->where('tgl_izin',$hariini)
        ->where('status_approved', 1)
        ->first();

        $rekapkaryawan = DB::table('karyawan')
        ->selectRaw('COUNT(nik) as jmlkar')
        ->first();
        return view('dashboard.dashboardadmin', compact('rekapizin', 'rekappresensi', 'rekapkaryawan'));
    }
}
