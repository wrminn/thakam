import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Link } from "wouter";

/** Assets */
import bodyBg from "@assets/Body-BG.jpg";
import ossTitle from "@assets/1.png";

import mayorCard from "@assets/นายก.png";
import licenseBtn from "@assets/Buton-1.png";
import smartCity from "@assets/Object.png";

import phone from "@assets/Phone.png";
import inPhoneBanner from "@assets/In-Phone-Banner.png";
import phoneBtn1 from "@assets/Phone-Button-1.png";
import phoneBtn2 from "@assets/Phone-Button-2.png";

import feeBanner from "@assets/Button-2.png";

import redBannerBG from "@assets/Banner.png"; // เป็นพื้นหลังหัวข้อของ 3 ปุ่ม
import btnAccident from "@assets/Button-3.png";
import btnTree from "@assets/Button-4.png";
import btnFire from "@assets/Button-5.png";
import btnRoad from "@assets/Button-6.png";
import btnLight from "@assets/Button-7.png";

export default function HomePage() {
  return (
    <div
      className="min-h-screen flex flex-col"
      style={{
        backgroundImage: `url(${bodyBg})`,
        backgroundRepeat: "repeat-x",
        backgroundSize: "auto 100%",
        backgroundPosition: "center top",
      }}
    >
      <Header />

      <main className="flex-1">
        {/* หัว One Stop Service (เวอร์ชันย่อ) */}
        <div className="mt-4 mx-auto w-full max-w-7xl px-3 sm:px-4">
          <div className="flex md:justify-end justify-center">
            <img src={ossTitle} alt="" className="w-[730px] md:max-w-[92vw] max-w-full h-auto" />
          </div>
        </div>

        {/* ------------ พื้นที่คอนเทนต์หลัก ------------- */}
        <section className="relative mx-auto mt-3 w-full max-w-7xl px-3 sm:px-4 pb-24 md:pb-28">
          {/* ========== ชั้นพื้นหลัง: Object.png (ให้ทับได้) ========== */}
          <div className="hidden md:flex pointer-events-none absolute left-0 right-0 bottom-2 md:bottom-0 justify-start">
            <img
              src={smartCity}
              alt="SMART CITY THAKAM"
              className="h-auto opacity-100 w-[220px] xs:w-[300px] sm:w-[340px] md:w-[380px] lg:w-[420px] xl:w-[460px]"
            />
          </div>

          {/* เลย์เอาต์ 3 คอลัมน์ (มือถือ 1 คอลัมน์) */}
          <div className="grid gap-6 md:gap-7 lg:gap-[28px] grid-cols-1 lg:[grid-template-columns:380px_1fr_400px]">
            {/* ===== ซ้าย: นายก + ขอใบอนุญาต ===== */}
            <div className="flex flex-col items-center gap-3 md:-mt-6">
              <img src={mayorCard} alt="" className="w-full max-w-[400px] h-auto" />
              <div className="relative md:translate-y-12 z-10 w-full flex justify-center">
                <Link href="/Dashboard" aria-label="ขอใบอนุญาตประกอบกิจการ" className="block w-full max-w-[400px]">
                  <img src={licenseBtn} alt="" className="w-full h-auto" />
                </Link>
              </div>
            </div>

            {/* ===== กลาง: โทรศัพท์ ===== */}
            <div className="flex flex-col items-center">
              <div className="relative w-full max-w-[380px]">
                {/* กรอบเครื่อง */}
                <img src={phone} alt="โทรศัพท์" className="w-full h-auto select-none pointer-events-none" />

                {/* ป้ายกลางบนหน้าจอ (ทำเป็นปุ่มลิงก์) */}
                <Link
                  href="/eservices"
                  aria-label="เข้าเมนู e-Services"
                  className="absolute inset-0"
                >
                  <img
                    src={inPhoneBanner}
                    alt="เข้าสู่บริการ e-Services"
                    className="absolute left-1/2 -translate-x-1/2 w-[80%] md:w-[84%] h-auto cursor-pointer transition-transform duration-150 active:scale-[0.98]"
                    style={{ top: "15%" }} 
                  />
                </Link>

                {/* ปุ่ม 2 ปุ่ม — อยู่ในจอ*/}
                <div className="absolute left-0 right-0 flex flex-col items-center gap-2" style={{ bottom: "15%" }}>
                  <button type="button" className="rounded-full">
                    <img src={phoneBtn1} alt="วิดีโอแนะนำการใช้งาน" className="h-[52px] md:h-[60px] w-auto" />
                  </button>
                  <button type="button" className="rounded-full">
                    <img src={phoneBtn2} alt="คู่มือแนะนำการใช้งาน" className="h-[52px] md:h-[60px] w-auto" />
                  </button>
                </div>
              </div>
            </div>

            {/* ===== ขวา: ค่าธรรมเนียม + แบนเนอร์พื้นหลัง 3 ปุ่ม ===== */}
            <div className="flex flex-col items-center lg:items-end gap-4">
              <Link href="/waste-services" aria-label="ค่าธรรมเนียมเก็บและขนขยะมูลฝอย" className="block w-full max-w-[420px]">
                <img src={feeBanner} alt="ค่าธรรมเนียม" className="w-full h-auto" />
              </Link>

              {/* พื้นหลัง Banner + 3 ปุ่ม */}
              <div className="relative w-full max-w-[450px]">
                <img src={redBannerBG} alt="แจ้งเหตุฉุกเฉิน" className="w-full h-auto" />

                {/* ปุ่ม 3 ใบ ปรับระยะตามขนาดจอ */}
                <div className="absolute left-0 right-0 px-14 grid grid-cols-3 gap-2 md:gap-5" style={{ top: "42%" }}>
                  <Link href="/emergency" className="block justify-self-start">
                    <img src={btnFire} alt="แจ้งเหตุไฟไหม้" className="w-[72px] md:w-[80px] h-auto" />
                  </Link>
                  <Link href="/emergency" className="block justify-self-center">
                    <img src={btnTree} alt="แจ้งเหตุต้นไม้ล้ม" className="w-[72px] md:w-[80px] h-auto" />
                  </Link>
                  <Link href="/emergency" className="block justify-self-end">
                    <img src={btnAccident} alt="แจ้งเหตุฉุกเฉิน" className="w-[72px] md:w-[80px] h-auto" />
                  </Link>
                </div> 
              </div>

              {/* ปุ่มยาว 2 แถบ */}
              <div className="w-full max-w-[450px] space-y-3 flex flex-col">
                {/* บนมือถือยกเลิก translate เพื่อไม่ให้ล้ำออกนอกขอบ */}
                <Link href="/service-request" className="block md:-translate-y-7 md:translate-x-11">
                  <img src={btnRoad} alt="แจ้งเหตุถนนเสีย" className="w-[260px] sm:w-[320px] h-auto mx-auto md:mx-0" />
                </Link>
                <Link href="/service-request" className="block md:-translate-y-5 md:translate-x-11">
                  <img src={btnLight} alt="แจ้งเหตุไฟเสีย" className="w-[260px] sm:w-[320px] h-auto mx-auto md:mx-0" />
                </Link>
              </div>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
}
