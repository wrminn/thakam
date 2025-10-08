@extends('layouts.app')
@section('title', $title)
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/template/detail.css') }}">

    <style>
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

        .lightbox-content {
            display: block;
            margin: 12% auto;
            max-width: 90%;
            max-height: 90%;
        }

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

    <link href="{{ asset('dflip/assets/css/dflip.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .book-container {
            margin-bottom: 50px;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 8px;
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
            <div class="container" style="padding: 20px;">

                <div class="book-container">
                    <h3>ไฟล์: {{ $list->elibrary_title }}</h3>

                    <div class="_df_book" id="df_book_{{ $list->elibrary_id }}"
                        source="{{ asset('storage/' . $list->elibrary_path) }}" style="width: 100%; height: 600px;"
                        flatDisplay="true" backgroundColor="#f9f9f9" webgl="true" sound="true" height="600">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('dflip/assets/js/dflip.min.js') }}" type="text/javascript"></script>


@endsection
