@extends('backend.menu.layout')
@section('title', $title)
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table caption-top" id="myTable">
                <caption>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="title-list fs-4 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-file-earmark-text" viewBox="0 0 16 16" style="margin-top: -4px;">
                                    <path
                                        d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5">
                                    </path>
                                    <path
                                        d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z">
                                    </path>
                                </svg>
                                {{ $title }}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="title-list fs-4 mb-1">
                            @if (!$form->isEmpty())
                                <select class="form-select" aria-label="Default select example"
                                    onchange="if (this.value) window.location.href=this.value">
                                    @foreach ($form as $item)
                                        <option
                                            value="{{ route('eserviceform.id', ['menu' => $menuId, 'id' => $item->gennericforms_id]) }}"
                                            {{ $item->gennericforms_id == $id ? 'selected' : '' }}>
                                            {{ $item->gennericforms_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </caption>

                <thead>
                    <tr>
                        <th scope="col" class="col-1" style="width: 20%;">วันที่</th>
                        <th scope="col"class="col-2" style="width: 30%;">ผู้ดำเนินการ</th>
                        <th scope="col"class="col-2" style="width: 30%;">สถานะ</th>
                        <th scope="col"class="col-2" style="width: 20%;">จัดการคำร้อง</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($list->isEmpty())
                        <tr>
                            <td colspan="5" align="center">ไม่มีข้อมูลในหัวข้อนี้</td>
                        </tr>
                    @else
                        @foreach ($list as $item)
                            <tr>
                                <td>{{ $item->field_date }}</td>
                                <td>

                                    @if ($item->form_status === 'R')
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            ● {{ $list->form_user_update ?? 'Admin' }}
                                        </span>
                                    @endif

                                </td>
                                <td>
                                    @if ($item->form_status === 'N')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            ● ยังไม่ได้ดำเนินการ
                                        </span>
                                    @elseif ($item->form_status === 'R')
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            ● ดำเนินการแล้ว
                                        </span>
                                    @endif
                                </td>
                                <td>

                                    <button type="button" class="btn btn-primary btn-sm btn-info-detail"
                                        data-bs-toggle="modal" data-bs-target="#infoModal"
                                        data-uploads="{{ json_encode($item->file) }}"
                                        data-status="{{ $item->form_status }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-file-richtext" viewBox="0 0 16 16">
                                            <path
                                                d="M7 4.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0m-.861 1.542 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V7.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V7s1.54-1.274 1.639-1.208M5 9a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z" />
                                            <path
                                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('GeneralRequestsAdminExportPDF', ['form' => $id, 'id' => $item->form_id]) }}"
                                        target="_black">
                                        <button type="button" class="btn btn-danger btn-sm" fdprocessedid="sivkwg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z">
                                                </path>
                                            </svg>
                                        </button>
                                    </a>
                                    {{-- <a href="{{ route('GeneralRequestsAdminExportPDFtest', ['form' => $id, 'id' => $item->form_id]) }}"
                                        target="_black">
                                        <button type="button" class="btn btn-danger btn-sm" fdprocessedid="sivkwg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z">
                                                </path>
                                            </svg>
                                        </button>
                                    </a> --}}
                                    {{-- <a href="{{ route('GeneralRequestsAdminExportPDFtest2pdf', ['form' => $id, 'id' => $item->form_id]) }}"
                                        target="_black">
                                        <button type="button" class="btn btn-danger btn-sm" fdprocessedid="sivkwg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z">
                                                </path>
                                            </svg>
                                        </button>
                                    </a> --}}
                                    @if ($item->form_status === 'N')
                                        <button type="button" class="btn btn-success btn-sm btn-reply"
                                            data-bs-toggle="modal" data-bs-target="#replyModal"
                                            data-id="{{ $item->form_id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z">
                                                </path>
                                            </svg>
                                        </button>
                                    @elseif ($item->form_status === 'R')
                                        <button type="button" class="btn btn-success btn-sm btn-show-message"
                                            data-bs-toggle="modal" data-bs-target="#messageModal"
                                            data-message="{{ $item->form_note ?? 'ไม่มีข้อความ' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif



                                </td>
                            </tr>
                        @endforeach

                    @endif


                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="replyForm" method="POST" action="{{ route('eservice.reply') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">ตอบกลับข้อความ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="form_id" id="modal-form-id">
                        <div class="mb-3">
                            <label for="reply_message" class="form-label">หมายเหตุ</label>
                            <textarea name="reply_message" id="reply_message" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-success">ส่งข้อความ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">รายละเอียดคำร้อง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>สถานะ:</strong> <span id="modal-status"></span></p>
                    <ul id="modal-files"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">หมายเหตุ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-message-content">
                    <!-- ข้อความจะถูกใส่จาก JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>





    {{ $list->links() }}
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-info-detail');

            buttons.forEach(button => {
                button.addEventListener('click', function() {

                    const statusEl = document.getElementById('modal-status');
                    const fileList = document.getElementById('modal-files');

                    if (!statusEl || !fileList) {
                        console.error('Modal elements not found');
                        return;
                    }

                    // set text
                    statusEl.textContent = this.dataset.status === 'N' ? 'ยังไม่ได้ดำเนินการ' :
                        'ดำเนินการแล้ว';


                    // set files
                    fileList.innerHTML = '';
                    let uploads = JSON.parse(this.dataset.uploads || '[]');
                    if (uploads.length > 0) {
                        uploads.forEach((file, index) => {
                            let li = document.createElement('li');
                            li.innerHTML =
                                `<a href="{{ asset('storage') }}/${file}" target="_blank">ไฟล์แนบ  ${index + 1}</a>`;

                            fileList.appendChild(li);
                        });
                    } else {
                        let li = document.createElement('li');
                        li.textContent = 'ไม่มีไฟล์แนบ';
                        fileList.appendChild(li);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-show-message').forEach(btn => {
                btn.addEventListener('click', function() {
                    const message = this.dataset.message;
                    document.getElementById('modal-message-content').textContent = message;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-reply').forEach(btn => {
                btn.addEventListener('click', function() {
                    // เอา id ของ texteditor ไปใส่ hidden input ใน modal
                    const texteditorId = this.dataset.id;
                    document.getElementById('modal-form-id').value = texteditorId;
                });
            });
        });
    </script>
@endsection
