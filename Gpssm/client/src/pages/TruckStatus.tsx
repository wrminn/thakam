import { useEffect, useMemo, useRef, useState } from "react";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { useLocation } from "wouter";

import bgCity from "@assets/Car StatusBody-BG.jpg";
import backPng from "@assets/Back-Button.png";
import banner1 from "@assets/Car StatusBanner-1.png";
import banner2 from "@assets/Car StatusBanner-2.png";
import banner3 from "@assets/Car StatusBanner-3.png";

// ตามไฟล์ของคุณ: user = Icon-2, truck = Icon-1
import userPinPng from "@assets/Car StatusIcon-2.png";
import truckPinPng from "@assets/Car StatusIcon-1.png";

import "leaflet/dist/leaflet.css";
import { MapContainer, TileLayer, Marker, Popup, useMap } from "react-leaflet";
import L from "leaflet";

type Coords = { lat: number; lng: number };

const DEFAULT_CENTER: Coords = { lat: 13.6105, lng: 100.9775 };
const DEFAULT_ZOOM = 14;

// หมุดบนแผนที่ (ขนาดแยกกัน)
const userIcon = L.icon({
  iconUrl: userPinPng,
  iconSize: [12, 18],
  iconAnchor: [9, 17],
  popupAnchor: [0, -14],
});
const truckIcon = L.icon({
  iconUrl: truckPinPng,
  iconSize: [18, 18],
  iconAnchor: [9, 17],
  popupAnchor: [0, -14],
});

function FlyTo({ center }: { center: Coords }) {
  const map = useMap();
  useEffect(() => {
    map.flyTo([center.lat, center.lng], Math.max(map.getZoom(), DEFAULT_ZOOM), {
      duration: 0.8,
    });
  }, [center, map]);
  return null;
}

export default function TruckStatusPage() {
  const [, nav] = useLocation();

  const [myPos, setMyPos] = useState<Coords | null>(null);
  const [center, setCenter] = useState<Coords>(DEFAULT_CENTER);
  const [base, setBase] = useState<"roadmap" | "satellite">("roadmap");
  const [locErr, setLocErr] = useState<string | null>(null);
  const [isFull, setIsFull] = useState(false); // ⬅️ โหมดเต็มหน้าจอ

  const trucks = useMemo<Coords[]>(
    () => [
      { lat: 13.616, lng: 100.975 },
      { lat: 13.606, lng: 100.982 },
      { lat: 13.603, lng: 100.969 },
      { lat: 13.612, lng: 100.960 },
      { lat: 13.620, lng: 100.987 },
      { lat: 13.598, lng: 100.978 },
    ],
    []
  );

  const goBack = () => (history.length > 1 ? history.back() : nav("/"));

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

  // --------- บล็อกแผนที่นำกลับใช้ซ้ำได้ (ปกติ / เต็มจอ) ----------
  const MapBlock = ({ full = false }: { full?: boolean }) => (
    <div className={`relative ${full ? "w-full h-full" : "rounded-2xl overflow-hidden border border-emerald-100 shadow-lg"}`}>
      {/* สวิตช์ แผนที่/ดาวเทียม */}
      <div className="absolute left-3 top-3 z-[1000]">
        <div className="bg-white/95 backdrop-blur rounded-full shadow px-2 py-1 flex gap-1">
          <button
            onClick={() => setBase("roadmap")}
            className={`px-3 py-1.5 rounded-full text-sm font-medium ${
              base === "roadmap" ? "bg-emerald-700 text-white" : "text-gray-700"
            }`}
          >
            แผนที่
          </button>
          <button
            onClick={() => setBase("satellite")}
            className={`px-3 py-1.5 rounded-full text-sm font-medium ${
              base === "satellite" ? "bg-emerald-700 text-white" : "text-gray-700"
            }`}
          >
            ดาวเทียม
          </button>
        </div>
      </div>

      {/* ปุ่มไปยังตำแหน่งฉัน */}
      <div className="absolute left-3 bottom-3 z-[1000]">
        <button
          onClick={locateMe}
          title="ไปยังตำแหน่งของฉัน"
          className="rounded-full bg-white/95 backdrop-blur shadow px-3 py-2 text-sm font-medium hover:bg-white"
        >
          ไปยังตำแหน่งฉัน
        </button>
      </div>

      {/* ปุ่มขยาย/ย่อ มุมขวาบน (สไตล์ใหม่) */}
      <div className="absolute right-3 top-3 z-[1000]">
        <button
          onClick={() => setIsFull(v => !v)}
          aria-label={isFull ? "ย่อแผนที่" : "ขยายแผนที่"}
          className="h-10 w-10 rounded-xl bg-white/95 hover:bg-white shadow-md grid place-items-center"
        >
          {/* ไอคอน SVG: ขยาย/ย่อ */}
          {isFull ? (
            // ย่อ (ลูกศรเข้าด้านใน)
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M9 3H3v6" />
              <path d="M3 3l7 7" />
              <path d="M15 21h6v-6" />
              <path d="M21 21l-7-7" />
            </svg>
          ) : (
            // ขยาย (ลูกศรออกมุม)
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M3 9V3h6" />
              <path d="M3 3l7 7" />
              <path d="M21 15v6h-6" />
              <path d="M21 21l-7-7" />
            </svg>
          )}
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

        {trucks.map((t, i) => (
          <Marker key={i} position={[t.lat, t.lng]} icon={truckIcon}>
            <Popup>รถขยะ #{i + 1}</Popup>
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

      {/* ปุ่มย้อนกลับบนสุด */}
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
          {/* ซ้าย: รูปเรียงตามดีไซน์ */}
          <section className="space-y-3 md:space-y-4">
            <img
              src={banner1}
              alt="ดูสถานะรถขยะ"
              className="w-full h-auto max-h-[380px] object-contain"
            />
            <section className="space-y-3 md:space-y-4 pr-3 md:pr-12">
              <img
                src={banner2}
                alt="= รถขยะ"
                className="block ml-auto w-[86%] md:w-[60%] h-auto object-contain"
              />
              <img
                src={banner3}
                alt="= ตำแหน่งของคุณ"
                className="block ml-auto w-[86%] md:w-[80%] h-auto object-contain"
              />
            </section>
            {locErr && (
              <div className="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                {locErr}
              </div>
            )}
          </section>

          {/* ขวา: แผนที่ (ปกติ) */}
          <section className="p-0">
          <MapBlock />
        </section>
        </div>
      </main>

      <Footer />

      {/* โหมดแผนที่เต็มหน้าจอ */}
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
