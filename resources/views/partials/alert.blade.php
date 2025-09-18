@if (session('success'))
    <div class="alert alert-success mx-auto text-center" style="max-width: 640px;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mx-auto text-center" style="max-width: 640px;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning mx-auto" style="max-width: 640px;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
