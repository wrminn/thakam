import { useMemo, useState, Suspense, lazy, useEffect } from "react";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import backPng from "@assets/Back-Button.png";
import bodyBg from "@assets/Body-BG2.jpg";

// ไอคอนหัวข้อ (ใช้ของเดิมในโปรเจกต์คุณ)
import secGov from "@assets/formsIcon-2.png";
import secWorks from "@assets/formsIcon-1.png";
import secFin from "@assets/icon-7.png";
import secHealth from "@assets/formsIcon-3.png";
import secEdu from "@assets/formsIcon-4.png";
import secTcc from "@assets/icon-8.png";

import { useLocation } from "wouter";
import { api } from "@/lib/api"; // ← ใช้ดึงข้อมูลเหมือน Dashboard

// ===== ฟอร์มที่โหลดจริงตอนนี้ =====
const formLoaders: Record<string, () => Promise<{ default: React.ComponentType<any> }>> = {
  "general-request": () => import("@/pages/forms/GeneralRequestForm"),
  "service-request": () => import("@/pages/forms/TrashBinRequestForm"),
};

// ---------- เมนูฟอร์ม ----------
type NavLeaf = { label: string; slug: string };
type NavSection = { title: string; items: NavLeaf[] };

const NAV: NavSection[] = [
  {
    title: "สำนักปลัด",
    items: [
      { label: "คำร้องทั่วไป", slug: "general-request" },
      { label: "คำร้องขอติดตั้งป้ายโฆษณาริมถนนสาธารณะ", slug: "roadside-ads-sign-install" },
      { label: "คำร้องเรียนการทุจริตและประพฤติมิชอบของเจ้าหน้าที่", slug: "official-misconduct-complaint" },
      { label: "คำขอเครื่องหมายรับรองผู้ประกอบธุรกิจพาณิชย์อิเล็กทรอนิกส์ (DBD Registered)", slug: "dbd-registered" },
      { label: "คำขอจดทะเบียนพาณิชย์ (ใหม่/เปลี่ยนแปลง/ยกเลิก)", slug: "commercial-registration" },
      { label: "คำร้องทะเบียนพาณิชย์", slug: "commercial-reg-request" },
      { label: "คําขอตรวจค้นเอกสาร/รับรองสําเนาเอกสาร/ใบแทน", slug: "document-search-certification" },
      { label: "หนังสือมอบอำนาจ", slug: "power-of-attorney" },
    ],
  },
  {
    title: "กองยุทธศาสตร์และงบประมาณ",
    items: [{ label: "คำร้องขอข้อมูลข่าวสาร", slug: "info-disclosure-request" }],
  },
  {
    title: "กองศึกษา",
    items: [
      { label: "ใบสมัครเรียน  ศพด.บ้านท่าข้าม", slug: "prek-thakham" },
      { label: "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดบางแสม", slug: "prek-bangsaem" },
      { label: "ใบสมัครเรียน ศพด.บ้านท่าข้าม วัดคลองพานทอง", slug: "prek-khlong-phanthong" },
    ],
  },
  {
    title: "กองสาธารณสุขฯ",
    items: [{ label: "คำร้องขอถังขยะ", slug: "service-request" }],
  },
  {
    title: "กองช่าง",
    items: [
      { label: "คำร้องทั่วไป (ซ่อมไฟฟ้าสาธารณะ , ซ่อมแซมถนน)", slug: "public-works-general" },
      { label: "ใบแจ้งการขุดดินหรือถมดิน", slug: "excavation-landfill-notice" },
    ],
  },
  {
    title: "กองสวัสดิการสังคม",
    items: [{ label: "คำร้องทั่วไปขอรับการช่วยเหลือ", slug: "welfare-assistance-general" }],
  },
];

// ---------- ROLES: อีเมล -> หัวข้อที่มีสิทธิ์เห็น ----------
const ADMIN_SECTION_MAP: Record<string, string> = {
  "admin01@example.com": "สำนักปลัด",
  "admin02@example.com": "กองยุทธศาสตร์และงบประมาณ",
  "admin03@example.com": "กองศึกษา",
  "admin04@example.com": "กองสาธารณสุขฯ",
  "admin05@example.com": "กองช่าง",
  "admin06@example.com": "กองสวัสดิการสังคม",
};

