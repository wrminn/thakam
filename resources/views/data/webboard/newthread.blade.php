@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <section class="b-detail">
        <style>
            .form-thread {
                background: #fff;
                padding: 2rem;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            }
        </style>


        <!-- Main -->
        <div class="container my-5">
            <div class="forum-header mb-3">
                <h3 class="mb-0">📌 กระดานกระทู้</h3>
            </div>
            <div class="form-thread">
                <h3 class="mb-4">📝 ตั้งกระทู้ใหม่</h3>

                <form action="{{ route('thread.insert', ['menu' => $menuId]) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="threadTitle" class="form-label">ชื่อกระทู้</label>
                        <input type="text" class="form-control" id="threadTitle" name="title"
                            placeholder="กรอกชื่อกระทู้">
                    </div>


                    <div class="mb-3">
                        <label for="threadContent" class="form-label">เนื้อหา</label>
                        <textarea class="form-control" id="threadContent" rows="6" name="content"
                            placeholder="พิมพ์รายละเอียดกระทู้ที่นี่..."></textarea>
                    </div>


                    <div class="mb-3">
                        <label for="threadTitle" class="form-label">ผู้โพสต์</label>
                        <input type="text" class="form-control" id="threadTitle" name="name"
                            placeholder="กรอกชื่อผู้โพสต์" required>
                    </div>
                    <div class="mb-3">
                        <label for="threadTitle" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="threadTitle" name="email" placeholder="กรอกอีเมล" required>
                    </div>


                    <!-- ปุ่ม -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('webboard', ['menu' => $menuId]) }}" class="btn btn-outline-secondary">⬅
                            ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">💾 บันทึกกระทู้</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


@endsection
