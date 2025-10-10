// src/pages/Payment.tsx
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { useEffect, useMemo, useState } from "react";
import { useLocation } from "wouter";
import backPng from "@assets/Back-Button.png";
import paymentBanner from "@assets/PaymentBanner.png";
import bgCity from "@assets/Car StatusBody-BG.jpg";

/* ================= Helpers: auth & role ================= */

// อ่านอีเมลแบบซิงก์ (รองรับหลายแหล่ง + ?as= สำหรับทดสอบ)
function getUserEmailSync(): string | null {
  const lower = (v: any) => (typeof v === "string" ? v.toLowerCase().trim() : null);

  try {
    const qs = new URLSearchParams(window.location.search);
    const as = qs.get("as");
    if (as) {
      const e = lower(as);
      if (e) {
        sessionStorage.setItem("devUserEmail", e);
        return e;
      }
    } else {
      const dev = sessionStorage.getItem("devUserEmail");
      if (dev) return lower(dev);
    }
  } catch {}

  // @ts-ignore
  const g = window as any;
  const cands = [g?.__USER?.email, g?.CURRENT_USER_EMAIL, g?.Laravel?.user?.email, g?.__AUTH__?.user?.email];
  for (const c of cands) {
    const e = lower(c);
    if (e) return e;
  }

  const escapeRegExp = (s: string) => s.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  const readCookie = (name: string) => {
    const m = document.cookie.match(new RegExp(`(?:^|; )${escapeRegExp(name)}=([^;]*)`));
    return m ? decodeURIComponent(m[1]) : null;
  };
  for (const k of ["X-User-Email", "userEmail", "email", "auth_email"]) {
    const e = lower(readCookie(k));
    if (e) return e;
  }

  for (const k of ["user", "authUser", "auth", "currentUser", "profile"]) {
    const raw = localStorage.getItem(k) || sessionStorage.getItem(k);
    if (!raw) continue;
    try {
      const j = JSON.parse(raw);
      const e = lower(j?.email || j?.user?.email || j?.data?.email || j?.data?.user?.email || j?.profile?.email);
      if (e) return e;
    } catch {}
  }

  const meta = document.querySelector('meta[name="user:email"], meta[name="user-email"]') as HTMLMetaElement | null;
  if (meta?.content) {
    const e = lower(meta.content);
    if (e) return e;
  }

  return null;
}

async function getUserEmailAsync(): Promise<string | null> {
  const endpoints = ["/api/auth/me", "/api/user", "/api/me", "/api/profile"] as const;

  const extract = (j: any): string | null => {
    const take = (v: any) => (typeof v === "string" ? v.toLowerCase().trim() : null);
    const arr = [
      j?.email,
      j?.user?.email,
      j?.data?.email,
      j?.data?.user?.email,
      j?.profile?.email,
      j?.email_address,
      j?.mail,
      (Array.isArray(j?.emails) && j.emails[0]) || null,
    ];
    for (const v of arr) {
      const e = take(v);
      if (e && e.includes("@")) return e;
    }
    return null;
  };

  for (const url of endpoints) {
    try {
      const res = await fetch(url, { credentials: "include" });
      if (!res.ok) continue;
      const hdr = res.headers.get("x-user-email") || res.headers.get("x-auth-email");
      if (hdr && hdr.includes("@")) return hdr.toLowerCase().trim();
      const j = await res.json().catch(() => null);
      const e = j ? extract(j) : null;
      if (e) return e;
    } catch {}
  }
  return null;
}

const ADMIN_PAYMENT_EMAIL = "admin04@example.com";

/* ================= Types & mappers ================= */

type UserSummary = {
  name: string;
  status: string; // ปกติ/ค้างชำระ ฯลฯ
  type: string;   // ประเภทบ้าน/ผู้ใช้งาน ฯลฯ
  address: string;
  nextDueDate?: string;
  nextAmount?: string;
  latest?: { month?: string; title?: string; status?: string } | null;
};

