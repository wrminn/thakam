@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <div class="text-center ">
        <div class="relative my-10 inline-block h-34" style="width: 594px">
            <img alt="{{ $title }}"class="w-full h-full object-contain" src="/spa/assets/list/label-frame.png">
            <span
                class="absolute inset-0 flex items-center justify-center text-[1.6rem] font-bold text-white drop-shadow-lg px-4 text-center">{{ $title }}</span>
        </div>
    </div>
    <section class="b-detail">
        <div class="form-wrapper">
            <img src="{{ asset('/storage/detailweb/logo.png') }}" alt="Logo" class="d-block mx-auto mb-3"
                style="max-width: 150px;">
            <h4 class="mb-4 text-center">เทศบาลตำบลบ้านโพธิ์</h4>
            <div class="list-group">
                @if ($list->isNotEmpty())
                    @foreach ($list as $item)
                        <a href="{{ route('showform', ['menu' => $menuId, 'id' => $item->gennericforms_id]) }}"
                            class="list-group-item list-group-item-action">{{ $item->gennericforms_name }}</a>
                    @endforeach
                @endif
            </div>
            <div class="d-flex justify-content-center align-items-center text-center mt-3">
                <a href="{{ asset('/คู่มือ E-service.pdf') }}" class="btn btn-primary" target="_black">
                    คู่มือการใช้งานระบบ E-Service
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
            </div>
        </div>
    </section>


@endsection
