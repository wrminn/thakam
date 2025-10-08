<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Flipbooks - Laravel</title>

    <link href="{{ asset('dflip/assets/css/dflip.min.css') }}" rel="stylesheet" type="text/css">
    {{-- <link href="{{ asset('dflip/assets/prime.css') }}" rel="stylesheet" type="text/css">  --}}

    <style>
        /* จัดรูปแบบให้ Flipbook ดูดีขึ้นในหน้าเว็บ */
        .book-container {
            margin-bottom: 50px;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="container" style="padding: 20px;">
        <h1 style="text-align: center;">คอลเลกชัน Flipbooks</h1>

        @foreach ($pdf_files as $filename)
            <div class="book-container">
                <h3>ไฟล์: {{ $filename }}</h3>

                <div class="_df_book" 
                     id="df_book_{{ $loop->index }}" 
                     source="{{ asset($filename) }}" 
                     style="width: 100%; height: 600px;" 
                     flatDisplay="true"
                     backgroundColor="#f9f9f9"
                     webgl="true" 
                     sound="true" 
                     height="600"> 
                    </div>
            </div>
        @endforeach
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('dflip/assets/js/dflip.min.js') }}" type="text/javascript"></script>

    </body>
</html>