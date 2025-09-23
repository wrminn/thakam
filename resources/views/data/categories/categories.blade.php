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
                        <a href="{{ route('categories.data', ['menu' => $menuId, 'cate' => $item->categories_id]) }}"
                            class="no-underline">
                            <div class="directory-card-cate">
                                <div class="card-body-directory">
                                    <div class="directory-topic">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-list-nested" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.5 11.5A.5.5 0 0 1 5 11h10a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 1 3h10a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5" />
                                        </svg>
                                        {{ $item->categories_name }}
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
