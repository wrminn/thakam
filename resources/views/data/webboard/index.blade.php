@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <div class="text-center ">
         <div class="title-menu">
            {{ $title }}
        </div>
    </div>
    <section class="b-detail">
        <style>
            .forum-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .threads-card {
                transition: 0.2s;
                border: 1px solid #e9ecef;
                background-color: #fff;
            }

            .threads-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            }

            .threads-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #0d6efd;
                text-decoration: none;
            }

            .threads-title:hover {
                text-decoration: underline;
            }

            .threads-meta {
                font-size: 0.9rem;
                color: #6c757d;
            }

            .category-badge {
                font-size: 0.75rem;
            }

            .stats {
                font-size: 0.85rem;
                color: #6c757d;
            }
        </style>


        <!-- Main -->
        <div class="container my-4">

            <!-- Header -->
            <div class="forum-header">
                {{-- <h3 class="mb-0">📌 กระดานกระทู้</h3> --}}
                @if (!$list->isEmpty())
                    <a href="{{ route('thread', ['menu' => $menuId]) }}" class="btn btn-primary btn-sm">+ ตั้งกระทู้ใหม่</a>
                @endif
            </div>
            @if ($list->isEmpty())
                <div class="alert alert-light border text-center py-5" role="alert">
                    <h5 class="mb-2">📭 ยังไม่มีกระทู้ในขณะนี้</h5>
                    <p class="mb-3 text-muted">เริ่มต้นสร้างกระทู้แรกได้เลย!</p>
                    <a href="{{ route('thread', ['menu' => $menuId]) }}" class="btn btn-primary btn-sm">+ ตั้งกระทู้ใหม่</a>
                </div>
            @else
                @foreach ($list as $item)
                    <div class="list-group">

                        <a href="{{ route('Thread.detail', ['menu' => $menuId, 'id' => $item->threads_id]) }}"
                            class="list-group-item list-group-item-action threads-card mb-3 rounded p-3">
                            <div class="d-flex justify-content-between">
                                <h5 class="threads-title mb-1">{{ $item->threads_title }}</h5>
                                <small class="threads-meta">⏰ {{ $item->threads_date_show }}</small>
                            </div>
                            <p class="mb-2 text-muted">{{ Str::limit($item->threads_content, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-secondary category-badge">{{ $item->threads_name }}</span>
                                    <span class="ms-2 threads-meta">โดย <b>{{ $item->threads_email }}</b></span>
                                </div>
                                <div class="stats">
                                    👁️ {{ $item->threads_view ?? '0' }} | 💬 {{ $item->form_count ?? '0' }}
                                </div>
                            </div>
                        </a>
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
