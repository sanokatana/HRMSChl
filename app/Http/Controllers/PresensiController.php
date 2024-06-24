<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\Pengajuancuti;
use App\Models\Pengajuanizin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");

        // Fetch the office location configuration
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        // Access the correct property 'lokasi_kantor'
        $lok = explode(',', $lok_kantor->lokasi_kantor);

        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];

        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda" . $radius . " meter dari kantor|radius";
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo "success|Terima Kasih, Hati Hati Di Jalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen Hubungi Tim IT|out";
                }
            } else {
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo "success|Terima Kasih, Selamat Berkerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen Hubungi Tim IT|in";
                }
            }
        }
    }


    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function getSisaCutiProfile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';
        $cutiGet = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();

        if ($cutiGet) {
            return response()->json(['sisa_cuti' => $cutiGet->sisa_cuti, 'cutiYear' => $periode]);
        } else {
            return response()->json(['sisa_cuti' => 0]);
        }
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }


    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('izin.izin', compact('dataizin', 'namabulan'));
    }

    public function getizin(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $historiizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_izin')
            ->get();

        return view('izin.getizin', compact('historiizin', 'tahun', 'bulan'));
    }
    public function getizincuti(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $historicuti = DB::table('pengajuan_cuti')
            ->whereRaw('MONTH(tgl_cuti)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_cuti)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_cuti')
            ->get();

        return view('izin.getizincuti', compact('historicuti', 'tahun', 'bulan'));
    }

    public function buatizin()
    {
        return view('izin.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $tgl_izin_akhir = $request->tgl_izin_akhir;
        $jml_hari = $request->jml_hari;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $pukul = $request->pukul;
        $currentDate = Carbon::now();

        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $foto = "Surat_" . $nik . "_" . $currentDate->format('d_m_Y') . "." . $extension;
        } else {
            $foto = "No_Document";
        }

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'tgl_izin_akhir' => $tgl_izin_akhir,
            'jml_hari' => $jml_hari,
            'status' => $status,
            'pukul' => $pukul,
            'keterangan' => $keterangan,
            'tgl_create' => $currentDate,
            'foto' => $foto
        ];

        $simpan = Pengajuanizin::create($data);

        if ($simpan) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/pengajuan_izin/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Di Simpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Di Simpan']);
        }
    }

    public function buatcuti()
    {
        $nik = auth()->user()->nik;
        $currentEmployee = DB::table('karyawan')->where('nik', $nik)->first();
        $kode_dept = $currentEmployee->kode_dept;
        $employees = DB::table('karyawan')
        ->where('kode_dept', $kode_dept)
        ->where('nik', '!=', $nik)
        ->get();

        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';
        $periode_awal = $cuti ? $cuti->periode_awal : '';
        $periode_akhir = $cuti ? $cuti->periode_akhir : '';
        $cutiGet = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();



        return view('izin.buatcuti', compact('periode', 'periode_awal', 'periode_akhir','employees', 'cutiGet'));
    }


    public function storecuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $periode = $request->periode;
        $sisa_cuti = $request->sisa_cuti;
        $tgl_cuti = $request->tgl_cuti;
        $tgl_cuti_sampai = $request->tgl_cuti_sampai;
        $jml_hari = $request->jml_hari;
        $sisa_cuti_setelah = $request->sisa_cuti_setelah;
        $kar_ganti = $request->kar_ganti;
        $note = $request->note;

        $data = [
            'nik' => $nik,
            'periode' => $periode,
            'sisa_cuti' => $sisa_cuti,
            'tgl_cuti' => $tgl_cuti,
            'tgl_cuti_sampai' => $tgl_cuti_sampai,
            'jml_hari' => $jml_hari,
            'sisa_cuti_setelah' => $sisa_cuti_setelah,
            'kar_ganti' => $kar_ganti,
            'note' => $note,
        ];

        // Start a transaction
        DB::beginTransaction();

        try {
            // Save the leave application
            $simpan = Pengajuancuti::create($data);

            if ($simpan) {
                // Update the sisa_cuti in the cuti table
                $cuti = DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->first();

                if ($cuti) {
                    $new_sisa_cuti = $cuti->sisa_cuti - $jml_hari;

                    DB::table('cuti')
                        ->where('nik', $nik)
                        ->where('tahun', $periode)
                        ->update(['sisa_cuti' => $new_sisa_cuti]);
                }

                // Commit the transaction
                DB::commit();

                return redirect('/presensi/izin')->with(['success' => 'Pengajuan Cuti Berhasil Di Simpan']);
            } else {
                // Rollback the transaction
                DB::rollBack();

                return redirect('/presensi/izin')->with(['error' => 'Pengajuan Cuti Gagal Di Simpan']);
            }
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            return redirect('/presensi/izin')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function getSisaCuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $periode = $request->periode;

        $cuti = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();

        if ($cuti) {
            return response()->json(['sisa_cuti' => $cuti->sisa_cuti]);
        } else {
            return response()->json(['sisa_cuti' => 0]);
        }
    }
    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)->first();
        return view('presensi.showmap', compact('presensi'));
    }
}
