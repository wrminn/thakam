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
                    แก้ไข {{ $title }}
                </div>
            </caption>
            <div class="card-body">

                @if (empty($cateID))
                    <form class="" action="{{ route('directory.update', ['menu' => $menuId, 'id' => $id]) }}"
                        method="post" enctype="multipart/form-data">
                    @else
                        <form class=""
                            action="{{ route('directory.edit.datacat', ['menu' => $menuId, 'id' => $id, 'cate' => $cateID]) }}"
                            method="post" enctype="multipart/form-data">
                @endif

                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">วันที่</label>
                            <input type="date" name="date" class="form-control" required
                                value="{{ $list->texteditor_date_show }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">

                            <label for="slot" class="form-label">รูปหัวข้อ
                                @if (!empty($list->texteditor_topic_picture))
                                    <img src="{{ asset('storage/' . $list->texteditor_topic_picture) }}" width="10%">
                                @endif
                            </label>
                            <input type="file" class="form-control" name="topic_picture" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="floor" class="form-label">หัวข้อ</label>
                    <input type="text" class="form-control" name="topic" required
                        value="{{ $list->texteditor_title }}">
                </div>

                <div class="mb-3">
                    <label for="slot" class="form-label">เนื้อหา</label>
                    <textarea class="form-control" name="detail" id="editor" cols="30" rows="6">{{ $list->texteditor_detail ?? '' }}</textarea>
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

                @if (!empty($file))

                    <div class="col">
                        <div class="mb-3">
                            <label for="slot" class="form-label fw-bold">📂 รายการไฟล์</label>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th style="width: 5%">#</th>
                                            <th>ชื่อไฟล์</th>
                                            <th style="width: 10%">การแสดงผล</th>
                                            <th style="width: 10%">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($file as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $item->texteditor_upload_file) }}"
                                                        target="_blank">
                                                        {{ $item->texteditor_upload_name }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $isPdf =
                                                            strtolower(
                                                                pathinfo(
                                                                    $item->texteditor_upload_name,
                                                                    PATHINFO_EXTENSION,
                                                                ),
                                                            ) === 'pdf';
                                                    @endphp

                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input type="checkbox" class="form-check-input toggle-visibility"
                                                            data-id="{{ $item->texteditor_upload_id }}"
                                                            {{ $item->texteditor_show === 'Y' ? 'checked' : '' }}
                                                            {{ $isPdf ? '' : 'disabled' }}>
                                                    </div>

                                                </td>
                                                <td class="text-center">
                                                    @if (empty($cateID))
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteItem('{{ route('directory.deleteOnefile', ['menu' => $menuId, 'id' => $item->texteditor_id, 'idfile' => $item->texteditor_upload_id]) }}')">
                                                        @else
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="deleteItem('{{ route('directory.deleteOnefile.category', ['menu' => $menuId, 'id' => $item->texteditor_id, 'idfile' => $item->texteditor_upload_id, 'cate' => $cateID]) }}')">
                                                    @endif
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5">
                                                        </path>
                                                    </svg>
                                                    ลบ
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                @endif

                <button class="btn btn-success" type="submit" name="insert">
                    บันทึก
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-visibility').on('change', function() {
                let idfile = $(this).data('id');

                $.ajax({
                    url: "{{ route('directory.toggleFileVisibility') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        idfile: idfile
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response.message);
                        } else {
                            alert('เกิดข้อผิดพลาด: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('ไม่สามารถอัปเดตสถานะได้');
                    }
                });
            });
        });
    </script>
    <!-- sweetalert2 -->
    <script>
        function deleteItem(url) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "เมื่อกดยืนยัน ไฟล์ที่คุณเลือกจะถูกลบออกจากระบบ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // redirect ไปที่ route ลบ
                }
            })
        }
    </script>
@endsection
