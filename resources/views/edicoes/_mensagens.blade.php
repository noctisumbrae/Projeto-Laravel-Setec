@if (session('success'))
    <div style="background:#d1e7dd;padding:10px;margin-bottom:12px;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="background:#f8d7da;padding:10px;margin-bottom:12px;">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div style="background:#f8d7da;padding:10px;margin-bottom:12px;">
        <ul style="margin:0;">
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif