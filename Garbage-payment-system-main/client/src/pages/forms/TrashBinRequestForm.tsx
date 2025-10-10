import { useState } from "react";
import { Send, Upload, CheckSquare } from "lucide-react";
import procedures from "@assets/คำร้องขอถังขยะ.png"; // ใช้รูปเดียวกับ GeneralRequestForm.tsx

const API_URL = "/api/trash-bin-requests"; // เปลี่ยนได้ภายหลังตาม backend

export default function TrashBinRequestForm() {
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

    const form = e.currentTarget;
    const fd = new FormData(form);

    const need = (k: string, label: string) => {
      if (!fd.get(k)) throw { field: k, message: `กรุณากรอก ${label}` };
    };

    try {
      need("date", "วันที่");
      need("applicant_name", "ชื่อ - นามสกุล");
      need("age", "อายุ");
      need("addr_province", "จังหวัด");
      need("phone", "เบอร์ติดต่อ");
      need("purpose", "ความประสงค์");

      // แนบไฟล์จาก state
      files.forEach((f) => fd.append("attachments[]", f));

      setSending(true);
      const res = await fetch(API_URL, { method: "POST", body: fd });

      if (!res.ok) {
        const j = await res.json().catch(() => ({} as any));
        throw new Error(j?.error ?? "ส่งข้อมูลไม่สำเร็จ");
      }

      setOk("ส่งคำร้องขอถังขยะสำเร็จ");
      form.reset();
      setFiles([]);
    } catch (e: any) {
      if (e?.field) setErrors({ [e.field]: e.message });
      else setErr(e?.message ?? String(e));
    } finally {
      setSending(false);
    }
  };

  return (
    <div className="mx-auto max-w-5xl">
      {/* กล่องพื้นหลังฟอร์ม: ไล่เฉดให้เข้าชุดกับของเดิม */}
      <div className="rounded-[28px] p-2 md:p-3 bg-gradient-to-b from-[#10b3a9] to-[#0b6b75] shadow-xl ring-1 ring-black/10">
        {/* แถบหัว ONLINE SERVICE + ขั้นตอน (ใช้รูปเดียวกับฟอร์มเดิม) */}
        <div className="px-3 pt-3">
          <img src={procedures} alt="ขั้นตอนและแนวทางปฏิบัติ" className="w-full rounded-2xl" />
        </div>

        {/* การ์ดฟอร์ม */}
        <form onSubmit={onSubmit} className="m-3 md:m-4 rounded-3xl border border-emerald-200 bg-white/95 p-4 md:p-6 shadow-lg text-emerald-900">
          {/* สถานะ */}
          {err && (
            <div className="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{err}</div>
          )}
          {ok && (
            <div className="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">{ok}</div>
          )}

       

          {/* ส่วนที่ 1: ข้อมูลทั่วไป */}
          <section className="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
              <label className="block text-sm font-medium">เขียนที่</label>
              <input name="place" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" placeholder="เช่น เทศบาลฯ" />
            </div>
            <div>
              <label className="block text-sm font-medium">วันที่ *</label>
              <input
                type="date"
                name="date"
                className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.date ? "ring-2 ring-red-400" : ""}`}
              />
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium">ชื่อ - นามสกุล *</label>
              <input
                name="applicant_name"
                className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.applicant_name ? "ring-2 ring-red-400" : ""}`}
                placeholder="ข้าพเจ้า..."
              />
            </div>

            <div>
              <label className="block text-sm font-medium">อายุ (ปี) *</label>
              <input name="age" className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.age ? "ring-2 ring-red-400" : ""}`} />
            </div>
            <div>
              <label className="block text-sm font-medium">สัญชาติ</label>
              <input name="nationality" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>

            {/* ที่อยู่ */}
            <div>
              <label className="block text-sm font-medium">บ้านเลขที่</label>
              <input name="addr_no" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">หมู่ที่</label>
              <input name="addr_moo" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">ตรอก/ซอย</label>
              <input name="addr_soi" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">ถนน</label>
              <input name="addr_road" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">ตำบล/แขวง</label>
              <input name="addr_subdistrict" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">อำเภอ/เขต</label>
              <input name="addr_district" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">จังหวัด *</label>
              <input name="addr_province" className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.addr_province ? "ring-2 ring-red-400" : ""}`} />
            </div>
            <div>
              <label className="block text-sm font-medium">หมายเลขโทรศัพท์ *</label>
              <input name="phone" className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.phone ? "ring-2 ring-red-400" : ""}`} />
            </div>
            <div>
              <label className="block text-sm font-medium">โทรสาร</label>
              <input name="fax" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>

            {/* ความประสงค์ */}
            <div className="md:col-span-2">
              <label className="block text-sm font-medium">มีความประสงค์ *</label>
              <textarea
                name="purpose"
                rows={4}
                className={`mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2 ${errors.purpose ? "ring-2 ring-red-400" : ""}`}
                placeholder="ระบุความประสงค์ เช่น ขอรับถังขยะ..."
              />
            </div>
          </section>

          {/* ส่วนที่ 2: เอกสารแนบตามแบบ */}
          <section className="mt-6 rounded-2xl border border-emerald-200 p-4">
            <div className="mb-2 flex items-center gap-2">
              <CheckSquare className="h-5 w-5" />
              <h3 className="font-semibold">เอกสารหลักฐานที่แนบ</h3>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
              <label className="inline-flex items-center gap-2">
                <input type="checkbox" name="doc_idcard" className="h-4 w-4" />
                <span>สำเนาบัตรประจำตัว (ประชาชน/ข้าราชการ/พนักงานรัฐวิสาหกิจ/อื่น ๆ)</span>
              </label>
              <label className="inline-flex items-center gap-2">
                <input type="checkbox" name="doc_household" className="h-4 w-4" />
                <span>สำเนาทะเบียนบ้าน</span>
              </label>
              <label className="inline-flex items-center gap-2">
                <input type="checkbox" name="doc_corporate" className="h-4 w-4" />
                <span>หนังสือรับรองการจดทะเบียนนิติบุคคล + บัตร ปชช. ผู้แทนนิติบุคคล</span>
              </label>
              <label className="inline-flex items-center gap-2">
                <input type="checkbox" name="doc_authority" className="h-4 w-4" />
                <span>หนังสือมอบอำนาจ (ถ้ามิได้มายื่นด้วยตนเอง)</span>
              </label>
            </div>
            <div className="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label className="block text-sm font-medium">เอกสารอื่น ๆ 1)</label>
                <input name="doc_other1" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
              </div>
              <div>
                <label className="block text-sm font-medium">เอกสารอื่น ๆ 2)</label>
                <input name="doc_other2" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
              </div>
            </div>

            {/* แนบไฟล์ */}
            <div className="mt-4">
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
                      <li key={i} className="truncate">{f.name}</li>
                    ))}
                  </ul>
                )}
              </div>
            </div>
          </section>

          {/* ส่วนที่ 3: ลงชื่อรับรอง */}
          <section className="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div className="md:col-span-2">
              <label className="block text-sm font-medium">ข้าพเจ้าขอรับรองว่าข้อความตามคำขอนี้เป็นความจริงทุกประการ</label>
            </div>
            <div>
              <label className="block text-sm font-medium">ลงชื่อผู้ขออนุญาต</label>
              <input name="signature_name" placeholder="(พิมพ์ชื่อ-สกุล)" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
            <div>
              <label className="block text-sm font-medium">วันที่ลงชื่อ</label>
              <input type="date" name="signature_date" className="mt-1 w-full rounded-xl border border-emerald-200 px-3 py-2" />
            </div>
          </section>

          {/* ปุ่มส่ง */}
          <div className="mt-6">
            <button
              type="submit"
              disabled={sending}
              className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 font-medium text-white shadow hover:bg-emerald-800 disabled:opacity-60"
            >
              <Send className="h-5 w-5" />
              {sending ? "กำลังส่ง..." : "ส่งคำร้องขอถังขยะ"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
