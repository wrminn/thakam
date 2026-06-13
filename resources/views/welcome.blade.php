<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>สถิตในใจไทยนิรันดร์</title>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #162333;
            font-family: 'Noto Sans Thai', sans-serif;
            overflow-x: hidden;
        }

        .royal-page {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            /* background:radial-gradient(circle at center,rgba(255, 255, 255, .08) 0%,rgba(22, 35, 51, 1) 55%); */
            background-image: url("/intro/BG.png");
        }

        /* Spotlight */

        .spotlight {
            position: absolute;
            width: 800px;
            height: 1000px;
            left: 50%;
            top: -250px;
            transform: translateX(-50%);
            background:
                radial-gradient(ellipse at center,
                    rgba(255, 255, 255, .25) 0%,
                    rgba(255, 255, 255, .08) 35%,
                    transparent 75%);

            animation: pulse 5s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: .5;
                transform: translateX(-50%) scale(1);
            }

            50% {
                opacity: 1;
                transform: translateX(-50%) scale(1.1);
            }
        }

        /* เมฆ */

        .cloud {
            position: absolute;
            width: 1200px;
            height: 300px;
            left: 50%;
            top: 280px;
            transform: translateX(-50%);
            background:
                radial-gradient(circle,
                    rgba(255, 255, 255, .25),
                    transparent 70%);

            filter: blur(50px);
        }

        /* แสง */

        .light-wrap {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .light {
            position: absolute;
        }

        /* ซ้ายบน */

        .light-1 {
            top: 5%;
            left: 5%;
            width: 150px;
            animation:
                float1 8s infinite ease-in-out,
                glow 4s infinite ease-in-out;
        }

        /* กลางบน */

        .light-2 {
            top: 10%;
            left: 48%;
            width: 70px;
            animation:
                float2 10s infinite ease-in-out,
                glow 3s infinite ease-in-out;
        }

        /* ขวาบน */

        .light-3 {
            top: 8%;
            right: 5%;
            width: 160px;
            animation:
                float3 11s infinite ease-in-out,
                glow 4s infinite ease-in-out;
        }

        /* ซ้ายล่าง */

        .light-4 {
            bottom: 20%;
            left: 5%;
            width: 90px;
            animation:
                float4 9s infinite ease-in-out,
                glow 3s infinite ease-in-out;
        }

        /* ขวาล่าง */

        .light-5 {
            bottom: 18%;
            right: 5%;
            width: 140px;
            animation:
                float5 12s infinite ease-in-out,
                glow 5s infinite ease-in-out;
        }

        @keyframes glow {

            0%,
            100% {
                opacity: .4;
                filter: brightness(.8);
            }

            50% {
                opacity: 1;
                filter:
                    brightness(1.8) drop-shadow(0 0 20px #fff) drop-shadow(0 0 50px #ffe7a3);
            }
        }

        @keyframes float1 {
            50% {
                transform: translate(20px, -20px);
            }
        }

        @keyframes float2 {
            50% {
                transform: translate(-20px, 15px);
            }
        }

        @keyframes float3 {
            50% {
                transform: translate(-30px, -15px);
            }
        }

        @keyframes float4 {
            50% {
                transform: translate(25px, -15px);
            }
        }

        @keyframes float5 {
            50% {
                transform: translate(-20px, 20px);
            }
        }

        /* Content */

        .content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 30px;
        }

        .princess {
            width: 650px;
            max-width: 90%;
            filter:
                grayscale(100%) drop-shadow(0 0 30px rgba(255, 255, 255, .25));
        }

        .title {
            margin-top: 10px;
            color: #d6b77a;
            font-size: 14px;
            font-weight: 700;
        }

        .subtitle {
            color: #d6b77a;
            font-size: 20px;
            margin-top: 8px;
        }

        .royal-name {
            margin-top: 15px;
            color: #d6b77a;
            font-size: 22px;
            line-height: 1.8;
            font-weight: 500;
            max-width: 1400px;
        }

        .footer {
            margin-top: 10px;
            color: #ddd;
            font-size: 18px;
        }

        .divider {
            width: 250px;
            height: 2px;
            background: #d6b77a;
            margin: 25px auto;
        }

        .organization {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }

        /* .enter-btn {
            margin-top: 20px;
            padding: 15px 80px;
            border: 2px solid #d6b77a;
            border-radius: 50px;
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            transition: .3s;
        }

        .enter-btn:hover {
            background: #d6b77a;
            color: #162333;
            box-shadow:
                0 0 20px rgba(214, 183, 122, .5);
        } */
         .enter-btn img {
            width: 50%;
            height: auto;
        }

        @media(max-width:768px) {

            .princess {
                width: 220px;
            }

            .title {
                font-size: 22px;
            }

            .subtitle {
                font-size: 16px;
            }

            .royal-name {
                font-size: 20px;
            }

            .organization {
                font-size: 18px;
            }

            .enter-btn {
                font-size: 18px;
                padding: 12px 40px;
            }

        }

        .button-group {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            flex-wrap: nowrap;
            margin-top: 15px;
            transform: translateY(-20px);
        }

        .royal-box {
            width: min(260px, 18vw);
        }

        .royal-btn {
            display: block;
        }

        .royal-btn img {
            width: 100%;
            height: auto;
            display: block;
        }

        .enter-btn img {
            width: 50%;
            height: auto;
        }

        .royal-box {
            position: relative;
            width: 320px;
        }

        .royal-icon {
            position: absolute;
            left: 50%;
            top: -40px;
            transform: translateX(-50%);

            width: 80px;
            height: 80px;

            border-radius: 50%;
            background: #162333;

            display: flex;
            justify-content: center;
            align-items: center;

            z-index: 5;
        }

        .royal-icon img {
            width: 70px;
        }

        .royal-btn {
            display: flex;
            justify-content: center;
            align-items: center;

            text-align: center;

            min-height: 75px;

            padding: 15px 25px;

            /* color: #fff; */
            text-decoration: none;

            border-radius: 50px;


            transition: .3s;

            font-size: 16px;
            line-height: 1.5;
        }

       

        .enter-btn {
            min-width: 280px;
            text-align: center;
        }

        @media(max-width:991px) {

            .button-group {
                gap: 25px;
            }

            .royal-box {
                width: 280px;
            }

            .enter-btn {
                width: 280px;
            }
        }
    </style>
    {{-- <style>
        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            background: #162333;
            font-family: 'Noto Sans Thai', sans-serif;
        }

        /* .royal-page {
            position: relative;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: url('/intro/BG.png') center center/cover no-repeat;
            background-size: cover;
            background-position: center;
        } */
        .royal-page {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            background: url('/intro/BG.png') center center/cover no-repeat;

        }

        /* กล่องหลัก */
        .content {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-top: 2vh;
            padding-bottom: 4vh;
            justify-content: flex-start;
        }

        /* รูป */

        .princess {
            height: 42vh;
            width: auto;
            max-width: 90vw;

            object-fit: contain;

            filter:
                grayscale(100%) drop-shadow(0 0 30px rgba(255, 255, 255, .25));
        }

        /* ตัวหนังสือ */

        .title {
            margin-top: 5px;
            color: #d6b77a;
            font-size: clamp(12px, 1vw, 16px);
            font-weight: 700;
        }

        .subtitle {
            color: #d6b77a;
            font-size: clamp(14px, 1.1vw, 20px);
            margin-top: 4px;
        }

        .royal-name {
            margin-top: 10px;
            color: #d6b77a;

            font-size: clamp(16px, 1.3vw, 26px);

            line-height: 1.6;
            font-weight: 500;

            max-width: 1200px;
        }

        .footer {
            margin-top: 8px;
            color: #ddd;

            font-size: clamp(12px, 1vw, 18px);
        }

        .divider {
            width: 180px;
            height: 2px;

            background: #d6b77a;

            margin: 15px auto;
        }

        .organization {
            color: #fff;

            font-size: clamp(14px, 1.2vw, 22px);

            font-weight: 600;
        }

        /* ปุ่ม */

        .button-group {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            flex-wrap: nowrap;
            margin-top: 15px;
            transform: translateY(-20px);
        }

        .royal-box {
            width: min(260px, 18vw);
        }

        .royal-btn {
            display: block;
        }

        .royal-btn img {
            width: 100%;
            height: auto;
            display: block;
        }

        .enter-btn img {
            width: 50%;
            height: auto;
        }



        /* แสง */

        .light-1 {
            width: min(150px, 8vw);
        }

        .light-2 {
            width: min(70px, 4vw);
        }

        .light-3 {
            width: min(160px, 8vw);
        }

        .light-4 {
            width: min(90px, 5vw);
        }

        .light-5 {
            width: min(140px, 8vw);
        }

        /* จอเล็ก */

        @media(max-width:991px) {

            .princess {
                height: 30vh;
            }

            .button-group {
                flex-direction: column;
                gap: 15px;
            }

            .royal-box {
                width: 220px;
            }

            .enter-btn {
                width: 220px;
                min-width: auto;
            }

            .organization {
                margin-top: 5px;
            }
        }
    </style> --}}
