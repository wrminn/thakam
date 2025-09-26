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

                    <form class="" action="{{ route('elibrary.insert', ['menu' => $menuId]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="slot" class="form-label">ปกหนังสือ</label>
                                    <input type="file" class="form-control" name="topic_picture" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="slot" class="form-label">ไฟล์ (pdf เท่านั้น)</label>
                                    <input type="file" class="form-control" name="file_pdf" accept=".pdf">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" name="topic" required>
                        </div>
                       
                        <button class="btn btn-success" type="submit" name="insert">
                            เพิ่มข้อมูล
                        </button>
                        <a href="{{ route('selectelibrary', ['menu' => $menuId]) }}" class="btn btn-warning">ย้อนกลับ</a>
                    </form>
                </div>
           
        </div>
    </div>
@endsection
