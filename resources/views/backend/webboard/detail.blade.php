@extends('backend.menu.layout')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <section class="b-detail">
        <style>
            .thread-card,
            .comment-card {
                border: 1px solid #e9ecef;
                background-color: #fff;
                border-radius: 10px;
                padding: 1rem;
                margin-bottom: 1rem;
                transition: 0.2s;
            }

            .thread-card:hover,
            .comment-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            }

            .thread-title {
                font-size: 1.2rem;
                font-weight: 600;
                color: #0d6efd;
                text-decoration: none;
            }

            .thread-title:hover {
                text-decoration: underline;
            }

            .comment-author {
                font-weight: 600;
                color: #495057;
            }

            .comment-time {
                font-size: 0.85rem;
                color: #6c757d;
            }

            .comment-content {
                margin-top: 0.3rem;
                color: #495057;
            }

            .forum-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }
        </style>


        <div class="container my-5">
            <div class="forum-header mb-3">
                <h3 class="mb-0">📌 กระดานกระทู้</h3>
                <h3 class="mb-0"><a href="/backend/webboard/menu/56" class="btn btn-warning">ย้อนกลับ</a></h3>
            </div>
            <!-- กระทู้หลัก -->
            <div class="thread-card">
                <div class="d-flex justify-content-between">
                    <h3 class="thread-title">{{ $list->threads_title }}</h3>
                    <small class="comment-time">⏰ {{ $list->threads_date_show }}</small>
                </div>
                <p class="mt-2 text-muted">
                    {{ $list->threads_content }}
                </p>
                <div>
                    <span class="badge bg-info text-dark">{{ $list->threads_name }}</span>
                    <span class="ms-2 comment-time">โดย <b>{{ $list->threads_email }}</b></span>
                </div>
            </div>

            <!-- ความคิดเห็น -->
            <h5 class="mt-4 mb-3">💬 ความคิดเห็น</h5>


            @if ($detail->isEmpty())
                <div class="alert alert-light border text-center py-5" role="alert">
                    <h5 class="mb-2">📭 ยังไม่มีความคิดเห็นในขณะนี้</h5>
                    {{-- <p class="mb-3 text-muted">เริ่มต้นสร้างกระทู้แรกได้เลย!</p> --}}
                </div>
            @else
                @foreach ($detail as $item_detail)
                    <div class="comment-card p-3 mb-3 border rounded shadow-sm">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">โดย {{ $item_detail->posts_name }}</span>
                            <small class="text-muted">{{ $item_detail->posts_date_show }}</small>
                        </div>

                        <div class="comment-content mb-2">
                            {{ $item_detail->posts_content }}
                        </div>

                        <div class="mb-2">
                            <span class="badge bg-primary">ความคิดเห็น</span>
                            <span class="ms-2 text-muted"><b>{{ $item_detail->posts_email ?? '-' }}</b></span>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            @if ($item_detail->posts_status == 'O')
                                <a href="{{ route('hidepost', ['menu' => $menuId, 'id' => $item_detail->threads_id]) }}"
                                    class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                                        <path
                                            d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
                                    </svg>
                                    ปิด
                                </a>
                            @else
                                <a href="{{ route('openpost', ['menu' => $menuId, 'id' => $item_detail->threads_id]) }}"
                                    class="btn btn-outline-success btn-sm d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                        <path
                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                    </svg>
                                    เปิด
                                </a>
                            @endif

                            <a href="{{ route('deletepost', ['menu' => $menuId, 'id' => $item_detail->threads_id]) }}"
                                onclick="return confirm('ต้องการลบบทความหรือไม่');"
                                class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                    class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg>
                                ลบ
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </section>

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
