@extends('backend.menu.layout')
@section('title', $title)
@section('content')


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
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
                    {{ $title }}
                </div>
            </caption>
            <div class="card-body">

                <form class="" action="{{ route('articles.insert', ['menu' => $menuId]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="slot" class="form-label">เนื้อหา</label>
                        <textarea class="form-control" name="detail" id="editor" cols="50" rows="15">{{ $list->texteditor_detail ?? '' }}</textarea>
                    </div>
                    @if (!empty($file))

                        <div class="col">
                            <div class="mb-3">
                                <label for="slot" class="form-label">รายการไฟล์</label>
                                @foreach ($file as $item)
                                    <div class="filedetail">
                                        <a href="{{ asset('storage/' . $item->texteditor_upload_file) }}"
                                            target="_blank">{{ $item->texteditor_upload_name }}
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="deleteItem('{{ route('articles.deletefile', ['menu' => $menuId, 'id' => $item->texteditor_upload_id]) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5">
                                                    </path>
                                                </svg>
                                                ลบ
                                            </button>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

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
                                                            <input type="checkbox"
                                                                class="form-check-input toggle-visibility"
                                                                data-id="{{ $item->texteditor_upload_id }}"
                                                                {{ $item->texteditor_show === 'Y' ? 'checked' : '' }}
                                                                {{ $isPdf ? '' : 'disabled' }}>
                                                        </div>

                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteItem('{{ route('articles.deletefile', ['menu' => $menuId, 'id' => $item->texteditor_upload_id]) }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                height="12" fill="currentColor" class="bi bi-trash3"
                                                                viewBox="0 0 16 16">
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

                    <div class="col">
                        <div class="mb-3">
                            <label for="slot" class="form-label">ไฟล์</label>
                            <input type="file" class="form-control" name="file">
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" name="insert">
                        บันทึก
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script> --}}

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
