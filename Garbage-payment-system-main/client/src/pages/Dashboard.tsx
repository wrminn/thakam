// client/src/pages/Dashboard.tsx
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { useEffect, useMemo, useState } from "react";
import { api } from "@/lib/api";
import { useAuth } from "@/hooks/useAuth";
const API_BASE =
  (import.meta as any)?.env?.VITE_API_BASE /* ถ้ามีตั้งค่าใน .env */ ||
  "";

// รองรับทั้ง "http://.../path", "/storage/...", "storage/..."
function resolveMediaUrl(p?: string | null): string {
  if (!p) return "";

  // ✅ ถ้าเป็น URL เต็มหรือ data URI อยู่แล้ว ให้คืนเลย
  const low = p.toLowerCase();
  if (low.startsWith("http://") || low.startsWith("https://") || low.startsWith("data:")) {
    return p;
  }

  // ✅ ให้มี leading slash สำหรับพาธที่มาจาก Storage::url (มักเป็น "/storage/...")
  const s = p.startsWith("/") ? p : `/${p}`;

  try {
    // ใช้ VITE_API_BASE ถ้าตั้งไว้ (เช่น http://192.168.1.x:8000), ไม่งั้น fallback เป็น origin ปัจจุบัน
    const base = API_BASE || window.location.origin;
    return new URL(s, base).href;
  } catch {
    return s;
  }
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

type EmergencyRow = {
  id: number;
  category: "accident" | "fire" | "tree" | "general";
  title?: string | null;
  reporterName?: string | null;
  phone?: string | null;
  description?: string | null;
  lat?: number | null;
  lng?: number | null;
  photo?: { original?: string; path?: string; mime?: string; size?: number }[] | null;
  createdAt?: string | null;
};

type Mode = "all" | "today" | "thisMonth";

/* ---------- UI atoms ---------- */
function Th({ children, className = "" }: any) {
  return <th className={`px-3 py-2 text-left font-semibold ${className}`}>{children}</th>;
}
function Td({ children, className = "", colSpan }: any) {
  return (
    <td className={`px-3 py-2 text-emerald-900 ${className}`} colSpan={colSpan}>
      {children}
    </td>
  );
}
function Panel({ children, tone }: { children: any; tone?: "error" | "info" }) {
  const cls =
    tone === "error"
      ? "border-red-200 bg-red-50 text-red-700"
      : "border-emerald-100 bg-white text-emerald-800";
  return <div className={`rounded-2xl p-4 shadow-sm ${cls}`}>{children}</div>;
}

/* ---------- Sidebar ---------- */
function SidebarMenu({
  data,
  activeKey,
  onSelect,
  wide = false,
}: {
  data: { header: string | null; items: { key: string; label: string; icon?: string }[] }[];
  activeKey: string;
  onSelect: (k: string) => void;
  wide?: boolean;
}) {
  return (
    <div>
      {data.map((group, gi) => (
        <div key={gi} className={gi > 0 ? "mt-4" : ""}>
          {group.header && (
            <div className="px-3 pb-2 text-[11px] font-semibold tracking-wider text-emerald-700/70">
              {group.header}
            </div>
          )}
          <nav className="space-y-1">
            {group.items.map((it) => {
              const Active = activeKey === it.key;
              return (
                <button
                  key={it.key}
                  onClick={() => onSelect(it.key)}
                  className={[
                    "w-full text-left rounded-xl transition ring-1",
                    wide ? "px-4 py-3" : "px-3 py-2",
                    Active
                      ? "bg-indigo-100 text-indigo-700 ring-indigo-200"
                      : "text-emerald-900 ring-transparent hover:bg-emerald-50",
                  ].join(" ")}
                >
                  <span className="inline-flex items-center gap-3">
                    {it.icon && <span className="inline-block w-5">{it.icon}</span>}
                    <span className={wide ? "text-[15px]" : "text-sm"}>{it.label}</span>
                  </span>
                </button>
              );
            })}
          </nav>
        </div>
      ))}
    </div>
  );
}

/* ---------- Forms table & toolbar ---------- */
function formatDateSafe(v: string | null) {
  if (!v) return "-";
  const d = new Date(v);
  return isNaN(d.getTime()) ? v : d.toLocaleDateString();
}

function FormsTable({ rows, onDownload }: { rows: FormRow[]; onDownload: (row: FormRow) => void }) {
  const safeRows = Array.isArray(rows) ? rows : [];
  return (
    <div className="overflow-x-auto rounded-2xl border border-emerald-100 bg-white shadow-sm">
      <table className="min-w-full text-sm">
        <thead>
          <tr className="bg-emerald-50 text-emerald-900">
            {["#", "วันที่ส่งเรื่อง", "ชื่อเรื่อง", "ผู้ส่ง", "อีเมล", "ประเภทสถานที่", "พิกัด", "บันทึกเมื่อ", "ดาวน์โหลด"].map((h) => (
              <Th key={h}>{h}</Th>
            ))}
          </tr>
        </thead>
        <tbody>
          {safeRows.length ? (
            safeRows.map((r, i) => (
              <tr key={r?.id ?? i} className="even:bg-emerald-50/30">
                <Td>{i + 1}</Td>
                <Td>{formatDateSafe(r?.date ?? null)}</Td>
                <Td className="max-w-[18rem] truncate" title={r?.subject ?? ""}>
                  {r?.subject ?? "-"}
                </Td>
                <Td>{r?.fullName ?? "-"}</Td>
                <Td>{r?.email ?? "-"}</Td>
                <Td>{r?.placeType ?? "-"}</Td>
                <Td>
                  {r?.lat != null && r?.lng != null
                    ? `${Number(r.lat).toFixed(6)}, ${Number(r.lng).toFixed(6)}`
                    : "-"}
                </Td>
                <Td>{formatDateSafe(r?.createdAt ?? null)}</Td>
                <Td>
                  <button
                    onClick={() => onDownload(r)}
                    className="rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs text-emerald-900 shadow-sm hover:bg-emerald-50"
                    title="ดาวน์โหลด PDF ของฟอร์มนี้"
                  >
                    ดาวน์โหลด
                  </button>
                </Td>
              </tr>
            ))
          ) : (
            <tr>
              <Td colSpan={9} className="py-6 text-center text-emerald-800/70">
                ไม่มีข้อมูล
              </Td>
            </tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

function FormsToolbar({
  mode,
  setMode,
  query,
  setQuery,
}: {
  mode: Mode;
  setMode: (m: Mode) => void;
  query: string;
  setQuery: (q: string) => void;
}) {
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

  return (
    <div className="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
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
  );
}

/* ---------- Emergencies table & toolbar ---------- */
function EmergenciesToolbar({
  mode,
  setMode,
  query,
  setQuery,
}: {
  mode: Mode;
  setMode: (m: Mode) => void;
  query: string;
  setQuery: (q: string) => void;
}) {
  const Btn = (v: Mode, label: string) => (
    <button
      onClick={() => setMode(v)}
      className={`rounded-xl border px-3 py-2 text-sm ${
        mode === v ? "border-indigo-300 bg-indigo-50 text-indigo-900" : "border-emerald-200 bg-white text-emerald-900 hover:bg-emerald-50"
      }`}
    >
      {label}
    </button>
  );
  return (
    <div className="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div className="flex flex-wrap gap-2">
        {Btn("all", "ทั้งหมด")}
        {Btn("today", "วันนี้")}
        {Btn("thisMonth", "เดือนนี้")}
      </div>
      <div className="flex items-center gap-2">
        <input
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="ค้นหา: หัวข้อ / ผู้แจ้ง / เบอร์ / รายละเอียด / หมวด"
          className="w-full md:w-[360px] rounded-xl border border-emerald-200 bg-white/90 px-3 py-2 text-sm text-emerald-900 shadow-sm outline-none placeholder:text-emerald-900/50 focus:border-emerald-300"
        />
        {query && (
          <button onClick={() => setQuery("")} className="rounded-xl border border-emerald-200 bg-white/90 px-3 py-2 text-sm">
            ล้าง
          </button>
        )}
      </div>
    </div>
  );
}
function ImagePreviewModal({ src, onClose }: { src: string; onClose: () => void }) {
  return (
    <div className="fixed inset-0 z-[3000] bg-black/70 backdrop-blur-sm" onClick={onClose}>
      <div
        className="absolute inset-4 md:inset-12 rounded-2xl bg-black/20 flex items-center justify-center"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="relative max-h-full max-w-full">
          <img
            src={src}
            alt="preview"
            className="max-h-[80vh] max-w-[90vw] object-contain rounded-xl shadow-2xl"
          />
          <button
            onClick={onClose}
            className="absolute -right-2 -top-2 rounded-full bg-white/90 px-3 py-1 text-sm font-semibold text-gray-800 shadow"
          >
            ปิด
          </button>
        </div>
      </div>
    </div>
  );
}

function EmergenciesTable({ rows }: { rows: EmergencyRow[] }) {
  const [preview, setPreview] = useState<string | null>(null);

  const catLabel = (c: string) =>
    c === "fire" ? "ไฟไหม้" : c === "accident" ? "อุบัติเหตุ" : c === "tree" ? "ต้นไม้ล้ม" : "ทั่วไป";

  const openMap = (lat: number, lng: number) => {
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, "_blank", "noopener,noreferrer");
  };

  return (
    <>
      <div className="overflow-x-auto rounded-2xl border border-emerald-100 bg-white shadow-sm">
        <table className="min-w-full text-sm">
          <thead>
            <tr className="bg-rose-50 text-rose-900">
              <Th>#</Th>
              <Th>วันที่แจ้ง</Th>
              <Th>หมวด</Th>
              <Th>หัวข้อ</Th>
              <Th>ผู้แจ้ง</Th>
              <Th>เบอร์</Th>
              <Th>รูป</Th>
              <Th>พิกัด</Th>
            </tr>
          </thead>
          <tbody>
            {rows.length ? (
              rows.map((r, i) => {
                const rawPath =
                  Array.isArray(r.photo) && r.photo.length > 0
                    ? (r.photo[0].path || r.photo[0].original || "")
                    : "";
                const firstPhotoUrl = resolveMediaUrl(rawPath); // ← ใช้ helper ที่เพิ่มมา

                const hasCoord = r.lat != null && r.lng != null;
                const lat = Number(r.lat ?? 0);
                const lng = Number(r.lng ?? 0);

                return (
                  <tr key={r.id} className="even:bg-rose-50/30">
                    <Td>{i + 1}</Td>
                    <Td>{r.createdAt ? new Date(r.createdAt).toLocaleString() : "-"}</Td>
                    <Td>{catLabel(r.category)}</Td>
                    <Td className="max-w-[18rem] truncate" title={r.title ?? ""}>
                      {r.title ?? "-"}
                    </Td>
                    <Td>{r.reporterName ?? "-"}</Td>
                    <Td>{r.phone ?? "-"}</Td>

                    {/* รูป (thumbnail คลิกดูใหญ่) */}
                    <Td>
                      {firstPhotoUrl ? (
                        <button
                          onClick={() => setPreview(firstPhotoUrl)}
                          className="group flex items-center gap-2"
                          title="คลิกเพื่อดูรูปใหญ่"
                        >
                          <img
                            src={firstPhotoUrl}
                            alt="evidence"
                            className="h-10 w-14 rounded-md object-cover ring-1 ring-rose-200 group-hover:opacity-90"
                            onError={(e) => {
                              // ถ้ารูปพัง ให้แสดง placeholder แทน
                              (e.currentTarget as HTMLImageElement).src =
                                "data:image/svg+xml;utf8," +
                                encodeURIComponent(
                                  `<svg xmlns='http://www.w3.org/2000/svg' width='112' height='80'><rect width='100%' height='100%' fill='#fee2e2'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#991b1b' font-size='12'>โหลดรูปไม่สำเร็จ</text></svg>`
                                );
                            }}
                          />
                          <span className="text-xs text-rose-700 underline decoration-dotted group-hover:no-underline">
                            ดูรูป
                          </span>
                        </button>
                      ) : (
                        <span className="text-emerald-800/60">-</span>
                      )}
                    </Td>

                    {/* พิกัด → Google Maps */}
                    <Td>
                      {hasCoord ? (
                        <button
                          onClick={() => openMap(lat, lng)}
                          className="rounded-md border border-emerald-200 bg-white px-2 py-1 text-xs text-emerald-900 shadow-sm hover:bg-emerald-50"
                          title="เปิด Google Maps"
                        >
                          {lat.toFixed(6)}, {lng.toFixed(6)}
                        </button>
                      ) : (
                        <span className="text-emerald-800/60">-</span>
                      )}
                    </Td>
                  </tr>
                );
              })
            ) : (
              <tr>
                <Td colSpan={8} className="py-6 text-center text-emerald-800/70">
                  ไม่มีข้อมูล
                </Td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {preview && <ImagePreviewModal src={preview} onClose={() => setPreview(null)} />}
    </>
  );
}



/* ---------------- Main ---------------- */
export default function Dashboard() {
  const { user, loading } = useAuth();

  // ----- tabs -----
  const allowedTabs = ["forms", "emergencies"] as const;
  const [tab, setTabState] = useState<"forms" | "emergencies">(() => {
    try {
      const q = new URLSearchParams(window.location.search).get("tab");
      return q === "emergencies" ? "emergencies" : "forms";
    } catch {
      return "forms";
    }
  });
  const setTab = (k: "forms" | "emergencies") => {
    try {
      const url = new URL(window.location.href);
      url.searchParams.set("tab", k);
      window.history.pushState({}, "", url.toString());
    } catch {}
    setTabState(k);
  };
  useEffect(() => {
    if (!allowedTabs.includes(tab)) setTab("forms");
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const sidebar = useMemo(
    () => [
      {
        header: null,
        items: [
          { key: "forms", label: "ข้อมูลฟอร์มที่ส่งเข้ามา", icon: "📝" },
          { key: "emergencies", label: "การแจ้งเหตุ", icon: "🚨" },
        ],
      },
    ],
    []
  );

  // ----- Forms: fetch + filters -----
  const [forms, setForms] = useState<FormRow[]>([]);
  const [formsErr, setFormsErr] = useState<string | null>(null);
  const [formsBusy, setFormsBusy] = useState(false);
  const [formMode, setFormMode] = useState<Mode>("all");
  const [formQuery, setFormQuery] = useState("");

  useEffect(() => {
    if (loading || tab !== "forms") return;
    (async () => {
      setFormsBusy(true);
      setFormsErr(null);
      try {
        const res = await api<{ ok: true; rows: FormRow[] }>("/general-requests");
        setForms(res.rows || []);
      } catch (e: any) {
        setFormsErr(e?.message || "โหลดข้อมูลฟอร์มไม่สำเร็จ");
        setForms([]);
      } finally {
        setFormsBusy(false);
      }
    })();
  }, [loading, tab]);

  const filteredForms = useMemo(() => {
    const now = new Date();
    let rows = [...forms];

    rows = rows.filter((r) => {
      const d = r.createdAt ? new Date(r.createdAt) : r.date ? new Date(r.date) : null;
      if (formMode === "today") return !!(d && sameDay(d, now));
      if (formMode === "thisMonth") return !!(d && sameMonth(d, now));
      return true;
    });

    const q = norm(formQuery);
    if (q) {
      rows = rows.filter((r) => {
        const hay = [
          r.subject,
          r.fullName,
          r.email,
          r.placeType,
          r.phone,
          r.province,
          r.detail,
          r.lat != null && r.lng != null ? `${r.lat},${r.lng}` : "",
        ]
          .map(norm)
          .join(" ");
        return hay.includes(q);
      });
    }

    rows.sort((a, b) => ((b.createdAt || b.date || "") as string).localeCompare((a.createdAt || a.date || "") as string));
    return rows;
  }, [forms, formMode, formQuery]);

  // ----- Emergencies: fetch + filters -----
  const [emergencies, setEmergencies] = useState<EmergencyRow[]>([]);
  const [emErr, setEmErr] = useState<string | null>(null);
  const [emBusy, setEmBusy] = useState(false);
  const [emMode, setEmMode] = useState<Mode>("all");
  const [emQuery, setEmQuery] = useState("");

  useEffect(() => {
    if (loading || tab !== "emergencies") return;
    (async () => {
      setEmBusy(true);
      setEmErr(null);
      try {
        const res = await api<{ ok: boolean; rows: EmergencyRow[] }>("/emergencies");
        setEmergencies(res?.rows ?? []);
      } catch (e: any) {
        setEmErr(e?.message || "โหลดข้อมูลการแจ้งเหตุไม่สำเร็จ");
        setEmergencies([]);
      } finally {
        setEmBusy(false);
      }
    })();
  }, [loading, tab]);

  const filteredEmergencies = useMemo(() => {
    const now = new Date();
    let rows = [...emergencies];

    rows = rows.filter((r) => {
      const d = r.createdAt ? new Date(r.createdAt) : null;
      if (emMode === "today") return !!(d && sameDay(d, now));
      if (emMode === "thisMonth") return !!(d && sameMonth(d, now));
      return true;
    });

    const q = norm(emQuery);
    if (q) {
      rows = rows.filter((r) => {
        const hay = [
          r.title,
          r.reporterName,
          r.phone,
          r.description,
          r.category,
          r.lat != null && r.lng != null ? `${r.lat},${r.lng}` : "",
        ]
          .map(norm)
          .join(" ");
        return hay.includes(q);
      });
    }

    rows.sort((a, b) => ((b.createdAt || "") as string).localeCompare((a.createdAt || "") as string));
    return rows;
  }, [emergencies, emMode, emQuery]);

  // ----- Utils -----
  function norm(s?: string | null) {
    return (s ?? "").toLowerCase().trim();
  }
  function sameDay(a: Date, b: Date) {
    return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
  }
  function sameMonth(a: Date, b: Date) {
    return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth();
  }

  // ----- Export PDF (ฟอร์ม) -----
  function composeAddress(r: FormRow) {
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
  }

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
            <div style="font-size:20px;font-weight:800;color:#064e3b;">แบบฟอร์มคำร้องขอใช้บริการถังขยะ</div>
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
      const [{ default: html2canvas }, { jsPDF }] = await Promise.all([
        import("html2canvas"),
        import("jspdf"),
      ]);
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
      pdf.save(`form_${row.id ?? "unknown"}.pdf`);
    } finally {
      document.body.removeChild(host);
    }
  };

  return (
    <div className="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#ecfbf3] via-[#d4f1e1] to-[#bce5ce]">
      {/* background blobs */}
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.7),_rgba(255,255,255,0))]" />
      <div className="absolute left-[-10%] top-[-10%] h-[420px] w-[420px] rounded-full bg-emerald-400/20 blur-3xl" />
      <div className="absolute right-[-20%] top-[20%] h-[360px] w-[360px] rounded-full bg-lime-300/25 blur-3xl" />
      <div className="absolute bottom-[-20%] left-[15%] h-[320px] w-[320px] rounded-full bg-teal-300/20 blur-3xl" />

      <div className="relative z-10 flex min-h-screen flex-col">
        <Header />

        <div className="mx-auto w-full max-w-7xl flex-1 px-4 py-6">
          {/* mobile header */}
          <div className="mb-4 flex items-center justify-between md:hidden">
            <h1 className="text-xl font-bold text-emerald-900">Dashboard</h1>
            <div className="w-[92px]" />
          </div>

          <div className="grid grid-cols-1 gap-4 md:grid-cols-[320px_1fr] lg:grid-cols-[360px_1fr]">
            {/* SIDEBAR */}
            <aside className="hidden md:block rounded-2xl border border-emerald-100 bg-white/80 shadow-sm backdrop-blur-md p-4">
              <SidebarMenu
                data={[
                  {
                    header: null,
                    items: [
                      { key: "forms", label: "ข้อมูลฟอร์มที่ส่งเข้ามา", icon: "📝" },
                      { key: "emergencies", label: "การแจ้งเหตุ", icon: "🚨" },
                    ],
                  },
                ]}
                activeKey={tab}
                onSelect={(k) => setTab(k as "forms" | "emergencies")}
                wide
              />
            </aside>

            {/* CONTENT */}
            <main className="min-w-0">
              <div className="mb-4 hidden items-center justify-between md:flex">
                <h1 className="text-2xl font-bold text-emerald-900">Dashboard</h1>
                <div className="w-[104px]" />
              </div>

              {loading && <Panel>กำลังโหลด...</Panel>}
              {!loading && !user && <Panel tone="error">กรุณาเข้าสู่ระบบเพื่อดูข้อมูล</Panel>}

              {/* FORMS */}
              {!loading && user && tab === "forms" && (
                <>
                  <h2 className="mb-2 text-xl font-semibold text-emerald-900">ข้อมูลฟอร์มที่ส่งเข้ามา</h2>
                  <FormsToolbar mode={formMode} setMode={setFormMode} query={formQuery} setQuery={setFormQuery} />
                  {formsBusy && <Panel>กำลังโหลดข้อมูล...</Panel>}
                  {formsErr && <Panel tone="error">เกิดข้อผิดพลาด: {formsErr}</Panel>}
                  {!formsBusy && !formsErr && <FormsTable rows={filteredForms} onDownload={downloadFormAsPdf} />}
                </>
              )}

              {/* EMERGENCIES */}
              {!loading && user && tab === "emergencies" && (
                <>
                  <h2 className="mb-2 text-xl font-semibold text-emerald-900">การแจ้งเหตุ</h2>
                  <EmergenciesToolbar mode={emMode} setMode={setEmMode} query={emQuery} setQuery={setEmQuery} />
                  {emBusy && <Panel>กำลังโหลดข้อมูล...</Panel>}
                  {emErr && <Panel tone="error">เกิดข้อผิดพลาด: {emErr}</Panel>}
                  {!emBusy && !emErr && <EmergenciesTable rows={filteredEmergencies} />}
                </>
              )}
            </main>
          </div>
        </div>

        <Footer />
      </div>
    </div>
  );
}
