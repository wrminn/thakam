@extends('backend.menu.layout')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .forum-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
    </style>
    <section class="b-detail">
        <div class="container my-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-success text-white text-center py-3">
                    <h4 class="mb-0">รายละเอียดแบบสอบถามความพึงพอใจ</h4>
                </div>
                <div class="card-body p-4">

                    <!-- ส่วนที่ 1: ข้อมูลทั่วไป -->
                    <h5 class="mb-3 text-success">ส่วนที่ 1: ข้อมูลทั่วไปผู้ขอรับบริการ</h5>
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><b>ชื่อผู้ขอรับบริการ:</b> {{ $list->satisfaction_customer_name }}</li>
                        <li class="list-group-item"><b>โทรศัพท์:</b> {{ $list->satisfaction_customer_phone }}</li>
                        <li class="list-group-item"><b>ที่อยู่:</b> {{ $list->satisfaction_customer_address }}</li>
                        <li class="list-group-item"><b>หน่วยงาน:</b> {{ $list->satisfaction_customer_department }}</li>
                    </ul>

                    <!-- ส่วนที่ 2: เรื่องที่ขอรับบริการ -->
                    <h5 class="mb-3 text-success">ส่วนที่ 2: เรื่องที่ขอรับบริการ</h5>
                    <p><b>หัวข้อ:</b> {{ $list->satisfaction_service_topic }}
                        @if ($list->satisfaction_service_topic == 'อื่นๆ')
                            ({{ $list->satisfaction_service_other }})
                        @endif
                    </p>

                    <!-- ส่วนที่ 3: ความพึงพอใจ -->
                    <h5 class="mb-3 text-success">ส่วนที่ 3: แบบสอบถามความพึงพอใจ</h5>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">หัวข้อประเมิน</th>
                                <th>คะแนน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">1. ความสุภาพของเจ้าหน้าที่</td>
                                <td>{{ $list->satisfaction_q1 }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">2. ความรวดเร็วในการให้บริการ</td>
                                <td>{{ $list->satisfaction_q2 }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">3. ความสะดวกของสถานที่ให้บริการ</td>
                                <td>{{ $list->satisfaction_q3 }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- ข้อมูลระบบ -->
                    
                    <div class="forum-header mt-3 text-muted">
                        <small>📅 วันที่บันทึก: {{ $list->satisfaction_date_insert }}</small><br>
                        <h3 class="mb-0"><a href="/backend/satisfaction/menu/54" class="btn btn-warning">ย้อนกลับ</a></h3>
                    </div>


                </div>
            </div>
        </div>

    </section>


@endsection
