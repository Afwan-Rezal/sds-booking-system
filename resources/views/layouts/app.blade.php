<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | {{config('app.name'), 'SDS Booking System'}}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

    @include('partials.navbar')
    @include('partials.alert') <!-- Warning popup/messages -->
    <div class="container">
        @yield('content')
    </div>

    <footer class="bg-light text-center py-3 mt-4">
        <small>&copy; {{ date('Y') }} SDS Booking System. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>