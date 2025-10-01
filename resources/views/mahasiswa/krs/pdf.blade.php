<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KRS - {{ $student->nim }}</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3, .header h4 { margin: 0; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        .course-table { width: 100%; border-collapse: collapse; }
        .course-table th, .course-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .course-table th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; }
        .signature { float: right; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        {{-- Ganti dengan path logo Anda jika ada --}}
        {{-- <img src="{{ public_path('logo.png') }}" alt="Logo" width="80"> --}}
        <h3>UNIVERSITAS MUHAMMADIYAH MALUKU</h3>
        <h4>KARTU RENCANA STUDI (KRS)</h4>
        <h5>Semester: {{ $activeSemester->name }}</h5>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Mahasiswa</td>
            <td width="1%">:</td>
            <td width="34%">{{ $student->user->name }}</td>
            <td width="15%">Program Studi</td>
            <td width="1%">:</td>
            <td width="34%">{{ $student->program->name_id }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $student->nim }}</td>
            <td>Dosen PA</td>
            <td>:</td>
            <td>{{ $student->academicAdvisor->full_name_with_degree ?? 'Belum Diatur' }}</td>
        </tr>
    </table>

    <table class="course-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Kelas</th>
                <th>Jadwal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enrollments as $key => $enrollment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $enrollment->courseClass->course->code }}</td>
                    <td>{{ $enrollment->courseClass->course->name }}</td>
                    <td>{{ $enrollment->courseClass->course->sks }}</td>
                    <td>{{ $enrollment->courseClass->name }}</td>
                    <td>{{ $enrollment->courseClass->day ?? '-' }}, {{ $enrollment->courseClass->start_time ? date('H:i', strtotime($enrollment->courseClass->start_time)) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="3" style="text-align: right; font-weight: bold;">Total SKS Diambil</td>
                <td style="font-weight: bold;">{{ $totalSks }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            Maluku, {{ now()->isoFormat('D MMMM YYYY') }}<br>
            Dosen Pembimbing Akademik,
            <br><br><br><br>
            (____________________________)<br>
            <strong>{{ $student->academicAdvisor->full_name_with_degree ?? 'Nama Dosen PA' }}</strong>
        </div>
    </div>

</body>
</html>