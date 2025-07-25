{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Arsip Dokumen dan Komisi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .split-screen {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .left-panel {
            background-color: #007bff;
            color: white;
            flex: 1;
            padding: 60px;
            display: flex;
            align-items: center;
        }

        .right-panel {
            flex: 1;
            background: url('{{ asset('images/welcome-page.jpg') }}') no-repeat center center;
            background-size: cover;
        }

        .content {
            max-width: 700px;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .title {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        .subtitle {
            font-weight: bold;
            color: #ffe600;
            margin-bottom: 1rem;
        }

        .description {
            color: #d6eaff;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .btn-login {
            background-color: #003366;
            color: #fff;
            border-radius: 30px;
            padding: 10px 30px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #001f4d;
        }

        @media (max-width: 768px) {
            .split-screen {
                flex-direction: column;
            }

            .left-panel, .right-panel {
                flex: none;
                width: 100%;
                height: 50vh;
            }

            .left-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="split-screen">
        <div class="left-panel">
            <div class="content">
                <img src="{{ asset('images/desnet-logo.png') }}" alt="Logo Desnet" class="logo">
                <div class="title">Managemen Arsip<br>Dokumen dan Komisi.</div>
                <div class="subtitle">PT DES Teknologi Informasi</div>
                <div class="description">
                    Aplikasi ini menggabungkan pengelolaan pengarsipan dokumen proyek dan sistem pengajuan komisi berbasis Web. 
                    Aplikasi ditujukan untuk mendukung pengelolaan workflow tim proyek (PM, staff, admin, HOD) 
                    hingga proses perhitungan dan persetujuan komisi berdasarkan status proyek.
                </div>
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            </div>
        </div>
        <div class="right-panel"></div>
    </div>
</body>
</html>
