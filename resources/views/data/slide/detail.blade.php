@extends('layouts.app')
@section('title', $title)
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/template/detail.css') }}">

    <style>
        /* กล่อง lightbox ครอบหน้าจอทั้งหมด */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* ภาพขยายกลางจอ */
        .lightbox-content {
            display: block;
            margin: 12% auto;
            max-width: 90%;
            max-height: 90%;
        }

        /* ปุ่มปิด */
        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>



    <div class="container-body">
        <div class="title-menu">{{ $title }}</div>
        <div class="card detail-body">
            <nav aria-label="breadcrumb" class="breadcrumb">
                <ol class="breadcrumb custom-breadcrumb">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (empty($breadcrumb['url']))
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}"
                                    class="no-underline">{{ $breadcrumb['name'] }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>


            <div class="detail-directory">

                <div class="directory-title">{{ $list->slide_title }}</div>
                @if (!is_null($list->slide_path))
                    <div class="directory-img-topic">
                        <img src="{{ asset('storage/' . $list->slide_path) }}" alt="" width="400"
                            class="img-directory-topic">
                    </div>
                @endif
            </div>
          
            <div id="lightbox" class="lightbox">
                <span class="close">&times;</span>
                <img class="lightbox-content" id="lightbox-img">
            </div>
        </div>
    </div>

    <script>
        // เลือก element
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const clickableImgs = document.querySelectorAll('.clickable-img');
        const closeBtn = document.querySelector('.close');

        // เมื่อคลิกที่ภาพเล็ก
        clickableImgs.forEach(img => {
            img.addEventListener('click', function() {
                lightbox.style.display = 'block';
                lightboxImg.src = this.src;
                document.body.style.overflow = 'hidden';
            });

        });

        // ปิดเมื่อคลิกปุ่ม X
        closeBtn.addEventListener('click', function() {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // ปิดเมื่อคลิกนอกภาพ
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    </script>
@endsection
