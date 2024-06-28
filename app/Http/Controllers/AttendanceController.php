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

        // Process presensi data to format for display
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

                $status = $this->getAttendanceStatus($date, $attendance);

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
    private function getAttendanceStatus($date, $attendance)
    {
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
            }
        }

        return implode(' ', $classes);
    }
}
