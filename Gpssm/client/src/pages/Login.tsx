import { useState } from "react";
import { useAuth } from "@/hooks/useAuth";
import { Link, useLocation } from "wouter";
import { Eye, EyeOff, ArrowLeft } from "lucide-react";

// === ปรับ path รูปให้ตรงกับโปรเจกต์คุณ ===
// แนะนำ: วางไฟล์ 3 รูปนี้ไว้ที่ src/assets/login/ หรือ public/
import bgImage from "@assets/BGLoginRegister.png";
import logoLogin from "@assets/Logoเข้าสู่ระบบ.png";
import btnLoginImg from "@assets/เข้าสู่ระบบButton.png";

export default function LoginPage() {
  const { login } = useAuth();
  const [, nav] = useLocation();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPw, setShowPw] = useState(false);
  const [err, setErr] = useState<string | null>(null);
  const [pending, setPending] = useState(false);

  function goBack() {
    if (window.history.length > 1) window.history.back();
    else nav("/");
  }

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setErr(null);
    setPending(true);
    try {
      await login(email.trim(), password);
      nav("/");
    } catch (e: any) {
      setErr(e?.message ?? "เข้าสู่ระบบไม่สำเร็จ");
    } finally {
      setPending(false);
    }
  }

  return (
    <div
      className="relative min-h-[100dvh] flex items-center justify-center px-4 py-8"
      style={{
        backgroundImage: `url(${bgImage})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
      }}
    >
      {/* overlay ไล่แสงให้ใกล้เคียงภาพตัวอย่าง */}
      <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_60%_at_30%_20%,rgba(255,255,255,0.10),rgba(0,0,0,0)_60%),radial-gradient(40%_40%_at_85%_75%,rgba(167,64,229,0.15),rgba(0,0,0,0)_60%)]" />

      {/* ปุ่มย้อนกลับ (เก็บไว้เผื่อใช้งาน) */}
      <button
        type="button"
        onClick={goBack}
        className="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-xl border border-white/30 bg-white/20 px-3 py-1.5 text-white shadow-sm backdrop-blur-md hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/40"
        aria-label="ย้อนกลับ"
        title="ย้อนกลับ"
      >
        <ArrowLeft className="h-4 w-4" />
        <span className="text-sm">ย้อนกลับ</span>
      </button>

      <div className="w-full max-w-[420px]">
        {/* กล่องแบบ glassmorphism */}
        <div className="relative rounded-[28px] bg-white/14 p-6 md:p-8 shadow-[0_20px_70px_-20px_rgba(0,0,0,0.6)] ring-1 ring-white/25 backdrop-blur-xl">
          {/* เส้นแสงขอบซ้าย-ขวาเล็กน้อยให้เหมือนภาพ */}
          <div className="pointer-events-none absolute -left-[1px] top-1/3 h-12 w-[2px] -translate-y-1/2 rounded-full bg-white/70 blur-[2px]" />
          <div className="pointer-events-none absolute -right-[1px] top-2/3 h-12 w-[2px] -translate-y-1/2 rounded-full bg-white/70 blur-[2px]" />

          {/* โลโก้เหนือหัวข้อ */}
          <div className="flex flex-col items-center text-center">
          <img
          src={logoLogin}
          alt="เข้าสู่ระบบ"
          className="h-44 md:h-46 w-auto drop-shadow-[0_6px_20px_rgba(255,255,255,0.35)]"
        />

          </div>

          {err && (
            <div
              role="alert"
              aria-live="assertive"
              className="mt-5 rounded-lg border border-red-300/60 bg-red-600/15 px-3 py-2 text-sm text-red-100"
            >
              {err}
            </div>
          )}

          <form onSubmit={submit} className="mt-6 space-y-4">
            <div>
              <label htmlFor="email" className="mb-1 block text-sm font-medium text-white/90">
                ชื่อผู้ใช้งาน
              </label>
              <div className="relative">
                <input
                  id="email"
                  type="email"
                  inputMode="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  aria-invalid={!!err}
                  className="w-full rounded-2xl border border-white/30 bg-white/80 px-3 py-3 pr-10 text-slate-900 outline-none placeholder:text-slate-500 focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="you@example.com"
                  autoComplete="email"
                />
         
              </div>
            </div>

            <div>
              <label htmlFor="password" className="mb-1 block text-sm font-medium text-white/90">
                รหัสผ่าน
              </label>
              <div className="relative">
                <input
                  id="password"
                  type={showPw ? "text" : "password"}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  aria-invalid={!!err}
                  className="w-full rounded-2xl border border-white/30 bg-white/80 px-3 py-3 pr-12 text-slate-900 outline-none placeholder:text-slate-500 focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="••••••••"
                  autoComplete="current-password"
                />
                <button
                  type="button"
                  className="absolute inset-y-0 right-2 my-auto rounded-md p-2 hover:bg-white/60 focus:outline-none focus:ring-2 focus:ring-white/40"
                  aria-label={showPw ? "ซ่อนรหัสผ่าน" : "แสดงรหัสผ่าน"}
                  onClick={() => setShowPw((s) => !s)}
                  title={showPw ? "ซ่อนรหัสผ่าน" : "แสดงรหัสผ่าน"}
                >
                  {showPw ? (
                    <EyeOff className="h-5 w-5 text-slate-700" />
                  ) : (
                    <Eye className="h-5 w-5 text-slate-700" />
                  )}
                </button>
              </div>
              {/* ลิงก์ลืมรหัสผ่าน (ให้หน้าตาเหมือนในภาพ—วางมุมขวา) */}
              <div className="mt-1 text-right">
                <a className="text-xs text-white/85 hover:underline" href="#">
                  ลืมรหัสผ่าน
                </a>
              </div>
            </div>

            {/* ปุ่มแบบใช้ภาพ */}
            <button
              disabled={pending}
              className="mt-1 inline-flex w-full items-center justify-center rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/50 disabled:opacity-60"
              type="submit"
            >
              <img
                src={btnLoginImg}
                alt={pending ? "กำลังเข้าสู่ระบบ..." : "เข้าสู่ระบบ"}
                className="h-11 md:h-12 w-auto"
              />
              <span className="sr-only">{pending ? "กำลังเข้าสู่ระบบ..." : "เข้าสู่ระบบ"}</span>
            </button>
          </form>

          <div className="mt-5 text-center text-sm text-white/90">
            ยังไม่มีบัญชี?{" "}
            <Link href="/register" className="font-semibold underline underline-offset-2">
              สมัครสมาชิก
            </Link>
          </div>
        </div>

        <p className="mt-4 text-center text-xs text-white/85 drop-shadow">
          มีปัญหาเข้าระบบ? กรุณาลองรีเฟรชหน้า หรือแจ้งผู้ดูแลระบบ
        </p>
      </div>
    </div>
  );
}
