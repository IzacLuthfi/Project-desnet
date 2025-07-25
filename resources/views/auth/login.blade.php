<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. DES Teknologi Informasi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('/images/login-bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(6px);
            text-align: center;
        }

        .login-card img {
            max-width: 180px;
            margin-bottom: 1rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #1e40af;
        }

        .form-group input {
            width: 100%;
            padding: 0.6rem 0.6rem 0.6rem 2.5rem;
            border: 1.5px solid #1e40af;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: #1e40af;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1.05rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #1e3a8a;
        }

        .alert {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 0.5rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: left;
        }

        ul {
            margin: 0;
            padding-left: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="/images/desnet-logo.png" alt="Logo Desnet">

            {{-- Notifikasi error --}}
            @if(session('error'))
                <div class="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <i class="fa fa-user"></i>
                    <input type="email" name="email" id="email" placeholder="Email" required autofocus autocomplete="email">
                </div>

                <div class="form-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Kata Sandi" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
