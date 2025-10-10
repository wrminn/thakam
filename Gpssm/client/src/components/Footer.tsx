import mapIcon from "@assets/Map-icon.png";
import callIcon from "@assets/Call-icon.png";
import footerBg from "@assets/Footer-BG.png";

export default function Footer() {
  return (
    <footer
      className="mt-auto text-white"
      style={{
        backgroundImage: `url(${footerBg})`,
        backgroundRepeat: "repeat-x",     // ต่อภาพพื้นหลังแนวนอน
        backgroundSize: "auto 100%",      // สูงพอดีฟุตเตอร์ แล้วไหลต่อทางขวาซ้าย
        backgroundPosition: "center",
      }}
    >
      <div className="mx-auto max-w-7xl px-3 sm:px-4 py-6 sm:py-8">
        {/* หัวข้อกลาง */}
        <h2 className="text-center font-extrabold tracking-wide text-[18px] sm:text-2xl md:text-3xl">
          เทศบาลตำบลท่าข้าม จังหวัดฉะเชิงเทรา
        </h2>

        {/* บัตรข้อมูลแบบ Pill */}
        <div className="mt-4 sm:mt-5 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
          {/* แผนที่ / ที่อยู่ */}
          <a
            href="https://maps.app.goo.gl/z3xP64k8rzYmN5Qw8"
            target="_blank"
            rel="noreferrer"
            className="inline-flex items-center rounded-full bg-white/35 backdrop-blur-[1px] text-white px-2 sm:px-3 py-1.5 sm:py-2"
          >
            <span className="mr-2 inline-flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-md bg-white/90">
              <img src={mapIcon} alt="" className="h-5 w-5 sm:h-6 sm:w-6" />
            </span>
            <span className="text-[13px] sm:text-[15px] md:text-base">
              122 หมู่ที่ 3 ตำบลท่าข้าม อำเภอบางปะกง จังหวัดฉะเชิงเทรา 24130
            </span>
          </a>

          {/* เบอร์โทร */}
          <div className="inline-flex items-center rounded-full bg-white/35 backdrop-blur-[1px] text-white px-2 sm:px-3 py-1.5 sm:py-2">
            <span className="mr-2 inline-flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-md bg-white/90">
              <img src={callIcon} alt="" className="h-5 w-5 sm:h-6 sm:w-6" />
            </span>
            <span className="text-[13px] sm:text-[15px] md:text-base">
              0-3857-3411-2 ต่อ 144
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
}