function composeAddress(u: any): string {
  // รองรับทั้งเคส users.* และ profile.address.* จากฝั่ง Laravel
  const a = u?.address || u?.profile?.address || u || {};
  const parts = [
    a.houseNo || a.house_no,
    a.village && `หมู่ ${a.village}`,
    a.subdistrict && `ต.${a.subdistrict}`,
    a.district && `อ.${a.district}`,
    a.province && `จ.${a.province}`,
  ].filter(Boolean);
  return parts.length ? `ที่อยู่: ${parts.join(' ')}` : 'ที่อยู่: —';
}

// ช่วยดีบั๊ก: คืนทั้ง ok/status/url/json
type FetchRes<T> = { ok: boolean; status: number; url: string; json: T | null };

async function fetchJson<T>(url: string): Promise<FetchRes<T>> {
  try {
    const res = await fetch(url, { credentials: "include" });
    const ct = res.headers.get("content-type") || "";
    const body = ct.includes("json") ? await res.json().catch(() => null) : null;
    return { ok: res.ok, status: res.status, url, json: body as T | null };
  } catch {
    return { ok: false, status: 0, url, json: null } as FetchRes<T>;
  }
}

async function fetchUserSummary(): Promise<UserSummary | null> {
  const DEBUG = new URLSearchParams(location.search).has("debug");
  const attempts = ["/api/me", "/api/profile", "/api/user", "/api/auth/me"] as const;

  // ลองทีละ endpoint จนกว่าจะได้ JSON
  const tried: FetchRes<any>[] = [];
  let profile: any = null;
  for (const url of attempts) {
    const r = await fetchJson<any>(url);
    tried.push(r);
    if (r.ok && r.json) { profile = r.json; break; }
  }
  if (DEBUG) console.log("[Payment] profile tried:", tried);

  if (!profile) return null;

  // ตัวช่วยหยิบค่าแรกที่ "มีจริง"
  const pick = (...v: any[]) => {
    for (const x of v) {
      if (x === undefined || x === null) continue;
      if (typeof x === "string" && x.trim() === "") continue;
      return x;
    }
    return undefined;
  };

  // โหนดที่ “น่าจะ” เป็น user จริง (รองรับหลากหลายโครงสร้าง)
  const source =
    profile?.user ??
    profile?.data?.user ??
    (Array.isArray(profile?.rows) ? profile.rows[0] : undefined) ??
    (Array.isArray(profile?.data?.rows) ? profile.data.rows[0] : undefined) ??
    profile;

  // ---- NAME ----
  const name =
    pick(
      source?.name,
      source?.full_name,
      source?.fullname,
      source?.display_name,
      [source?.first_name, source?.last_name].filter(Boolean).join(" ") || undefined,
      profile?.profile?.name
    ) || "—";

  // ---- STATUS / TYPE ----
  const status = pick(source?.status, profile?.status, source?.account_status, "ปกติ");
  const type   = pick(source?.type,   profile?.type,   source?.account_type,   "ปกติ");

  // ---- ADDRESS (รองรับ flat / object และคีย์ยอดนิยม) ----
  const addrFlat = {
    houseNo    : pick(source?.house_no, source?.houseNo, source?.address_line, source?.addr_no),
    village    : pick(source?.village, source?.moo, source?.village_no),
    subdistrict: pick(source?.subdistrict, source?.tambon),
    district   : pick(source?.district, source?.amphoe),
    province   : pick(source?.province),
  };

  const addrObj =
    profile?.address ||
    profile?.profile?.address ||
    addrFlat;

  const parts = [
    addrObj?.houseNo,
    addrObj?.village && `หมู่ ${addrObj.village}`,
    addrObj?.subdistrict && `ต.${addrObj.subdistrict}`,
    addrObj?.district && `อ.${addrObj.district}`,
    addrObj?.province && `จ.${addrObj.province}`,
  ].filter(Boolean);

  const address = parts.length ? `ที่อยู่: ${parts.join(" ")}` : "ที่อยู่: —";

  // (อาจมี/อาจไม่มี) API สรุปการชำระ
  const sum  = await fetchJson<any>("/api/payments/summary");
  const last = await fetchJson<any>("/api/payments/latest");
  if (DEBUG) {
    console.log("[Payment] summary:", sum);
    console.log("[Payment] latest:", last);
  }

  return {
    name,
    status,
    type,
    address,
    nextDueDate: sum.ok ? (sum.json?.next_due_date || sum.json?.nextDueDate) : undefined,
    nextAmount: sum.ok ? (sum.json?.next_amount || sum.json?.nextAmount) : undefined,
    latest: last.ok && last.json
      ? { month: last.json?.month || last.json?.period,
          title: last.json?.title || last.json?.item,
          status: last.json?.status || last.json?.state }
      : null,
  };
}

