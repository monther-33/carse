{{-- Shown by the service worker when the network is down. Self-contained (inline styles,
     no assets) because it must render with no connection at all. --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#14244f">
    <title>{{ __('pwa.offline_title') }} — {{ $brand->name() }}</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f3f4f6;
               font-family: "IBM Plex Sans Arabic", Tahoma, Arial, sans-serif; color: #111827; padding: 1rem; box-sizing: border-box; }
        .card { max-width: 24rem; width: 100%; background: #fff; border-radius: 1rem; padding: 2rem; text-align: center;
                box-shadow: 0 20px 25px -5px rgb(0 0 0 / .1); }
        .icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; border-radius: 1rem; background: linear-gradient(135deg, #2f5bd3, #14244f);
                display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2rem; }
        h1 { font-size: 1.25rem; margin: 0 0 .5rem; }
        p { color: #6b7280; font-size: .9rem; line-height: 1.6; margin: 0 0 1.5rem; }
        button { background: #1d3a91; color: #fff; border: 0; border-radius: .5rem; padding: .7rem 1.5rem; font: inherit; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⚡</div>
        <h1>{{ __('pwa.offline_title') }}</h1>
        <p>{{ __('pwa.offline_body', ['name' => $brand->name()]) }}</p>
        <button type="button" onclick="location.reload()">{{ __('pwa.retry') }}</button>
    </div>
</body>
</html>
