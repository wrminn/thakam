<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/login/styles.css') }}">
</head>

<body>
    <div class="content">
        <div class="text">
            Login
            <p class="front-title">เข้าสู่ระบบ</p>
        </div>
        <form action="{{ route('login.submit') }}" method="post">
            @csrf

            @if ($errors->any())
                <div class="field">
                    <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif
            <div class="field">
                <input type="email" name="email" required>
                <span class="fas fa-user"></span>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="password" name="password" required>
                <span class="fas fa-lock"></span>
                <label>Password</label>
            </div>

            <button class="login">Sign in</button>

        </form>
    </div>
</body>

</html>
