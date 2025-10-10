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
            Login DEMO
            <p class="front-title">องการบริหารส่วนตำบล demo</p>
        </div>
        <form action="#">
            <div class="field">
                <input type="text" required>
                <span class="fas fa-user"></span>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="password" required>
                <span class="fas fa-lock"></span>
                <label>Password</label>
            </div>
           
            <button class="login">Sign in</button>
            
        </form>
    </div>
</body>

</html>
