<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="shortcut icon" type="image/png" href="/spa/assets/LOGO.png">
    <link rel="icon" type="image/svg+xml" href="/spa/assets/LOGO.png">
    <meta name="description" content="เทศบาลตำบลท่าข้าม">
    <title>เทศบาลตำบลท่าข้าม</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
            overflow: hidden;
            position: relative;
        }

        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .btn {
            position: fixed;
            bottom: 5vh;
            left: 50%;
            transform: translateX(-50%);
            padding: 1.5vh 5vw;
            max-width: 300px;
            font-size: clamp(14px, 2.5vw, 18px);
            border-radius: 50px;
            color: white;
            text-decoration: none;
            transition: all .2s ease;
            z-index: 10;
        }

        .btn:hover {
            /* box-shadow: 0 12px 28px rgba(163, 98, 13, 0.4); */
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
            width: 30vw;
            max-width: 300px;
            height: auto;
            transition: transform 0.2s;
            margin: -25px;
        }

        .image-button img:hover {
            transform: scale(1.05);
        }

        .html-intro {
            width: 100%;
            height: 100%;
        }

        .nameweb {
            width: 100%;
            text-align: center;
            font-family: 'Mitr', sans-serif;
            font-size: 30px;
            color: #ffffff;
            z-index: 1;
            position: fixed;
            bottom: 15vh;
        }

        @media (max-width: 1919px) {
            .bg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: auto;
                object-fit: cover;
                z-index: -1;
            }

            .html-intro {
                width: 100%;
                height: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- รูปพื้นหลัง -->
    {{-- <img src="{{ asset('/intro/default.png') }}" alt="Background" class="bg"> --}}
    {{-- <iframe src="{{ asset('intro/online/index.html') }}" frameborder="0" class="html-intro"></iframe> --}}
    <iframe src="https://intro-ts.sosmartsolution.com/intro/online/index.html" frameborder="0" class="html-intro"></iframe>

    <!-- ปุ่ม -->
    <div class="nameweb">เทศบาลตำบลท่าข้าม จังหวัดฉะเชิงเทรา</div>
    <a href="/home" class="btn image-button">
        <img src="https://intro-ts.sosmartsolution.com/intro/online/Enter-site.png" alt="เข้าสู่ระบบ">
    </a>
    {{-- <a href="/home" class="btn image-button">
        <img src="{{ asset('/intro/online/Enter-site.png') }}" alt="เข้าสู่ระบบ">
    </a> --}}

</body>

</html>
