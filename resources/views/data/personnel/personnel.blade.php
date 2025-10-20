@extends('layouts.app')
@section('title', $title)
@section('content')
    <style>
        .leader p {
            margin: 0;
            font-size: 14px;
            color: #555
        }


        .deputies {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: space-evenly;
            flex-direction: row;
            flex-wrap: wrap;
        }


        .per-content {
            text-align: center;
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            height: 410px;
        }


        .deputy img,
        .leader img {
            width: 200px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform .2s;
            height: 250px;
        }

        .deputy img:hover,
        .leader img:hover {
            transform: scale(1.05)
        }


        .deputy h3,
        .leader h3 {
            margin: 10px 0 5px;
            font-size: 16px
        }

        .deputy p {
            margin: 0;
            font-size: 13px;
            color: #444
        }


        .leader {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }


        @media(max-width:600px) {
            .leader img {
                width: 140px
            }

            .deputy img {
                width: 100px
            }
        }

        .img-group {
            padding: 40px;
        }
    </style>
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
                    <div class="org-chart">
                        @foreach ($list as $item)
                            <!-- Governor -->
                            @if ($item['personnel_seq'] == '1')
                                <div class="leader">
                                    <div class="per-content">
                                        <img class="leader-img" src="{{ asset('storage/' . $item->personnel_path) }}"
                                            alt="Deputy" data-name="{{ $item->personnel_name }}"
                                            data-role="{{ $item->personnel_position }}" />
                                        <h3>{{ $item->personnel_name }}</h3>
                                        <p>{{ $item->personnel_position }}</p>
                                        <p>{{ $item->personnel_tel }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="deputies">
                            @foreach ($list as $item)
                                <!-- Deputies -->

                                @if ($item['personnel_seq'] != '1')
                                    <div class="deputy">
                                        <div class="per-content">
                                            <img class="leader-img" src="{{ asset('storage/' . $item->personnel_path) }}"
                                                alt="Deputy" data-name="{{ $item->personnel_name }}"
                                                data-role="{{ $item->personnel_position }}" />
                                            <h3>{{ $item->personnel_name }}</h3>
                                            <p>{{ $item->personnel_position }}</p>
                                            <p>{{ $item->personnel_tel }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <section class="img-group">
                <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade position-relative"
                    data-bs-ride="carousel" data-bs-interval="2500">
                    <div class="carousel-inner mt-5">
                        @forelse($File as $key => $slide)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $slide->personnelgroup_path) }}" class="d-block w-100"
                                    alt="slide {{ $key + 1 }}"
                                    style="width: 1905px; height:600px; object-fit: cover;">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="https://www.w3schools.com/howto/img_snow_wide.jpg" class="d-block w-100"
                                    alt="..." style="width: 1905px; height:600px; object-fit: cover;">
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>


        </div>
        <div class="mt-5">
            {{ $list->links() }}
        </div>
    </div>


@endsection
