<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SoftPay') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('favicon-16x16.png') }}" sizes="16x16" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; background: #f8f9fa; color: #374151; }
    </style>
</head>
<body style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;">
    <div style="width:100%; max-width:420px;">
        <div style="text-align:center; margin-bottom:32px;">
            <div style="font-size:28px; font-weight:600; color:#111111; letter-spacing:-1px;">SoftPay</div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">WhatsApp Business Platform</div>
        </div>
        <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 4px 12px rgba(0,0,0,.08); border:1px solid #e5e7eb;">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
