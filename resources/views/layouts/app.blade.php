<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="/img/logo.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'เทศบาลตำบลท่าข้าม')</title>
    <!-- Fonts -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/home.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/book.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/mo2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/mobs.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/template/or.css') }}">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- <script src="bootstrap/js/bootstrap.min.js"></script> --}}
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>


<body>
    <div id="app">
        <section class="header-section">
            <div class="header-container">
                <div class="header-text-left">
                    <a href="/home"><img src="/img/logo.png" alt="โลโก้" class="header-logo"></a>
                    <div class="heard-title-box">
                        <div class="header-title-th">เทศบาลตำบลท่าข้าม</div>
                        <div class="header-title-en">Thakam Subdistrict Municipality</div>
                        <div class="header-box-contact">
                            <a href="/contact/menu/74">
                                <div class="header-box-contact-title">ติดต่อองค์กร</div>
                            </a>
                            <div class="header-box-contact-tel">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                                </svg>
                            </div>
                            <div class="box-tel">
                                0-3857-3411-2 ต่อ 144
                            </div>
                            <div class="header-box-contact-email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z" />
                                </svg>
                            </div>
                            <div class="box-email">
                                admin@thakam.go.th
                            </div>
                        </div>
                    </div>

                </div>
                <div class="header-text-right">
                    <div class="header-box-login">
                        <div class="header-login-title"><a href="/backend" class="no-underline">เข้าสู่ระบบ</a></div>
                        <div class="header-register-title"><a href="https://demo.trush.sosmartsolution.com/register"
                                class="no-underline" target="_blank">สมัครสมาชิก</a></div>
                    </div>

                    <div class="header-box-flag">
                        <div class="flag-img">
                            <img src="/img/flag/TH.webp" alt="ไทย" width="30" onclick="changeLang('th')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/SG.png" alt="สิงคโปร์" title="English" width="30"
                                onclick="changeLang('en')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/MY.png" alt="มาเลเซีย" title="มาเลย์" width="30"
                                onclick="changeLang('ms')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/ID.png" alt="อินโดนีเซีย" title="อินโดนีเซีย" width="30"
                                onclick="changeLang('id')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/PH.png" alt="ฟิลิปปินส์" title="ฟิลิปปินส์" width="30"
                                onclick="changeLang('tl')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/VN.png" alt="เวียดนาม" title="เวียดนาม" width="30"
                                onclick="changeLang('vi')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/LA.png" alt="ลาว" title="ลาว" width="30"
                                onclick="changeLang('lo')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/MM.png" alt="เมียนมา" title="พม่า" width="30"
                                onclick="changeLang('my')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/KH.png" alt="กัมพูชา" title="เขมร"width="30"
                                onclick="changeLang('km')">
                        </div>
                        <div class="flag-img">
                            <img src="/img/flag/BN.png" alt="บรูไน" title="บรูไน"width="30"
                                onclick="changeLang('ms')">
                        </div>

                    </div>
                    <div id="google_translate_element" style="display:none;"></div>

                    <div class="box-Language">
                        เปลี่ยนภาษา | Language
                    </div>

                    <div class="disability-box">
                        <div id="toggleAccessibility" class="access-btn">
                            <img src="/img/disability.png" width="30">
                        </div>
                        <div class="font-box">

                            <span class="font-small">ก-</span>
                            <span class="font-default">ก</span>
                            <span class="font-big">ก+</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="box-menu">
            <nav class="nav-strip">
                <div class="nav-pill has-submenu">
                    ข้อมูลพื้นฐาน
                    <div class="submenu">
                        <a href="/articles/menu/1">ประวัติความเป็นมา</a>
                        <a href="/articles/menu/2">วิสัยทัศน์</a>
                        <a href="/articles/menu/3">ข้อมูลสภาพทั่วไป</a>
                        <a href="/articles/menu/4">บริการขั้นพื้นฐาน</a>
                        <div class="submenu-item has-submenu">
                            ข้อมูลหมู่บ้าน/ชุมชน
                            <div class="submenu sub-submenu">
                                <a href="/personnel/menu/5">ผู้นำชุมชน</a>
                                <a href="/articles/menu/6">รายละเอียดชุมชน</a>
                            </div>
                        </div>
                        <a href="/directory/menu/7">ผลิตภัณฑ์ชุมชน</a>
                        <a href="/directory/menu/8">สถานที่สำคัญ</a>
                    </div>
                </div>
                <div class="nav-pill has-submenu">
                    อำนาจหน้าที่
                    <div class="submenu">
                        <a href="/articles/menu/29">เทศบาลตำบล</a>
                        <a href="/articles/menu/30">สำนักปลัด</a>
                        <a href="/articles/menu/31">กองยุทธศาสตร์และงบประมาณ</a>
                        <a href="/articles/menu/32">กองคลัง</a>
                        <a href="/articles/menu/33">กองช่าง</a>
                        <a href="/articles/menu/34">กองสาธารณสุขและสิ่งแวดล้อม</a>
                        <a href="/articles/menu/35">กองการศึกษา</a>
                        <a href="/articles/menu/36">กองสวัสดิการสังคม</a>
                        <a href="/articles/menu/37">หน่วยตรวจสอบภายใน</a>
                    </div>
                </div>
                <div class="nav-pill has-submenu">
                    บุคลากร
                    <div class="submenu">
                        <a href="/articles/menu/9">แผนผังโครงสร้างองค์กร</a>
                        <a href="/personnel/menu/10">คณะผู้บริหาร</a>
                        <a href="/personnel/menu/11">สมาชิกสภา</a>
                        {{-- <a href="/personnel/menu/12">ผู้บริหารส่วนราชการ</a> --}}
                        <a href="/personnel/menu/13">สำนักปลัด</a>
                        <a href="/personnel/menu/14">กองยุทธศาสตร์และงบประมาณ</a>
                        <a href="/personnel/menu/15">กองคลัง</a>
                        <a href="/personnel/menu/16">กองช่าง</a>
                        <a href="/personnel/menu/17">กองสาธารณสุขและสิ่งแวดล้อม</a>
                        <a href="/personnel/menu/18">กองการศึกษา</a>
                        <a href="/personnel/menu/19">กองสวัสดิการสังคม</a>
                        <a href="/personnel/menu/20">หน่วยตรวจสอบภายใน</a>
                        <a href="/categories/menu/26">การบริหารและพัฒนาทรัพยากรบุคคล</a>
                    </div>
                </div>

                <div class="nav-pill has-submenu">
                    แผนงานและงบประมาณ
                    <div class="submenu">
                        @forelse($recentMenu as $Menu)
                            <a
                                href="/directory/menu/38/cate/{{ $Menu->categories_id }}">{{ $Menu->categories_name }}</a>
                        @empty
                        @endforelse
                    </div>
                </div>

                <div class="nav-pill has-submenu">
                    ผลการดำเนินงาน
                    <div class="submenu">
                        <a href="/categories/menu/21">ผลงานองค์กร</a>
                        <a href="/categories/menu/22">รายงานทางการเงิน</a>
                        <a href="/categories/menu/23">รายงานผลการดำเนินงาน</a>
                        <a href="/categories/menu/24">รายงานการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ</a>
                        <a href="/categories/menu/25">ข้อมูลเชิงสถิติ</a>
                        <a href="/categories/menu/27">มาตรการส่งเสริมความโปร่งใสและป้องกันการทุจริต</a>
                        <a href="/categories/menu/28">ประมวลจริยธรรมและการขับเคลื่อนจริยธรรม</a>
                    </div>
                </div>

                <div class="nav-pill has-submenu">
                    กฎหมายและระเบียบ
                    <div class="submenu">
                        <a href="/directory/menu/39">เทศบัญญัติและคำสั่ง</a>
                        <a href="/directory/menu/40">กฎหมายอื่นๆที่เกี่ยวข้อง</a>
                        <a href="/directory/menu/76">แผนพัฒนาเศรษฐกิจและสังคมแห่งชาติ</a>
                        <a
                            href="https://www.dla.go.th/land/legalItemFon.do?searchCondition.typeLegalItem=2">พระราชบัญญัติและพระราชกฤษฎีกา</a>
                        <a href="https://www.dla.go.th/land/legalItemFon.do?searchCondition.typeLegalItem=3">กฎหมาย
                            ระเบียบ และประกาศกระทรวง</a>
                    </div>
                </div>

                <div class="nav-pill has-submenu">
                    บริการประชาชน
                    <div class="submenu">
                        {{-- <a href="/complaint/menu/41">รับเเจ้งเรื่องราวร้องเรียนร้องทุกข์ </a>
                        <a href="/corruption/menu/42">รับเเจ้งเรื่องราวร้องเรียนการทุจริตและประพฤติมิชอบ</a> --}}
                        <a href="https://eservice-thakam.sosmartsolution.com/FormeService/id/1">รับเเจ้งเรื่องราวร้องเรียนร้องทุกข์
                        </a>
                        <a
                            href="https://eservice-thakam.sosmartsolution.com/FormeService/id/3">รับเเจ้งเรื่องราวร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                        <a href="/satisfaction/menu/43">แบบสอบถามความพึงพอใจ</a>
                        <a href="/directory/menu/44">รายงานผลสำรวจความพึงพอใจ </a>
                        {{-- <a href="/categories/menu/45">คู่มือการทำงานของหน่วยงาน</a> --}}
                        <a href="/directory/menu/46">ดาวน์โหลดแบบฟอร์ม</a>
                        <a href="/directory/menu/81">คู่มือหรือแนวทางการปฏิบัติสําหรับเจ้าหน้าที่</a>
                        <a href="/directory/menu/82">คู่มือสำหรับประชาชน</a>
                        <a href="https://eservice-thakam.sosmartsolution.com/Requestforms"
                            target="_blank">ยื่นคำร้องออนไลน์ E-service</a>
                        <a href="https://thakam.trash.sosmartsolution.com/" target="_blank">ยื่นคำขออนุญาตออนไลน์ (one
                            stop service)</a>
                    </div>
                </div>

            </nav>
        </section>

        <section class="slide-top">
            {{-- <img src="/img/ficslide.webp" alt="" class="box-img-slide-top"> --}}
            <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade position-relative">


                <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade position-relative">
                    <div class="carousel-inner">
                        {{-- @forelse($SlideTop as $key => $slide)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                @php
                                    $extension = pathinfo($slide->slide_path, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                                    <video class="d-block w-100" preload="auto" autoplay muted playsinline
                                        style="object-fit: cover; height: auto;">
                                        <video class="d-block w-100" preload="auto" autoplay muted loop playsinline style="object-fit: cover; height: 600px;">
                                        <source src="{{ asset('storage/' . $slide->slide_path) }}"
                                            type="video/{{ $extension }}">
                                        เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $slide->slide_path) }}" class="d-block w-100"
                                        alt="slide {{ $key + 1 }}" style="object-fit: cover; height: 600px;">
                                @endif
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="https://www.w3schools.com/howto/img_snow_wide.jpg" class="d-block w-100"
                                    alt="..." style="width: 1905px; height:600px; object-fit: cover;">
                            </div>
                        @endforelse --}}
                        <div class="carousel-item active">
                            <img src="{{ asset('/img/personnel/0004.png') }}" class="d-block w-100" alt="slide 1"
                                style="object-fit: cover; height: auto;width:100% !important">
                            {{-- <img src="{{ asset('/img/personnel/0004.png') }}" class="d-block w-100"
                                alt="slide 1" style="object-fit: cover; height: 580px;width:100% !important"> --}}
                        </div>
                        <div class="button-topnayok">
                            <div class="box-topnayok">
                                <a href="/directory/menu/71"><img src="/img/personnel/สารจากนายก.png"
                                        class="banner-top-img-topnayok">
                                </a>
                            </div>
                            <div class="box-topnayok">
                                <a href="/directory/menu/72"><img src="/img/personnel/เจตจำนงสุจริต.png"
                                        class="banner-top-img-topnayok">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Controls + Indicators (overlay) --}}
                {{-- <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center align-items-center gap-3 mb-3"
                    style="z-index: 10;height: 10px;">

                    <button class="carousel-control-prev position-static" type="button"
                        data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                            <path
                                d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                        </svg>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <div class="carousel-indicators position-static m-0 ">
                        @forelse($SlideTop as $key => $slide)
                            <button type="button" data-bs-target="#carouselExampleSlidesOnly"
                                data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"
                                aria-current="{{ $key === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $key + 1 }}">
                            </button>
                        @empty
                            <button type="button" class="active" data-bs-target="#carouselExampleSlidesOnly"
                                data-bs-slide-to="0"></button>
                        @endforelse
                    </div>

                    <button class="carousel-control-next position-static" type="button"
                        data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path
                                d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                        </svg>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div> --}}

            </div>


        </section>

        <div class="br-top"></div>

        {{-- <section class="animation-top">
            <img src="https://www.w3schools.com/howto/img_snow_wide.jpg" class="d-block w-100" alt="..."
                style="width: 1905px; height:650px">
            <video autoplay muted loop playsinline style="width: 100%;">
                <source src="/img/Animation.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section> --}}

        <section class="vistion-top">
            <div class="search-bar-container">
                {{-- <div class="search-button vision">วิสัยทัศน์</div> --}}
                <div class="vision" style="z-index: 5;"><img src="/img/vission/3.png" alt=""></div>
                <div class="search-button intercity-port">
                    <div class="scroll-text">ท่าข้ามเมืองน่าอยู่ พัฒนาสู่ EEC</div>
                </div>
                <div class="search-box-1" style="z-index: 5;">
                    <div class="search-box-img">
                        <img src="/img/vission/2.png" alt="">
                    </div>
                    <div class="search-box">
                        {{-- <input type="text" placeholder="" class="search-input">
                        <div class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div> --}}
                        <input type="text" id="googleSearchInput" placeholder="" class="search-input">
                        <div class="search-icon" onclick="googleSearch()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div>
                    </div>

                    {{-- <script async src="https://cse.google.com/cse.js?cx=07dbb266289c14fbb"></script>
                    <div class="gcse-search"></div> --}}

                </div>
        </section>

        <main class="py-4">
            @yield('content')
        </main>

        <section class="box-view">
            <div class="counter-title">
                จำนวนผู้เข้าชมเว็บไซต์
                <small>number of website visitors</small>
            </div>

            <div class="counter-item">
                <span class="counter-number">{{ $stats['2min'] }}</span>
                <span class="counter-label">ขณะนี้</span>
            </div>

            <div class="counter-item">
                <span class="counter-number">{{ $stats['today'] }}</span>
                <span class="counter-label">วันนี้</span>
            </div>

            <div class="counter-item">
                <span class="counter-number">{{ $stats['weekly'] }}</span>
                <span class="counter-label">สัปดาห์นี้</span>
            </div>

            <div class="counter-item">
                <span class="counter-number">{{ $stats['monthly'] }}</span>
                <span class="counter-label">เดือนนี้</span>
            </div>

            <div class="counter-item">
                <span class="counter-number">{{ $stats['yearly'] }}</span>
                <span class="counter-label">ปีนี้</span>
            </div>

            <div class="counter-total">
                <span class="counter-number">{{ $stats['total'] }}</span>
                <span class="counter-label">ทั้งหมด</span>
            </div>
        </section>

        {{-- <section class="box-view-two">
            <div class="null-content"></div>
        </section> --}}

        <section class="box-footer">
            <div class="footer-one">
                <div class="footer-logo">
                    <a href="/home"><img src="/img/logo.png" alt="โลโก้" class="header-logo"></a>
                </div>
                <div class="footer-contact" style="width: 1000px;">
                    <p style="display: flex;flex-direction: column;">
                        <span class="ft-by-one" style="font-size: 50px;font-weight: 900;">เทศบาลตำบลท่าข้าม</span>
                        <span class="ft-by-two" style="font-size: 24px;margin-top: -15px;    font-weight: 700;">Thakam Subdistrict
                            Municipality</span>
                        <span class="ft-by-three" style="font-size: 16px;    font-weight: 500;">122 หมู่ที่ 3 ตำบลท่าข้าม
                            อำเภอบางปะกง</span>
                        <span class="ft-by-three" style="font-size: 16px;    font-weight: 500;">จังหวัดฉะเชิงเทรา 24130</span>
                    </p>
                    <div class="text-footer">
                        <div class="text-footer-one">
                            <img src="/img/13Footer/Mail.png" alt="">
                            <div style="display: flex;flex-direction: column;">
                                <span>admin@thakam.go.th</span>
                                <span>saraban_05240403@dla.go.th</span>
                            </div>
                        </div>
                        <div class="text-footer-two">
                            <img src="/img/13Footer/Call.png" alt="">
                            <div>0-3857-3411-2 ต่อ 144</div>
                        </div>
                        <div class="text-footer-three">
                            <img src="/img/13Footer/Fax.png" alt="">
                            <div>0-3857-3411-2</div>
                        </div>
                    </div>
                </div>
                <div class="footer-link">
                    <div style="font-weight: 900;">
                        ข้อมูลเว็บไซต์
                    </div>
                    <div class="link-footer-two">
                        <a href="/home">หน้าแรก</a>
                        <a href="/webboard/menu/75">กระดานกระทู้</a>
                        <a href="/contact/menu/74">ติดต่อ</a>
                        <a href="/Sitemap">แผนผังเว็บไซต์</a>
                        <a href="https://eservice-thakam.sosmartsolution.com/FormeService/id/1"
                            target="_blank">รับเเจ้งเรื่องราวร้องเรียนร้องทุกข์</a>
                    </div>
                </div>
                <div class="footer-ma">
                    <a href="http://webmail.thakam.go.th" target="_blank"><img src="/img/13Footer/1.png"
                            alt="" width="250"></a>
                    <a href=""><img src="/img/13Footer/2.png" alt="" width="250"></a>
                    <a href=""><img src="/img/13Footer/3.png" alt="" width="250"></a>
                    <a href="/backend"><img src="/img/13Footer/4.png" alt="" width="250"></a>
                </div>
            </div>
        </section>

        <section class="Copyright">
            <div class="footer-two">
                <div class="tfone">การแสดงผลหน้าเว็บไซต์จะสมบูรณ์ที่สุด บนบราวเซอร์ Google Chrome & FireFox
                    ความละเอียดหน้าจอไม่ต่ำกว่า 1366x768 pixel และไม่เกิน 1600x1200 pixel</div>
                {{-- <div class="tfone">Copyright @ บริษัท So Smart Solution สงวนสิทธิ์ 2025</div> --}}
                <div class="tfone">
                    <a href="/articles/menu/83">
                        <div class="menu-item">การปฏิเสธความรับผิด</div>
                    </a>
                    <a href="/articles/menu/84">
                        <div class="menu-item">นโยบายการคุ้มครองข้อมูลส่วนบุคคล</div>
                    </a>
                    <a href="/articles/menu/85">
                        <div class="menu-item">นโยบายเว็บไซต์</div>
                    </a>
                    <a href="/articles/menu/86">
                        <div class="menu-item">นโยบายการรักษาความมั่นคงปลอดภัยเว็บไซต์</div>
                    </a>
                </div>
            </div>
        </section>


        <div class="cookie-box" id="cookieBox">
            <p>
                เว็บไซต์นี้ใช้คุกกี้เพื่อให้ท่านได้รับประสบการณ์การใช้งานที่ดียิ่งขึ้น
                <a href="/articles/menu/84" target="_blank">เรียนรู้เพิ่มเติม</a>
            </p>
            <form id="acceptCookieForm">
                @csrf
                <button type="submit">ยอมรับ</button>
            </form>
        </div>

    </div>

</body>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
                pageLanguage: 'th',
                includedLanguages: 'en,th,zh-CN,ja,ms,id,tl,vi,lo,my,km'
            },
            'google_translate_element'
        );
    }

    function changeLang(lang) {
        var select = document.querySelector("select.goog-te-combo");
        if (select) {
            select.value = lang;
            select.dispatchEvent(new Event('change'));
        }
    }
