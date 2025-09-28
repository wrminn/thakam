@extends('layouts.app')
@section('title', $title)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <div class="text-center ">
        <div class="title-menu">
            {{ $title }}
        </div>
    </div>
    <section class="b-detail">
        <div class="form-wrapper">
            <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="d-block mx-auto mb-3"
                style="max-width: 150px;">
            <h3 class="mb-4 text-center">{{ $title }}</h3>
            <h4 class="mb-4 text-center">เทศบาลตำบลท่าข้าม</h4>
            <h4 class="mb-4 text-center">122 หมู่ที่ 3 ตำบลท่าข้าม อำเภอบางปะกง จังหวัดฉะเชิงเทรา 24130</h4>
            <h4 class="mb-4 text-center">เบอร์โทรสำนักงาน 0-3857-3411-2 ต่อ 144 แฟกซ์ 0-3857-3411-2</h4>
            <h4 class="mb-4 text-center"> E-mail : admin@thakam.go.th</h4>
            <form class="" action="{{ route('contact.insert', ['menu' => $menuId]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="col-12 mb-2">
                    <label class="form-label">หัวข้อ</label>
                    <input type="text" name="topic" class="form-control">
                </div>
                <div class="row mb-3">
                    <div class="col-md-9">
                        <label class="form-label">ชื่อ - นามสกุล</label>
                        <input type="text" class="form-control" name="name">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">เบอร์โทร</label>
                        <input class="form-control" type="tel" name="tel" pattern="[0-9]{10}" maxlength="10"
                            required>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">ที่อยู่</label>
                    <input type="text" name="address" class="form-control" placeholder="1234 Main St">
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" placeholder="excemple@gmail.com">
                </div>
                <div class="mb-3">
                    <label for="slot" class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="detail" id="editor" cols="30" rows="6"></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary px-5">บันทึก</button>
                </div>
            </form>
            <div class="mt-3">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7755.850156394513!2d101.085491!3d13.601388!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d3d003d705b77%3A0x3cf46533314b8b10!2z4LmA4LiX4Lio4Lia4Liy4Lil4LiV4Liz4Lia4Lil4Lia4LmJ4Liy4LiZ4LmC4Lie4LiY4Li04LmM!5e0!3m2!1sth!2sth!4v1757558645364!5m2!1sth!2sth"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
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

@endsection
