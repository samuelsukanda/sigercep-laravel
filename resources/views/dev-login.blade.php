<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Login - SIGERCEP</title>
    <link rel="shortcut icon" href="{{ asset('images/logors.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Nunito+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Nunito Sans', sans-serif;
            background: #0a0f1a;
            color: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 20px;
            padding: 32px 40px;
        }

        .head h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .head p {
            font-size: 14px;
            color: #8b9bbf;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead tr {
            text-align: left;
            color: #6b7a99;
            border-bottom: 1px solid rgba(255, 255, 255, 0.13);
        }

        thead th {
            padding: 8px 10px;
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        tbody tr:hover {
            background: rgba(59, 110, 248, 0.06);
        }

        td {
            padding: 10px;
            vertical-align: top;
        }

        .user a {
            color: #5b8afb;
            font-weight: 600;
            text-decoration: none;
        }

        .user a:hover {
            text-decoration: underline;
        }

        .user .uname {
            font-size: 12px;
            color: #6b7a99;
        }

        .role {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .role-it {
            color: #34d399;
            background: rgba(52, 211, 153, 0.10);
        }

        .role-stage2 {
            color: #fbbf24;
            background: rgba(251, 191, 36, 0.10);
        }

        .role-user {
            color: #5b8afb;
            background: rgba(59, 110, 248, 0.10);
        }

        .role-none {
            color: #6b7a99;
            background: rgba(107, 122, 153, 0.10);
        }

        .foot {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            font-size: 13px;
            color: #6b7a99;
        }

        .foot a {
            color: #5b8afb;
            text-decoration: none;
        }

        .foot a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="head">
            <h1>Dev Login</h1>
            <p>Klik user untuk login sebagai user tersebut (hanya aktif di environment local).</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Unit</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="user">
                            <a href="{{ route('dev-login.as', $user->id) }}">{{ $user->name }}</a>
                            <div class="uname">{{ $user->username }}</div>
                        </td>
                        <td>{{ $user->unit }}</td>
                        <td>{{ $user->jabatan }}</td>
                        <td>
                            @if (strtolower(trim($user->unit ?? '')) === 'teknologi dan informasi')
                                <span class="role role-it">IT</span>
                            @elseif (\App\Helpers\PermissionHelper::isStage2($user))
                                <span class="role role-stage2">Tahap 2 (Manajer Umum)</span>
                            @elseif (\App\Helpers\PermissionHelper::canManageChangeRequest($user))
                                <span class="role role-user">Peminta / Approver</span>
                            @else
                                <span class="role role-none">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="foot">
            <a href="{{ route('login') }}">&larr; Login normal</a>
        </div>
    </div>
</body>

</html>
