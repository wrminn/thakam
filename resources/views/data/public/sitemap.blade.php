@extends('layouts.app')
@section('title', 'แผนผังเว็บไซต์')

@section('content')
    <link rel="stylesheet" href="{{ asset('/css/template/detail.css') }}">
    <style>
        .detail-articles,
        .detail-directory {
            padding: 30px 30px;
        }

        .sitemap-container {
            padding: 20px 30px;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .sitemap-title {
            font-size: 26px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            border-left: 6px solid #0077d4;
            padding-left: 12px;
        }

        .sitemap-section {
            margin-bottom: 30px;
        }

        .sitemap-section h3 {
            font-size: 20px;
            font-weight: bold;
            color: #222;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e3e3e3;
        }

        .sitemap-list ul {
            margin-left: 20px;
            padding-left: 0;
            list-style-type: none;
        }

        .sitemap-list li {
            margin: 5px 0;
            position: relative;
            padding-left: 16px;
        }

        .sitemap-list li::before {
            content: "•";
            color: #0077d4;
            font-size: 20px;
            position: absolute;
            left: 0;
            top: -2px;
        }

        .sitemap-list a {
            color: #0066cc;
            text-decoration: none;
            font-size: 15px;
        }

        .sitemap-list a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="container-body">
        <div class="title-menu">แผนผังเว็บไซต์</div>
        <div class="card detail-body">
            <div class="detail-directory">

                {{-- ลูปเมนูหลัก --}}
                @foreach ($menus as $mainTitle => $items)
                    <div class="sitemap-section">
                        <h3>{{ $mainTitle }}</h3>

                        <div class="sitemap-list">
                            <ul>
                                @foreach ($items as $item)
                                    <li><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection
