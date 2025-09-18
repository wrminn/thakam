<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>PDF Report</title>
    <style>
        body {
            font-family: freeserif; /* Html2Pdf รองรับ freeserif/freesans/times */
            font-size: 20px;
            line-height: 1.2;
        }

        .title_doc {
            text-align: center;
            margin-bottom: 10px;
        }

        .dotted-line {
            border-bottom: 1px dotted black;
            display: inline-block;
            min-width: 50px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .section {
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="title_doc">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/detailweb/logo.png'))) }}" alt="Logo" height="80">
        <h3>แบบฟอร์มคำร้องทั่วไป</h3>
    </div>

    <div class="section text-right">
        วันที่: <span class="dotted-line">{{ $list->field_date }}</span>
    </div>

    <div class="section text-left">
        เรื่อง: <span class="dotted-line" style="min-width: 70%">{{ $list->field_4 }}</span>
    </div>

    <div class="section text-left">
        เรียน: นายกเทศมนตรีตำบลบ้านโพธิ์
    </div>

    <div class="section text-left">
        ข้าพเจ้า: <span class="dotted-line" style="min-width: 60%">{{ $list->field_1 }}{{ $list->field_2 }}</span><br>
        บ้านเลขที่: <span class="dotted-line">{{ $list->field_5 }}</span>
        หมู่ที่: <span class="dotted-line">{{ $list->field_6 }}</span>
        ตรอก/ซอย: <span class="dotted-line">{{ $list->field_7 }}</span>
        ถนน: <span class="dotted-line">{{ $list->field_8 }}</span>
        แขวง/ตำบล: <span class="dotted-line">{{ $list->field_9 }}</span>
        เขต/อำเภอ: <span class="dotted-line">{{ $list->field_10 }}</span>
        จังหวัด: <span class="dotted-line">{{ $list->field_11 }}</span>
        เบอร์โทร: <span class="dotted-line">{{ $list->field_3 }}</span>
    </div>

    <div class="section text-left">
        เรื่องที่ร้อง: <span class="dotted-line" style="min-width: 60%">{{ $list->field_4 }}</span><br>
        รายละเอียดการขอความอนุเคราะห์: <span class="dotted-line" style="min-width: 60%">{{ $list->field_13 }}</span>
    </div>

    <div class="section text-center">
        จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์ในเรื่องดังกล่าว
    </div>

    <div class="section text-center" style="margin-top: 20px;">
        ขอแสดงความนับถือ<br>
        ลงชื่อ: <span class="dotted-line" style="min-width: 35%">{{ $list->field_2 }}</span><br>
        ( <span class="dotted-line" style="min-width: 35%">{{ $list->field_1 }}{{ $list->field_2 }}</span> )<br>
        ผู้ยื่นคำร้อง
    </div>

    <div class="footer">
        "ซื่อสัตย์สุจริต มุ่งสัมฤทธิ์ของงาน ยืดมั่นมาตรฐาน บริการด้วยใจเป็นธรรม"
    </div>

</body>

</html>
