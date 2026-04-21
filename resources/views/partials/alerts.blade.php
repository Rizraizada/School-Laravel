@if (session('success'))
    <div class="alert-box success">
        {{ session('success') }}
    </div>
@endif

@if (session('status'))
    <div class="alert-box info">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-box error">
        <strong>Please fix the following:</strong>
        <ul style="margin: 8px 0 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
