@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('status'))
    <div class="alert alert-info">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 1rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
