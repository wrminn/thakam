@extends('backend.menu.layout')
@section('title', $title)
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table caption-top" id="myTable">
                <caption>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="title-list fs-4 mb-1">
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

                            {{-- <p class="sum-list">ทั้งหมด 1 รายการ</p> --}}
                        </div>
                        <div class="col-md-1 ms-auto">
                            <a href="/backend/addbanner/menu/{{ $menuId }}"><button type="button"
                                    class="btn btn-outline-primary" fdprocessedid="2gfdil">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z">
                                        </path>
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z">
                                        </path>
                                    </svg>
                                    เพิ่มข้อมูล
                                </button>
                            </a>
                        </div>
                    </div>


                </caption>

                <thead>
                    <tr>
                        <th scope="col" class="col-1">ลำดับ</th>
                        <th scope="col"class="col-2" style="width: 55%;">ชื่อ</th>
                        <th scope="col"class="col-3" style="width: 15%;">ลิงก์</th>
                        <th scope="col"class="col-3">ภาพ</th>
                        {{-- <th scope="col">รายการไฟล์</th> --}}
                        <th scope="col"class="col-4">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($list->isEmpty())
                        <tr>
                            <td colspan="5" align="center">ไม่มีข้อมูลในหัวข้อนี้</td>
                        </tr>
                    @else
                        @foreach ($list as $item)
                            <tr>
                                <th scope="row">{{ $startIndex + $loop->index }}</th>
                                <td>{{ Str::limit($item->banner_title, 30) }}</td>
                                <td>{{ Str::limit($item->banner_link, 20) }}</td>
                                <td>
                                    @if (!empty($item->banner_topic_picture))
                                        <img src="{{ asset('storage/' . $item->banner_topic_picture) }}" width="30%">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('editbannerone', ['menu' => $menuId, 'id' => $item->banner_id]) }}">

                                        <button type="button" class="btn btn-outline-warning" fdprocessedid="2gfdil">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z">
                                                </path>
                                            </svg>
                                            แก้ไข
                                        </button>
                                    </a>

                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="deleteItem('{{ route('deletebannerone', ['menu' => $menuId, 'id' => $item->banner_id]) }}')">
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

                    @endif


                </tbody>
            </table>
        </div>
    </div>
    {{ $list->links() }}
    <script>
        function deleteItem(url) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "เมื่อกดยืนยัน ข้อมูลที่คุณเลือกจะถูกลบออกจากระบบ",
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
