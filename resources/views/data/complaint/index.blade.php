@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <div class="text-center ">
        <div class="relative my-10 inline-block h-34" style="width: 594px">
            <img alt="{{ $title }}"class="w-full h-full object-contain" src="/spa/assets/list/label-frame.png">
            <span class="absolute inset-0 flex items-center justify-center text-[1.6rem] font-bold text-white drop-shadow-lg px-4 text-center">{{ $title }}</span>
        </div>
    </div>
    <section class="b-detail">

        <div class="form-wrapper">
            <img src="{{ asset('/storage/detailweb/logo.png') }}" alt="Logo" class="d-block mx-auto mb-3"
                style="max-width: 150px;">
            <h3 class="mb-4 text-center">{{ $title }}</h3>
            <h4 class="mb-4 text-center">เทศบาลตำบลบ้านโพธิ์</h4>
            <form class="" action="{{ route('complaint.insert', ['menu' => $menuId]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ชื่อ - นามสกุล</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">อายุ</label>
                        <input type="number" min="10" max="100" class="form-control" name="age">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">เบอร์โทร</label>
                        <input class="form-control" type="tel" name="tel" pattern="[0-9]{10}" maxlength="10"
                            required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><strong>เพศ</strong></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="ชาย">
                        ชาย
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="หญิง">
                        หญิง
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">ที่อยู่</label>
                    <input type="text" name="address" class="form-control" placeholder="1234 Main St">
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" placeholder="excemple@gmail.com">
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">เรื่อง ร้องเรียนร้องทุกข์</label>
                    <input type="text" name="topic" class="form-control">
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="slot" class="form-label">รูปภาพ</label>
                        <input type="file" class="form-control" name="img" accept="image/*">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="slot" class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="detail" id="editor" cols="30" rows="6"></textarea>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary px-5">บันทึก</button>
                </div>
            </form>
        </div>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <script>
        $('#editor').summernote({
            placeholder: 'เขียนเนื้อหาที่นี่...',
            tabsize: 2,
            height: 300,
            toolbar: [
                // จัดชุดเครื่องมือเอง โดยไม่ใส่ picture และ video
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'table']], // เอา picture / video ออก
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>

@endsection
