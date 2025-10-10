// src/pages/emergency.tsx
import { useEffect, useState } from "react";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { useLocation } from "wouter";
import { useMutation } from "@tanstack/react-query";
import "leaflet/dist/leaflet.css";
import { MapContainer, TileLayer, Marker, Popup, useMap, useMapEvents } from "react-leaflet";
import L from "leaflet";

// assets
import bgCity from "@assets/Car StatusBody-BG.jpg";
import backPng from "@assets/Back-Button.png";
import userPinPng from "@assets/Car StatusIcon-2.png"; // = ตำแหน่งของคุณ

type Coords = { lat: number; lng: number };
const DEFAULT_CENTER: Coords = { lat: 13.6105, lng: 100.9775 };
const DEFAULT_ZOOM = 14;

const userIcon = L.icon({
  iconUrl: userPinPng,
  iconSize: [12, 18],
  iconAnchor: [9, 17],
  popupAnchor: [0, -14],
});

function FlyTo({ center }: { center: Coords }) {
  const map = useMap();
  useEffect(() => {
    map.flyTo([center.lat, center.lng], Math.max(map.getZoom(), DEFAULT_ZOOM), { duration: 0.8 });
  }, [center, map]);
  return null;
}

function ClickToMark({ onPick }: { onPick: (c: Coords) => void }) {
  useMapEvents({
    click(e) {
      onPick({ lat: e.latlng.lat, lng: e.latlng.lng });
    },
  });
  return null;
}

const toCategory = (label: string): "tree" | "fire" | "accident" | "general" => {
  if (label === "เหตุต้นไม้ล้ม") return "tree";
  if (label === "เหตุไฟไหม้") return "fire";
  if (label === "อุบัติเหตุ") return "accident";
  // "เหตุฉุกเฉิน" หรือ "เหตุทั่วไป"
  return "general";
};

