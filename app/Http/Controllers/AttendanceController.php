<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get the number of days in the current month
        $daysInMonth = Carbon::now()->daysInMonth;

        // Get all karyawan data
        $karyawan = DB::table('karyawan')->get();

        // Get all presensi data for the current month
        $presensi = DB::table('presensi')
            ->select('nik', 'tgl_presensi', 'jam_in')
            ->whereMonth('tgl_presensi', $currentMonth)
            ->whereYear('tgl_presensi', $currentYear)
            ->get();

        // Get national holidays for the current month
        $liburNasional = DB::table('libur_nasional')
            ->whereMonth('tgl_libur', $currentMonth)
            ->whereYear('tgl_libur', $currentYear)
            ->pluck('tgl_libur')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });

        // Get all approved leave data for the current month
        $cuti = DB::table('pengajuan_cuti')
            ->select('nik', 'tgl_cuti', 'tgl_cuti_sampai')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('tgl_cuti', $currentMonth)
                      ->whereYear('tgl_cuti', $currentYear)
                      ->orWhereMonth('tgl_cuti_sampai', $currentMonth)
                      ->whereYear('tgl_cuti_sampai', $currentYear);
            })
            ->get();

        // Process presensi and cuti data to format for display
        $attendanceData = [];
        foreach ($karyawan as $k) {
            $row = [
                'nama_lengkap' => $k->nama_lengkap,
                'attendance' => []
            ];

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = Carbon::create($currentYear, $currentMonth, $i);
                $dateString = $date->toDateString();
                $attendance = $presensi->where('nik', $k->nik)->where('tgl_presensi', $dateString)->first();
                $isCuti = $this->checkCuti($cuti, $k->nik, $date);

                $status = $this->getAttendanceStatus($date, $attendance, $isCuti);

                // Check if the date is a national holiday
                if ($liburNasional->contains($dateString)) {
                    $status = 'LN'; // Mark as national holiday
                }

                $row['attendance'][] = [
                    'status' => $status,
                    'class' => $this->getAttendanceClass($date, $status)
                ];
            }

            $attendanceData[] = $row;
        }

        // Prepare data for the view
        $data = [
            'attendanceData' => $attendanceData,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear
        ];

        return view('attendance.attendance', $data);
    }

    // Helper function to determine attendance status
    private function getAttendanceStatus($date, $attendance, $isCuti)
    {
        if ($isCuti) {
            return 'C';
        }

        if ($attendance) {
            $jam_in = Carbon::parse($attendance->jam_in);
            if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
                return $jam_in->gt(Carbon::parse('08:05:00')) ? 'T' : 'P';
            } else {
                return $jam_in->gt(Carbon::parse('08:05:00')) ? 'T' : 'P';
            }
        } else {
            return $date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY ? 'L' : '';
        }
    }

    // Helper function to determine if the date falls within a leave period
    private function checkCuti($cuti, $nik, $date)
    {
        foreach ($cuti as $c) {
            if ($c->nik == $nik && $date->between(Carbon::parse($c->tgl_cuti), Carbon::parse($c->tgl_cuti_sampai))) {
                return true;
            }
        }
        return false;
    }

    // Helper function to determine CSS classes for attendance cell
    // Helper function to determine CSS classes for attendance cell
private function getAttendanceClass($date, $status)
    {
        $classes = [];
        if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
            if ($status == 'T') {
                $classes[] = 'late';
            } else if ($status == 'P') {
                $classes[] = '';
            } else {
                $classes[] = 'weekend';
            }
        } else {
            if ($status == 'T') {
                $classes[] = 'late';
            } else if ($status == 'LN') {
                $classes[] = 'dark-yellow';
            } else if ($status == 'C') {
                $classes[] = 'cuti';
            }
        }

        return implode(' ', $classes);
    }

}
