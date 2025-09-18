<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แบบคำร้องทั่วไป</title>

    <style>
        @font-face {
            font-family: 'sarabun';
            font-style: normal;
            font-weight: normal;
            src: url("file://{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'sarabun-bold';
            font-style: normal;
            font-weight: bold;
            /* src: url("{{ public_path('fonts/THSarabunNew-Bold.ttf') }}") format('truetype'); */
            src: url("file://{{ public_path('fonts/THSarabunNew-Bold.ttf') }}") format('truetype');

        }

        body {
            /* font-family: 'sarabun', 'sarabun-bold', sans-serif; */
            font-family: 'thsarabun', sans-serif;
            font-size: 18px;
            line-height: 1.6;
        }

        .title_doc {
            text-align: center;
            margin-bottom: 10px;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 500px;
            padding: 0 3px;
        }

        @page {
            margin: 10mm;
            /* margin: 20mm 15mm 10mm 15mm; */
            /* เว้นขอบกระดาษ */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 20pt;
        }

        /* .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 16px;
        } */
    </style>
</head>

<body>

    @php
        $parts = explode(' ', $list->field_date);
        $day = $parts[0] ?? '';
        $month = $parts[1] ?? '';
        $year = $parts[2] ?? '';
    @endphp

    <div class="title_doc">
        {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/detailweb/logo.png'))) }}" alt="Logo" height="80"><br> --}}
        <strong>คำร้องขอสนับสนุนน้ำอุปโภค-บริโภค</strong>
    </div>
    <div class="box_text" style="text-align: right;">
        <div style="margin-right: 10px; margin-top: 5px;">
            เขียนที่ สำนักงานเทศบาลตำบลบ้านโพธิ์
        </div>
        <div style="margin-right: 80px; margin-top: 5px;">
            <span>วันที่</span>
            <span class="dotted-line" style="width: 5%; text-align: center;">&nbsp;&nbsp;&nbsp; {{ $day }}
                &nbsp;&nbsp;&nbsp;</span>
            <span>เดือน</span>
            <span class="dotted-line"
                style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $month }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span>พ.ศ.</span>
            <span class="dotted-line"
                style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;{{ $year }}&nbsp;&nbsp;&nbsp;</span>
        </div>
    </div>

    <p><strong>เรื่อง</strong> ขอความอนุเคราะห์สนับสนุนน้ำเพื่ออุปโภค-บริโภค <br>
        <strong>เรียน</strong> นายกเทศมนตรีเทศบาลตำบลบ้านโพธิ์
    </p>

    <p> ข้าพเจ้า <span class="dotted-line"
            style="width: 87%; text-align: start; margin-left: 10px;">&nbsp;&nbsp;&nbsp;{{ $list->field_1 }}&nbsp;&nbsp;{{ $list->field_2 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        อาศัยอยู่บ้านเลขที่ <span class="dotted-line"
            style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_3 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        หมู่ที่ <span class="dotted-line"
            style="width: 6%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_4 }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <span> ตำบลบ้านโพธิ์ อำเภอบ้านโพธิ์ จังหวัดฉะเชิงเทรา</span><br>
        อาชีพ <span class="dotted-line"
            style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;{{ $list->field_5 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        มีจำนวนสมาชิกที่อาศัยอยู่จริงในบ้าน จำนวน <span class="dotted-line"
            style="width: 5%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_6 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>คน
        เบอร์โทรศัพท์ <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_7 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขอยื่นคำร้องขอรับการสนับสนุนน้ำเพื่อการอุปโภคบริโภค -
        บริโภคจากเทศบาลตำบลพระอาจารย์ ในการแก้ปัญหาการขาดแคลนน้ำ โดยการจัดหาน้ำให้ต่อไป จำนวน <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_8 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        เที่ยว ซึ่งน้ำจำนวนดังกล่าวจะช่วยบรรเทาความเดือดร้อนในเบื้องต้นได้ <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์

    <div class="" style="text-align: right;margin-top: 40px;margin-right: 60px;">
        <p style="margin-right: 50px;">ขอแสดงความนับถือ</p>
        <p style="margin-right: 0px;">ลงชื่อ..............{{ $list->field_2 }}....................ผู้ยื่นคำร้อง</p>
        <p style="margin-right: 75px;"> ( {{ $list->field_1 }}  {{ $list->field_2 }})</p>
        <p style="margin-right: 75px;">ผู้ยื่นคำร้อง</p>
    </div>
</body>

</html>