/* ================= Main Page ================= */

export default function PaymentPage() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  const [email, setEmail] = useState<string | null>(getUserEmailSync());
  const isAuthed = !!email;
  const isAdmin = email === ADMIN_PAYMENT_EMAIL;

  useEffect(() => {
    if (email) return;
    let alive = true;
    (async () => {
      const e = await getUserEmailAsync();
      if (!alive) return;
      if (e) setEmail(e);
    })();
    return () => { alive = false; };
  }, [email]);

  const goLogin = () => {
    const next = window.location.pathname + window.location.search + window.location.hash;
    nav(`/login?next=${encodeURIComponent(next)}`);
  };

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

      <div className="w-full max-w-7xl mx-auto px-3 md:px-6 mt-3 md:mt-6">
        <button
          onClick={goBack}
          className="inline-block rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400"
          aria-label="ย้อนกลับ"
        >
          <img src={backPng} alt="ย้อนกลับ" className="h-12 sm:h-14 md:h-16 lg:h-20 xl:h-14 w-auto select-none pointer-events-none" />
        </button>
      </div>

      <main className="w-full max-w-7xl mx-auto flex-1 px-3 md:px-6 pb-10">
        <div className="grid grid-cols-1 gap-6 md:gap-8 lg:grid-cols-[minmax(300px,420px),1fr] items-start">
          <section className="pt-2">
            <img src={paymentBanner} alt="การชำระเงิน" className="w-[72%] md:w-[68%] lg:w-[100%] xl:w-[100%] h-auto object-contain" />
          </section>

          <section className="rounded-[24px] bg-white/90 backdrop-blur shadow-xl p-4 md:p-6">
            {!isAuthed ? (
              <LoginBlock onLogin={goLogin} />
            ) : isAdmin ? (
              <AdminPanel />
            ) : (
              <UserPanel />
            )}
          </section>
        </div>
      </main>

      <Footer />
    </div>
  );
}

/* ================= Blocks ================= */

function LoginBlock({ onLogin }: { onLogin: () => void }) {
  return (
    <div className="text-center text-emerald-900">
      <h2 className="text-xl md:text-2xl font-semibold">กรุณาเข้าสู่ระบบ</h2>
      <p className="mt-2">ต้องล็อกอินก่อนจึงจะสามารถดูและชำระเงินได้</p>
      <button onClick={onLogin} className="mt-4 inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 font-medium text-white hover:bg-emerald-800">
        ไปหน้าเข้าสู่ระบบ
      </button>
    </div>
  );
}

