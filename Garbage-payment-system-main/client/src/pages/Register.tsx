import { useState, useRef } from "react";
import { useAuth } from "@/hooks/useAuth";
import { Link, useLocation } from "wouter";
import { Eye, EyeOff, ArrowLeft } from "lucide-react";

// ปรับ path รูปให้ตรงโปรเจกต์คุณ
import bgImage from "@assets/BGLoginRegister.png";
import logoRegister from "@assets/LogoRegister.png";
import btnRegisterImg from "@assets/RegisterButton.png";

export default function RegisterPage() {
  const { register } = useAuth();
  const [, nav] = useLocation();

  const [email, setEmail] = useState("");
  const [fullName, setFullName] = useState("");
  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [showPw, setShowPw] = useState(false);
  const [showPw2, setShowPw2] = useState(false);

  // ฟิลด์ตกแต่งให้เหมือนภาพ (ยังไม่ส่งหลังบ้าน)
  const [title, setTitle] = useState("");
  const [age, setAge] = useState("");
  const [phone, setPhone] = useState("");
  const [address, setAddress] = useState("");
  const [prov, setProv] = useState("เลย");
  const [district, setDistrict] = useState("เมือง");
  const [amphoe, setAmphoe] = useState("เมือง");

  const [err, setErr] = useState<string | null>(null);
  const [pending, setPending] = useState(false);

  const nameRef = useRef<HTMLInputElement>(null);

  function goBack() {
    if (history.length > 1) history.back();
    else nav("/");
  }

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setErr(null);

    if (!fullName.trim()) {
      setErr("กรุณากรอกชื่อ-นามสกุล");
      nameRef.current?.focus();
      return;
    }
    if (password.length < 6) {
      setErr("รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร");
      return;
    }
    if (password !== confirm) {
      setErr("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน");
      return;
    }

    setPending(true);
    try {
      await register(email.trim(), password, fullName.trim());
      nav("/");
    } catch (e: any) {
      setErr(e?.message ?? "สมัครสมาชิกไม่สำเร็จ");
    } finally {
      setPending(false);
    }
  }

  return (
    <div
        className="relative min-h-[100dvh] flex items-center justify-center px-4 pt-[88px] pb-10 md:py-10"
        style={{
          backgroundImage: `url(${bgImage})`,
          backgroundSize: "cover",
          backgroundRepeat: "no-repeat",
          backgroundPosition: "center",
          // กัน notch / safe area บน iOS
          paddingTop: "max(88px, env(safe-area-inset-top))",
        }}
      >
      {/* Glow overlays ใกล้เคียง mockup */}
      <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_60%_at_30%_15%,rgba(255,255,255,0.10),rgba(0,0,0,0)_60%),radial-gradient(45%_45%_at_85%_75%,rgba(167,64,229,0.20),rgba(0,0,0,0)_60%)]" />

      {/* ปุ่มย้อนกลับ */}
      <button
        type="button"
        onClick={goBack}
        className="absolute left-3 top-3 z-50 inline-flex items-center gap-1.5 rounded-xl border border-white/30 bg-white/20 px-3 py-1.5 text-white shadow-sm backdrop-blur-md hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/40"
      >
        <ArrowLeft className="h-4 w-4" />
        <span className="text-sm">ย้อนกลับ</span>
      </button>

      <div className="w-full max-w-4xl">
        <div className="relative mx-auto rounded-[28px] bg-white/14 p-5 md:p-8 shadow-[0_20px_70px_-20px_rgba(0,0,0,0.6)] ring-1 ring-white/25 backdrop-blur-xl">
          {/* side glow */}
          <div className="pointer-events-none absolute -left-[1px] top-1/3 h-12 w-[2px] -translate-y-1/2 rounded-full bg-white/70 blur-[2px]" />
          <div className="pointer-events-none absolute -right-[1px] top-2/3 h-12 w-[2px] -translate-y-1/2 rounded-full bg-white/70 blur-[2px]" />

          {/* Header โลโก้ + ข้อความ “ลงทะเบียน” */}
          <div className="mb-4 flex flex-col items-center text-center">
            <img
              src={logoRegister}
              alt="ลงทะเบียน"
              className="h-24 md:h-28 w-auto drop-shadow-[0_6px_20px_rgba(255,255,255,0.35)]"
            />
            <h1 className="mt-1 text-[28px] md:text-[34px] font-extrabold leading-tight text-white drop-shadow">
              ลงทะเบียน
            </h1>
          </div>

          {err && (
            <div className="mb-4 rounded-lg border border-red-300/60 bg-red-600/15 px-3 py-2 text-sm text-red-100">
              {err}
            </div>
          )}

          <form onSubmit={submit} className="space-y-4">
            {/* แถว 1: อีเมล */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">
                  อีเมล <span className="text-red-300">*</span>
                </label>
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="อีเมล"
                  autoComplete="email"
                />
              </div>

              {/* รหัสผ่าน/ยืนยันรหัสผ่าน */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="mb-1 block text-sm font-medium text-white/90">
                    รหัสผ่าน <span className="text-red-300">*</span>
                  </label>
                  <div className="relative">
                    <input
                      type={showPw ? "text" : "password"}
                      required
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                      className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 pr-11 text-slate-900 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                      placeholder="รหัสผ่าน (ต้องไม่ต่ำกว่า 6 ตัว)"
                      autoComplete="new-password"
                    />
                    <button
                      type="button"
                      onClick={() => setShowPw((s) => !s)}
                      className="absolute inset-y-0 right-2 my-auto rounded-md p-2 hover:bg-white/60 focus:outline-none focus:ring-2 focus:ring-white/40"
                      aria-label={showPw ? "ซ่อนรหัสผ่าน" : "แสดงรหัสผ่าน"}
                    >
                      {showPw ? <EyeOff className="h-5 w-5 text-slate-700" /> : <Eye className="h-5 w-5 text-slate-700" />}
                    </button>
                  </div>
                </div>

                <div>
                  <label className="mb-1 block text-sm font-medium text-white/90">
                    ยืนยันรหัสผ่าน <span className="text-red-300">*</span>
                  </label>
                  <div className="relative">
                    <input
                      type={showPw2 ? "text" : "password"}
                      required
                      value={confirm}
                      onChange={(e) => setConfirm(e.target.value)}
                      className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 pr-11 text-slate-900 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                      placeholder="ยืนยันรหัสผ่าน"
                      autoComplete="new-password"
                    />
                    <button
                      type="button"
                      onClick={() => setShowPw2((s) => !s)}
                      className="absolute inset-y-0 right-2 my-auto rounded-md p-2 hover:bg-white/60 focus:outline-none focus:ring-2 focus:ring-white/40"
                      aria-label={showPw2 ? "ซ่อนรหัสผ่าน" : "แสดงรหัสผ่าน"}
                    >
                      {showPw2 ? <EyeOff className="h-5 w-5 text-slate-700" /> : <Eye className="h-5 w-5 text-slate-700" />}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            {/* แถว 2: คำนำหน้า + ชื่อ-นามสกุล */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">
                  คำนำหน้าชื่อ <span className="text-red-300">*</span>
                </label>
                <select
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  className="w-full appearance-none rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                >
                  <option value="">เลือกคำนำหน้า</option>
                  <option>นาย</option>
                  <option>นาง</option>
                  <option>นางสาว</option>
                </select>
              </div>

              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">
                  ชื่อ-นามสกุล <span className="text-red-300">*</span>
                </label>
                <input
                  ref={nameRef}
                  type="text"
                  required
                  value={fullName}
                  onChange={(e) => setFullName(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 placeholder:text-slate-500 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="ชื่อ-นามสกุล"
                  autoComplete="name"
                />
              </div>
            </div>

            {/* แถว 3: อายุ + โทรศัพท์ */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">
                  อายุ
                </label>
                <input
                  type="number"
                  value={age}
                  onChange={(e) => setAge(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="อายุ"
                />
              </div>

              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">
                  เบอร์โทรศัพท์
                </label>
                <input
                  type="tel"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                  placeholder="เบอร์โทรศัพท์"
                />
              </div>
            </div>

            {/* แถว 4: ที่อยู่ (textarea) */}
            <div>
              <label className="mb-1 block text-sm font-medium text-white/90">
                ที่อยู่ <span className="text-red-300">*</span>
              </label>
              <textarea
                value={address}
                onChange={(e) => setAddress(e.target.value)}
                rows={3}
                className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                placeholder="ที่อยู่"
              />
            </div>

            {/* แถว 5: จังหวัด/ตำบล/อำเภอ */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">จังหวัด</label>
                <input
                  value={prov}
                  onChange={(e) => setProv(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                />
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">ตำบล</label>
                <input
                  value={district}
                  onChange={(e) => setDistrict(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                />
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium text-white/90">อำเภอ</label>
                <input
                  value={amphoe}
                  onChange={(e) => setAmphoe(e.target.value)}
                  className="w-full rounded-2xl border border-white/30 bg-white/85 px-3 py-3 text-slate-900 outline-none focus:bg-white focus:ring-2 focus:ring-white/60"
                />
              </div>
            </div>

            {/* ปุ่มแบบรูปภาพ */}
            <button
              disabled={pending}
              className="mt-1 inline-flex w-full items-center justify-center rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/50 disabled:opacity-60"
              type="submit"
            >
              <img
                src={btnRegisterImg}
                alt={pending ? "กำลังลงทะเบียน..." : "ลงทะเบียน"}
                className="h-11 md:h-12 w-15 max-w-xs md:max-w-none"
              />
              <span className="sr-only">{pending ? "กำลังลงทะเบียน..." : "ลงทะเบียน"}</span>
            </button>
          </form>

          <div className="mt-3 text-center text-sm text-white/90">
            มีบัญชีแล้ว?{" "}
            <Link href="/login" className="font-semibold underline underline-offset-2 text-red-300">
              เข้าสู่ระบบ
            </Link>
          </div>
        </div>

        <p className="mt-4 text-center text-xs text-white/85 drop-shadow">
          *ช่องที่มีเครื่องหมาย <span className="text-red-300">*</span> จำเป็นต้องกรอก
        </p>
      </div>
    </div>
  );
}
