<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ฟอร์มคำร้อง</title>
    <style>
        body {
            font-family: 'TH Sarabun New', sans-serif;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td,
        th {
            padding: 5px;
            vertical-align: top;
        }

        th {
            text-align: left;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>แบบฟอร์มคำร้อง</h3>
    <table>
        <tr>
            <th>วันที่:</th>
            <td>{{ $list->field_15 }}</td>
        </tr>
        <tr>
            <th>คำนำหน้า:</th>
            <td>{{ $list->field_1 }}</td>
        </tr>
        <tr>
            <th>ชื่อ - นามสกุล:</th>
            <td>{{ $list->field_2 }}</td>
        </tr>
        <tr>
            <th>เบอร์โทร:</th>
            <td>{{ $list->field_3 }}</td>
        </tr>
        <tr>
            <th>เรื่อง:</th>
            <td>{{ $list->field_4 }}</td>
        </tr>
        <tr>
            <th>ที่อยู่:</th>
            <td>
                บ้านเลขที่ {{ $list->field_5 }} หมู่ที่ {{ $list->field_6 }} ตรอก/ซอย {{ $list->field_7 }} ถนน
                {{ $list->field_8 }} <br>
                แขวง/ตำบล {{ $list->field_9 }} เขต/อำเภอ {{ $list->field_10 }} จังหวัด {{ $list->field_11 }}
                รหัสไปรษณีย์ {{ $list->field_12 }}
            </td>
        </tr>
        <tr>
            <th>คำร้อง:</th>
            <td>{{ $list->field_13 }}</td>
        </tr>
        <tr>
            <th>จำนวนเอกสารแนบ:</th>
            <td>{{ $list->field_14 }} ฉบับ</td>
        </tr>
    </table>
</body>

</html>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>PDF Report</title>

    <style>
        @font-face {
            font-family: 'sarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'sarabun-bold';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew-Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'sarabun', 'sarabun-bold', sans-serif;
            font-size: 20px;
            margin: 0;
            padding: 0;
            line-height: 1;
        }


        .regis_number {
            text-align: right;
            margin-right: 8px;
        }

        .title_doc {
            text-align: center;
        }

        .box_text {
            border: 1px solid rgb(255, 255, 255);
            text-align: center;
        }

        .box_text span {
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }

        .box_text input[type="checkbox"] {
            width: 17px;
            /* ปรับขนาด checkbox ให้พอดีกับข้อความ */
            height: 17px;
            /* ปรับความสูงให้พอดีกับข้อความ */
            margin-right: 5px;
            margin-left: 5px;
            /* เว้นระยะห่างระหว่าง checkbox และข้อความ */
        }

        .box_text_border {
            margin-top: 5px;
            padding-left: 5px;
            padding-right: 5px;
            margin-bottom: 5px;
            border: 2px solid black;
            text-align: left;
            ;
        }

        .box_text_border span {
            display: inline-flex;
            align-items: left;
            line-height: 0.3;
        }

        .box_text_border input[type="checkbox"] {
            width: 17px;
            /* ปรับขนาด checkbox ให้พอดีกับข้อความ */
            height: 17px;
            /* ปรับความสูงให้พอดีกับข้อความ */
            margin-right: 5px;
            margin-left: 5px;
            /* เว้นระยะห่างระหว่าง checkbox และข้อความ */
        }

        .dotted-line {
            margin-left: 2px;
            color: blue;
            border-bottom: 2px dotted blue;
            word-wrap: break-word;

            overflow-wrap: break-word;
        }

        .footer {
            position: absolute;
            /* ทำให้ footer ยึดที่ด้านล่าง */
            bottom: -50px;
            font-size: 23px;
            /* ตั้งให้ footer อยู่ที่ด้านล่างสุดของหน้ากระดาษ */
            width: 100%;
            /* ให้ footer กว้างเต็มหน้ากระดาษ */
            text-align: center;
            /* จัดข้อความให้ตรงกลาง */
            padding: 5px 0;
            /* เพิ่มพื้นที่ด้านบนและล่างให้กับ footer */
        }
    </style>
</head>

<body>

    

    <div class="title_doc">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/detailweb/logo.png'))) }}"
            alt="Logo" height="100"> <br><strong>แบบฟอร์มคำร้องทั่วไป</strong>
    </div>
    <div class="box_text" style="text-align: right;">
        {{-- <span style="line-height: 0.7;">
            องค์การบริหารส่วนตำบลพระอาจารย์<br>
            ถนนคลองหกวา หมู่ที่ 5 ตำบลพระอาจารย์<br>
            อำเภอองครักษ์ จังหวัดนครนายก 26120
        </span> --}}
        <div style="margin-right: 80px; margin-top: 10px;">
            <span>วันที่</span>
            <span class="dotted-line" style="width: 5%; text-align: center;"> {{ $list->field_15 }}</span>
            {{-- <span>เดือน</span>
            <span class="dotted-line" style="width: 15%; text-align: center;">{{ $month }}</span>
            <span>พ.ศ.</span>
            <span class="dotted-line" style="width: 10%; text-align: center;">{{ $year }}</span> --}}
        </div>
    </div>
    
    <div class="box_text" style="text-align: left;">
        <span>เรื่อง</span>
        <span class="dotted-line" style="min-width: 95%; text-align: start; margin-left: 10px;">{{ $list->field_4 }}</span>
    </div>
    <div class="box_text" style="text-align: left;">
        <span>เรียน นายกเทศมนตรีตำบลบ้านโพธิ์ </span>
    </div>
    {{-- <div class="box_text" style="text-align: left;">
        <span>สิ่งที่ส่งมาด้วย</span>
        <span class="dotted-line" style="min-width: 88%; text-align: start; margin-left: 10px;">{{ $list->included }}</span>
        <br>
    </div> --}}

    <div class="box_text" style="text-align: left; margin-left:50px;">
        <span style="margin-left:2rem;">ข้าพเจ้า</span>
        <span class="dotted-line" style="width: 87%; text-align: start; margin-left: 10px;">{{ $list->field_1 }}{{ $list->field_2 }}</span>
    </div>
    <div class="box_text" style="text-align: left; ">
        <span>อาศัยอยู่บ้านเลขที่</span>
        <span class="dotted-line" style="width: 16%; text-align: center;">{{ $list->field_5 }}</span>
        <span>หมู่ที่</span>
        <span class="dotted-line" style="width: 17%; text-align: center;">{{ $list->field_6 }}</span>
        <span>ตรอก/ซอย</span>
        <span class="dotted-line" style="width: 20%; text-align: center;">{{ $list->field_7 }}</span>
        <span>ถนน</span>
        <span class="dotted-line" style="width: 20%; text-align: center;">{{ $list->field_8 }}</span>
        <span>แขวง/ตำบล</span>
        <span class="dotted-line"style="width: 18%; text-align: center;">{{ $list->field_9 }}</span>
        <span>เขต/อำเภอ</span>
        <span class="dotted-line" style="width: 18%; text-align: center;">{{ $list->field_10 }}</span>
        <span>จังหวัด</span>
        <span class="dotted-line"style="width: 18%; text-align: center;">{{ $list->field_11 }}</span>
        <span>เบอร์โทรติดต่อ</span>
        <span class="dotted-line" style="width: 19%; text-align: center;">{{ $list->field_3 }}</span>
    </div>
    <div class="box_text" style="text-align: left; margin-left:5rem">
        <span>เรื่องที่ร้องต่อองค์การบริหารส่วนตำบลพระอาจารย์กรณี</span>
        <span class="dotted-line" style="min-width: 50%; text-align: start;">{{ $list->field_4 }}</span>
    </div>
    <div class="box_text" style="text-align: left;">
        <span style="margin-left:5rem;">ข้าพเจ้าขอความอนุเคราะห์ให้องค์การบริหารส่วนตำบลพระอาจารย์ดำเนินการ</span>
        <span class="dotted-line" style="min-width: 30%; text-align: start;">{{ $list->field_13 }}</span>
    </div>
    <div class="box_text" style="text-align: center; ">
        <span style="margin-left:2rem;">จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์ในเรื่อง ดังกล่าว
            จักขอบคุณยิ่ง</span>
    </div>

    <div class="box_text" style="text-align: center; margin-top:2rem; margin-bottom:2rem; margin-left: 30px;">
        <span>ขอแสดงความนับถือ</span>
    </div>
    <div class="box_text" style="text-align: center; margin-top:0.5rem; margin-bottom:1.5rem; ">
        <span>ลงชื่อ</span>
        <span class="dotted-line" style="width: 35%; text-align: center;">{{ $list->field_2 }}
        </span>
        <div style="margin-left: 30px;">
            <span>(</span>
            <span class="dotted-line" style="width: 35%; text-align: center;">{{ $list->field_1 }}{{ $list->field_2 }}</span>
            <span>)</span>
        </div>
        <div style="margin-left: 30px;">
            <span>ผู้ยื่นคำร้อง</span>
        </div>
    </div>

    <div class="footer font-sarabun-bold">
        <p>"ซื่อสัตย์สุจริต มุ่งสัมฤทธิ์ของงาน ยืดมั่นมาตรฐาน บริการด้วยใจเป็นธรรม"</p>
    </div>

</body>

</html>
