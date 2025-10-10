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
                {{-- <a href="#">แก้ไขข้อมูลโปรไฟล์</a> --}}
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-blue-600 hover:text-blue-800">
                    ออกจากระบบ
                </a>



            </div>
        </div>

        <!-- เมนู -->
        <div class="menu">

            <div class="menu-item has-submenu">
                ข้อมูลพื้นฐานตำบล
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/1">ประวัติความเป็นมา</a>
                <a href="/backend/listtexteditor/menu/2">ข้อมูลสภาพทั่วไป</a>
                <a href="#" class="noactive">ข้อมูลชุมชน</a>
                <a href="/backend/listtexteditor/menu/3">ข้อมูลและรายละเอียดชุมชน</a>
                <a href="/backend/list/menu/4">ผู้นำชุมชน</a>
                <a href="/backend/list/menu/5">ผลิตภัณฑ์ชุมชน</a>
                <a href="/backend/list/menu/6">สถานที่สำคัญ</a>
                <a href="/backend/listtexteditor/menu/7">แกลอรี่ภาพถ่ายภูมิทัศน์</a>
                <a href="/backend/listtexteditor/menu/8">บริการขั้นพื้นฐาน</a>
                <a href="/backend/listtexteditor/menu/9">ยุทธศาสตร์การพัฒนา</a>
            </div>
            <div class="menu-item has-submenu">
                บุคลากร
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/10">โครงสร้างองค์กร</a>
                <a href="/backend/list/menu/11">คณะผู้บริหาร</a>
                <a href="/backend/list/menu/12">สมาชิกสภา</a>
                <a href="/backend/list/menu/13">ผู้บริหารส่วนราชการ</a>
                <a href="/backend/list/menu/14">สำนักปลัดเทศบาล</a>
                <a href="/backend/list/menu/15">กองคลัง</a>
                <a href="/backend/list/menu/16">กองช่าง</a>
                <a href="/backend/list/menu/17">กองการศึกษา</a>
                <a href="/backend/list/menu/18">กองสาธารณสุขและสิ่งแวดล้อม</a>
                <a href="/backend/list/menu/19">กองสวัสดิการสังคม</a>
                <a href="/backend/list/menu/20">กองส่งเสริมการเกษตร</a>
                <a href="/backend/list/menu/21">กองยุทธศาสตร์และงบประมาณ</a>
                <a href="/backend/list/menu/22">กองการประปา</a>
                <a href="/backend/list/menu/23">หน่วยตรวจสอบภายใน</a>
            </div>
            <?php /*
            <div class="menu-item has-submenu">
                ผลการดำเนินงาน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/24">รายงานแสดงฐานะการเงิน</a>
                <a href="/backend/list/menu/25">งบแสดงฐานะทางการเงิน</a>
                <a href="/backend/list/menu/26">รายงานแสดงรายรับ-รายจ่าย</a>
                <a href="/backend/list/menu/27">แผนการใช้จ่ายเงินงบประมาณประจำปี</a>
                <a href="/backend/list/menu/28">รายงานการตรวจสอบการเงิน สำนักงานการตรวจเงินแผ่นดิน</a>
                <a href="#" class="noactive">รายงานผลการดำเนินงาน</a>
                <a href="/backend/list/menu/29">รายงานผลการดำเนินงาน ประจำไตรมาส</a>
                <a href="/backend/list/menu/30">รายงานผลการดำเนินงาน ประจำปีงบประมาณ</a>
                <a href="#" class="noactive">รายงานผลการดำเนินงาน</a>
                <a href="/backend/list/menu/31">รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ</a>
                <a href="/backend/list/menu/32">รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุรายเดือน</a>
                <a href="/backend/list/menu/33">รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุประจำปี</a>
                <a href="/backend/list/menu/34">ความก้าวหน้าในการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ</a>
                <a href="#" class="noactive">ข้อมูลเชิงสถิติ</a>
                <a href="/backend/list/menu/35">ข้อมูลเชิงสถิติการให้บริการ</a>
                <a href="/backend/list/menu/36">ข้อมูลเชิงสถิติเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                <a href="#" class="noactive">การบริหารเเละพัฒนาทรัพยากรบุคคล</a>
                <a href="/backend/list/menu/37">ประกาศและนโยบายการบริหารทรัพยากรบุคคล</a>
                <a href="/backend/list/menu/38">หลักเกณฑ์การบริหารและพัฒนาทรัพยากรบุคคล</a>
                <a href="/backend/list/menu/39">การดำเนินการตามนโยบายการบริหารทรัพยากรบุคคล</a>
                <a href="/backend/list/menu/40">รายงานผลการบริหารและพัฒนาทรัพยากรบุคคลประจำปี</a>
                <a href="/backend/list/menu/41">แผนการบริหารและพัฒนาทรัพยากรบุคคล</a>
                <a href="/backend/list/menu/42">มาตรฐานการกำหนดตำแหน่ง</a>
                <a href="#" class="noactive">มาตรการส่งเสริมความโปร่งใสและป้องกันการทุจริต</a>
                <a href="/backend/list/menu/43">มาตรการป้องกันการรับสินบน</a>
                <a href="/backend/list/menu/44">มาตรการเผยแพร่ข้อมูลต่อสาธารณะ</a>
                <a href="/backend/list/menu/45">มาตรการตรวจสอบการใช้ดุลพินิจ</a>
                <a href="/backend/list/menu/46">มาตรการส่งเสริมความโปรงใส่ในการจัดซื้อจัดจ้าง</a>
                <a href="/backend/list/menu/47">มาตรการจัดการเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                <a href="/backend/list/menu/48">มาตรการให้ผู้มีส่วนได้เสียมีส่วนร่วมในการป้องกันทุจริตและประพฤติมิชอบ</a>
                <a href="/backend/list/menu/49">มาตรการป้องกันการขัดกันระหว่างผลประโยชน์ส่วนตนกับผลประโยชน์ส่วนรวม</a>
                <a href="/backend/list/menu/50">มาตรการส่งเสริมคุณธรรมและความโปร่งในภายในหน่วยงาน</a>
                <a href="/backend/list/menu/51">รายงานผลการดำเนินการเพื่อส่งเสริมคุณธรรมและความโปร่งใสภายในหน่วยงาน</a>
                <a href="/backend/list/menu/52">แนวปฏิบัติการจัดการเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                
            </div>
            */
            ?>
            <div class="menu-item has-submenu">
                อำนาจหน้าที่
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/53">อำนาจหน้าที่ เทศบาล</a>
                <a href="/backend/listtexteditor/menu/54">สำนักปลัดเทศบาล</a>
                <a href="/backend/listtexteditor/menu/55">กองคลัง</a>
                <a href="/backend/listtexteditor/menu/56">กองช่าง</a>
                <a href="/backend/listtexteditor/menu/57">กองการศึกษา</a>
                <a href="/backend/listtexteditor/menu/58">กองสาธารณสุขและสิ่งแวดล้อม</a>
                <a href="/backend/listtexteditor/menu/59">กองสวัสดิการสังคม</a>
                <a href="/backend/listtexteditor/menu/60">กองส่งเสริมการเกษตร</a>
                <a href="/backend/listtexteditor/menu/61">กองยุทธศาสตร์และงบประมาณ</a>
                <a href="/backend/listtexteditor/menu/62">กองการประปา</a>
                <a href="/backend/listtexteditor/menu/63">หน่วยตรวจสอบภายใน</a>
            </div>
            <div class="menu-item has-submenu">
                แผนพัฒนาท้องถิ่น
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/64">แผนพัฒนาท้องถิ่น</a>
                <a href="/backend/list/menu/65">แผนการดำเนินงานประจำปี</a>
                <a href="/backend/list/menu/66">แผนแม่บทระบบเทคโนโลยีสารสนเทศ</a>
                <a href="/backend/list/menu/67">แผนการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ</a>
                <a href="/backend/list/menu/68">แผนอัตรากำลัง</a>
                <a href="/backend/list/menu/69">แผนยุทธศาสตร์และการพัฒนา</a>
                <a href="/backend/list/menu/70">แผนปฏิบัติการป้องกันการทุจริตประจำปี</a>
                <a href="/backend/list/menu/71">เทศบัญญัติงบประมาณรายจ่าย</a>
                <a href="/backend/list/menu/72">การประเมินความเสี่ยงการทุจริตและประพฤติมิชอบประจำปี</a>
                <a href="/backend/list/menu/73">รายงานผลการดำเนินการป้องกันการทุจริตและประพฤติมิชอบประจำปี</a>
                <a href="/backend/list/menu/74">รายงานติดตามและประเมินผลแผนพัฒนา</a>
            </div>
            <div class="menu-item has-submenu">
                กฎหมายและระเบียบ
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="#" class="noactive">ข้อบัญญัติ และคำสั่ง อบต./เทศบัญญัติ และคำสั่งเทศบาล</a>
                <a href="/backend/listtexteditor/menu/75">ข้อบัญญัติ และคำสั่ง อบต./เทศบัญญัติ และคำสั่งเทศบาล</a>
                <a href="#" class="noactive">กฎหมาย/ระเบียบของเทศบาล</a>
                <a href="/backend/list/menu/76">กฎหมายที่เกี่ยวข้อง</a>
                <a href="/backend/list/menu/77">กฎหมาย/ระเบียบ</a>
                <a href="/backend/list/menu/78">กฎหมายเกี่ยวกับภาษี</a>
                <a href="/backend/list/menu/79">ระเบียบเกี่ยวกับการจัดทำแผนพัฒนา</a>
                <a href="/backend/list/menu/80">กฎหมายที่เกี่ยวกับการปฏิบัติงาน</a>
                <a href="/backend/list/menu/81">กฎหมายเกี่ยวกับการจัดซื้อจัดจ้าง</a>
                <a href="/backend/list/menu/82">กฎหมายเกี่ยวกับการจัดตั้ง/ขอบเขตอำนาจหน้าที่ของเทศบาล</a>
                <a href="/backend/list/menu/83">พระราชบัญญัติ และพระราชกฤษฎีกา</a>
                <a href="/backend/list/menu/84">กฎหมาย ระเบียบ และประกาศกระทรวง</a>
            </div>
            <div class="menu-item has-submenu">
                เมนูสำหรับประชาชน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listtexteditor/menu/85">รับเรื่องราวร้องทุกข์ </a>
                <a href="/backend/list/menu/86">รับแจ้งร้องเรียนทุจริตประพฤติมิชอบ</a>
                <a href="/backend/list/menu/87">แบบสอบถามความพึงพอใจ</a>
                <a href="/backend/list/menu/88">รายงานผลการสำรวจความพึงพอใจการให้บริการ</a>
                <a href="#" class="noactive">คู่มืออื่นๆ</a>
                <a href="/backend/list/menu/89">คู่มือสำหรับประชาชน</a>
                <a href="/backend/list/menu/90">คู่มือการป้องกันการทุจริต</a>
                <a href="/backend/list/menu/91">คู่มือหรือมาตรฐานการปฏิบัติงาน</a>
                <a href="/backend/list/menu/92">E-Service</a>
                <a href="/backend/list/menu/93">ดาวน์โหลดแบบฟอร์ม</a>
                <a href="/backend/list/menu/94">เบี้ยยังชีพผู้สูงอายุ</a>
                <a href="/backend/list/menu/95">เบี้ยยังชีพคนพิการ</a>
                <a href="/backend/list/menu/96">คำถามที่พบบ่อย</a>
                <?php /* <a href="/backend/list/menu/97">ระบบจองห้องประชุมและเครื่องเสียงห้องประชุม</a> */
                ?>
            </div>
            <div class="menu-item has-submenu">
                แบนเนอร์ภายใน
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/banner/menu/98">E-Library</a>
                <a href="/backend/banner/menu/99">เบี้ยยังชีพผู้สูงอายุ</a>
                <a href="/backend/banner/menu/100">เบี้ยยังชีพคนพิการ</a>
            </div>
            <div class="menu-item has-submenu">
                แบนเนอร์ผู้บริหาร
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="#" class="noactive">ภาพสไลด์ผู้บริหาร</a>
                <a href="/backend/banner/menu/101">ภาพสไลด์นายก</a>
                <a href="/backend/banner/menu/102">ภาพสไลด์ปลัด</a>
                <a href="/backend/banner/menu/103">สายด่วนนายก</a>
                <a href="/backend/banner/menu/104">สายด่วนปลัด</a>
                <a href="/backend/banner/menu/105">สารจากนายก</a>
                <a href="/backend/banner/menu/106">เจตจำนงสุจริตของผู้บริหาร</a>
                <a href="/backend/banner/menu/107">รับเรื่องราวร้องทุกข์ </a>
                <a href="/backend/banner/menu/108">รับแจ้งร้องเรียนทุจริตประพฤติมิชอบ </a>
                <a href="/backend/banner/menu/109">การประเมินคุณธรรม และความโปร่งใส (ITA)</a>
            </div>
            <div class="menu-item has-submenu">
                ประกาศความเคลื่อนไหว
                <i class='bx bx-chevron-right chevron'></i>
            </div>
            <div class="submenu">
                <a href="/backend/listpdf/menu/110">ประกาศจัดซื้อจัดจ้าง</a>
                <a href="/backend/listpdf/menu/111">ผลประกาศจัดซื้อจัดจ้าง</a>
                <a href="/backend/listpdf/menu/112">ประกาศราคากลาง</a>
                <a href="/backend/listpdf/menu/113">รายงานผลจัดซื้อจัดจ้าง</a>
            </div>

            <a href="/backend/list/menu/114">
                <div class="menu-item">วิดีทัศน์</div>
            </a>
            <a href="/backend/list/menu/115">
                <div class="menu-item">ป้ายประกาศ</div>
            </a>
            <a href="/backend/list/menu/116">
                <div class="menu-item">E-SERVICE</div>
            </a>
            <a href="/backend/list/menu/117">
                <div class="menu-item">กิจกรรม</div>
            </a>
            <a href="/backend/list/menu/118">
                <div class="menu-item">ข่าวประชาสัมพันธ์</div>
            </a>
            <a href="/backend/list/menu/119">
                <div class="menu-item">แนะนำสถานที่ท่องเที่ยว</div>
            </a>
            <a href="/backend/list/menu/120">
                <div class="menu-item">โรงแรม ปั้มน้ำมัน ร้านอาหาร</div>
            </a>

            {{-- <a href="/backend/list/menu/20">
                <div class="menu-item">กิจกรรม</div>
            </a>
            <a href="/backend/list/menu/21">
                <div class="menu-item">กิจกรรม</div>
            </a>
            <a href="/backend/list/menu/22">
                <div class="menu-item">กิจกรรม</div>
            </a> --}}


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
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

</html>
