<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: xbriyaz; font-size: 11pt; color: #222; direction: rtl; }
        .letterhead { width: 100%; border-bottom: 2px solid #1d3a91; padding-bottom: 6px; margin-bottom: 12px; }
        .letterhead td { vertical-align: middle; }
        .company { font-size: 16pt; font-weight: bold; color: #14244f; }
        .muted { color: #666; font-size: 9pt; }
        h1 { text-align: center; font-size: 15pt; margin: 6px 0 12px; color: #14244f; }
        table.grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.grid th { background: #eef2fb; border: 1px solid #c9d2e8; padding: 5px; font-size: 10pt; text-align: right; }
        table.grid td { border: 1px solid #d5d9e2; padding: 5px; font-size: 10pt; }
        table.info { width: 100%; margin-bottom: 10px; }
        table.info td { padding: 3px 4px; font-size: 10.5pt; }
        .label { color: #555; width: 22%; }
        .num { direction: ltr; text-align: left; }
        .total-row td { font-weight: bold; background: #f6f7fb; }
        .words { border: 1px dashed #999; padding: 6px; margin: 8px 0; font-size: 10.5pt; }
        .signatures { width: 100%; margin-top: 40px; }
        .signatures td { text-align: center; padding-top: 30px; font-size: 10pt; }
        .terms { font-size: 10pt; line-height: 1.7; }
    </style>
</head>
<body>
<table class="letterhead">
    <tr>
        <td style="width: 70%">
            <div class="company">{{ $company['name'] }}</div>
            <div class="muted">
                @if ($company['address']){{ $company['address'] }}@endif
                @if ($company['phone']) — <span class="num">{{ $company['phone'] }}</span>@endif
            </div>
        </td>
        <td style="width: 30%; text-align: left">
            @if ($company['logo'])
                <img src="{{ $company['logo'] }}" style="height: 60px">
            @endif
        </td>
    </tr>
</table>

@yield('content')
</body>
</html>