</head>

<body>

    <div class="royal-page">

        <div class="spotlight"></div>

        <div class="cloud"></div>

        <div class="light-wrap">

            <img src="{{ asset('intro/Light-1.png') }}" class="light light-1">

            <img src="{{ asset('intro/Light-2.png') }}" class="light light-2">

            <img src="{{ asset('intro/Light-1.png') }}" class="light light-3">

            <img src="{{ asset('intro/Light-2.png') }}" class="light light-4">

            <img src="{{ asset('intro/Light-1.png') }}" class="light light-5">

        </div>

        <div class="content">

            <img src="{{ asset('intro/princess.png') }}" class="princess">

            <div class="title">
                สถิตในใจไทยนิรันดร์
            </div>

            <div class="subtitle">
                น้อมสำนึกในพระกรุณาธิคุณเป็นล้นพ้น
            </div>

            <div class="royal-name">
                สมเด็จพระเจ้าลูกเธอ เจ้าฟ้าพัชรกิติยาภา นเรนทิราเทพยวดี กรมหลวงราชสาริณีสิริพัชร มหาวัชรราชธิดา
            </div>

            <div class="footer">
                ข้าพระพุทธเจ้า คณะผู้บริหาร สมาชิกสภาฯ ข้าราชการ พนักงาน และลูกจ้าง
            </div>

            <div class="divider"></div>

            <div class="organization">
                เทศบาลตำบลท่าข้าม จังหวัดฉะเชิงเทรา
            </div>

            <div class="button-group">

                <div class="royal-box">
                    <a href="https://www.royaloffice.th/" class="royal-btn" target="_blank">
                        <img src="{{ asset('intro/a1.png') }}" alt="">
                    </a>
                </div>

                <a href="/" class="enter-btn">
                    {{-- เข้าสู่เว็บไซต์ --}}
                    <img src="{{ asset('/intro/Button.png') }}" alt="เข้าสู่ระบบ">
                </a>

                <div class="royal-box">

                    <a href="https://www.royaloffice.th/12/06/2026/%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%81%E0%B8%B2%E0%B8%A8%E0%B8%AA%E0%B8%B3%E0%B8%99%E0%B8%B1%E0%B8%81%E0%B8%9E%E0%B8%A3%E0%B8%B0%E0%B8%A3%E0%B8%B2%E0%B8%8A%E0%B8%A7%E0%B8%B1%E0%B8%87-12_06_2569/"
                        target="_blank" class="royal-btn">
                        <img src="{{ asset('intro/a2.png') }}" alt="">
                    </a>
                </div>

            </div>

        </div>

    </div>

</body>

</html>
