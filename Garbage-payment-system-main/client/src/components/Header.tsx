import { Link } from "wouter";
import { useAuth } from "@/hooks/useAuth";

import logoUrl from "@assets/LOGO.png";
import headerBgUrl from "@assets/Header-BG.jpg";
import ossBadgeUrl from "@assets/2.png";
import loginBtnUrl from "@assets/Login.png";
import registerBtnUrl from "@assets/Register.png";
import logoutBtnUrl from "@assets/Logout-Button.png";

export default function Header() {
  const { user, logout } = useAuth();

  return (
    <header className="sticky top-0 z-50">
      {/* BG แบบต่อเนื่องแนวนอน*/}
      <div
        className="w-full border-b border-emerald-800/30 shadow-sm"
        style={{
          backgroundImage: `url(${headerBgUrl})`,
          backgroundRepeat: "repeat-x",     
          backgroundSize: "auto 100%",    
          backgroundPosition: "center top",
        }}
      >
        <div className="mx-auto max-w-7xl px-3 sm:px-4 py-3 sm:py-4">
          <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            {/*ข้อความ */}
            <div className="flex items-center gap-3 sm:gap-4">
              <Link
                href="/"
                aria-label="กลับหน้าแรก"
                title="กลับหน้าแรก"
                className="rounded-full transition-transform duration-200 hover:scale-105 focus:outline-none"
              >
                <img
                  src={logoUrl}
                  alt="ตราเทศบาล"
                   className="h-26 w-32 sm:h-28 sm:w-28 md:h-36 md:w-36 lg:h-40 lg:w-40 rounded-full"
                  draggable={false}
                />
              </Link>

              <div className="flex flex-col text-white drop-shadow-[0_1px_1px_rgba(0,0,0,0.45)]">
                <h1 className="font-extrabold tracking-tight leading-tight text-[24px] sm:text-[32px] md:text-[38px]">
                  เทศบาลตำบลท่าข้าม
                </h1>

                <div className="mt-1 flex flex-wrap items-center gap-2">
                  <span className="text-white/95 text-[15px] sm:text-[16px]">
                    ระบบยื่นคำร้องออนไลน์
                  </span>
                  <img
                    src={ossBadgeUrl}
                    alt="One Stop Service"
                    className="h-5 sm:h-6"
                    draggable={false}
                  />
                </div>
              </div>
            </div>

            {/* ปุ่มด้านขวา */}
            <div className="flex flex-col items-center sm:items-end gap-1.5">
              <div className="flex items-center gap-2">
                {!user ? (
                  <>
                    <Link href="/login" aria-label="เข้าสู่ระบบ" title="เข้าสู่ระบบ">
                      <img
                        src={loginBtnUrl}
                        alt="เข้าสู่ระบบ"
                        className="h-8 sm:h-9 md:h-10 transition-transform duration-200 hover:scale-105 drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"
                        draggable={false}
                      />
                    </Link>
                    <Link href="/register" aria-label="สมัครสมาชิก" title="สมัครสมาชิก">
                      <img
                        src={registerBtnUrl}
                        alt="สมัครสมาชิก"
                        className="h-8 sm:h-9 md:h-10 transition-transform duration-200 hover:scale-105 drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"
                        draggable={false}
                      />
                    </Link>
                  </>
                ) : (
                  <button
                    onClick={logout}
                    aria-label="ออกจากระบบ"
                    title="ออกจากระบบ"
                    className="transition-transform duration-200 hover:scale-105 active:scale-100"
                  >
                    <img
                      src={logoutBtnUrl}
                      alt="ออกจากระบบ"
                      className="h-8 sm:h-9 md:h-10 transition-transform duration-200 hover:scale-105 drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"
                      draggable={false}
                    />
                  </button>
                )}
              </div>

              <div className="px-1 text-center sm:text-right text-[12px] sm:text-[14px] leading-snug text-white/95">
                <span className="italic font-semibold">*</span>คำแนะนำ<span className="italic">*</span> สมัครสมาชิกเพื่อติดตามสถานะการดำเนินการ
              </div>
            </div>
          </div>
        </div>
      </div>

      
    </header>
  );
}
