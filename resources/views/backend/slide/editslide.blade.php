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

            @if ($menuId == '69')

                <div class="card-body">

                    <form class="" action="{{ route('editslideone.video', ['menu' => $menuId, 'id' => $id]) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="floor" class="form-label">หัวข้อ</label>
                            <input type="text" class="form-control" name="title" required
                                value="{{ $list->slide_title }}">
                        </div>
                        @if ($list->slide_type == 'L')
                            <div class="mb-3">
                                <label for="floor" class="form-label">ลิงก์</label>
                                <input type="text" class="form-control" name="link" required
                                    value="{{ $list->slide_link }}">
                            </div>
                        @else
                            <div class="row">

                                <div class="col">
                                    <div class="mb-3">
                                        <label for="slot" class="form-label">วีดีโอ
                                            @if (!empty($list->slide_path))
                                                <img src="{{ asset('storage/' . $list->slide_path) }}" width="10%">
                                            @endif
                                        </label>
                                        <input type="file" class="form-control" name="video" id="videoInput"
                                            accept="video/mp4">
                                    </div>
                                </div>
                            </div>

                        @endif


                        <button class="btn btn-success" type="submit" name="insert">
                            บันทึก
                        </button>
                        <input class="btn btn-warning" type="button" value="ย้อนกลับ" onClick="history.go(-1)">
                    </form>
                </div>
            @else
                <div class="card-body">

                    <form class="" action="{{ route('editslideone', ['menu' => $menuId, 'id' => $id]) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col">
                                <div class="mb-3">
                                    <label for="slot" class="form-label">รูปหัวข้อ
                                        @if (!empty($list->slide_path))
                                            <img src="{{ asset('storage/' . $list->slide_path) }}" width="10%">
                                        @endif
                                    </label>
                                    <input type="file" class="form-control" name="topic_picture" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">หัวข้อ</label>
                            <input type="text" class="form-control" name="title" required
                                value="{{ $list->slide_title }}">
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">ลิงก์</label>
                            <input type="text" class="form-control" name="link" required
                                value="{{ $list->slide_link }}">
                        </div>

                        <button class="btn btn-success" type="submit" name="insert">
                            บันทึก
                        </button>
                        <a href="{{ route('selectslide', ['menu' => $menuId]) }}" class="btn btn-warning">ย้อนกลับ</a>
                    </form>
                </div>
            @endif
        </div>
    </div>


@endsection
