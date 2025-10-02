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
                        <a href="{{ route('slide.detail', ['menu' => $menuId, 'id' => $item->slide_id]) }}"
                            class="no-underline">
                            <div class="directory-card">
                                <div class="card-body-directory">
                                    <div class="directory-topic">
                                        <div class="directory-img-box">
                                            @if ($item->slide_path)
                                                <img src="{{ asset('storage/' . $item->slide_path) }}"
                                                    class="directory-img" alt="topic picture">
                                            @else
                                                <img src="{{ asset('img/logo.png') }}" alt="default logo"
                                                    class="directory-img" style="">
                                            @endif
                                        </div>
                                        {{ $item->slide_title }}
                                    </div>
                                   
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