export default function EmergencyPage() {
  const [, nav] = useLocation();
  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

  const [base, setBase] = useState<"roadmap" | "satellite">("roadmap");
  const [center, setCenter] = useState<Coords>(DEFAULT_CENTER);
  const [myPos, setMyPos] = useState<Coords | null>(null);
  const [isFull, setIsFull] = useState(false);

  const [form, setForm] = useState({
    // ค่าตั้งต้น: ให้สอดคล้องกับตัวเลือกใน select
    type: "เหตุฉุกเฉิน" as "เหตุต้นไม้ล้ม" | "เหตุไฟไหม้" | "เหตุฉุกเฉิน" ,
    title: "",
    reporterName: "",
    phone: "",
    description: "",
    lat: undefined as number | undefined,
    lng: undefined as number | undefined,
    photoUrl: undefined as string | undefined,
  });

  // preview ของรูป
  const [photoFile, setPhotoFile] = useState<File | null>(null);
  const [photoPreview, setPhotoPreview] = useState<string | null>(null);

  useEffect(() => {
    if (!photoFile) return;
    const url = URL.createObjectURL(photoFile);
    setPhotoPreview(url);
    return () => URL.revokeObjectURL(url);
  }, [photoFile]);

const mutation = useMutation({
  mutationFn: async () => {
    const fd = new FormData();
    const cat = toCategory(form.type);

    // ✅ สร้าง title ถ้าเว้นว่าง
    const safeTitle =
      form.title && form.title.trim()
        ? form.title.trim()
        : `การแจ้งเหตุ - ${form.type} - ${new Date().toLocaleString()}`;

    fd.append("category", cat);
    fd.append("title", safeTitle); // ← ใส่เสมอ

    if (form.reporterName) fd.append("reporterName", form.reporterName);
    if (form.phone) fd.append("phone", form.phone);
    if (form.description) fd.append("description", form.description);
    if (form.lat != null) fd.append("lat", String(form.lat));
    if (form.lng != null) fd.append("lng", String(form.lng));
    if (photoFile) fd.append("photo", photoFile);
    else if (form.photoUrl) fd.append("photoUrl", form.photoUrl);

    const res = await fetch("/api/emergencies", { method: "POST", body: fd });
    if (!res.ok) {
      let detail = "";
      try { const j = await res.json(); detail = j?.error || JSON.stringify(j); }
      catch { detail = await res.text(); }
      throw new Error(`HTTP ${res.status} — ${detail || "ส่งข้อมูลไม่สำเร็จ"}`);
    }
    return (await res.json()) as { ok: boolean; id: number };
  },
  onSuccess: ({ id }) => {
    alert(`ส่งแจ้งเหตุสำเร็จ เลขที่อ้างอิง: ${id}`);
    setForm({
      type: "เหตุฉุกเฉิน",
      title: "",
      reporterName: "",
      phone: "",
      description: "",
      lat: undefined,
      lng: undefined,
      photoUrl: undefined,
    });
    setPhotoFile(null);
    setPhotoPreview(null);
    setMyPos(null);
    setCenter(DEFAULT_CENTER);
  },
  onError: (e: any) => {
    alert(`ส่งไม่สำเร็จ: ${e?.message ?? e}`);
  },
});

  const locateMe = () => {
    if (!("geolocation" in navigator)) {
      alert("อุปกรณ์นี้ไม่รองรับการระบุตำแหน่ง");
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const c = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        setMyPos(c);
        setCenter(c);
        setForm((s) => ({ ...s, lat: c.lat, lng: c.lng }));
      },
      (e) => alert(e.message || "ไม่สามารถดึงตำแหน่งได้"),
      { enableHighAccuracy: true, timeout: 10000 }
    );
  };

  const tileUrl =
    base === "satellite"
      ? "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"
      : "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";

  const MapBlock = ({ full = false }: { full?: boolean }) => (
    <div className={`relative ${full ? "w-full h-full" : "rounded-2xl overflow-hidden"}`}>
      {/* แท็บ แผนที่/ดาวเทียม */}
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

      {/* ปุ่มขยาย/ย่อ แบบกรอบ */}
      <div className="absolute right-3 top-3 z-[1000]">
        <button
          onClick={() => setIsFull((v) => !v)}
          aria-label={isFull ? "ย่อแผนที่" : "ขยายแผนที่"}
          className="h-10 w-10 rounded-xl bg-white/95 hover:bg-white shadow-md grid place-items-center"
        >
          {isFull ? (
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M9 3H3v6" /><path d="M3 3l7 7" />
              <path d="M15 21h6v-6" /><path d="M21 21l-7-7" />
            </svg>
          ) : (
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M3 9V3h6" /><path d="M3 3l7 7" />
              <path d="M21 15v6h-6" /><path d="M21 21l-7-7" />
            </svg>
          )}
        </button>
      </div>

      {/* ปุ่มตำแหน่งฉัน */}
      <div className="absolute left-3 bottom-3 z-[1000]">
        <button onClick={locateMe} className="rounded-full bg-white/95 backdrop-blur shadow px-3 py-2 text-sm font-medium hover:bg-white">
          ไปยังตำแหน่งฉัน
        </button>
      </div>

      <MapContainer
        center={[center.lat, center.lng]}
        zoom={DEFAULT_ZOOM}
        scrollWheelZoom
        zoomControl={false}
        attributionControl={false}
        className={full ? "w-full h-full" : "w-full h-[420px] md:h-[560px]"}
      >
        <FlyTo center={center} />
        <TileLayer attribution="" url={tileUrl} />
        <ClickToMark
          onPick={(c) => {
            setMyPos(c);
            setCenter(c);
            setForm((s) => ({ ...s, lat: c.lat, lng: c.lng }));
          }}
        />

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

      {/* ตัวหน้า (ซ้ายฟอร์ม / ขวาแผนที่) */}
      <main className="mx-auto w-full max-w-7xl flex-1 px-3 md:px-6 pb-10 pt-4">
        <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
          {/* ซ้าย: ฟอร์ม */}
          <section className="rounded-2xl border border-emerald-200/60 bg-white/90 shadow-sm backdrop-blur">
            {/* แถบเลือกเหตุ */}
            <div className="px-4 pt-4">
              <label className="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-3 py-2 text-white shadow">
                <span className="text-sm">เลือกเหตุที่ต้องการแจ้ง</span>
                <select
                  className="rounded-md bg-white/95 px-2 py-1 text-gray-800 outline-none"
                  value={form.type}
                  onChange={(e) => setForm((s) => ({ ...s, type: e.target.value as any }))}
                >
                  <option>เหตุต้นไม้ล้ม</option>
                  <option>เหตุไฟไหม้</option>
                  <option>เหตุฉุกเฉิน</option>
                </select>
              </label>
            </div>

            <form
              className="space-y-4 p-4"
              onSubmit={(e) => {
                e.preventDefault();
                mutation.mutate();
              }}
            >
              {/* อัปโหลดรูปภาพ (มี Preview) */}
              <div className="rounded-2xl border-2 border-dashed border-emerald-200/80 bg-white p-4">
                {!photoPreview ? (
                  <label className="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-xl py-8 text-emerald-700">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8">
                      <rect x="3" y="3" width="18" height="18" rx="2" />
                      <path d="M3 16l5-5 4 4 2-2 7 7" />
                      <circle cx="9" cy="8" r="2" />
                    </svg>
                    <div className="text-sm">เลือกรูปภาพของคุณ</div>
                    <input
                      type="file"
                      accept="image/*"
                      className="hidden"
                      onChange={(e) => {
                        const f = e.target.files?.[0];
                        if (!f) return;
                        setPhotoFile(f);
                      }}
                    />
                  </label>
                ) : (
                  <div className="space-y-3">
                    <div className="relative aspect-video w-full overflow-hidden rounded-xl border border-emerald-100">
                      <img src={photoPreview} alt="รูปที่เลือก" className="h-full w-full object-cover" />
                    </div>
                    <div className="flex items-center justify-center gap-3">
                      <label className="rounded-xl bg-emerald-600 px-4 py-2 text-white shadow hover:bg-emerald-700 cursor-pointer">
                        เปลี่ยนรูป
                        <input
                          type="file"
                          accept="image/*"
                          className="hidden"
                          onChange={(e) => {
                            const f = e.target.files?.[0];
                            if (!f) return;
                            setPhotoFile(f);
                          }}
                        />
                      </label>
                      <button
                        type="button"
                        onClick={() => {
                          setPhotoFile(null);
                          setPhotoPreview(null);
                        }}
                        className="rounded-xl bg-gray-100 px-4 py-2 text-gray-700 shadow hover:bg-gray-200"
                      >
                        ลบรูป
                      </button>
                    </div>
                  </div>
                )}
              </div>

              {/* ชื่อ + เบอร์ */}
              <input
                type="text"
                placeholder="ชื่อผู้แจ้งเหตุ"
                className="w-full rounded-xl border border-emerald-200/80 bg-white px-3 py-2 outline-none ring-emerald-500/30 focus:ring"
                value={form.reporterName}
                onChange={(e) => setForm((s) => ({ ...s, reporterName: e.target.value }))}
              />
              <input
                type="tel"
                placeholder="เบอร์โทรศัพท์ที่ติดต่อได้"
                className="w-full rounded-xl border border-emerald-200/80 bg-white px-3 py-2 outline-none ring-emerald-500/30 focus:ring"
                value={form.phone}
                onChange={(e) => setForm((s) => ({ ...s, phone: e.target.value }))}
              />

              {/* รายละเอียด */}
              <textarea
                rows={6}
                placeholder="รายละเอียด"
                className="w-full resize-y rounded-xl border border-emerald-200/80 bg-white px-3 py-2 outline-none ring-emerald-500/30 focus:ring"
                value={form.description}
                onChange={(e) => setForm((s) => ({ ...s, description: e.target.value }))}
              />

              {/* ส่ง */}
              <div className="flex items-center justify-center">
                <button
                  type="submit"
                  disabled={mutation.isPending}
                  className="rounded-2xl bg-red-600 px-6 py-3 text-lg font-semibold text-white shadow hover:bg-red-700 disabled:opacity-60"
                >
                  {mutation.isPending ? "กำลังส่ง..." : "คลิกเพื่อแจ้งเหตุ"}
                </button>
              </div>
            </form>
          </section>

          {/* ขวา: แผนที่ */}
          <section className="p-0">
            <MapBlock />
            {/* legend นอกแมพ ด้านล่างขวา */}
            <div className="mt-2 flex justify-end pr-2">
              <div className="flex items-center gap-2 rounded-full bg-white/95 px-3 py-1.5 shadow">
                <img src={userPinPng} alt="" className="h-4 w-4" />
                <span className="text-sm text-gray-800">= ตำแหน่งของคุณ</span>
              </div>
            </div>
          </section>
        </div>
      </main>

      <Footer />

      {/* แผนที่เต็มหน้าจอ */}
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
