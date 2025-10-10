import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Link, useLocation } from "wouter";

/** Assets */
import bodyBg from "@assets/Body-BG2.jpg";
import backBtn from "@assets/Back-Button.png";

// รูปหมวด (ที่มีอยู่ในโปรเจกต์) — หมวดที่ไม่มีรูปจะใช้ของสำนักปลัดเป็นค่าเริ่มต้น
import officeImg from "@assets/2-2.png";   // สำนักปลัด (ใช้เป็น fallback)
import engineerImg from "@assets/3.png";   // กองช่าง
import financeImg from "@assets/6.png";    // กองยุทธศาสตร์ฯ (ถ้าไม่มีจะ fallback เป็น officeImg)
import eduImg from "@assets/5.png";        // กองศึกษา
import healthImg from "@assets/4.png";
import healthImg1 from "@assets/7กองสวัสดิการสังคม.png";      // กองสาธารณสุขฯ

const DEST = "/service-request"; // fallback ปลายทาง

type Dept = {
  key: string;

  img: string;        // ถ้าไม่มีจะส่ง officeImg มาแทน
  items: string[];    // ป้ายปุ่มภายในหมวด
};

// ลิงก์ปลายทางของแต่ละรายการ
const FORM_LINKS: Record<string, string> = {
  // สำนักปลัด
  "คำร้องทั่วไป": "/service-request?form=general#form",
  "คำร้องขอติดตั้งป้ายโฆษณาริมถนนสาธารณะ": "/service-request?slug=roadside-ads-sign-install#form",
  "คำร้องเรียนการทุจริตและประพฤติมิชอบของเจ้าหน้าที่": "/service-request?slug=official-misconduct-complaint#form",
  "คำขอเครื่องหมายรับรองผู้ประกอบธุรกิจพาณิชย์อิเล็กทรอนิกส์ (DBD Registered)": "/service-request?slug=dbd-registered#form",
  "คำขอจดทะเบียนพาณิชย์ (ใหม่/เปลี่ยนแปลง/ยกเลิก)": "/service-request?slug=commercial-registration#form",
  "คำร้องทะเบียนพาณิชย์": "/service-request?slug=commercial-reg-request#form",
  "คําขอตรวจค้นเอกสาร/รับรองสําเนาเอกสาร/ใบแทน": "/service-request?slug=document-search-certification#form",
  "หนังสือมอบอำนาจ": "/service-request?slug=power-of-attorney#form",

  // กองยุทธศาสตร์และงบประมาณ
  "คำร้องขอข้อมูลข่าวสาร": "/service-request?slug=info-disclosure-request#form",

  // กองศึกษา
  "ใบสมัครเรียน  ศพด.บ้านท่าข้าม": "/service-request?slug=prek-thakham#form",
  "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดบางแสม": "/service-request?slug=prek-bangsaem#form",
  "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดคลองพานทอง": "/service-request?slug=prek-khlong-phanthong#form",

  // กองสาธารณสุขฯ
  "คำร้องขอถังขยะ": "/service-request?form=trash-bin#form",

  // กองช่าง
  "คำร้องทั่วไป (ซ่อมไฟฟ้าสาธารณะ , ซ่อมแซมถนน)": "/service-request?slug=public-works-general#form",
  "ใบแจ้งการขุดดินหรือถมดิน": "/service-request?slug=excavation-landfill-notice#form",

  // กองสวัสดิการสังคม
  "คำร้องทั่วไปขอรับการช่วยเหลือ": "/service-request?slug=welfare-assistance-general#form",
};

// ถ้าไม่พบในแมป ให้ไปหน้า /service-request เป็นค่าเริ่มต้น
const linkFor = (label: string) => FORM_LINKS[label] ?? DEST;