</script>

<script>
    function googleSearch() {
        const query = document.getElementById("googleSearchInput").value.trim();
        if (query) {
            // ใช้ Google Custom Search Engine ที่คุณสร้างไว้
            const searchUrl = `https://cse.google.com/cse?cx=07dbb266289c14fbb&q=${encodeURIComponent(query)}`;
            window.open(searchUrl, '_blank'); // เปิดในแท็บใหม่
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('#carouselExampleSlidesOnly');
        const bgImage = document.querySelector('.box-img-slide-top');
        const bsCarousel = new bootstrap.Carousel(carousel, {
            interval: false, // ปิดการเลื่อนอัตโนมัติทั้งหมด
            pause: false,
            ride: false
        });

        function playVideoIfExists(item) {
            const video = item.querySelector('video');

            if (video) {
                bgImage.style.display = 'none';
                bsCarousel.pause(); // หยุดเลื่อน
                video.currentTime = 0;
                video.play();

                // เมื่อวิดีโอจบ ค่อยเปลี่ยนสไลด์
                video.onended = function() {
                    bsCarousel.next();
                };
            } else {
                bgImage.style.display = 'block';
                // ถ้าไม่มีวิดีโอ ให้รอ 5 วิ ก่อนเลื่อนไป
                setTimeout(() => {
                    bsCarousel.next();
                }, 5000);
            }
        }

        // เล่นสไลด์แรกตอนโหลด
        const firstItem = carousel.querySelector('.carousel-item.active');
        playVideoIfExists(firstItem);

        // ทุกครั้งที่สไลด์เปลี่ยน (เลื่อนไปแล้ว)
        carousel.addEventListener('slid.bs.carousel', function(event) {
            const activeItem = carousel.querySelector('.carousel-item.active');
            playVideoIfExists(activeItem);
        });
    });
