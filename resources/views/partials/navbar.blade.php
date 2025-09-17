<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Add your right-aligned nav items here -->

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth') }}">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Link 2</a>
                </li>

            </ul>
        </div>
    </div>
</nav>