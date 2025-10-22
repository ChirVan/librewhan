<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width"/>
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; background:#f5f7fa; margin:0; padding:0;}
      .email-wrapper { max-width:600px; margin:30px auto; background:#ffffff; border-radius:6px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);}
      .email-header { background:#0d6efd; color:#fff; padding:18px 24px; }
      .email-header .brand { font-weight:700; font-size:18px; }
      .email-body { padding:20px 24px; color:#333; line-height:1.5; }
      .email-footer { padding:16px 24px; font-size:12px; color:#8898a6; background:#f8fafc; text-align:center; }
      .btn { display:inline-block; padding:10px 16px; color:#fff; background:#0d6efd; border-radius:4px; text-decoration:none; }
    </style>
  </head>
  <body>
    <div class="email-wrapper">
      <div class="email-header">
        <div class="brand">{{ config('app.name') }}</div>
      </div>

      <div class="email-body">
        @if(!empty($title))
          <h3 style="margin-top:0">{{ $title }}</h3>
        @endif

        {{-- Body is rendered as HTML --}}
        {!! $bodyHtml !!}
      </div>

      <div class="email-footer">
        © {{ date('Y') }} {{ config('app.name') }} — If you didn't expect this email, please contact support.
      </div>
    </div>
  </body>
</html>