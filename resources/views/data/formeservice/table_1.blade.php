@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <section class="b-detail">
        <div class="form-wrapper">
            <img src="{{ asset('/storage/detailweb/logo.png') }}" alt="Logo" class="d-block mx-auto mb-3"
                style="max-width: 150px;">
            <h4 class="mb-4 text-center">{{ $title }}</h4>
            <h4 class="mb-4 text-center">คำร้องทั่วไป</h4>
            <div class="list-group">
                <form action="{{ route('showform.save', ['menu' => $menuId, 'id' => $id]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf


                    <div class="mb-3">
                        <label class="form-label">วันที่</label>
                        <input type="text" name="field_15" class="form-control" value="{{ $Date }}" readonly>
                    </div>


                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="prefix" class="form-label">คำนำหน้า<span class="text-danger">*</span></label>
                            <select id="prefix" name="field_1" class="form-control" required>
                                <option value="">-- โปรดเลือก --</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">ชื่อ - นามสกุล<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_2" id="name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="tel" class="form-label">เบอร์โทร<span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="field_3" id="tel" pattern="[0-9]{10}"
                                maxlength="10" required>
                        </div>
                    </div>


                    <div class="mb-3 mt-3">
                        <label for="subject" class="form-label">เรื่อง<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="field_4" id="subject" required>
                    </div>


                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">บ้านเลขที่<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_5" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">หมู่ที่<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_6" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ตรอก/ซอย<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_7" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ถนน<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_8" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label class="form-label">แขวง/ตำบล<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_9" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">เขต/อำเภอ<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_10" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">จังหวัด<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="field_11" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" class="form-control" name="field_12">
                        </div>
                    </div>


                    <div class="mb-3 mt-3">
                        <label class="form-label">ขอยื่นคำร้องต่อนายกเทศมนตรีเทศบาลตำบลบ้านโพธิ์ ดังนี้</label>
                        <textarea class="form-control" name="field_13" rows="3"></textarea>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">พร้อมนี้ได้แนบเอกสารหลักฐานมาด้วย จำนวน</label>
                        <input type="number" name="field_14" class="form-control d-inline-block" style="width: 80px;"
                            max="5"> ฉบับ
                    </div>


                    <div class="mb-3">
                        <label class="form-label">ไฟล์แนบ</label>
                        <input type="file" id="files" name="files[]" class="form-control"
                            accept=".doc,.docx,.pdf,.xls,.xlsx" multiple>
                        <small class="text-muted">รองรับเฉพาะ .doc, .docx, .pdf, .xls, .xlsx, .png, .jpeg สูงสุด 5
                            ไฟล์</small>
                    </div>


                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง'
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('files').addEventListener('change', function() {
            const maxFiles = 5;
            const maxSize = 25 * 1024 * 1024; // 25MB
            let isValid = true;

            if (this.files.length > maxFiles) {
                Swal.fire({
                    icon: 'error',
                    title: 'ไฟล์เกินจำนวน',
                    text: `❌ เลือกไฟล์ได้สูงสุด ${maxFiles} ไฟล์เท่านั้น`,
                });
                isValid = false;
            }

            for (let file of this.files) {
                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    Swal.fire({
                        icon: 'error',
                        title: 'ไฟล์ใหญ่เกินไป',
                        html: `❌ ไฟล์ <b>${file.name}</b> (${fileSizeMB} MB) เกิน 25MB`,
                    });
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                this.value = "";
            }
        });
    </script>

@endsection
