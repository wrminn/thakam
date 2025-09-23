<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="shortcut icon" type="image/png" href="/spa/assets/LOGO.png">
    <link rel="icon" type="image/svg+xml" href="/spa/assets/LOGO.png">
    <meta name="description" content="เทศบาลตำบลท่าข้าม">
    <title>เทศบาลตำบลท่าข้าม</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
            overflow: hidden;
            /* กัน scroll bar */
            position: relative;
        }

        /* รูปพื้นหลังเต็มจอ */
        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* ให้รูปครอบเต็มจอ (เหมือน background-size: cover) */
            z-index: -1;
            /* ส่งรูปไปข้างหลัง */
        }

        /* ปุ่มลอยด้านล่างตรงกลาง */
        .btn {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            padding: 14px 40px;
            border-radius: 50px;
            /* background: linear-gradient(180deg, #d1901f, #a3620d); */
            color: white;
            /* font-weight: 700; */
            /* font-size: 18px; */
            text-decoration: none;
            /* box-shadow: 0 8px 20px rgba(163, 98, 13, 0.3); */
            transition: all .2s ease;
            z-index: 10;
        }

        .btn:hover {
            box-shadow: 0 12px 28px rgba(163, 98, 13, 0.4);
            transform: translate(-50%, -2px);
        }

        .btn:active {
            transform: translate(-50%, 1px);
        }

        .image-button {
            border: none;
            padding: 0;
            background: none;
            cursor: pointer;
        }

        .image-button img {
            width: 250px;
            /* ปรับขนาดรูปตามต้องการ */
            height: auto;
            transition: transform 0.2s;
        }

        .image-button img:hover {
            transform: scale(1.05);
            /* effect เล็กน้อยเวลาชี้เมาส์ */
        }
    </style>
</head>

<body>

    <!-- รูปพื้นหลัง -->
    <img src="{{ asset('/intro/default.png') }}" alt="Background" class="bg">

    <!-- ปุ่ม -->
    <a href="/home" class="btn image-button"> <img src="{{ asset('/intro/Button.png') }}" alt="เข้าสู่ระบบ">
    </a>

</body>

</html>
