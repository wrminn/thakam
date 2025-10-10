@extends('admin.layout')
@section('title', 'เพิ่มข้อมูล')
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
                    เพิ่มข้อมูลข่าวประชาสัมพันธ์
                </div>
            </caption>
            <div class="card-body">

                <form class="" action="{{ route('texteditor.insert', ['menu' => $menuId]) }}" method="post" enctype="multipart/form-data">
                    @csrf
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
                                <input type="file" class="form-control" name="topic_picture">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="floor" class="form-label">หัวข้อ</label>
                        <input type="text" class="form-control" name="topic" required>
                    </div>



                    <div class="mb-3">
                        <label for="slot" class="form-label">เนื้อหา</label>
                        <textarea class="form-control" name="detail" id="" cols="30" rows="6"></textarea>
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
                                <small class="text-muted">รองรับเฉพาะ .doc, .docx, .pdf, .xls, .xlsx สูงสุด 5 ไฟล์</small>
                            </div>
                        </div>
                    </div>




                    <button class="btn btn-success" type="submit" name="insert">
                        เพิ่มข้อมูล
                    </button>
                    <input class="btn btn-warning" type="button" value="ย้อนกลับ" onClick="history.go(-1)">
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('images').addEventListener('change', function() {
            if (this.files.length > 20) {
                alert("❌ เลือกรูปภาพได้สูงสุด 20 รูปเท่านั้น");
                this.value = ""; // reset input
            }
        });

        document.getElementById('files').addEventListener('change', function() {
            if (this.files.length > 5) {
                alert("❌ เลือกไฟล์เอกสารได้สูงสุด 5 ไฟล์เท่านั้น");
                this.value = ""; // reset input
            }
        });
    </script>
@endsection
