@extends('layouts.app')
@section('title', $title)
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/template/detail.css') }}">
    <div class="container-body">
        <div class="title-menu">{{ $title }}</div>
        <div class="card detail-body">
            <nav aria-label="breadcrumb" class="breadcrumb">
                <ol class="breadcrumb custom-breadcrumb">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (empty($breadcrumb['url']) || $loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}"
                                    class="no-underline">{{ $breadcrumb['name'] }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            <div class="directory-box menu-{{ $menuId }}">
                @if ($list->isEmpty())
                    <div class="No-information-found">
                        ไม่พบข้อมูล
                    </div>
                @else
                    @foreach ($list as $item)
                        <a href="{{ route('directory.detail', ['menu' => $menuId, 'id' => $item->texteditor_id]) }}"
                            class="no-underline">
                            <div class="directory-card">
                                <div class="card-body-directory">
                                    <div class="directory-topic">
                                        <div class="directory-img-box">
                                            @if ($item->texteditor_topic_picture)
                                                <img src="{{ asset('storage/' . $item->texteditor_topic_picture) }}"
                                                    class="directory-img" alt="topic picture">
                                            @else
                                                <img src="{{ asset('img/logo.png') }}" alt="default logo"
                                                    class="directory-img" style="">
                                            @endif
                                        </div>
                                        {{ $item->texteditor_title }}
                                    </div>
                                    <div class="directory-date"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-calendar-event"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                        </svg>
                                        {{ $item->texteditor_date_show_buddhist }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

        </div>
        <div class="mt-5">
            {{ $list->links() }}
        </div>
    </div>



@endsection
