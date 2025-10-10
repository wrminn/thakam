@extends('admin.layout')
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
                    {{ $title }}
                </div>
            </caption>
            <div class="card-body">

                <form class="" action="{{ route('listtexteditor.insert', ['menu' => $menuId]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="slot" class="form-label">เนื้อหา</label>
                        <textarea class="form-control" name="detail" id="" cols="30" rows="15">{{ $list->texteditor_detail ?? '' }}</textarea>
                    </div>
                    @if (!empty($file))

                        <div class="col">
                            <div class="mb-3">
                                <label for="slot" class="form-label">รายการไฟล์</label>
                                @foreach ($file as $item)
                                    <div class="filedetail">
                                        <a href="{{ asset('storage/' .$item->texteditor_upload_file) }}" target="_blank">{{ $item->texteditor_upload_name }}
                                            <a href="{{ route('delete.filetexteditor', ['menu' => $menuId,'id' => $item->texteditor_upload_id,]) }}"
                                                onclick="return confirm('ต้องการลบไฟล์นี้ใช่หรือไม่')"
                                                style="color:brown">&nbsp;&nbsp;&nbsp;&nbsp;
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5">
                                                    </path>
                                                </svg>
                                            </a>
                                        </a>
                                    </div>
                                @endforeach
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


@endsection
