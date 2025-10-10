// src/pages/Payment.tsx
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { useLocation } from "wouter";
import backPng from "@assets/Back-Button.png";
import paymentBanner from "@assets/PaymentBanner.png";
import bgCity from "@assets/Car StatusBody-BG.jpg";

export default function PaymentPage() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  return (
    <div
      className="min-h-screen flex flex-col"
      style={{
        backgroundImage: `url(${bgCity})`,
        backgroundRepeat: "no-repeat",
        backgroundSize: "cover",
        backgroundPosition: "center top",
      }}
    >
      <Header />

      {/* ปุ่มย้อนกลับ */}
      <div className="w-full max-w-7xl mx-auto px-3 md:px-6 mt-3 md:mt-6">
        <button
          onClick={goBack}
          className="inline-block rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400"
          aria-label="ย้อนกลับ"
        >
          <img
            src={backPng}
            alt="ย้อนกลับ"
            className="h-12 sm:h-14 md:h-16 lg:h-20 xl:h-14 w-auto select-none pointer-events-none"
          />
        </button>
      </div>


      <Footer />
    </div>
  );
}
