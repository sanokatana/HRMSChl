@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Attendance
                </div>
                <h2 class="page-title">
                    Attendance Table
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th style="border-color: black; border-style: solid; border-width: 1px;">Nama Karyawan</th>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                            <th style="border-color: black; border-style: solid; border-width: 1px;" class="{{ $currentMonth == Carbon\Carbon::now()->month && $i == Carbon\Carbon::now()->day ? 'today' : '' }}">
                                                {{ $i }}
                                            </th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendanceData as $row)
                                        <tr>
                                            <td style="border-color: black; border-style: solid; border-width: 1px;">{{ $row['nama_lengkap'] }}</td>
                                            @foreach($row['attendance'] as $day)
                                            <td style="text-align: center; border-color: black; border-style: solid; border-width: 1px;" class="{{ $day['class'] }}">
                                                @if($day['status'] == 'T' && ($currentMonth == Carbon\Carbon::now()->month && $i == Carbon\Carbon::now()->day))
                                                    <span>{{ $day['status'] }}</span>
                                                @elseif($day['status'] == 'LN')
                                                    LN
                                                @else
                                                    {{ $day['status'] }}
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        // Your JavaScript code, if any
    });
</script>
@endpush
