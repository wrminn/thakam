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

                        </div>

                    </div>


                </caption>

                <thead>
                    <tr>
                        <th scope="col" class="col-1">ลำดับ</th>
                        <th scope="col"class="col-2" style="width: 50%;">ชื่อผู้ขอรับบริการ</th>
                        <th scope="col"class="col-2" style="width: 14%;">เรื่องที่ขอรับบริการ</th>
                        <th scope="col"class="col-3" style="width: 15%;">รายละเอียด</th>
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
                                <td>{{ Str::limit($item->satisfaction_customer_name, 30) }}</td>
                                <td>{{ $item->satisfaction_service_topic }}</td>
                                <td>
                                    <a href="{{ route('satisfactiondetail', ['menu' => $menuId, 'id' => $item->satisfaction_id]) }}">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            fdprocessedid="2gfdil">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z">
                                                </path>
                                            </svg>
                                            รายละเอียด
                                        </button>
                                    </a>
                                </td>
                               
                            </tr>
                        @endforeach

                    @endif


                </tbody>
            </table>
        </div>
    </div>
    {{ $list->links() }}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง'
            });
        </script>
    @endif
@endsection
