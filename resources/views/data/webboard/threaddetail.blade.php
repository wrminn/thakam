@extends('layouts.app')
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
                <a href="{{ route('webboard', ['menu' => $menuId]) }}" class="btn btn-outline-secondary">⬅
                    ย้อนกลับ</a>
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
                    <div class="comment-card">
                        <div class="d-flex justify-content-between">
                            <span class="comment-author">โดย {{ $item_detail->posts_name }}</span>
                            <small class="comment-time">{{ $item_detail->posts_date_show }}</small>
                        </div>
                        <div class="comment-content">
                            {{ $item_detail->posts_content }}
                        </div>
                        <div class="comment-meta">
                            <span class="badge bg-primary category-badge">ความคิดเห็น</span>
                            <span class="ms-2 text-muted"><b>{{ $item_detail->posts_email ?? '-' }}</b></span>
                        </div>
                    </div>
                @endforeach
            @endif


            <!-- ฟอร์มแสดงความคิดเห็น -->
            <div class="thread-card mt-4">
                <h5>✍️ แสดงความคิดเห็น</h5>
                <form action="{{ route('Post.insert', ['menu' => $menuId, 'id' => $id]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control" rows="4" placeholder="พิมพ์ความคิดเห็นของคุณ..." name="content"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="threadTitle" class="form-label">ผู้โพสต์</label>
                        <input type="text" class="form-control" id="threadTitle" name="name"
                            placeholder="กรอกชื่อผู้โพสต์">
                    </div>
                    <div class="mb-3">
                        <label for="threadTitle" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="threadTitle" name="email" placeholder="กรอกอีเมล">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">ส่งความคิดเห็น</button>
                    </div>
                </form>
            </div>

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
