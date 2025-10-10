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
                        <a href="{{ route('elibrary.detail', ['menu' => $menuId, 'id' => $item->elibrary_id]) }}"
                            class="no-underline">
                            <div class="directory-card">
                                <div class="card-body-directory">
                                    <div class="directory-topic">
                                        <div class="directory-img-box">
                                            @if ($item->elibrary_path_page)
                                                <img src="{{ asset('storage/' . $item->elibrary_path_page) }}"
                                                    class="directory-img" alt="topic picture">
                                            @else
                                                <img src="{{ asset('img/representation.png') }}" alt="default logo"
                                                    class="directory-img" style="">
                                            @endif
                                        </div>
                                        {{ $item->elibrary_title }}
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