// ----- ตัวช่วยอ่านอีเมลผู้ใช้ (ครอบจักรวาล + dev override ?as=...)
function getCurrentUserEmailSync(): string | null {
  const lower = (v: any) => (typeof v === "string" ? v.toLowerCase().trim() : null);

  // DEV override: ?as=admin01@example.com  (เก็บแค่ใน sessionStorage)
  try {
    const qs = new URLSearchParams(window.location.search);
    const asParam = qs.get("as");
    if (asParam) {
      const e = lower(asParam);
      if (e) {
        sessionStorage.setItem("devUserEmail", e);
        return e;
      }
    } else {
      sessionStorage.removeItem("devUserEmail");
    }
  } catch {}

  const dev = lower(sessionStorage.getItem("devUserEmail"));
  if (dev) return dev;

  // @ts-ignore
  const g = window as any;
  const candidates = [
    g?.__USER?.email,
    g?.CURRENT_USER_EMAIL,
    g?.Laravel?.user?.email,
    g?.__AUTH__?.user?.email,
  ];
  for (const c of candidates) {
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
      const e = lower(
        j?.email || j?.user?.email || j?.data?.email || j?.data?.user?.email || j?.profile?.email
      );
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

async function getCurrentUserEmailAsync(): Promise<string | null> {
  const endpoints = ["/api/auth/me", "/api/user", "/api/me", "/user", "/api/profile", "/profile", "/auth/user"] as const;

  const extractEmail = (j: any): string | null => {
    const take = (v: any) => (typeof v === "string" ? v.toLowerCase().trim() : null);
    const cands = [
      j?.email,
      j?.user?.email,
      j?.data?.email,
      j?.data?.user?.email,
      j?.profile?.email,
      j?.email_address,
      j?.mail,
      j?.username,
      j?.login,
      j?.userPrincipalName,
      Array.isArray(j?.emails) ? j.emails[0] : null,
      Array.isArray(j?.user?.emails) ? j.user.emails[0] : null,
      Array.isArray(j?.data?.emails) ? j.data.emails[0] : null,
    ];
    for (const v of cands) {
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
      const e = j ? extractEmail(j) : null;
      if (e) return e;
    } catch {}
  }
  return null;
}

/* ---------------- Types ---------------- */
type FormRow = {
  id: number;
  date: string | null;
  subject: string | null;
  prefix: string | null;
  fullName: string | null;
  age: number | string | null;
  phone: string | null;
  email: string | null;
  houseNo: string | null;
  moo: string | null;
  road: string | null;
  subdistrict: string | null;
  district: string | null;
  province: string | null;
  postcode: string | null;
  placeType: string | null;
  placeTypeOther: string | null;
  detail: string | null;
  lat: number | null;
  lng: number | null;
  attachments: { filename: string; url: string; mimetype?: string; size?: number }[] | null;
  consent: boolean | null;
  createdAt: string | null;
};
type Mode = "all" | "today" | "thisMonth";

/* ---------- HistoryPane: แสดงประวัติของ "ฟอร์มที่เลือก" ---------- */
function HistoryPane({ slug, label }: { slug: string; label: string }) {
  const [rows, setRows] = useState<FormRow[]>([]);
  const [busy, setBusy] = useState(false);
  const [err, setErr] = useState<string | null>(null);
  const [mode, setMode] = useState<Mode>("all");
  const [query, setQuery] = useState("");
  const ENDPOINT = "/general-requests"; 
  const [showAll, setShowAll] = useState(false);     
  const [fallbackUsed, setFallbackUsed] = useState(false); 
  // โหลดข้อมูลและกรองตามฟอร์ม
  useEffect(() => {
  let alive = true;
  (async () => {
    setBusy(true);
    setErr(null);
    setRows([]);
    setFallbackUsed(false);

    const normalize = (j: any): any[] | null => {
      if (!j) return null;
      if (Array.isArray(j)) return j;
      if (Array.isArray(j?.rows)) return j.rows;
      if (Array.isArray(j?.data)) return j.data;
      if (Array.isArray(j?.items)) return j.items;
      return null;
    };

    const s = (v: any) => (v == null ? "" : String(v).toLowerCase());
    const hasAny = (text: string, kws: string[]) => kws.some((k) => text.includes(k));

    // คีย์เวิร์ด fallback ต่อฟอร์ม (เสริมให้ถังขยะครอบคลุมขึ้น)
    const KEYWORDS_BY_SLUG: Record<string, string[]> = {
      "service-request": [
        "ถังขยะ","ขอถังขยะ","ขอใช้บริการถังขยะ","คำร้องขอถังขยะ",
        "ขยะ","ภาชนะรองรับขยะ","ถังขยะมูลฝอย",
        "trash","bin","trash-bin","trashbin","service-request"
      ],
      "general-request": ["คำร้องทั่วไป","general"],
      "roadside-ads-sign-install": ["ป้ายโฆษณา","โฆษณา","ads","sign"],
      "official-misconduct-complaint": ["ทุจริต","ประพฤติมิชอบ","ร้องเรียน","complaint","misconduct"],
      "dbd-registered": ["dbd","registered","พาณิชย์อิเล็กทรอนิกส์"],
      "commercial-registration": ["ทะเบียนพาณิชย์","พาณิชย์"],
      "commercial-reg-request": ["ทะเบียนพาณิชย์","คำร้อง"],
      "document-search-certification": ["ตรวจค้นเอกสาร","รับรองสำเนา","ใบแทน","certification"],
      "power-of-attorney": ["มอบอำนาจ","power of attorney"],
      "info-disclosure-request": ["ข้อมูลข่าวสาร","เปิดเผยข้อมูล","info"],
      "prek-thakham": ["สมัครเรียน","ศพด.","ท่าข้าม"],
      "prek-bangsaem": ["สมัครเรียน","ศพด.","บางแสม"],
      "prek-khlong-phanthong": ["สมัครเรียน","ศพด.","คลองพานทอง"],
      "public-works-general": ["ซ่อมไฟ","ไฟฟ้าสาธารณะ","ถนน","public works"],
      "excavation-landfill-notice": ["ขุดดิน","ถมดิน","excavation","landfill"],
      "welfare-assistance-general": ["ขอรับการช่วยเหลือ","สวัสดิการ"],
    };

    const isThisForm = (row: any) => {
      const subject = s(row.subject) || s(row.title) || s(row.form_title);
      const detail  = s(row.detail)  || s(row.description);
      const type    = s(row.type)    || s(row.form_type) || s(row.category);
      const slugField = s(row.slug)  || s(row.form_slug);

      // 1) slug/form_slug ตรงกับเมนู
      if (slugField && (slugField === slug || slugField.includes(slug))) return true;
      // 2) type/category สื่อถึง slug
      if (type && (type === slug || type.includes(slug))) return true;

      // 3) คีย์เวิร์ดเฉพาะฟอร์ม (เสริมหนักให้ service-request)
      const kws = KEYWORDS_BY_SLUG[slug] || [];
      if (kws.length && (hasAny(subject, kws) || hasAny(detail, kws) || hasAny(type, kws))) return true;

      // 4) คีย์เวิร์ดจาก label
      const labelKw = s(label).split(/\s+/).filter(Boolean);
      if (labelKw.length && (hasAny(subject, labelKw) || hasAny(detail, labelKw))) return true;

      return false;
    };

    try {
      const res = await api<any>("/general-requests");
      const all = normalize(res);
      if (!all) {
        setErr("โหลดข้อมูลไม่สำเร็จ (response ไม่ใช่ array / {rows:[...]})");
        return;
      }

      // กรองหรือไม่กรองตามสวิตช์
      let list = showAll ? all : all.filter(isThisForm);

      // ✅ Fallback อัตโนมัติสำหรับฟอร์ม 'ถังขยะ' ถ้ากรองไม่เจออะไร
      if (!showAll && list.length === 0 && slug === "service-request") {
        list = all;
        setFallbackUsed(true);
        console.info("[HistoryPane] no rows matched; fallback to ALL (service-request)");
      } else {
        setFallbackUsed(false);
      }

      const toRow = (o: any): FormRow => ({
        id: o.id ?? o.form_id ?? o.request_id ?? 0,
        date: o.date ?? o.submitted_at ?? o.created_at ?? null,
        createdAt: o.createdAt ?? o.created_at ?? o.updated_at ?? null,
        subject: o.subject ?? o.title ?? o.form_title ?? label,
        prefix: o.prefix ?? o.titleName ?? o.name_prefix ?? null,
        fullName: o.fullName ?? o.full_name ?? o.name ?? o.applicant ?? null,
        age: o.age ?? null,
        phone: o.phone ?? o.tel ?? o.phone_number ?? null,
        email: o.email ?? null,
        houseNo: o.houseNo ?? o.house_no ?? o.address_no ?? null,
        moo: o.moo ?? o.village ?? null,
        road: o.road ?? o.street ?? null,
        subdistrict: o.subdistrict ?? o.tambon ?? o.sub_district ?? null,
        district: o.district ?? o.amphoe ?? o.district_name ?? null,
        province: o.province ?? null,
        postcode: o.postcode ?? o.postal_code ?? null,
        placeType: o.placeType ?? o.place_type ?? o.location_type ?? null,
        placeTypeOther: o.placeTypeOther ?? o.place_type_other ?? null,
        detail: o.detail ?? o.description ?? null,
        lat: o.lat ?? o.latitude ?? null,
        lng: o.lng ?? o.longitude ?? null,
        attachments: o.attachments ?? o.files ?? null,
        consent: o.consent ?? o.privacy_consent ?? null,
      });

      const mapped = list
        .map(toRow)
        .sort((a, b) => ((b.createdAt || b.date || "") as string).localeCompare((a.createdAt || a.date || "") as string));

      setRows(mapped);
    } catch (e: any) {
      setErr(e?.message || "เกิดข้อผิดพลาดในการโหลดข้อมูล");
    } finally {
      if (alive) setBusy(false);
    }
  })();
  return () => { alive = false; };
  // ให้รีโหลดเมื่อสลับสวิตช์/เปลี่ยนฟอร์ม
}, [slug, label, showAll]);


  const filtered = useMemo(() => {
    const now = new Date();
    const sameDay = (a: Date, b: Date) =>
      a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    const sameMonth = (a: Date, b: Date) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth();

    let items = [...rows];
    items = items.filter((r) => {
      const d = r.createdAt ? new Date(r.createdAt) : r.date ? new Date(r.date) : null;
      if (mode === "today") return !!(d && sameDay(d, now));
      if (mode === "thisMonth") return !!(d && sameMonth(d, now));
      return true;
    });

    const norm = (s?: string | number | null) => (s == null ? "" : String(s).toLowerCase().trim());
    const q = norm(query);
    if (q) {
      items = items.filter((r) => {
        const hay = [
          r.subject,
          r.fullName,
          r.email,
          r.phone,
          r.placeType,
          r.province,
          r.detail,
          r.lat != null && r.lng != null ? `${r.lat},${r.lng}` : "",
        ]
          .map(norm)
          .join(" ");
        return hay.includes(q);
      });
    }

    items.sort((a, b) => ((b.createdAt || b.date || "") as string).localeCompare((a.createdAt || a.date || "") as string));
    return items;
  }, [rows, mode, query]);

  // ====== PDF ======
  const composeAddress = (r: FormRow) => {
    const parts = [
      r.houseNo ? `เลขที่ ${r.houseNo}` : null,
      r.moo ? `หมู่ ${r.moo}` : null,
      r.road ? `ถ.${r.road}` : null,
      r.subdistrict ? `ต.${r.subdistrict}` : null,
      r.district ? `อ.${r.district}` : null,
      r.province ? `จ.${r.province}` : null,
      r.postcode || null,
    ].filter(Boolean);
    return parts.length ? parts.join(" ") : "-";
  };

  const downloadFormAsPdf = async (row: FormRow) => {
    const host = document.createElement("div");
    host.style.position = "fixed";
    host.style.left = "-99999px";
    host.style.top = "0";
    host.style.width = "794px";
    host.style.padding = "24px";
    host.style.background = "#ffffff";
    host.style.fontFamily =
      "system-ui, -apple-system, 'Segoe UI', Roboto, 'Noto Sans Thai', 'Sarabun', sans-serif";
    host.style.color = "#064e3b";

    const addr = composeAddress(row);
    const latlng =
      row.lat != null && row.lng != null
        ? `${Number(row.lat).toFixed(6)}, ${Number(row.lng).toFixed(6)}`
        : "-";
    const files = (row.attachments || [])
      .map((f, i) => `<li>${i + 1}. ${f.filename || "-"}</li>`)
      .join("");

    host.innerHTML = `
      <div style="border:1px solid #d1fae5;border-radius:16px;padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
          <div>
            <div style="font-size:20px;font-weight:800;color:#064e3b;">แบบฟอร์ม: ${label}</div>
            <div style="font-size:12px;color:#065f46;margin-top:4px;">เลขที่รายการ: ${row.id ?? "-"}</div>
          </div>
          <div style="font-size:12px;color:#065f46;">พิมพ์เมื่อ: ${new Date().toLocaleString()}</div>
        </div>
        <hr style="border:none;border-top:1px solid #ecfdf5;margin:12px 0 20px 0;" />
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:14px;line-height:1.5;">
          <div><b>วันที่ส่งเรื่อง:</b> ${row.date ? new Date(row.date).toLocaleDateString() : "-"}</div>
          <div><b>บันทึกเมื่อ:</b> ${row.createdAt ? new Date(row.createdAt).toLocaleString() : "-"}</div>
          <div style="grid-column:1 / span 2"><b>ชื่อเรื่อง:</b> ${row.subject ?? "-"}</div>
          <div><b>คำนำหน้า:</b> ${row.prefix ?? "-"}</div>
          <div><b>ผู้ส่ง:</b> ${row.fullName ?? "-"}</div>
          <div><b>อายุ:</b> ${row.age ?? "-"}</div>
          <div><b>โทรศัพท์:</b> ${row.phone ?? "-"}</div>
          <div style="grid-column:1 / span 2"><b>อีเมล:</b> ${row.email ?? "-"}</div>
          <div style="grid-column:1 / span 2"><b>ที่อยู่:</b> ${addr}</div>
          <div><b>ประเภทสถานที่:</b> ${row.placeType ?? "-"}</div>
          <div><b>รายละเอียดประเภท (อื่นๆ):</b> ${row.placeTypeOther ?? "-"}</div>
          <div style="grid-column:1 / span 2"><b>รายละเอียดเพิ่มเติม:</b> ${row.detail ?? "-"}</div>
          <div><b>พิกัด (lat,lng):</b> ${latlng}</div>
          <div><b>ยินยอมข้อมูลส่วนบุคคล:</b> ${row.consent ? "ยินยอม" : "ไม่ยินยอม"}</div>
          <div style="grid-column:1 / span 2">
            <b>ไฟล์แนบ:</b>
            ${files ? `<ol style="margin:6px 0 0 20px">${files}</ol>` : "-"}
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(host);
    try {
      const [{ default: html2canvas }, { jsPDF }] = await Promise.all([import("html2canvas"), import("jspdf")]);
      const canvas = await html2canvas(host, {
        scale: 2,
        useCORS: true,
        backgroundColor: "#ffffff",
        windowWidth: host.scrollWidth,
        windowHeight: host.scrollHeight,
      });
      const imgData = canvas.toDataURL("image/png");
      const pdf = new jsPDF("p", "mm", "a4");
      const pageW = pdf.internal.pageSize.getWidth();
      const pageH = pdf.internal.pageSize.getHeight();
      const margin = 10;
      const imgW = pageW - margin * 2;
      const imgH = (canvas.height * imgW) / canvas.width;

      if (imgH <= pageH - margin * 2) {
        pdf.addImage(imgData, "PNG", margin, margin, imgW, imgH);
      } else {
        const ratio = imgW / canvas.width;
        const pageCanvasH = (pageH - margin * 2) / ratio;
        let heightLeft = canvas.height;
        let sY = 0;
        let pageIndex = 0;
        while (heightLeft > 0) {
          const partCanvas = document.createElement("canvas");
          partCanvas.width = canvas.width;
          partCanvas.height = Math.min(pageCanvasH, heightLeft);
          const ctx = partCanvas.getContext("2d")!;
          ctx.drawImage(canvas, 0, sY, canvas.width, partCanvas.height, 0, 0, canvas.width, partCanvas.height);
          const partImg = partCanvas.toDataURL("image/png");
          if (pageIndex > 0) pdf.addPage();
          pdf.addImage(partImg, "PNG", margin, margin, imgW, partCanvas.height * ratio);
          heightLeft -= partCanvas.height;
          sY += partCanvas.height;
          pageIndex++;
        }
      }
      pdf.save(`form_${slug}_${row.id ?? "unknown"}.pdf`);
    } finally {
      document.body.removeChild(host);
    }
  };

  // ===== Toolbar & Table =====
  const ModeBtn = ({ v, label }: { v: Mode; label: string }) => (
    <button
      onClick={() => setMode(v)}
      className={[
        "rounded-xl border px-3 py-2 text-sm transition",
        mode === v
          ? "border-indigo-300 bg-indigo-50 text-indigo-900"
          : "border-emerald-200 bg-white text-emerald-900 hover:bg-emerald-50",
      ].join(" ")}
    >
      {label}
    </button>
  );

  const formatDateSafe = (v: string | null) => {
    if (!v) return "-";
    const d = new Date(v);
    return isNaN(d.getTime()) ? v : d.toLocaleDateString();
  };

  return (
    <div className="rounded-3xl border border-emerald-200 bg-white/95 p-6 shadow-lg text-emerald-900">
      <h2 className="text-xl font-bold">ประวัติการส่งฟอร์ม: {label}</h2>

      {/* เครื่องมือกรอง/ค้นหา */}
      <div className="my-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div className="flex flex-wrap gap-2">
          <ModeBtn v="all" label="ทั้งหมด" />
          <ModeBtn v="today" label="วันนี้" />
          <ModeBtn v="thisMonth" label="เดือนนี้" />
        </div>

        <div className="flex items-center gap-2">
          <input
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="ค้นหา: ชื่อเรื่อง / ผู้ส่ง / อีเมล / ประเภท / พิกัด"
            className="w-full md:w-[360px] rounded-xl border border-emerald-200 bg-white/90 px-3 py-2 text-sm text-emerald-900 shadow-sm outline-none placeholder:text-emerald-900/50 focus:border-emerald-300"
          />
          {query && (
            <button
              onClick={() => setQuery("")}
              className="rounded-xl border border-emerald-200 bg-white/90 px-3 py-2 text-sm text-emerald-900 shadow-sm"
            >
              ล้าง
            </button>
          )}
        </div>
      </div>

      {busy && <div className="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">กำลังโหลดข้อมูล...</div>}
      {err && (
        <div className="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
          เกิดข้อผิดพลาด: {err}
        </div>
      )}

      {!busy && !err && (
        <div className="overflow-x-auto rounded-2xl border border-emerald-100 bg-white shadow-sm">
          <table className="min-w-full text-sm">
            <thead>
              <tr className="bg-emerald-50 text-emerald-900">
                {["#", "วันที่ส่งเรื่อง", "ชื่อเรื่อง", "ผู้ส่ง", "อีเมล", "ประเภทสถานที่", "พิกัด", "บันทึกเมื่อ", "ดาวน์โหลด"].map((h) => (
                  <th key={h} className="px-3 py-2 text-left font-semibold">
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtered.length ? (
                filtered.map((r, i) => (
                  <tr key={r?.id ?? i} className="even:bg-emerald-50/30">
                    <td className="px-3 py-2">{i + 1}</td>
                    <td className="px-3 py-2">{formatDateSafe(r?.date ?? null)}</td>
                    <td className="px-3 py-2 max-w-[18rem] truncate" title={r?.subject ?? ""}>
                      {r?.subject ?? "-"}
                    </td>
                    <td className="px-3 py-2">{r?.fullName ?? "-"}</td>
                    <td className="px-3 py-2">{r?.email ?? "-"}</td>
                    <td className="px-3 py-2">{r?.placeType ?? "-"}</td>
                    <td className="px-3 py-2">
                      {r?.lat != null && r?.lng != null
                        ? `${Number(r.lat).toFixed(6)}, ${Number(r.lng).toFixed(6)}`
                        : "-"}
                    </td>
                    <td className="px-3 py-2">{formatDateSafe(r?.createdAt ?? null)}</td>
                    <td className="px-3 py-2">
                      <button
                        onClick={() => downloadFormAsPdf(r)}
                        className="rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs text-emerald-900 shadow-sm hover:bg-emerald-50"
                        title="ดาวน์โหลด PDF ของฟอร์มนี้"
                      >
                        ดาวน์โหลด
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={9} className="px-3 py-6 text-center text-emerald-800/70">
                    ไม่มีข้อมูล
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

type Selected = { slug: string; label: string; view: "form" | "history" } | null;

export default function ServiceRequestPage() {
  const [, nav] = useLocation();

  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [openItem, setOpenItem] = useState<Record<string, boolean>>({});
  const [selected, setSelected] = useState<Selected>(null);

  // ===== resolve email (sync -> async) =====
  const [currentEmail, setCurrentEmail] = useState<string | null>(getCurrentUserEmailSync());

  useEffect(() => {
    if (currentEmail) return;
    let alive = true;
    (async () => {
      const e = await getCurrentUserEmailAsync();
      if (!alive) return;
      if (e) setCurrentEmail(e);
    })();
    return () => {
      alive = false;
    };
  }, [currentEmail]);

  const ownerSectionTitle = currentEmail ? ADMIN_SECTION_MAP[currentEmail] : null;
  const canSeeSidebar = !!ownerSectionTitle; // admin01..06 เห็น Sidebar เสมอ

  const NAV_VISIBLE = useMemo(
    () => (canSeeSidebar ? NAV.filter((s) => s.title === ownerSectionTitle) : []),
    [canSeeSidebar, ownerSectionTitle]
  );
  const isAuthed = !!currentEmail;

  const goLogin = () => {
    const next = window.location.pathname + window.location.search + window.location.hash;
    window.location.href = `/login?next=${encodeURIComponent(next)}`;
  };

  // helper: เปิดเมนูหมวดให้เห็น + setSelected
  const pickForm = (slug: string, view: "form" | "history" = "form") => {
    const sec = NAV.find((s) => s.items.some((i) => i.slug === slug));
    const item = sec?.items.find((i) => i.slug === slug);
    if (!item) return;
    setSelected({ slug, label: item.label, view });
    if (sec) {
      const key = `${sec.title}::${slug}`;
      setOpenItem((o) => ({ ...o, [key]: true }));
    }
  };

  // เลือกอัตโนมัติจาก URL (รองรับทั้ง ?slug= และ ?form=trash-bin)
  useEffect(() => {
    const applyFromUrl = () => {
      if (!currentEmail) return;

      const qs = new URLSearchParams(window.location.search);
      let slug = qs.get("slug") || "";
      const form = (qs.get("form") || "").toLowerCase();
      if (!slug) {
        if (form === "trash-bin") slug = "service-request";
        if (form === "general") slug = "general-request";
      }
      if (!slug) return;

      const hash = window.location.hash;
      const view: "form" | "history" = hash === "#history" ? "history" : "form";

      pickForm(slug, view);

      if (view === "form") {
        setTimeout(() => {
          document.getElementById("form")?.scrollIntoView({ behavior: "smooth", block: "start" });
        }, 0);
      }
    };

    applyFromUrl();
    const onUrlChange = () => applyFromUrl();
    window.addEventListener("popstate", onUrlChange);
    window.addEventListener("hashchange", onUrlChange);
    return () => {
      window.removeEventListener("popstate", onUrlChange);
      window.removeEventListener("hashchange", onUrlChange);
    };
  }, [currentEmail]);

  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  // เลือกคอมโพเนนต์ฟอร์มจาก slug
  const FormComp = useMemo(() => {
    if (!selected || selected.view !== "form") return null;
    const loader = formLoaders[selected.slug];
    return loader ? lazy(loader) : null;
  }, [selected]);

  // ปิดสกรอลพื้นหลังเมื่อ drawer เปิด
  useEffect(() => {
    if (!sidebarOpen) return;
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    return () => {
      document.body.style.overflow = prev;
    };
  }, [sidebarOpen]);

  // ปิด Drawer ด้วย ESC
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") setSidebarOpen(false);
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, []);

  return (
    <div
      className="relative min-h-screen overflow-hidden"
      style={{
        backgroundImage: `url(${bodyBg})`,
        backgroundSize: "cover",
        backgroundPosition: "center top",
        backgroundRepeat: "no-repeat",
      }}
    >
      <div className="pointer-events-none absolute inset-0 bg-white/0" />
      <div className="relative z-10 flex min-h-screen flex-col">
        <Header />

        <main className="mx-auto w-full max-w-[1200px] flex-1 px-3 md:px-4 pb-10 pt-3">
          {/* Top bar (mobile) */}
          <div className="sticky top-0 z-20 mb-3 -mx-3 md:hidden bg-transparent shadow-none ring-0 px-3 py-2 ">
            <div className="relative flex items-center justify-between">
              <button
                onClick={goBack}
                className="inline-block rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400"
                aria-label="ย้อนกลับ"
              >
                <img src={backPng} alt="ย้อนกลับ" className="h-12 sm:h-14 md:h-16 lg:h-20 xl:h-14 w-auto select-none pointer-events-none" />
              </button>

              {/* ปุ่มเมนูเฉพาะผู้ที่เห็น Sidebar */}
              {canSeeSidebar && (
                <button
                  onClick={() => setSidebarOpen(true)}
                  className="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-white/95 px-3 py-2 text-emerald-900 shadow-sm"
                  aria-expanded={sidebarOpen}
                  aria-controls="drawer-menu"
                >
                  <span className="text-lg">☰</span>
                  <span className="text-sm">เมนูฟอร์ม</span>
                </button>
              )}
            </div>
          </div>

          {/* ปุ่ม Back (desktop) */}
          <div className="mb-4 hidden items-center gap-3 md:flex">
            <button onClick={goBack} className="inline-block rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400" aria-label="ย้อนกลับ">
              <img src={backPng} alt="ย้อนกลับ" className="h-12 sm:h-14 md:h-16 lg:h-20 xl:h-14 w-auto select-none pointer-events-none" />
            </button>
          </div>

          <div className={`grid gap-4 ${canSeeSidebar ? "md:grid-cols-[320px,1fr] lg:grid-cols-[360px,1fr]" : "md:grid-cols-1"}`}>
            {/* ===== Sidebar (Desktop) ===== */}
            {isAuthed && canSeeSidebar && (
              <aside className="hidden md:block">
                <SidebarBox
                  nav={NAV_VISIBLE}
                  openItem={openItem}
                  setOpenItem={setOpenItem}
                  setSelected={(s) => setSelected(s)}
                />
              </aside>
            )}

            {/* ===== Content ===== */}
            <section>
              {!isAuthed && (
                <div className="rounded-3xl border border-emerald-200 bg-white/95 p-8 shadow-lg text-emerald-900/90 text-center max-w-xl mx-auto">
                  <h2 className="text-xl md:text-2xl font-semibold">กรุณาเข้าสู่ระบบ</h2>
                  <p className="mt-2">ต้องล็อกอินก่อนจึงจะสามารถใช้งานแบบฟอร์มได้</p>
                  <button
                    onClick={goLogin}
                    className="mt-4 inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 font-medium text-white hover:bg-emerald-800"
                  >
                    ไปหน้าเข้าสู่ระบบ
                  </button>
                </div>
              )}

              {isAuthed && (
                <>
                  {!canSeeSidebar && !selected && (
                    <div className="rounded-3xl border border-emerald-200 bg-white/95 p-6 shadow-lg text-emerald-900/90 text-center">
                      กรุณาเข้าผ่านลิงก์แบบฟอร์มโดยตรง
                    </div>
                  )}

                  {canSeeSidebar && !selected && (
                    <div className="rounded-3xl border border-emerald-200 bg-white/95 p-6 shadow-lg text-emerald-900/90">
                      เลือกแบบฟอร์มจากเมนูด้านซ้าย แล้วเนื้อหาจะปรากฏที่นี่
                    </div>
                  )}

                  {selected?.view === "history" && <HistoryPane slug={selected.slug} label={selected.label} />}

                  {selected?.view === "form" &&
                    (() => {
                      const Comp = FormComp;
                      return Comp ? (
                        <Suspense
                          fallback={
                            <div className="rounded-3xl border border-emerald-200 bg-white/95 p-6 shadow-lg text-emerald-900">
                              กำลังโหลดฟอร์ม...
                            </div>
                          }
                        >
                          <div id="form">
                            <Comp />
                          </div>
                        </Suspense>
                      ) : (
                        <div className="rounded-3xl border border-emerald-200 bg-white/95 p-6 shadow-lg text-emerald-900">
                          ยังไม่มีฟอร์มสำหรับ: {selected.slug}
                        </div>
                      );
                    })()}
                </>
              )}
            </section>
          </div>
        </main>

        <Footer />
      </div>

      {/* ===== MOBILE DRAWER SIDEBAR ===== */}
      {isAuthed && canSeeSidebar && (
        <MobileDrawer
          open={sidebarOpen}
          onClose={() => setSidebarOpen(false)}
          onPick={(sel) => {
            setSelected(sel);
            setSidebarOpen(false);
            window.scrollTo({ top: 0, behavior: "smooth" });
          }}
          nav={NAV_VISIBLE}
          openItem={openItem}
          setOpenItem={setOpenItem}
        />
      )}
    </div>
  );
}

function SidebarBox({
  nav,
  openItem,
  setOpenItem,
  setSelected,
}: {
  nav: NavSection[];
  openItem: Record<string, boolean>;
  setOpenItem: React.Dispatch<React.SetStateAction<Record<string, boolean>>>;
  setSelected: (s: Selected) => void;
}) {
  function getSectionIcon(title: string) {
    const t = title.replace(/\s+/g, "");
    if (t.includes("สาธารณสุข") || t.includes("สิ่งแวดล้อม")) return secHealth;
    if (t.includes("การศึกษา") || t.includes("ศาสนา") || t.includes("วัฒนธรรม")) return secEdu;
    if (t.includes("ยุทธศาสตร์") || t.includes("งบประมาณ")) return secFin;
    if (t.includes("ช่าง")) return secWorks;
    if (t.includes("สวัสดิการ")) return secTcc;
    if (t.includes("คลัง")) return secFin;
    if (t.includes("สำนักปลัด")) return secGov;
    return secGov;
  }

  return (
    <div className="rounded-3xl border border-emerald-200 bg-white/95 p-2 shadow-sm ring-1 ring-emerald-100">
      {nav.map((sec) => (
        <div key={sec.title} className="mb-3 last:mb-0 overflow-hidden rounded-2xl border border-slate-200 bg-white">
          <div className="flex items-center gap-3 px-3 py-2.5 text-white font-semibold bg-gradient-to-r from-[#134e9e] to-[#082149]">
            <img src={getSectionIcon(sec.title)} alt="" className="shrink-0 h-8 w-8 md:h-10 md:w-10 lg:h-11 lg:w-11" />
            <span className="leading-none">{sec.title}</span>
          </div>

          <ul className="pb-2 pt-1">
            {sec.items.map((it) => {
              const key = `${sec.title}::${it.slug}`;
              const isOpen = !!openItem[key];
              return (
                <li key={key} className="px-2">
                  <button
                    onClick={() => setOpenItem((o) => ({ ...o, [key]: !o[key] }))}
                    className="mt-1 flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-slate-800 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#134e9e]/30"
                    aria-expanded={isOpen}
                  >
                    <span className="truncate">{it.label}</span>
                    <svg className={`h-4 w-4 text-slate-600 transition ${isOpen ? "rotate-90" : ""}`} viewBox="0 0 20 20" fill="currentColor">
                      <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 018.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                  </button>

                  {isOpen && (
                    <div className="ml-3 mt-1 mb-2 space-y-1 rounded-lg bg-slate-50 p-1 ring-1 ring-slate-200">
                      <button
                        onClick={() => setSelected({ slug: it.slug, label: it.label, view: "form" })}
                        className="block w-full rounded-md px-3 py-2 text-left text-[15px] text-white bg-[#c71b30] hover:bg-[#b01a2a] active:bg-[#991724]"
                      >
                        ฟอร์ม
                      </button>
                      <button
                        onClick={() => setSelected({ slug: it.slug, label: it.label, view: "history" })}
                        className="block w-full rounded-md px-3 py-2 text-left text-[15px] text-white bg-[#c71b30] hover:bg-[#b01a2a] active:bg-[#991724]"
                      >
                        ประวัติการส่งฟอร์ม
                      </button>
                    </div>
                  )}
                </li>
              );
            })}
          </ul>
        </div>
      ))}
    </div>
  );
}

function MobileDrawer({
  open,
  onClose,
  onPick,
  nav,
  openItem,
  setOpenItem,
}: {
  open: boolean;
  onClose: () => void;
  onPick: (s: Selected) => void;
  nav: NavSection[];
  openItem: Record<string, boolean>;
  setOpenItem: React.Dispatch<React.SetStateAction<Record<string, boolean>>>;
}) {
  function getSectionIcon(title: string) {
    const t = title.replace(/\s+/g, "");
    if (t.includes("สาธารณสุข") || t.includes("สิ่งแวดล้อม")) return secHealth;
    if (t.includes("การศึกษา") || t.includes("ศาสนา") || t.includes("วัฒนธรรม")) return secEdu;
    if (t.includes("ยุทธศาสตร์") || t.includes("งบประมาณ")) return secFin;
    if (t.includes("ช่าง")) return secWorks;
    if (t.includes("สวัสดิการ")) return secGov;
    if (t.includes("คลัง")) return secFin;
    if (t.includes("สำนักปลัด")) return secGov;
    return secGov;
  }

  return (
    <div className={`fixed inset-0 z-50 md:hidden ${open ? "" : "pointer-events-none"}`} aria-hidden={!open}>
      <div className={`absolute inset-0 bg-black/30 transition-opacity ${open ? "opacity-100" : "opacity-0"}`} onClick={onClose} />
      <div
        id="drawer-menu"
        className={`absolute left-0 top-0 h-full w-[92vw] max-w-[420px] transform rounded-r-2xl border-r border-slate-200 bg-white shadow-2xl transition-transform duration-200 ${
          open ? "translate-x-0" : "-translate-x-full"
        }`}
        role="dialog"
        aria-modal="true"
      >
        <div className="flex h-full flex-col">
          <div className="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <div className="text-base font-semibold text-slate-800">เมนูฟอร์ม</div>
            <button onClick={onClose} className="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-slate-800" aria-label="Close menu">
              ✕
            </button>
          </div>

          <div className="flex-1 overflow-y-auto p-4" style={{ WebkitOverflowScrolling: "touch" }}>
            {nav.map((sec) => (
              <div key={sec.title} className="mb-3 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div className="flex items-center gap-3 px-3 py-2.5 text-white font-semibold bg-gradient-to-r from-[#134e9e] to-[#082149]">
                  <img src={getSectionIcon(sec.title)} alt="" className="shrink-0 h-8 w-8 md:h-10 md:w-10" />
                  <span className="leading-none">{sec.title}</span>
                </div>

                <ul className="pb-2 pt-1">
                  {sec.items.map((it) => {
                    const key = `${sec.title}::${it.slug}`;
                    const isOpen = !!openItem[key];
                    return (
                      <li key={key} className="px-2">
                        <button
                          onClick={() => setOpenItem((o) => ({ ...o, [key]: !o[key] }))}
                          className="mt-1 flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-slate-800 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#134e9e]/30"
                          aria-expanded={isOpen}
                        >
                          <span className="truncate">{it.label}</span>
                          <svg className={`h-4 w-4 text-slate-600 transition ${isOpen ? "rotate-90" : ""}`} viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 018.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                          </svg>
                        </button>

                        {isOpen && (
                          <div className="ml-3 mt-1 mb-2 space-y-1 rounded-lg bg-slate-50 p-1 ring-1 ring-slate-200">
                            <button
                              onClick={() => onPick({ slug: it.slug, label: it.label, view: "form" })}
                              className="block w-full rounded-md px-3 py-2 text-left text-[15px] text-white bg-[#c71b30] hover:bg-[#b01a2a] active:bg-[#991724]"
                            >
                              ฟอร์ม
                            </button>
                            <button
                              onClick={() => onPick({ slug: it.slug, label: it.label, view: "history" })}
                              className="block w-full rounded-md px-3 py-2 text-left text-[15px] text-white bg-[#c71b30] hover:bg-[#b01a2a] active:bg-[#991724]"
                            >
                              ประวัติการส่งฟอร์ม
                            </button>
                          </div>
                        )}
                      </li>
                    );
                  })}
                </ul>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
