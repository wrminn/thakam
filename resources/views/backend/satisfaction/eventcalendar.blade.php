@extends('backend.menu.layout')
@section('title', $title)

@section('content')

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <label for="monthSelect" class="mb-0">เดือน:</label>
                <select id="monthSelect" class="form-select w-auto"></select>

                <label for="yearSelect" class="mb-0 ms-3">ปี (พ.ศ.):</label>
                <select id="yearSelect" class="form-select w-auto"></select>
            </div>
        </div>

        <div id="calendar" class="shadow-sm rounded-3 p-3 bg-white"></div>
    </div>

    <!-- Modal เพิ่ม/แก้ไข -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="eventForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กิจกรรม</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ชื่อกิจกรรม</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>รายละเอียด</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>วันเริ่ม</label>
                            <input type="date" name="start" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>วันสิ้นสุด</label>
                            <input type="date" name="end" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>สี</label>
                            <input type="color" name="color" class="form-control form-control-color">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" id="btnDelete">ลบ</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var form = document.getElementById('eventForm');
            var btnDelete = document.getElementById('btnDelete');
            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            var currentEventId = null;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                events: '/backend/event-calendar/menu/{{ $menuId }}/json',
                dateClick: function(info) {
                    form.reset();
                    currentEventId = null;
                    form.action = "{{ route('eventcalendar.insert', $menuId) }}";
                    document.getElementById('formMethod').value = 'POST';
                    form.start.value = info.dateStr;
                    form.end.value = info.dateStr;
                    btnDelete.style.display = 'none';
                    modal.show();
                },
                eventClick: function(info) {
                    currentEventId = info.event.id;
                    console.log(info.event.startStr);

                    form.reset();
                    form.title.value = info.event.title;
                    // form.start.value = info.event.startStr;
                    // form.end.value = info.event.endStr ?? info.event.startStr;
                    const start = info.event.start; // Date object
                    const end = info.event.end || start; // fallback = start

                    function formatDate(date) {
                        const y = date.getFullYear();
                        const m = ('0' + (date.getMonth() + 1)).slice(-2);
                        const d = ('0' + date.getDate()).slice(-2);
                        return `${y}-${m}-${d}`;
                    }

                    form.start.value = formatDate(start);
                    form.end.value = formatDate(end);
                    form.color.value = info.event.backgroundColor || '#3788d8';
                    form.description.value = info.event.extendedProps.description || '';
                    form.action = '/backend/event-calendar/menu/{{ $menuId }}/id/' +
                        currentEventId;
                    document.getElementById('formMethod').value = 'PUT';
                    btnDelete.style.display = 'inline-block';
                    modal.show();
                }
            });

            calendar.render();

            // ลบกิจกรรม
            btnDelete.addEventListener('click', function() {
                if (!currentEventId) return;
                if (confirm('คุณต้องการลบกิจกรรมนี้หรือไม่?')) {
                    fetch('/backend/event-calendardelete/menu/{{ $menuId }}/id/' + currentEventId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    }).then(res => {
                        modal.hide();
                        calendar.refetchEvents(); // ตอนนี้จะโหลด events ใหม่จาก /json
                    });
                }
            });

            // เพิ่ม dropdown เดือน/ปี
            var monthSelect = document.getElementById('monthSelect');
            var yearSelect = document.getElementById('yearSelect');
            var today = new Date();

            var monthNames = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.',
                'พ.ย.', 'ธ.ค.'
            ];
            for (var m = 0; m < 12; m++) {
                var opt = document.createElement('option');
                opt.value = m;
                opt.text = monthNames[m];
                monthSelect.appendChild(opt);
            }
            for (var y = today.getFullYear() - 5; y <= today.getFullYear() + 5; y++) {
                var opt = document.createElement('option');
                opt.value = y;
                opt.text = y + 543;
                yearSelect.appendChild(opt);
            }

            monthSelect.value = today.getMonth();
            yearSelect.value = today.getFullYear();

            function updateCalendar() {
                calendar.gotoDate(new Date(yearSelect.value, monthSelect.value, 1));
            }
            monthSelect.addEventListener('change', updateCalendar);
            yearSelect.addEventListener('change', updateCalendar);

        });
    </script>

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
