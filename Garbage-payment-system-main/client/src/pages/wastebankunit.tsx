import Header from "@/components/Header";
import Footer from "@/components/Footer";
import WastePayment from "@/components/WastePaymentDashboard";
import { useLocation } from "wouter";
import { ArrowLeft } from "lucide-react";

export default function HomePage() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  return (
    <div className="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#ecfbf3] via-[#d4f1e1] to-[#bce5ce]">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.7),_rgba(255,255,255,0))]" />
      <div className="absolute left-[-10%] top-[-10%] h-[420px] w-[420px] rounded-full bg-emerald-400/20 blur-3xl" />
      <div className="absolute right-[-20%] top-[20%] h-[360px] w-[360px] rounded-full bg-lime-300/25 blur-3xl" />
      <div className="absolute bottom-[-20%] left-[15%] h-[320px] w-[320px] rounded-full bg-teal-300/20 blur-3xl" />

      <div className="relative z-10 flex min-h-screen flex-col">
        <Header />

        {/* ปุ่มย้อนกลับ */}
        <main className="mx-auto w-full max-w-7xl flex-1 px-4 pt-4">
          <button
            onClick={goBack}
            className="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-white/90 px-4 py-2 text-emerald-800 shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
          >
            <ArrowLeft className="h-5 w-5" />
            ย้อนกลับ
          </button>
        </main>

        <Footer />
      </div>
    </div>
  );
}
