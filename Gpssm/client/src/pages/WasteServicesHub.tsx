import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Link, useLocation } from "wouter";
import { ArrowLeft } from "lucide-react";

// Background for this hub
import garbageBg from "@assets/GarbageBody-BG.jpg";
import backBtn from "@assets/Back-Button.png";
// Buttons (images)
import imgTruck from "@assets/GarbageButton-1.png";
import imgPay from "@assets/GarbageButton-2.png";
import imgHazard from "@assets/GarbageButton-3.png";
import imgRequest from "@assets/GarbageButton-4.png";



function ImageButton({ to, img, aria }: { to: string; img: string; aria: string }) {
  return (
    <Link
      href={to}
      aria-label={aria}
      className="group block focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 rounded-2xl"
    >
      <img
        src={img}
        alt=""
        className="w-full h-auto object-contain select-none transition-transform duration-200 group-active:scale-95 group-hover:brightness-105"
        draggable={false}
        loading="lazy"
        decoding="async"
      />
    </Link>
  );
}

export default function WasteServicesHub() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  return (
    <div
      className="min-h-screen flex flex-col"
      style={{
        backgroundImage: `url(${garbageBg})`,
        backgroundRepeat: "no-repeat",
        backgroundSize: "cover",
        backgroundPosition: "center top",
      }}
    >
      <Header />

      <main className="mx-auto w-full max-w-8xl flex-1 px-3 md:px-6 py-6 md:py-10">
        {/* Back button */}
     <div className="w-full max-w-[1950px] mx-auto px-3 md:px-0 mt-3 md:mt-6">
        <button
          onClick={goBack}
          className="inline-block rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400"
          aria-label="ย้อนกลับ"
        >
          <img
            src={backBtn}
            alt="ย้อนกลับ"
            className="h-12 sm:h-14 md:h-16 lg:h-20 xl:h-14 w-auto select-none pointer-events-none"
          />
        </button>
      </div>


        {/* Image-only buttons */}
        <section className="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
        <div className="flex justify-center [&_img]:w-[260px] md:[&_img]:w-[340px] lg:[&_img]:w-[380px] [&_img]:h-auto">
          <ImageButton to="/truck-status" img={imgTruck} aria="สถานะรถเก็บขยะ" />
        </div>
        <div className="flex justify-center [&_img]:w-[260px] md:[&_img]:w-[340px] lg:[&_img]:w-[380px] [&_img]:h-auto">
          <ImageButton to="/payment" img={imgPay} aria="ชำระค่าธรรมเนียม" />
        </div>
        <div className="flex justify-center [&_img]:w-[260px] md:[&_img]:w-[340px] lg:[&_img]:w-[380px] [&_img]:h-auto">
          <ImageButton to="/hazard-dumps" img={imgHazard} aria="แจ้งทิ้งขยะอันตราย" />
        </div>
        <div className="flex justify-center [&_img]:w-[260px] md:[&_img]:w-[340px] lg:[&_img]:w-[380px] [&_img]:h-auto">
          <ImageButton to="/service-request?form=trash-bin" img={imgRequest} aria="ขอรับบริการถังขยะ" />
        </div>  

      </section>

      </main>

      <Footer />
    </div>
  );
}
