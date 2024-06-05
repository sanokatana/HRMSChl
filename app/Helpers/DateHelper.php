<?php

namespace App\Helpers;
use Carbon\Carbon;

class DateHelper
{
    public static function formatIndonesianDate($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $timestamp = strtotime($date);
        $dayName = $days[date('l', $timestamp)];
        $day = date('j', $timestamp);
        $monthName = $months[date('F', $timestamp)];
        $year = date('Y', $timestamp);
        return "$dayName $day $monthName $year";
    }
    public static function getStatusText($status)
    {
        switch ($status) {
            case 'S':
                return 'Sakit';
            case 'Tmk':
                return 'Tidak masuk kerja';
            case 'Dt':
                return 'Datang terlambat';
            case 'Pa':
                return 'Pulang awal';
            case 'Tam':
                return 'Tidak masuk aben';
            case 'Tap':
                return 'Tidak absen pulang';
            case 'Tjo':
                return 'Tukar Jadwal';
            case 'Off':
                return 'Off';
            default:
                return 'Izin';
        }
    }

    public static function formatTimeToPM($time)
    {
        return Carbon::parse($time)->format('g:i A');
    }
}