</script>


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(document).ready(function() {
        const cookieBox = $('#cookieBox');

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // ซ่อนกล่องถ้ามี cookie
        if (getCookie('cookieAccepted')) {
            cookieBox.hide();
        }

        $('#acceptCookieForm').submit(function(e) {
            e.preventDefault(); // ป้องกัน form submit ปกติ

            const date = new Date();
            date.setDate(date.getDate() + 1); // เพิ่ม 1 วัน
            document.cookie = `cookieAccepted=true; expires=${date.toUTCString()}; path=/`;


            // ดึง CSRF token จาก form
            const token = $(this).find('input[name="_token"]').val();

            // ส่ง AJAX POST
            $.ajax({
                url: "/cookie/save",
                method: "POST",
                data: {
                    _token: token
                },
                success: function(res) {
                    cookieBox.fadeOut();
                },
                error: function() {
                    alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const body = document.body;
        const btn = document.getElementById("toggleAccessibility");

        // ถ้ามีค่าใน localStorage → เปิดโหมด
        if (localStorage.getItem("accessibilityMode") === "on") {
            body.classList.add("accessibility-mode");
        }

        btn.addEventListener("click", function() {
            body.classList.toggle("accessibility-mode");

            if (body.classList.contains("accessibility-mode")) {
                localStorage.setItem("accessibilityMode", "on");
            } else {
                localStorage.removeItem("accessibilityMode");
            }
        });
    });
</script>

<script>
    const body = document.querySelector('body');
    const btnSmall = document.querySelector('.font-small');
    const btnDefault = document.querySelector('.font-default');
    const btnBig = document.querySelector('.font-big');

    let currentSize = 16;

    btnSmall.addEventListener('click', () => {
        currentSize = 14;
        body.style.fontSize = currentSize + 'px';
    });

    btnDefault.addEventListener('click', () => {
        currentSize = 16;
        body.style.fontSize = currentSize + 'px';
    });

    btnBig.addEventListener('click', () => {
        currentSize = 20;
        body.style.fontSize = currentSize + 'px';
    });
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</html>
