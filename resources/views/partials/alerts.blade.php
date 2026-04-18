@if (session('success'))
    <div class="card mb-3" style="border-left: 4px solid var(--success);">
        {{ session('success') }}
    </div>
@endif

@if (session('status'))
    <div class="card mb-3" style="border-left: 4px solid var(--primary);">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="card mb-3" style="border-left: 4px solid var(--danger);">
        <strong>Please fix the following:</strong>
        <ul style="margin: 8px 0 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
