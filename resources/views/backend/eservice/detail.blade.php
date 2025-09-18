@extends('backend.menu.layout')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h4 class="mb-0 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        class="bi bi-file-earmark-text me-2 text-primary" viewBox="0 0 16 16">
                        <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1
                                    .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0
                                    2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1
                                    0 1h-2a.5.5 0 0 1-.5-.5" />
                        <path d="M9.5 0H4a2 2 0 0 0-2
                                    2v12a2 2 0 0 0 2 2h8a2 2 0 0 0
                                    2-2V4.5zm0 1v2A1.5 1.5 0 0 0
                                    11 4.5h2V14a1 1 0 0 1-1
                                    1H4a1 1 0 0 1-1-1V2a1 1 0 0
                                    1 1-1z" />
                    </svg>
                    {{ $title }}
                </h4>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div
                    class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="mb-0">เรื่อง : {{ $list->contacts_topic }}</h5>
                    @if ($list->contacts_status == 'R')
                        <span class="badge bg-success px-3 py-2 fs-6">
                            ● ดำเนินการแล้ว
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2 fs-6">
                            ● ยังไม่ได้ดำเนินการ
                        </span>
                    @endif
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-secondary mb-3">รายละเอียดการร้องเรียน</h6>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th class="bg-light w-25">ชื่อ</th>
                                    <td>{{ $list->contacts_name ?? '-' }}</td>
                                </tr>
                                
                                <tr>
                                    <th class="bg-light">ที่อยู่</th>
                                    <td>{{ $list->contacts_address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">เบอร์ติดต่อ</th>
                                    <td>{{ $list->contacts_tel ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">E-mail</th>
                                    <td>{{ $list->contacts_email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">รายละเอียด</th>
                                    <td>{!! nl2br(strip_tags($list->contacts_detail)) ?? '-' !!}</td>
                                </tr>
                                
                                <tr>
                                    <th class="bg-light">วันที่ส่งแบบฟอร์ม</th>
                                    <td>{{ $list->contact_date_show_buddhist }}</td>
                                </tr>
                                @if (!empty($list->contacts_note))
                                    <tr>
                                        <th class="bg-light">หมายเหตุ</th>
                                        <td>
                                            {{ $list->contacts_note }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if ($list->contacts_status == 'N')
                        <form action="{{ route('contactdetail.update', ['menu' => $menuId, 'id' => $id]) }}"
                            method="post" enctype="multipart/form-data" class="border rounded p-4 bg-light shadow-sm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">สถานะการดำเนินการ</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusN"
                                        value="N" checked>
                                    <label class="form-check-label" for="statusN">ยังไม่ได้ดำเนินการ</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusR"
                                        value="R">
                                    <label class="form-check-label" for="statusR">ดำเนินการแล้ว</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label fw-bold">หมายเหตุ</label>
                                <input type="text" class="form-control" name="note" id="note"
                                    placeholder="กรอกหมายเหตุ" value="{{ $list->contacts_note ?? '' }}" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="bi bi-check-circle"></i> บันทึก
                                </button>
                                <button class="btn btn-warning px-4" type="button" onclick="history.back()">
                                    <i class="bi bi-arrow-left-circle"></i> ย้อนกลับ
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-end mt-3">
                            <button class="btn btn-warning px-4" type="button" onclick="history.back()">
                                <i class="bi bi-arrow-left-circle"></i> ย้อนกลับ
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>


@endsection
