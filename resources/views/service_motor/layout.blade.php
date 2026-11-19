
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Service Motor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            color: #222;
        }
        header {
            background: #172554;
            color: white;
            padding: 20px 5%;
        }
        nav {
            background: #1e3a8a;
            padding: 14px 5%;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 18px;
            display: inline-block;
            margin-bottom: 5px;
        }
        main {
            max-width: 1150px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 11px;
            text-align: left;
        }
        th {
            background: #e5e7eb;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 7px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type=radio] {
            width: auto;
            margin-right: 5px;
        }
        button, .btn {
            padding: 9px 14px;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .danger { background: #dc2626; }
        .secondary { background: #64748b; }
        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            margin-bottom: 15px;
        }
        .error {
            color: #dc2626;
            font-size: 13px;
        }
    </style>
</head>
<script src="{{ asset('js/service-motor.js') }}"></script>
<body>
    <header>
        <h1>Manajemen Service Motor</h1>
        <p>Sistem Pengelolaan Data Service dan Pembayaran</p>
    </header>

    <nav>
        <a href="{{ route('service_motor.index','pelanggan') }}">Pelanggan</a>
        <a href="{{ route('service_motor.index','kendaraan') }}">Kendaraan</a>
        <a href="{{ route('service_motor.index','daftar-service') }}">Daftar Service</a>
        <a href="{{ route('service_motor.index','data-service') }}">Data Service</a>
        <a href="{{ route('service_motor.index','pembayaran-service') }}">Pembayaran</a>
    </nav>

    <main>
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                <strong>Periksa kembali input:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
