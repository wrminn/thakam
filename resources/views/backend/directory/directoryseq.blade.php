@extends('backend.menu.layout')
@section('title', $title)
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

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

                            {{-- <p class="sum-list">ทั้งหมด 1 รายการ</p> --}}
                        </div>
                        <div class="col-md-1 ms-auto">
                            <a href="{{ route('directory', ['menu' => $menuId]) }}" class="btn btn-warning">ย้อนกลับ</a>
                        </div>
                    </div>


                </caption>

                <thead>
                    <tr>
                        <th scope="col" class="col-1">ลำดับ</th>
                        <th scope="col"class="col-3">หัวข้อ</th>
                        <th scope="col"class="col-3">วันที่</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    @if ($list->isEmpty())
                        <tr>
                            <td colspan="6" align="center">ไม่มีข้อมูลในหัวข้อนี้</td>
                        </tr>
                    @else
                        @foreach ($list as $item)
                            <tr data-id="{{ $item->texteditor_id }}">
                                <td scope="row" class="seq">
                                    {{ $startIndex + $loop->index }}
                                </td>
                                <td>{{ $item->texteditor_title }}</td>
                                <td>{{ $item->texteditor_date_show }}</td>
                            </tr>
                        @endforeach
                    @endif


                </tbody>
            </table>
        </div>
    </div>
    {{ $list->links() }}


    {{-- <script>
        const el = document.getElementById('sortable');

        new Sortable(el, {
            animation: 150,
            onEnd: function() {
                updateSeq();
                saveSeq();
            }
        });

        function updateSeq() {
            const rows = document.querySelectorAll("#sortable tr");
            rows.forEach((row, index) => {
                row.querySelector(".seq").innerText = index + 1;
            });
        }

        function saveSeq() {
            const data = Array.from(document.querySelectorAll("#sortable tr")).map(row => ({
                id: row.dataset.id,
                seq: row.querySelector(".seq").innerText
            }));

            fetch("{{ route('updateseqdirectory', ['menu' => $menuId]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        users: data
                    })
                })
                .then(res => res.json())
                .then(res => console.log("บันทึกเรียบร้อย:", res));
        }
    </script> --}}

    <script>
        const el = document.getElementById('sortable');

        new Sortable(el, {
            animation: 150,

            // ตรวจสอบก่อนปล่อย (drag end)
            onMove: function(evt) {
                const draggedRow = evt.dragged; // แถวที่โดนลาก
                const relatedRow = evt.related; // แถวที่ mouse อยู่เหนือในขณะลาก

                if (!relatedRow) return true; // ถ้ายังไม่มี target ให้ข้ามไปก่อน

                // ดึงวันที่จากคอลัมน์ที่ 3 ของ source และ target
                const draggedDate = draggedRow.querySelectorAll("td")[2].innerText.trim();
                const targetDate = relatedRow.querySelectorAll("td")[2].innerText.trim();

                // ถ้าวันไม่ตรงกัน → ห้ามลากไปตำแหน่งนั้น
                if (draggedDate !== targetDate) {
                    return false; // ❌ ยกเลิกเฉพาะ move ครั้งนี้ แต่ drag ยังไม่หยุด
                }

                return true;
            },

            onEnd: function(evt) {
                updateSeq();
                saveSeq();
            }
        });

        function updateSeq() {
            const rows = document.querySelectorAll("#sortable tr");
            rows.forEach((row, index) => {
                row.querySelector(".seq").innerText = index + 1;
            });
        }

        function saveSeq() {
            const data = Array.from(document.querySelectorAll("#sortable tr")).map(row => ({
                id: row.dataset.id,
                seq: row.querySelector(".seq").innerText
            }));

            fetch("{{ route('updateseqdirectory', ['menu' => $menuId]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        users: data
                    })
                })
                .then(res => res.json())
                .then(res => console.log("บันทึกเรียบร้อย:", res));
        }
    </script>



@endsection
