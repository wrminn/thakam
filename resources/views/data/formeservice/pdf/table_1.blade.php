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
            font-size: 20px;
            line-height: 1.6;
        }

        .title_doc {
            text-align: center;
            margin-bottom: 20px;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 500px;
            padding: 0 5px;
        }

        @page {
            margin: 20mm 15mm 10mm 15mm;
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
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/detailweb/logo.png'))) }}"
            alt="Logo" height="80"><br>
        <strong>แบบฟอร์มคำร้องทั่วไป</strong>
    </div>
    <div class="box_text" style="text-align: right;">
        <div style="margin-right: 80px; margin-top: 10px;">
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

    <p><strong>เรื่อง</strong> <span class="dotted-line" style="width: 70%">&nbsp;&nbsp; {{ $list->field_4 }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
    <p><strong>เรียน</strong> นายกเทศมนตรีเทศบาลตำบลบ้านโพธิ์</p>

    <p>
        ข้าพเจ้า <span class="dotted-line"
            style="width: 87%; text-align: start; margin-left: 10px;">&nbsp;&nbsp;&nbsp;{{ $list->field_1 }}
            {{ $list->field_2 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
        อาศัยอยู่บ้านเลขที่ <span class="dotted-line"
            style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_5 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        หมู่ที่ <span class="dotted-line"
            style="width: 6%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_6 }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
        ตรอก/ซอย <span class="dotted-line"
            style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;{{ $list->field_7 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        ถนน <span class="dotted-line"
            style="width: 5%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_8 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        แขวง/ตำบล <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_9 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <br>

        เขต/อำเภอ <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_10 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        จังหวัด <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_11 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        รหัสไปรษณีย์ <span class="dotted-line"
            style="width: 10%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_12 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        เบอร์โทรติดต่อ <span class="dotted-line"
            style="width: 15%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_3 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </p>

    <p>
        ขอยื่นคำร้องต่อนายกเทศมนตรีเทศบาลตำบลบ้านโพธิ์ ดังนี้<br>
        <span class="dotted-line"
            style="width: 90%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_13 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </p>

    <p>
        พร้อมนี้ได้แนบเอกสารหลักฐานมาด้วย จำนวน
        <span
            class="dotted-line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $list->field_14 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        ฉบับ
    </p>

    <p style="margin-top: 40px; text-align: center;">
        ขอแสดงความนับถือ
    </p>

    <p style="margin-top: 40px; text-align: center;">
        ลงชื่อ ..............{{ $list->field_2 }}....................<br>
        ( {{ $list->field_1 }} {{ $list->field_2 }} )<br>
        ผู้ยื่นคำร้อง
    </p>

    <div class="footer">
        <p>"ซื่อสัตย์สุจริต มุ่งสัมฤทธิ์ของงาน ยึดมั่นมาตรฐาน บริการด้วยใจเป็นธรรม"</p>
    </div>

</body>

</html>
