@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <div class="text-center ">
        <div class="relative my-10 inline-block h-34" style="width: 594px">
            <img alt="{{ $title }}"class="w-full h-full object-contain" src="/spa/assets/list/label-frame.png">
            <span
                class="absolute inset-0 flex items-center justify-center text-[1.6rem] font-bold text-white drop-shadow-lg px-4 text-center">{{ $title }}</span>
        </div>
    </div>
    <section class="b-detail">
        <form action="{{ route('satisfaction.insert', ['menu' => $menuId]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container my-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">แบบสอบถามความพึงพอใจของประชาชน</h4>
                    </div>
                    <div class="card-body p-4">

                        <!-- ส่วนที่ 1: ข้อมูลทั่วไป -->
                        <h5 class="mb-3 text-primary">ส่วนที่ 1: ข้อมูลทั่วไปผู้ขอรับบริการ</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">ชื่อผู้ขอรับบริการ</label>
                                <input type="text" class="form-control" name="customer_name"
                                    placeholder="กรอกชื่อผู้ขอรับบริการ" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">โทรศัพท์</label>
                                <input class="form-control" type="tel" name="customer_phone" pattern="[0-9]{10}"
                                    maxlength="10" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ที่อยู่</label>
                                <textarea class="form-control" name="customer_address" rows="2" placeholder="กรอกที่อยู่" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">หน่วยงานที่มาขอรับบริการ</label>
                                <input type="text" class="form-control" name="customer_department" required>
                            </div>
                        </div>

                        <!-- ส่วนที่ 2: เรื่องที่ขอรับบริการ -->
                        <h5 class="mb-3 text-primary">ส่วนที่ 2: เรื่องที่ขอรับบริการ</h5>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="service_topic" value="ขอข้อมูลข่าวสาร"
                                    id="topic1" required>
                                <label class="form-check-label" for="topic1">การขอข้อมูลข่าวสาร</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="service_topic"
                                    value="เบี้ยยังชีพผู้สูงอายุ" id="topic2" required>
                                <label class="form-check-label" for="topic2">การขอรับเบี้ยยังชีพผู้สูงอายุ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="service_topic"
                                    value="ยื่นเรื่องร้องทุกข์" id="topic3" required>
                                <label class="form-check-label" for="topic3">การยื่นเรื่องร้องทุกข์</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="service_topic"
                                    value="ขออนุญาตปลูกสร้างอาคาร" id="topic4" required>
                                <label class="form-check-label" for="topic4">การขออนุญาตปลูกสร้างอาคาร</label>
                            </div>
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="radio" name="service_topic" value="อื่นๆ"
                                    id="topic5" required>
                                <label class="form-check-label me-2" for="topic5">อื่น ๆ</label>
                                <input type="text" class="form-control form-control-sm w-50" name="service_other"
                                    placeholder="โปรดระบุ">
                            </div>
                        </div>

                        <!-- ส่วนที่ 3: ความพึงพอใจ -->
                        <h5 class="mb-3 text-primary">ส่วนที่ 3: แบบสอบถามความพึงพอใจ</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">หัวข้อประเมิน</th>
                                        <th>มากที่สุด (5)</th>
                                        <th>มาก (4)</th>
                                        <th>ปานกลาง (3)</th>
                                        <th>น้อย (2)</th>
                                        <th>ควรปรับปรุง (1)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">1. ความสุภาพของเจ้าหน้าที่</td>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <td><input type="radio" name="q1" value="{{ $i }}" required>
                                            </td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-start">2. ความรวดเร็วในการให้บริการ</td>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <td><input type="radio" name="q2" value="{{ $i }}" required>
                                            </td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-start">3. ความสะดวกของสถานที่ให้บริการ</td>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <td><input type="radio" name="q3" value="{{ $i }}"
                                                    required>
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">ข้อเสนอแนะ</label>
                            <textarea class="form-control" name="suggestions" rows="2"></textarea>
                        </div>
                        <input type="text" name="ch" value="" style="display: none">

                        <!-- ปุ่ม -->
                        <div class="d-flex justify-content-between">
                            <a href="/backend/webboard/menu/56" class="btn btn-secondary">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-primary">ส่งแบบสอบถาม</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง'
            });
        </script>
    @endif
@endsection
