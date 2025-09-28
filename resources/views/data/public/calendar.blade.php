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
        <div class="container py-4">
            {{-- <h2 class="mb-4 text-center">📅 ปฏิทินกิจกรรม</h2> --}}
            <div id="calendar"></div>
        </div>
    </section>
    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                firstDay: 0, // 0=Sunday, 1=Monday
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                events: '{{ route('calendar.events') }}', // ดึงข้อมูลจาก JSON

                eventClick: function(info) {
                    alert(
                        "กิจกรรม: " + info.event.title + "\n" +
                        "เริ่ม: " + info.event.start.toLocaleString() + "\n" +
                        (info.event.end ? "สิ้นสุด: " + info.event.end.toLocaleString() : "")
                    );
                }
            });

            calendar.render();
        });
    </script>

@endsection
