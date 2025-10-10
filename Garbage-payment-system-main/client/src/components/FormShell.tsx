import { ReactNode } from "react";
import { Send } from "lucide-react";

type Props = {
  title: string;
  bannerSrc: string;         // รูปหัวฟอร์ม (เช่น @assets/แบบฟอร์มคำร้องทั่วไป(แจ้งถนนชำรุด).png)
  children: ReactNode;       // เนื้อหาฟิลด์ของฟอร์ม
  onSubmit: (e: React.FormEvent<HTMLFormElement>) => void;
  sending?: boolean;
  okMsg?: string | null;
  errMsg?: string | null;
  submitText?: string;       // เปลี่ยนข้อความปุ่มได้ (default: ส่งคำขอ/ส่งคำร้อง/ส่งข้อมูล)
};

export default function FormShell({
  title, bannerSrc, children, onSubmit, sending, okMsg, errMsg, submitText,
}: Props) {
  return (
    <form
      onSubmit={onSubmit}
      className="mx-auto max-w-5xl rounded-3xl border border-emerald-200 bg-white/95 p-5 shadow-lg text-emerald-900"
    >
      {/* หัวเรื่อง + แบนเนอร์ */}
      <div className="mb-3 text-center">
        <h1 className="text-2xl font-extrabold text-emerald-900 md:text-3xl">
          {title}
        </h1>
      </div>
      <div className="mb-5">
        <img
          src={bannerSrc}
          alt="ขั้นตอนและแนวทางปฏิบัติ"
          className="w-full rounded-2xl shadow-md ring-1 ring-emerald-100"
        />
      </div>

      {/* สถานะ */}
      {errMsg && (
        <div className="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
          {errMsg}
        </div>
      )}
      {okMsg && (
        <div className="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
          {okMsg}
        </div>
      )}

      {/* เนื้อหาฟิลด์ */}
      <div className="space-y-6">{children}</div>

      {/* ปุ่มส่ง */}
      <div className="mt-6">
        <button
          type="submit"
          disabled={!!sending}
          className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 font-medium text-white shadow hover:bg-emerald-800 disabled:opacity-60"
        >
          <Send className="h-5 w-5" />
          {sending ? "กำลังส่ง..." : (submitText ?? "ส่งคำขอ")}
        </button>
      </div>
    </form>
  );
}
