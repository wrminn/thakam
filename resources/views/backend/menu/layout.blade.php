<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu/summernote-lite.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- Summernote Fullscreen Override --- */
        .note-editor.fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            z-index: 9999 !important;
            /* ให้ลอยบนสุด */
            display: flex !important;
            flex-direction: column !important;
        }

        /* Toolbar ด้านบน */
        .note-editor.fullscreen .note-toolbar {
            flex: 0 0 auto !important;
            border-bottom: 1px solid #ddd !important;
            background: #f8f9fa !important;
            z-index: 10000 !important;
        }

        /* เนื้อหาที่แก้ไข */
        .note-editor.fullscreen .note-editing-area {
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
        }

        .note-editor.fullscreen .note-editable {
            flex: 1 1 auto !important;
            height: auto !important;
            overflow-y: auto !important;
            padding: 15px !important;
            background: #fff !important;
        }

        /* Footer (status bar) */
        .note-editor.fullscreen .note-statusbar {
            flex: 0 0 auto !important;
            border-top: 1px solid #ddd !important;
            background: #f8f9fa !important;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">ท่าข้าม</div>

        <!-- โปรไฟล์ -->
        <div class="profile" id="profileBtn">
            <img src="{{ asset('img/menu/001.jpg') }}" alt="Profile">
            <div class="info">ผู้ใช้งาน</div>
            <div class="profile-popup" id="profilePopup">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-blue-600 hover:text-blue-800">
                    ออกจากระบบ
                </a>
            </div>
        </div>

        <!-- เมนู -->
        <?php /*
        <div class="menu">
            <div class="menu-item has-submenu">
                ข้อมูลพื้นฐานตำบล
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="#">ประวัติความเป็นมา</a>
                <a href="#">ข้อมูลสภาพทั่วไป</a>
            </div>

            <div class="menu-item has-submenu">
                ข้อมูลชุมชน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="#">ผู้นำชุมชน</a>
                <div class="menu-item has-submenu">
                    ผลิตภัณฑ์ชุมชน
                    <i class='bx bx-chevron-right chevron'></i>
                </div>
                <div class="submenu level-2">
                    <a href="#">สถานที่สำคัญ</a>
                    <div class="menu-item has-submenu">
                        แกลอรี่ภาพถ่าย
                        <i class='bx bx-chevron-right chevron'></i>
                    </div>
                    <div class="submenu level-3">
                        <a href="#">ภาพเก่า</a>
                        <a href="#">ภาพใหม่</a>
                    </div>
                </div>
            </div>
        </div>
        */
        ?>
        <div class="menu">
            <div class="menu-item has-submenu">
                ข้อมูลพื้นฐาน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/articles/menu/1">ประวัติความเป็นมา</a>
                <a href="/backend/articles/menu/2">วิสัยทัศน์</a>
                <a href="/backend/articles/menu/3">ข้อมูลสภาพทั่วไป</a>
                <a href="/backend/articles/menu/4">บริการขั้นพื้นฐาน</a>
                <div class="menu-item has-submenu">
                    ข้อมูลหมู่บ้าน/ชุมชน
                    <i class='bx bx-chevron-right chevron'></i>
                </div>
                <div class="submenu level-2">
                    <a href="/backend/personnel/menu/5">ผู้นำชุมชน</a>
                    <a href="/backend/articles/menu/6">รายละเอียดชุมชน</a>
                </div>
                <a href="/backend/directory/menu/7">ผลิตภัณฑ์ชุมชน</a>
                <a href="/backend/directory/menu/8">สถานที่สำคัญ</a>
            </div>
            <div class="menu-item has-submenu">
                อำนาจหน้าที่
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/articles/menu/29">เทศบาลตำบล</a>
                <a href="/backend/articles/menu/30">สำนักปลัด</a>
                <a href="/backend/articles/menu/31">กองยุทธศาสตร์</a>
                <a href="/backend/articles/menu/32">กองคลัง</a>
                <a href="/backend/articles/menu/33">กองช่าง</a>
                <a href="/backend/articles/menu/34">กองสาธารณสุขและสิ่งแวดล้อม</a>
                <a href="/backend/articles/menu/35">กองการศึกษา</a>
                <a href="/backend/articles/menu/36">กองสวัสดิการสังคม</a>
                <a href="/backend/articles/menu/37">หน่วยตรวจสอบภายใน</a>
            </div>
            <div class="menu-item has-submenu">
                บุคลากร
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/articles/menu/9">แผนผังโครงสร้างองค์กร</a>
                <a href="/backend/personnel/menu/10">คณะผู้บริหาร</a>
                <a href="/backend/personnel/menu/11">สภาชิกสภา</a>
                <a href="/backend/personnel/menu/12">ผู้บริหารส่วนราชการ</a>
                <a href="/backend/personnel/menu/13">สำนักปลัด</a>
                <a href="/backend/personnel/menu/14">กองยุทธศาสตร์</a>
                <a href="/backend/personnel/menu/15">กองคลัง</a>
                <a href="/backend/personnel/menu/16">กองช่าง</a>
                <a href="/backend/personnel/menu/17">กองสาธารณสุขและสิ่งแวดล้อม</a>
                <a href="/backend/personnel/menu/18">กองการศึกษา</a>
                <a href="/backend/personnel/menu/19">กองสวัสดิการสังคม</a>
                <a href="/backend/personnel/menu/20">หน่วยตรวจสอบภายใน</a>
            </div>
            <a href="/backend/directory/menu/38/cate/0">
                <div class="menu-item">แผนงานและงบประมาณ</div>
            </a>
            <div class="menu-item has-submenu">
                ผลการดำเนินงาน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/directory/menu/21/cate/0">ผลงานองค์กร</a>
                <a href="/backend/directory/menu/22/cate/0">รายงานทางการเงิน</a>
                <a href="/backend/directory/menu/23/cate/0">รายงานผลการดำเนินงาน</a>
                <a href="/backend/directory/menu/24/cate/0">รายงานการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ</a>
                <a href="/backend/directory/menu/25/cate/0">ข้อมูลเชิงสถิติ</a>
                <a href="/backend/directory/menu/26/cate/0">การบริหารและพัฒนาทรัพยากรบุคล</a>
                <a href="/backend/directory/menu/27/cate/0">มาตรการส่งเสริมความโปร่งใสและป้องกันการทุจริต</a>
                <a href="/backend/directory/menu/28/cate/0">ประมวลจริยธรรมและการขับเคลื่อนจริยธรรม</a>
            </div>


            <div class="menu-item has-submenu">
                กฎหมายเเละระเบียบ
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/directory/menu/39">เทศบัญญัติและคำสั่ง</a>
                <a href="/backend/directory/menu/40">กฎหมายที่เกี่ยวข้อง</a>
            </div>
            <div class="menu-item has-submenu">
                บริการประชาชน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu level-1">
                <a href="/backend/complaint/menu/41">รับเเจ้งเรื่องราวร้องเรียนร้องทุกข์</a>
                <a href="/backend/corruption/menu/42">รับเเจ้งเรื่องราวร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                <a href="/backend/satisfaction/menu/43">แบบสอบถามความพึงพอใจ</a>
                <a href="/backend/directory/menu/44">รายงานผลสำรวจความพึงพอใจ</a>
                <a href="/backend/directory/menu/45/cate/0">คู่มือการทำงานของหน่วยงาน</a>
                <a href="/backend/directory/menu/46">ดาวน์โหลดแบบฟอร์ม</a>
                <a href="/backend/eservice/menu/47">ยื่นคำร้องออนไลน์ E-service</a>

            </div>
            <div class="menu-item has-submenu">
                ประกาศความเคลื่อนไหว
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/directory/menu/48">ประกาศจัดซื้อจัดจ้าง</a>
                <a href="/backend/directory/menu/49">ผลประกาศจัดซื้อจัดจ้าง</a>
                <a href="/backend/directory/menu/50">รายงานผลการจัดซื้อจัดจ้าง</a>
            </div>
            <a href="/backend/directory/menu/51">
                <div class="menu-item">กิจกรรม</div>
            </a>
            <a href="/backend/directory/menu/52">
                <div class="menu-item">ข่าวประชาสัมพันธ์</div>
            </a>
            <div class="menu-item has-submenu">
                แบนเนอร์
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/banner/menu/53">แบนเนอร์แนะนำ</a>
                <a href="/backend/banner/menu/54">แบนเนอร์ภายนอก</a>
                <a href="/backend/banner/menu/55">แบนเนอร์ภายใน</a>
                <div class="menu-item has-submenu">
                    งานบริการและอื่นๆ
                    <i class='bx bx-chevron-right chevron'></i>
                </div>
                <div class="submenu level-2">
                    <a href="/backend/directory/menu/56">รางวัลแห่งความภูมิใจ</a>
                    <a href="/backend/event-calendar/menu/57">ปฏิทินกิจกรรม</a>
                    <a href="/backend/directory/menu/58">การประเมินคุณธรรมและความโปร่งใส (ITA)</a>
                    <a href="/backend/directory/menu/59">การประเมินประสิทธิภาพภายใน (LPA)</a>
                    <a href="/backend/directory/menu/60">การจัดการองค์ความรู้ (KM)</a>
                    <a href="/backend/directory/menu/61/cate/0">ศูนย์ข้อมูลข่าสาร</a>
                    <a href="/backend/directory/menu/62">ข้อมูลอาเซียน</a>
                    <a href="/backend/articles/menu/63">ประชาสัมพันธ์การเลือกตั้ง</a>
                    <a href="/backend/directory/menu/64/cate/0">รายงานกิจการสภา</a>
                    <a href="/backend/directory/menu/65">แนะนำสถานที่ท่องเที่ยว</a>
                    <a href="/backend/directory/menu/66">แนะนำร้านอาหาร</a>
                </div>

            </div>
            {{-- <a href="46"> --}}
            <a href="/backend/popup/menu/67">
                <div class="menu-item">POP UP</div>
            </a>
            <a href="/backend/slide/menu/68">
                <div class="menu-item">ภาพสไลด์</div>
            </a>
            <a href="/backend/slide/menu/69">
                <div class="menu-item">วิดีทัศน์</div>
            </a>
            <a href="/backend/slide/menu/70">
                <div class="menu-item">ป้ายประกาศ</div>
            </a>
            <a href="/backend/directory/menu/71">
                <div class="menu-item">สารจากนายก</div>
            </a>
            <a href="/backend/articles/menu/72">
                <div class="menu-item">เจตจำนงสุจริตของผู้บริหาร</div>
            </a>

            {{-- <a href="54"> --}}
            {{-- <a href="/backend/satisfaction/menu/54">
                <div class="menu-item">แบบสำรวจความคิดเห็น</div>
            </a> --}}
            <a href="/backend/vote/menu/73">
                <div class="menu-item">ข้อมูลการแสดงความคิดเห็น (Vote)</div>
            </a>
            <a href="/backend/contact/menu/74">
                <div class="menu-item">ติดต่อ</div>
            </a>
            {{-- <a href="56"> --}}
            <a href="/backend/webboard/menu/75">
                <div class="menu-item">กระดานสนทนา</div>
            </a>



        </div>
    </div>
    <div class="main-content">
        @yield('content')
    </div>

</body>
<script src="{{ asset('js/menu/main.js') }}"></script>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '{{ session('success') }}',
            confirmButtonText: 'ตกลง'
        });
    </script>
@endif


</html>