/* ---------- แผงสำหรับผู้ใช้ทั่วไป (ข้อมูลจริง) ---------- */
function UserPanel() {
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState<UserSummary | null>(null);

  const [slipFile, setSlipFile] = useState<File | null>(null);
  const [note, setNote] = useState("");

  useEffect(() => {
    let alive = true;
    (async () => {
      const d = await fetchUserSummary();
      if (!alive) return;
      setData(d);
      setLoading(false);
    })();
    return () => { alive = false; };
  }, []);

  const submitPayment = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!slipFile) return alert("กรุณาแนบสลิปการชำระเงิน");

    const fd = new FormData();
    fd.append("slip", slipFile);
    if (note) fd.append("note", note);

    try {
      const res = await fetch("/api/payments/upload-slip", { method: "POST", body: fd, credentials: "include" });
      if (!res.ok) throw new Error();
      alert("อัปโหลดสลิปเรียบร้อย");
      setSlipFile(null);
      setNote("");
    } catch {
      alert("อัปโหลดสลิปไม่สำเร็จ");
    }
  };

  // แสดงผล
  if (loading) return <div className="text-emerald-900">กำลังโหลดข้อมูล...</div>;
  if (!data) return <div className="text-red-700">ไม่พบข้อมูลผู้ใช้</div>;

  return (
    <div>
      {/* สรุปสถานะ */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div className="rounded-xl bg-[#efefef] px-3 py-2 text-gray-700">{data.name}</div>
        <div className="rounded-xl bg-[#efefef] px-3 py-2 text-gray-700">สถานะ: {data.status || '—'}</div>
        <div className="rounded-xl bg-[#efefef] px-3 py-2 text-gray-700">ประเภท: {data.type || '—'}</div>
      </div>

      <div className="mt-3">
        <div className="rounded-2xl bg-[#efefef] px-3 py-3 text-gray-700 min-h-[110px]">{data.address}</div>
      </div>

      {/* กำหนดชำระถัดไป / ล่าสุด */}
      <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        <div className="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
          <div className="font-semibold text-emerald-900">กำหนดชำระรอบถัดไป</div>
          <div className="mt-1 text-emerald-800">ชำระก่อนวันที่: <b>{data.nextDueDate || '—'}</b></div>
          <div className="mt-1 text-emerald-800">จำนวนเงินโดยประมาณ: <b>{data.nextAmount ? `${data.nextAmount} บาท` : '—'}</b></div>
        </div>

        <div className="rounded-xl border border-emerald-200 bg-white px-4 py-3">
          <div className="font-semibold text-emerald-900">ประวัติการชำระล่าสุด</div>
          <div className="mt-1 text-emerald-800">
            เดือน: {data.latest?.month || '—'} | รายการ: {data.latest?.title || '—'} | สถานะ: {data.latest?.status || '—'}
          </div>
        </div>
      </div>

      {/* ตารางประวัติ (เชื่อมต่อจริงค่อยเติม) */}
      <div className="mt-4">
        <span className="inline-block rounded-full bg-emerald-700 text-white px-4 py-2 text-sm font-semibold shadow">
          ประวัติการชำระ:
        </span>
      </div>
      <div className="mt-3 overflow-hidden rounded-xl border border-gray-200">
        <table className="w-full text-sm">
          <thead className="bg-white">
            <tr className="text-gray-700">
              <th className="px-4 py-2 text-left font-semibold border-b">เดือน</th>
              <th className="px-4 py-2 text-left font-semibold border-b">รายการ</th>
              <th className="px-4 py-2 text-left font-semibold border-b">สถานะ</th>
              <th className="px-4 py-2 text-left font-semibold border-b">ใบเสร็จ</th>
              <th className="px-4 py-2 text-left font-semibold border-b">ชำระ</th>
            </tr>
          </thead>
          <tbody className="bg-white/70">
            <tr>
              <td className="px-4 py-3 border-b text-gray-600">—</td>
              <td className="px-4 py-3 border-b text-gray-600">—</td>
              <td className="px-4 py-3 border-b text-gray-600">—</td>
              <td className="px-4 py-3 border-b text-gray-600">—</td>
              <td className="px-4 py-3 border-b text-gray-600">—</td>
            </tr>
          </tbody>
        </table>
      </div>

      {/* ฟอร์มชำระเงิน (แนบสลิป) */}
      <form onSubmit={submitPayment} className="mt-6 rounded-2xl border border-emerald-200 bg-white p-4 space-y-3">
        <div className="text-emerald-900 font-semibold text-lg">ชำระเงิน / แนบสลิป</div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <label className="block text-sm text-emerald-900">ไฟล์สลิป <span className="text-red-600">*</span></label>
            <input
              required
              type="file"
              accept="image/*,application/pdf"
              onChange={(e) => setSlipFile(e.target.files?.[0] ?? null)}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
            />
          </div>
          <div>
            <label className="block text-sm text-emerald-900">หมายเหตุ</label>
            <input
              type="text"
              value={note}
              onChange={(e) => setNote(e.target.value)}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
              placeholder="เช่น โอนผ่านแอปธนาคาร..."
            />
          </div>
        </div>
        <div className="pt-2">
          <button className="inline-flex items-center justify-center rounded-xl bg-[#c71b30] px-4 py-2.5 text-white hover:bg-[#b01a2a]">
            ส่งสลิปชำระเงิน
          </button>
        </div>
      </form>
    </div>
  );
}

