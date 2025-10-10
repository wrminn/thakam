// src/pages/HazardDumps.tsx
import { useEffect, useMemo, useState } from "react";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { MapContainer, TileLayer, Marker, Popup, useMap, useMapEvents } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import { useLocation } from "wouter";
import L from "leaflet";

// ===== assets (ปรับ path ให้ตรงโปรเจคคุณ) =====
import bgCity from "@assets/Car StatusBody-BG.jpg";
import backPng from "@assets/Back-Button.png";
import toxicBanner1 from "@assets/Toxic Trash Banner-1.png";
import toxicBanner2 from "@assets/Toxic Trash Banner-2.png";
import banner3 from "@assets/Car StatusBanner-3.png";
import userPinPng from "@assets/Car StatusIcon-2.png";
import toxicIcon1 from "@assets/Toxic Trash Icon-1.png";

// ===== types =====
type Coords = { lat: number; lng: number };
type Bin = { id: number | string; lat: number; lng: number; note?: string };

const DEFAULT_CENTER: Coords = { lat: 13.6105, lng: 100.9775 };
const DEFAULT_ZOOM = 14;

const userIcon = L.icon({
  iconUrl: userPinPng,
  iconSize: [12, 18],
  iconAnchor: [9, 17],
  popupAnchor: [0, -14],
});
const toxicBinIcon = L.icon({
  iconUrl: toxicIcon1,
  iconSize: [22, 22],
  iconAnchor: [11, 21],
  popupAnchor: [0, -14],
});

// ====== AUTH HELPERS (แนวเดียวกับ ServiceRequest.tsx) ======
function lower(v: any) {
  return typeof v === "string" ? v.toLowerCase().trim() : null;
}
function getCurrentUserEmailSync(): string | null {
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
      const e = lower(v);
      if (e && e.includes("@")) return e;
    }
    return null;
  };

  for (const url of endpoints) {
    try {
      const res = await fetch(url, { credentials: "include" });
      if (!res.ok) continue;

      const hdr = res.headers.get("x-user-email") || res.headers.get("x-auth-email");
      if (hdr && hdr.includes("@")) return lower(hdr)!;

      const j = await res.json().catch(() => null);
      const e = j ? extractEmail(j) : null;
      if (e) return e;
    } catch {}
  }
  return null;
}

// ===== API base & helpers (Sanctum + XSRF, รองรับ fallback ชื่อฟิลด์) =====
const API_BASE: string = (window as any).__API_BASE?.toString() || "";
function readCookie(name: string) {
  const m = document.cookie.match(
    new RegExp("(?:^|; )" + name.replace(/([.$?*|{}()[\]\\/+^])/g, "\\$1") + "=([^;]*)")
  );
  return m ? decodeURIComponent(m[1]) : null;
}
async function getCsrfCookie() {
  try {
    // สำคัญ: ต้องเป็นโดเมน/พอร์ตเดียวกับ backend
    await fetch(`${API_BASE}/sanctum/csrf-cookie`, { credentials: "include" });
  } catch (e) {
    console.warn("getCsrfCookie failed:", e);
  }
}
type ApiOptions = RequestInit & { raw?: boolean };
async function apiFetch(path: string, opts: ApiOptions = {}) {
  const url = `${API_BASE}${path}`;
  const method = (opts.method || "GET").toUpperCase();
  const isWrite = method !== "GET" && method !== "HEAD";

  // base headers
  const baseHeaders: Record<string, string> = { Accept: "application/json" };
  const headers: HeadersInit =
    opts.headers instanceof Headers ? opts.headers : { ...baseHeaders, ...(opts.headers || {}) };

  // เขียนข้อมูล → ขอ csrf-cookie ล่วงหน้า + แนบ X-XSRF-TOKEN
  if (isWrite) {
    await getCsrfCookie();
    const xsrf = readCookie("XSRF-TOKEN");
    if (xsrf) (headers as any)["X-XSRF-TOKEN"] = xsrf;
  }

  const res = await fetch(url, { credentials: "include", ...opts, headers });

  const ct = res.headers.get("content-type") || "";
  let body: any = null;
  try {
    body = ct.includes("application/json") ? await res.json() : await res.text();
  } catch {}

  if (!res.ok) {
    const msg = `[${res.status}] ${res.statusText} ${
      typeof body === "string" ? body.slice(0, 300) : JSON.stringify(body).slice(0, 300)
    }`;
    throw new Error(msg);
  }
  return body;
}