// ===== หมวดหมู่ตามที่กำหนด =====
const DEPARTMENTS: Dept[] = [
  {
    key: "office",

    img: officeImg,
    items: [
      "คำร้องทั่วไป",
      "คำร้องขอติดตั้งป้ายโฆษณาริมถนนสาธารณะ",
      "คำร้องเรียนการทุจริตและประพฤติมิชอบของเจ้าหน้าที่",
      "คำขอเครื่องหมายรับรองผู้ประกอบธุรกิจพาณิชย์อิเล็กทรอนิกส์ (DBD Registered)",
      "คำขอจดทะเบียนพาณิชย์ (ใหม่/เปลี่ยนแปลง/ยกเลิก)",
      "คำร้องทะเบียนพาณิชย์",
      "คําขอตรวจค้นเอกสาร/รับรองสําเนาเอกสาร/ใบแทน",
      "หนังสือมอบอำนาจ",
    ],
  },
  {
    key: "strategy",

    img: financeImg || officeImg, // ถ้าไม่ชัวร์หรือไม่มีไฟล์ ให้ใช้ officeImg
    items: ["คำร้องขอข้อมูลข่าวสาร"],
  },
  {
    key: "edu",

    img: eduImg || officeImg,
    items: [
      "ใบสมัครเรียน  ศพด.บ้านท่าข้าม",
      "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดบางแสม",
      "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดคลองพานทอง",
    ],
  },
  {
    key: "health",

    img: healthImg || officeImg,
    items: ["คำร้องขอถังขยะ"],
  },
  {
    key: "works",
 
    img: engineerImg || officeImg,
    items: [
      "คำร้องทั่วไป (ซ่อมไฟฟ้าสาธารณะ , ซ่อมแซมถนน)",
      "ใบแจ้งการขุดดินหรือถมดิน",
    ],
  },
  {
    key: "welfare",
   
    img: healthImg1, // ยังไม่มีรูป → ใช้ของสำนักปลัด
    items: ["คำร้องทั่วไปขอรับการช่วยเหลือ"],
  },
];

export default function EServicesPage() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  return (
    <div
      className="min-h-screen flex flex-col"
      style={{
        backgroundImage: `url(${bodyBg})`,
        backgroundRepeat: "no-repeat",
        backgroundSize: "cover",
        backgroundPosition: "center top",
      }}
    >
      <Header />

      {/* ปุ่มย้อนกลับ */}
      <div className="w-full max-w-[1180px] mx-auto px-3 md:px-0 mt-3 md:mt-6">
        <button
          onClick={goBack}
          className="inline-block focus:outline-none focus:ring-2 focus:ring-emerald-400 rounded-xl"
        >
          <img src={backBtn} alt="ย้อนกลับ" className="h-9 md:h-11 w-auto" />
        </button>
      </div>

      <main className="w-full max-w-[1180px] mx-auto flex-1 px-3 md:px-0 pb-16">
        {/* Masonry columns */}
        <div className="columns-1 md:columns-2 gap-x-4 md:gap-x-6">
          {DEPARTMENTS.map((dept) => (
            <DeptPanel key={dept.key} dept={dept} />
          ))}
        </div>
      </main>

      <Footer />
    </div>
  );
}

function DeptPanel({ dept, className = "" }: { dept: Dept; className?: string }) {
  return (
    <section
      className={
        "relative inline-block w-full align-top mb-4 md:mb-6 " + // สำคัญสำหรับ masonry
        "rounded-2xl bg-white/85 shadow-sm backdrop-blur border border-emerald-200/60 p-3 md:p-4 " +
        (className || "")
      }
      style={{ breakInside: "avoid" }} // กันการ์ดแตกคอลัมน์
    >
      <div className="grid grid-cols-[120px,1fr] gap-3 md:grid-cols-[170px,1fr] md:gap-4">
        {/* รูปหมวด */}
        <div className="text-center">
          <div className="mx-auto flex h-28 w-28 md:h-40 md:w-40 items-center justify-center rounded-2xl bg-white shadow">
            <img
              src={dept.img || officeImg}
              onError={(e) => {
                (e.currentTarget as HTMLImageElement).src = officeImg; // fallback ถ้ารูปหาย
              }}
           
              className="h-24 w-24 md:h-36 md:w-36 object-contain"
            />
          </div>
          <p className="mt-2 text-teal-800 text-sm md:text-base leading-tight"></p>
        </div>

        {/* รายการในหมวด */}
        <ul className="flex flex-col gap-2">
          {dept.items.map((label, i) => (
            <li key={i}>
              <Link
                href={linkFor(label)}
                className="group flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-3 py-2 text-white shadow hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-white/60"
              >
                <span
                  aria-hidden
                  className="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white/15 ring-1 ring-white/30"
                >
                  ▶
                </span>
                <span className="text-[15px] md:text-base leading-snug">{label}</span>
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}
