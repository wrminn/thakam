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
            @if ($menuId == '69')
                <div class="card-body">
                    <form id="uploadForm" action="{{ route('slide.insert.video', ['menu' => $menuId]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="floor" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" name="topic" required>
                            </div>
                            <label class="form-label">เลือกประเภท</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inputType" id="radioVideo"
                                    value="V" required>
                                <label class="form-check-label" for="radioVideo">อัปโหลดวิดีโอ</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inputType" id="radioLink"
                                    value="L" required>
                                <label class="form-check-label" for="radioLink">กรอกลิงก์</label>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="videoGroup">
                            <label class="form-label">วิดีโอ (MP4 ไม่เกิน 3 นาที)</label>
                            <input type="file" class="form-control" name="video" id="videoInput" accept="video/mp4">
                            <small class="text-danger d-none" id="videoError">❌ วิดีโอห้ามเกิน 3 นาที</small>
                        </div>

                        <div class="mb-3 d-none" id="linkGroup">
                            <label class="form-label">ลิงก์</label>
                            <input type="text" class="form-control" name="link" id="linkInput">
                        </div>

                        <button class="btn btn-success" type="submit" name="insert">
                            เพิ่มข้อมูล
                        </button>
                        <input class="btn btn-warning" type="button" value="ย้อนกลับ" onClick="history.go(-1)">
                    </form>
                </div>
 
                <script>
                    const radioVideo = document.getElementById('radioVideo');
                    const radioLink = document.getElementById('radioLink');
                    const videoGroup = document.getElementById('videoGroup');
                    const linkGroup = document.getElementById('linkGroup');
                    const videoInput = document.getElementById('videoInput');
                    const linkInput = document.getElementById('linkInput');
                    const videoError = document.getElementById('videoError');
                    const form = document.getElementById('uploadForm');

                    // ฟังก์ชัน toggle ช่องกรอก + required
                    function toggleInput() {
                        if (radioVideo.checked) {
                            videoGroup.classList.remove('d-none');
                            linkGroup.classList.add('d-none');
                            linkInput.value = "";
                            linkInput.removeAttribute('required');
                            videoInput.setAttribute('required', 'required');
                        } else if (radioLink.checked) {
                            linkGroup.classList.remove('d-none');
                            videoGroup.classList.add('d-none');
                            videoInput.value = "";
                            videoError.classList.add('d-none');
                            videoInput.removeAttribute('required');
                            linkInput.setAttribute('required', 'required');
                        }
                    }

                    radioVideo.addEventListener('change', toggleInput);
                    radioLink.addEventListener('change', toggleInput);

                    // ตรวจสอบความยาววิดีโอ
                    videoInput.addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const video = document.createElement('video');
                            video.preload = 'metadata';
                            video.onloadedmetadata = function() {
                                window.URL.revokeObjectURL(video.src);
                                if (video.duration > 180) {
                                    videoError.classList.remove('d-none');
                                    event.target.value = "";
                                } else {
                                    videoError.classList.add('d-none');
                                }
                            };
                            video.src = URL.createObjectURL(file);
                        }
                    });

                    // validation เพิ่มเติมก่อน submit
                    form.addEventListener('submit', function(e) {
                        if (radioVideo.checked && !videoInput.value) {
                            e.preventDefault();
                            alert("กรุณาอัปโหลดวิดีโอ");
                        }
                        if (radioLink.checked && !linkInput.value.trim()) {
                            e.preventDefault();
                            alert("กรุณากรอกลิงก์");
                        }
                    });
                </script>
            @else
                <div class="card-body">

                    <form class="" action="{{ route('slide.insert', ['menu' => $menuId]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col">
                                <div class="mb-3">
                                    <label for="slot" class="form-label">รูป</label>
                                    <input type="file" class="form-control" name="topic_picture" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" name="topic" required>
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">ลิงก์</label>
                            <input type="text" class="form-control" name="link" required>
                        </div>

                        <button class="btn btn-success" type="submit" name="insert">
                            เพิ่มข้อมูล
                        </button>
                        <a href="{{ route('selectslide', ['menu' => $menuId]) }}" class="btn btn-warning">ย้อนกลับ</a>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
