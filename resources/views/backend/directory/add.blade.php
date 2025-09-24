@extends('backend.menu.layout')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="card">
            <caption>

                <div class="title-list fs-4 md-2 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-file-earmark-text" viewBox="0 0 16 16" style="margin-top: -4px;">
                        <path
                            d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5">
                        </path>
                        <path
                            d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z">
                        </path>
                    </svg>
                    เพิ่มข้อมูล {{ $title }}
                </div>
            </caption>
            <div class="card-body">
                @if (empty($cateID))
                    <form class="" action="{{ route('directory.insert', ['menu' => $menuId]) }}" method="post"
                        enctype="multipart/form-data">
                    @else
                        <form class=""
                            action="{{ route('directory.insert.datacat', ['menu' => $menuId, 'cate' => $cateID]) }}"
                            method="post" enctype="multipart/form-data">
                @endif

                @csrf
                @if (empty($cateID))
                    <div class="row">

                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">วันที่</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="slot" class="form-label">รูปหัวข้อ</label>
                                <input type="file" class="form-control" name="topic_picture" accept="image/*">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="floor" class="form-label">หัวข้อ</label>
                    <input type="text" class="form-control" name="topic" required>
                </div>

                <div class="mb-3">
                    <label for="slot" class="form-label">เนื้อหา</label>
                    <textarea class="form-control" name="detail" id="editor" cols="30" rows="6"></textarea>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">เพิ่มรูปภาพ (สูงสุด 20 รูป)</label>
                            <input type="file" id="images" name="images[]" class="form-control" accept="image/*"
                                multiple>
                            <small class="text-muted">อัพโหลดได้สูงสุด 20 รูป</small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">เพิ่มไฟล์เอกสาร (สูงสุด 5 ไฟล์)</label>
                            <input type="file" id="files" name="files[]" class="form-control"
                                accept=".doc,.docx,.pdf,.xls,.xlsx" multiple>
                            <small class="text-muted">รองรับเฉพาะ .doc, .docx, .pdf, .xls, .xlsx สูงสุด 5
                                ไฟล์</small>
                        </div>
                    </div>
                </div>

                <button class="btn btn-success" type="submit" name="insert">
                    เพิ่มข้อมูล
                </button>
                @if (empty($cateID))
                    <a href="{{ route('directory', ['menu' => $menuId]) }}" class="btn btn-warning">ย้อนกลับ</a>
                @else
                    <a href="{{ route('directory.category', ['menu' => $menuId, 'cate' => $cateID]) }}"
                        class="btn btn-warning">ย้อนกลับ</a>
                @endif

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <script>
        document.getElementById('images').addEventListener('change', function() {
            const maxFiles = 20;
            const maxSize = 20 * 1024 * 1024;
            let isValid = true;

            // ตรวจสอบจำนวนไฟล์
            if (this.files.length > maxFiles) {
                alert(`❌ เลือกรูปภาพได้สูงสุด ${maxFiles} รูปเท่านั้น`);
                isValid = false;
            }

            // ตรวจสอบขนาดไฟล์
            for (let file of this.files) {
                if (file.size > maxSize) {
                    alert(`❌ ไฟล์ ${file.name} มีขนาดเกิน 20MB`);
                    isValid = false;
                    break;
                }
            }

            // ถ้าไม่ผ่านเงื่อนไขใด ๆ reset ค่า
            if (!isValid) {
                this.value = "";
            }
        });

        document.getElementById('files').addEventListener('change', function() {
            const maxFiles = 5;
            const maxSize = 25 * 1024 * 1024;
            let isValid = true;


            if (this.files.length > maxFiles) {
                alert(`❌ เลือกไฟล์ได้สูงสุด ${maxFiles} ไฟล์เท่านั้น`);
                isValid = false;
            }

            for (let file of this.files) {
                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    alert(`❌ ไฟล์ ${file.name} (${fileSizeMB} MB) เกิน 25MB`);
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                this.value = "";
            }
        });
    </script>

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
