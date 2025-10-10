import { useState } from "react";
import { Send, Upload } from "lucide-react";
import procedures from "@assets/formsBanner-2.png";

const API_URL = "/api/general-requests"; // เปลี่ยนได้

export default function GeneralRequestForm() {
  const [sending, setSending] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [ok, setOk] = useState<string | null>(null);
  const [err, setErr] = useState<string | null>(null);
  const [files, setFiles] = useState<File[]>([]);
  
  const onFiles = (e: React.ChangeEvent<HTMLInputElement>) => {
    const list = Array.from(e.target.files || []).filter(
      (f) => /(image\/(png|jpe?g)|application\/pdf)/i.test(f.type)
    );
    setFiles(list);
  };

const onSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
  e.preventDefault();
  setErrors({});
  setOk(null);
  setErr(null);

  const form = e.currentTarget;          // ✅ จับตัวฟอร์มไว้ก่อน (กัน null)
  const fd = new FormData(form);

  const need = (k: string, label: string) => {
    if (!fd.get(k)) throw { field: k, message: `กรุณากรอก ${label}` };
  };

  try {
    need("date", "วันที่");
    need("subject", "เรื่อง");
    need("firstName", "ชื่อ");
    need("lastName", "นามสกุล");
    need("age", "อายุ");
    need("addr_province", "จังหวัด");
    need("phone", "เบอร์โทรติดต่อ");
    need("detail", "รายละเอียดคำร้อง");

    // แนบไฟล์จาก state
    files.forEach((f) => fd.append("attachments[]", f));

    setSending(true);
    const res = await fetch(API_URL, { method: "POST", body: fd });

    if (!res.ok) {
      const j = await res.json().catch(() => ({}));
      throw new Error(j?.error ?? "ส่งข้อมูลไม่สำเร็จ");
    }

    setOk("ส่งคำร้องทั่วไปสำเร็จ");
    form.reset();            // ✅ ใช้ตัวแปร form ที่เราจับไว้ ไม่ใช้ e.currentTarget โดยตรง
    setFiles([]);            // ✅ ล้าง state ไฟล์ด้วย (เพราะเป็น controlled)
  } catch (e: any) {
    if (e?.field) setErrors({ [e.field]: e.message });
    else setErr(e?.message ?? String(e));
  } finally {
    setSending(false);
  }
};


 return (
  <div className="mx-auto max-w-5xl">
    {/* กล่องพื้นหลังฟอร์ม: ไล่เฉด teal ตามภาพ */}
    <div className="rounded-[28px] p-2 md:p-3 bg-gradient-to-b from-[#10b3a9] to-[#0b6b75] shadow-xl ring-1 ring-black/10">
      {/* แถบหัว ONLINE SERVICE + ขั้นตอน */}
      <div className="px-3 pt-3">
        <img
          src={procedures}
          alt="ขั้นตอนและแนวทางปฏิบัติ"
          className="w-full rounded-2xl "
        />
      </div>

      {/* การ์ดฟอร์มสีขาวด้านใน */}
      <form
        onSubmit={onSubmit}
        className="m-3 md:m-4 rounded-3xl border border-emerald-200 bg-white/95 p-4 md:p-6 shadow-lg text-emerald-900"
      >
        {/* สถานะ */}
        {err && (
          <div className="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {err}
          </div>
        )}
        {ok && (
          <div className="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
            {ok}
          </div>
        )}

        {/* ฟิลด์ฟอร์มเดิมทั้งหมด (เหมือนของเดิม) */}
        <section className="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label className="block text-sm font-medium">วันที่ *</label>
            <input
              type="date"
              name="date"
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.date ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>
          <div>
            <label className="block text-sm font-medium">เรื่อง *</label>
            <input
              name="subject"
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.subject ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium">สิ่งที่ต้องร้องขอ</label>
            <input
              name="request"
              className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2"
              placeholder="ระบุรายการที่ต้องการให้ดำเนินการ"
            />
          </div>

          <div>
            <label className="block text-sm font-medium">คำนำหน้า</label>
            <select name="prefix" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2">
              <option value="">เลือกคำนำหน้า</option>
              <option>นาย</option>
              <option>นาง</option>
              <option>นางสาว</option>
              <option>อื่น ๆ</option>
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium">ชื่อ - นามสกุล *</label>
            <div className="grid grid-cols-2 gap-2">
              <input
                name="firstName"
                placeholder="ชื่อ"
                className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                  errors.firstName ? "ring-2 ring-red-400" : ""
                }`}
              />
              <input
                name="lastName"
                placeholder="นามสกุล"
                className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                  errors.lastName ? "ring-2 ring-red-400" : ""
                }`}
              />
            </div>
          </div>
          <div>
            <label className="block text-sm font-medium">อายุ (ปี) *</label>
            <input
              name="age"
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.age ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>

          <div>
            <label className="block text-sm font-medium">บ้านเลขที่</label>
            <input name="addr_no" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
          </div>
          <div>
            <label className="block text-sm font-medium">หมู่</label>
            <input name="addr_moo" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
          </div>
          <div>
            <label className="block text-sm font-medium">ตำบล</label>
            <input name="addr_subdistrict" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
          </div>
          <div>
            <label className="block text-sm font-medium">อำเภอ/เขต *</label>
            <input name="addr_district" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
          </div>
          <div>
            <label className="block text-sm font-medium">จังหวัด *</label>
            <input
              name="addr_province"
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.addr_province ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>
          <div>
            <label className="block text-sm font-medium">เบอร์ติดต่อ *</label>
            <input
              name="phone"
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.phone ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium">เรื่องร้องเรียน/ข้อเสนอแนะตามสมควร *</label>
            <textarea
              name="detail"
              rows={5}
              className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${
                errors.detail ? "ring-2 ring-red-400" : ""
              }`}
            />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium">ภาพประกอบ/แผนที่จุดเกิดเหตุ (ถ้ามี)</label>
            <textarea name="map_note" rows={3} className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium">ไฟล์หลักฐาน (jpg, jpeg, png, pdf)</label>
            <div className="mt-2 rounded-xl border border-dashed border-emerald-300 bg-emerald-50/40 p-3">
              <label className="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white px-3 py-2 text-emerald-900 shadow-sm ring-1 ring-emerald-200 hover:bg-emerald-50">
                <Upload className="h-5 w-5" />
                เลือกไฟล์
                <input
                  type="file"
                  name="attachments[]"
                  multiple
                  accept=".jpg,.jpeg,.png,.pdf"
                  className="hidden"
                  onChange={onFiles}
                />
              </label>
              {!!files.length && (
                <ul className="ml-1 mt-2 list-disc space-y-1 pl-4 text-sm text-emerald-900/90">
                  {files.map((f, i) => (
                    <li key={i} className="truncate">
                      {f.name}
                    </li>
                  ))}
                </ul>
              )}
            </div>
          </div>
        </section>

        <div className="mt-6">
          <button
            type="submit"
            disabled={sending}
            className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 font-medium text-white shadow hover:bg-emerald-800 disabled:opacity-60"
          >
            <Send className="h-5 w-5" />
            {sending ? "กำลังส่ง..." : "คลิกเพื่อส่งฟอร์มข้อมูล"}
          </button>
        </div>
      </form>
    </div>
  </div>
);

}
