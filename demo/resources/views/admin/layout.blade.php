<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    {{-- <link rel="stylesheet" href="style.css"> --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu/styles.css') }}">

</head>

<body>
    <div class="sidebar">
        <div class="logo">WEB-DEMO</div>

        <!-- โปรไฟล์ -->
        <div class="profile" id="profileBtn">
            <img src="{{ asset('img/menu/001.jpg') }}" alt="Profile">
            <div class="info">ผู้ใช้งาน</div>
            <div class="profile-popup" id="profilePopup">
                <a href="#">แก้ไขข้อมูลโปรไฟล์</a>
                <a href="#">ออกจากระบบ</a>
            </div>
        </div>

        <!-- เมนู -->
        <div class="menu">

            <?php /* ?>
            <div class="menu-item has-submenu">
                <i class='bx bx-grid-alt'></i> Dashboard
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="#">Submenu 1</a>
                <a href="#">Submenu 2</a>
                <a href="#">Submenu 3</a>
            </div>
            <div class="menu-item has-submenu">
                <i class='bx bx-box'></i> จัดการสินค้า
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="#">ประเภทสินค้า</a>
                <a href="#">สินค้า</a>
                <a href="#">สินค้าใหม่</a>
            </div>

            <div class="menu-item has-submenu">
                <i class='bx bx-paw'></i> จัดการสัตว์
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="#">ประเภทสัตว์</a>
                <a href="#">เพิ่มสัตว์</a>
                <a href="#">สัตว์ใหม่</a>
            </div>
            <?php */ ?>
            <a href="/backend/managemenu">
                <div class="menu-item">ระบบจัดการเมนู</div>
            </a>
            <a href="/backend/list/menu/1">
                <div class="menu-item">ประวัติความเป็นมา</div>
            </a>
            <a href="/backend/list/menu/2">
                <div class="menu-item">ข้อมูลสภาพทั่วไป</div>
            </a>
            <a href="/backend/list/menu/3">
                <div class="menu-item">ข้อมูลชุมชน</div>
            </a>
            <a href="/backend/list/menu/4">
                <div class="menu-item">ผลิตภัณท์ชุมชน</div>
            </a>
            <a href="/backend/list/menu/5">
                <div class="menu-item">สถานที่สำคัญ</div>
            </a>
            <a href="/backend/list/menu/6">
                <div class="menu-item">แกลอลี่ภาพถ่ายภูมิทัศน์</div>
            </a>
            <a href="/backend/list/menu/7">
                <div class="menu-item">ข่าวประชาสัมพันธ์</div>
            </a>


        </div>
    </div>
    {{-- <div class="main-content">
        @yield('content')
    </div> --}}

    <div class="col">
        <div class="main-content">
            @yield('content')
        </div>
    </div>


</body>
<script src="{{ asset('js/menu/main.js') }}"></script>

</html>