/* ---------- แผงสำหรับแอดมิน (admin04@example.com) ---------- */
function AdminPanel() {
  const [form, setForm] = useState({
    userEmail: "",
    period: "",
    amount: "",
    status: "รอตรวจสอบ" as "รอตรวจสอบ" | "ชำระแล้ว" | "ค้างชำระ",
    note: "",
    slip: null as File | null,
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: call backend ส่งข้อมูลแจ้งการชำระให้ user
    alert("บันทึกแจ้งการชำระ (เดโม)");
    setForm({ userEmail: "", period: "", amount: "", status: "รอตรวจสอบ", note: "", slip: null });
  };

  return (
    <div className="space-y-6">
      <div className="text-emerald-900">
        <h2 className="text-xl md:text-2xl font-bold">แอดมิน: บันทึก/ส่งข้อมูลการชำระเงินให้ผู้ใช้</h2>
        <p className="text-emerald-800/80 mt-1">สำหรับ admin04@example.com เท่านั้น</p>
      </div>

      <form onSubmit={submit} className="rounded-2xl border border-emerald-200 bg-white p-4 space-y-3">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label className="block text-sm text-emerald-900">อีเมลผู้ใช้ <span className="text-red-600">*</span></label>
            <input
              required
              type="email"
              value={form.userEmail}
              onChange={(e) => setForm((f) => ({ ...f, userEmail: e.target.value }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
              placeholder="user@example.com"
            />
          </div>

          <div>
            <label className="block text-sm text-emerald-900">งวด/เดือน <span className="text-red-600">*</span></label>
            <input
              required
              type="month"
              value={form.period}
              onChange={(e) => setForm((f) => ({ ...f, period: e.target.value }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
            />
          </div>

          <div>
            <label className="block text-sm text-emerald-900">จำนวนเงิน (บาท) <span className="text-red-600">*</span></label>
            <input
              required
              type="number"
              min="0"
              step="0.01"
              value={form.amount}
              onChange={(e) => setForm((f) => ({ ...f, amount: e.target.value }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
              placeholder="เช่น 120.00"
            />
          </div>

          <div>
            <label className="block text-sm text-emerald-900">สถานะ</label>
            <select
              value={form.status}
              onChange={(e) => setForm((f) => ({ ...f, status: e.target.value as any }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
            >
              <option>รอตรวจสอบ</option>
              <option>ชำระแล้ว</option>
              <option>ค้างชำระ</option>
            </select>
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm text-emerald-900">หมายเหตุ</label>
            <input
              type="text"
              value={form.note}
              onChange={(e) => setForm((f) => ({ ...f, note: e.target.value }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
              placeholder="บันทึกเพิ่มเติม"
            />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm text-emerald-900">แนบสลิป (ถ้ามี)</label>
            <input
              type="file"
              accept="image/*,application/pdf"
              onChange={(e) => setForm((f) => ({ ...f, slip: e.target.files?.[0] ?? null }))}
              className="mt-1 block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2"
            />
          </div>
        </div>

        <div className="pt-2">
          <button className="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-white hover:bg-emerald-800">
            บันทึก/ส่งแจ้งการชำระ
          </button>
        </div>
      </form>

      <div>
        <div className="text-emerald-900 font-semibold mb-2">รายการล่าสุด</div>
        <div className="overflow-hidden rounded-xl border border-gray-200">
          <table className="w-full text-sm">
            <thead className="bg-white">
              <tr className="text-gray-700">
                <th className="px-4 py-2 text-left font-semibold border-b">ผู้ใช้</th>
                <th className="px-4 py-2 text-left font-semibold border-b">งวด</th>
                <th className="px-4 py-2 text-left font-semibold border-b">จำนวน</th>
                <th className="px-4 py-2 text-left font-semibold border-b">สถานะ</th>
                <th className="px-4 py-2 text-left font-semibold border-b">แนบสลิป</th>
              </tr>
            </thead>
            <tbody className="bg-white/70">
              <tr>
                <td className="px-4 py-3 border-b text-gray-600">—</td>
                <td className="px-4 py-3 border-b text-gray-600">—</td>
                <td className="px-4 py-3 border-b text-gray-600">—</td>
                <td className="px-4 py-3 border-b text-gray-600">—</td>
                <td className="px-4 py-3 border-b text-gray-600">—</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