// ===== Map helpers =====
function FlyTo({ center }: { center: Coords }) {
  const map = useMap();
  useEffect(() => {
    map.flyTo([center.lat, center.lng], Math.max(map.getZoom(), DEFAULT_ZOOM), { duration: 0.8 });
  }, [center, map]);
  return null;
}
function ClickToAdd({ onAdd, enabled }: { onAdd: (lat: number, lng: number) => void; enabled: boolean }) {
  useMapEvents({
    click(e) {
      if (!enabled) return;
      const { lat, lng } = e.latlng;
      if (confirm(`ปักหมุดใหม่ที่นี่?\nlat: ${lat.toFixed(6)}, lng: ${lng.toFixed(6)}`)) onAdd(lat, lng);
    },
  });
  return null;
}

export default function HazardDumpsPage() {
  const [, nav] = useLocation();

  // ===== auth =====
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

  const isAdmin = useMemo(() => currentEmail === "admin04@example.com", [currentEmail]);

  // ===== map/ui =====
  const [myPos, setMyPos] = useState<Coords | null>(null);
  const [center, setCenter] = useState<Coords>(DEFAULT_CENTER);
  const [base, setBase] = useState<"roadmap" | "satellite">("roadmap");
  const [locErr, setLocErr] = useState<string | null>(null);
  const [isFull, setIsFull] = useState(false);

  // ===== data =====
  const [bins, setBins] = useState<Bin[]>([]);
  const [loading, setLoading] = useState(false);

  // ดึงรายการหมุด
  const fetchBins = async () => {
    try {
      const data = await apiFetch(`/api/hazard-bins`, { method: "GET" });
      const arr = Array.isArray(data) ? data : Array.isArray(data?.data) ? data.data : [];
      setBins(arr);
    } catch (e: any) {
      console.error("fetchBins:", e?.message || e);
    }
  };

  // เพิ่มหมุด (รองรับ 3 รูปแบบ)
  const addBin = async (lat: number, lng: number) => {
    if (!isAdmin) return;
    setLoading(true);
    try {
      // A) JSON: { lat, lng }
      try {
        await apiFetch(`/api/hazard-bins`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ lat, lng, note: "ปักโดย admin" }),
        });
      } catch (e: any) {
        // B) JSON: { latitude, longitude }
        if (String(e?.message || "").includes("[400]") || String(e?.message || "").includes("[422]")) {
          try {
            await apiFetch(`/api/hazard-bins`, {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ latitude: lat, longitude: lng, note: "ปักโดย admin" }),
            });
          } catch {
            // C) multipart/form-data (รองรับทั้งสองชื่อ)
            const fd = new FormData();
            fd.append("lat", String(lat));
            fd.append("lng", String(lng));
            fd.append("latitude", String(lat));
            fd.append("longitude", String(lng));
            fd.append("note", "ปักโดย admin");
            await apiFetch(`/api/hazard-bins`, { method: "POST", body: fd });
          }
        } else {
          throw e;
        }
      }
      await fetchBins();
    } catch (e) {
      console.error("addBin:", e);
      alert("เพิ่มไม่สำเร็จ");
    } finally {
      setLoading(false);
    }
  };

  // อัปเดตพิกัด (ลากหมุด)
  const updateBin = async (id: number | string, lat: number, lng: number) => {
    if (!isAdmin) return;
    try {
      // A) JSON lat/lng
      try {
        await apiFetch(`/api/hazard-bins/${id}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ lat, lng }),
        });
      } catch (e: any) {
        // B) JSON latitude/longitude
        if (String(e?.message || "").includes("[400]") || String(e?.message || "").includes("[422]")) {
          await apiFetch(`/api/hazard-bins/${id}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ latitude: lat, longitude: lng }),
          });
        } else {
          throw e;
        }
      }
    } catch (e) {
      console.error("updateBin:", e);
      alert("อัปเดตตำแหน่งไม่สำเร็จ");
      await fetchBins();
    }
  };

  // ลบหมุด
  const deleteBin = async (id: number | string) => {
    if (!isAdmin) return;
    if (!confirm("ลบหมุดนี้จริงหรือไม่?")) return;
    setLoading(true);
    try {
      await apiFetch(`/api/hazard-bins/${id}`, { method: "DELETE" });
      await fetchBins();
    } catch (e) {
      console.error("deleteBin:", e);
      alert("ลบไม่สำเร็จ");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchBins();
  }, []);

  const [, navigate] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : navigate("/"));
  const locateMe = () => {
    setLocErr(null);
    if (!("geolocation" in navigator)) {
      setLocErr("อุปกรณ์นี้ไม่รองรับการระบุตำแหน่ง");
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const c = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        setMyPos(c);
        setCenter(c);
      },
      (e) => setLocErr(e.message || "ไม่สามารถดึงตำแหน่งได้"),
      { enableHighAccuracy: true, timeout: 10000 }
    );
  };

  const tileUrl =
    base === "satellite"
      ? "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"
      : "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";

  // ===== Map block =====
  const MapBlock = ({ full = false }: { full?: boolean }) => (
    <div className={`relative ${full ? "w-full h-full" : "rounded-2xl overflow-hidden border border-emerald-100 shadow-lg"}`}>
      {/* controls */}
      <div className="absolute left-3 top-3 z-[1000]">
        <div className="bg-white/95 backdrop-blur rounded-full shadow px-2 py-1 flex gap-1">
          <button
            onClick={() => setBase("roadmap")}
            className={`px-3 py-1.5 rounded-full text-sm font-medium ${base === "roadmap" ? "bg-emerald-700 text-white" : "text-gray-700"}`}
          >
            แผนที่
          </button>
          <button
            onClick={() => setBase("satellite")}
            className={`px-3 py-1.5 rounded-full text-sm font-medium ${base === "satellite" ? "bg-emerald-700 text-white" : "text-gray-700"}`}
          >
            ดาวเทียม
          </button>
        </div>
      </div>

      <div className="absolute left-3 bottom-3 z-[1000] flex gap-2">
        <button
          onClick={locateMe}
          title="ไปยังตำแหน่งของฉัน"
          className="rounded-full bg-white/95 backdrop-blur shadow px-3 py-2 text-sm font-medium hover:bg-white"
        >
          ไปยังตำแหน่งฉัน
        </button>
        {isAdmin && (
          <button
            onClick={() => fetchBins()}
            disabled={loading}
            className="rounded-full bg-emerald-600 text-white shadow px-3 py-2 text-sm font-medium hover:bg-emerald-700 disabled:opacity-60"
            title="รีโหลดหมุด"
          >
            รีโหลด
          </button>
        )}
      </div>

      <div className="absolute right-3 top-3 z-[1000]">
        <button
          onClick={() => setIsFull((v) => !v)}
          aria-label={isFull ? "ย่อแผนที่" : "ขยายแผนที่"}
          className="h-10 w-10 rounded-xl bg-white/95 hover:bg-white shadow-md grid place-items-center"
        >
          {isFull ? "−" : "+"}
        </button>
      </div>

      <MapContainer
        center={[center.lat, center.lng]}
        zoom={DEFAULT_ZOOM}
        scrollWheelZoom
        zoomControl={false}
        attributionControl={false}
        className={full ? "w-full h-full" : "w-full h-[400px] md:h-[550px]"}
      >
        <FlyTo center={center} />
        <TileLayer attribution="" url={tileUrl} />
        <ClickToAdd onAdd={addBin} enabled={isAdmin} />

        {bins.map((p) => (
          <Marker
            key={p.id}
            position={[p.lat, p.lng]}
            icon={toxicBinIcon}
            // ====== ปัด/ลากหมุดได้สำหรับแอดมิน ======
            draggable={isAdmin}
            eventHandlers={{
              dragend: (e) => {
                if (!isAdmin) return;
                const m = e.target as L.Marker;
                const { lat, lng } = m.getLatLng();
                // อัปเดตใน state ให้ลื่นก่อน
                setBins((prev) =>
                  prev.map((b) => (b.id === p.id ? { ...b, lat, lng } : b))
                );
                // ยิง PUT ไปบันทึกจริง
                updateBin(p.id, lat, lng);
              },
            }}
          >
            <Popup>
              <div className="min-w-[180px]">
                <div className="font-medium">ถังขยะ (ID: {p.id})</div>
                {p.note && <div className="text-sm text-gray-600">{p.note}</div>}
                {isAdmin ? (
                  <>
                    <div className="text-xs text-gray-600 mt-1">
                      lat: {p.lat.toFixed(6)}, lng: {p.lng.toFixed(6)}
                    </div>
                    <div className="mt-2 flex gap-2">
                      <button
                        className="px-2 py-1 rounded bg-red-500 text-white text-xs"
                        onClick={() => deleteBin(p.id)}
                      >
                        ลบหมุด
                      </button>
                      <span className="text-xs text-gray-500 self-center">ลากหมุดเพื่อย้าย</span>
                    </div>
                  </>
                ) : (
                  <div className="mt-2 text-xs text-gray-500">ข้อมูลจากระบบ</div>
                )}
              </div>
            </Popup>
          </Marker>
        ))}

        {myPos && (
          <Marker position={[myPos.lat, myPos.lng]} icon={userIcon}>
            <Popup>ตำแหน่งของคุณ</Popup>
          </Marker>
        )}
      </MapContainer>
    </div>
  );

  return (
    <div
      className="min-h-screen flex flex-col"
      style={{
        backgroundImage: `url(${bgCity})`,
        backgroundSize: "cover",
        backgroundPosition: "center top",
        backgroundRepeat: "no-repeat",
      }}
    >
      <Header />

      {/* back button */}
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

      <main className="w-full max-w-7xl mx-auto flex-1 px-3 md:px-6 pb-8">
        <div className="grid grid-cols-1 lg:grid-cols-[minmax(340px,520px),1fr] gap-6 md:gap-8 mt-2 md:mt-4 items-start">
          {/* ซ้าย: แบนเนอร์/ข้อมูล */}
          <section className="space-y-3 md:space-y-4">
            <img src={toxicBanner1} alt="จุดถังขยะมีพิษ" className="w-full h-auto max-h-[380px] object-contain" />
            <section className="space-y-3 md:space-y-4 pr-3 md:pr-12">
              <img src={toxicBanner2} alt="= ถังขยะ" className="block ml-auto w-[86%] md:w-[60%] h-auto object-contain" />
              <img src={banner3} alt="= ตำแหน่งของคุณ" className="block ml-auto w-[86%] md:w-[80%] h-auto object-contain" />
            </section>

            {/* แสดงสถานะ “เฉพาะแอดมินเท่านั้น” */}
            {isAdmin && (
              <div className="rounded-xl border bg-white/90 px-3 py-2 text-sm">
                <div className="font-medium mb-1">สถานะผู้ใช้</div>
                <div className="text-gray-700">
                  ลงชื่อเข้าใช้เป็น <span className="font-semibold">{currentEmail ?? "unknown"}</span>{" "}
                  <span className="ml-1 inline-block rounded bg-emerald-600 text-white px-2 py-0.5 text-xs">ADMIN</span>
                </div>
                <ul className="mt-2 text-xs text-gray-600 list-disc list-inside">
                  <li>คลิกบนแผนที่เพื่อปักหมุดใหม่</li>
                  <li>ลากหมุดเพื่อย้ายตำแหน่ง (บันทึกอัตโนมัติ)</li>
                  <li>กดที่หมุดเพื่อเปิด Popup และลบหมุด</li>
                  <li>ปุ่ม “รีโหลด” มุมซ้ายล่างของแผนที่เพื่อดึงข้อมูลล่าสุด</li>
                </ul>
              </div>
            )}
          </section>

          {/* ขวา: แผนที่ + แผงรายการ (เฉพาะ admin จะเห็น) */}
          <section className="p-0 space-y-4">
            <MapBlock />

            {isAdmin && (
              <div className="rounded-xl border p-3 bg-white/90">
                <div className="flex items-center justify-between mb-2">
                  <div className="font-medium">รายการถังขยะทั้งหมด ({bins.length})</div>
                  <button
                    className="px-3 py-1.5 rounded bg-emerald-600 text-white text-sm"
                    onClick={() => fetchBins()}
                    disabled={loading}
                  >
                    รีโหลด
                  </button>
                </div>
                <div className="max-h-[320px] overflow-auto divide-y">
                  {bins.map((b) => (
                    <div key={b.id} className="py-2 flex items-center justify-between">
                      <div>
                        <div className="text-sm">ID: {b.id}</div>
                        <div className="text-xs text-gray-600">
                          ({b.lat.toFixed(6)}, {b.lng.toFixed(6)}) {b.note ? `— ${b.note}` : ""}
                        </div>
                      </div>
                      <div className="flex gap-2">
                        <button className="text-xs px-2 py-1 rounded bg-red-500 text-white" onClick={() => deleteBin(b.id)}>
                          ลบ
                        </button>
                      </div>
                    </div>
                  ))}
                  {bins.length === 0 && <div className="py-6 text-center text-sm text-gray-500">ยังไม่มีหมุด</div>}
                </div>
              </div>
            )}
          </section>
        </div>
      </main>

      <Footer />

      {isFull && (
        <div className="fixed inset-0 z-[2000] bg-black/60 backdrop-blur-sm">
          <div className="absolute inset-4 md:inset-8 rounded-2xl overflow-hidden bg-white shadow-2xl">
            <MapBlock full />
          </div>
        </div>
      )}
    </div>
  );
}
