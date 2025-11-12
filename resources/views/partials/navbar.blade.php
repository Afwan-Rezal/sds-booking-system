<nav class="navbar navbar-expand-lg sds-navbar mb-4">
    <div class="container">
        <a class="navbar-brand sds-navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Add your right-aligned nav items here -->

                @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link sds-navbar-link" href="#">Welcome, {{ Auth::user()->username }}</a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger nav-link" style="display: inline; padding: 0.375rem 1rem; border: none; background: #dc3545; color: #fff; cursor: pointer;">
                                Logout
                            </button>
                        </form>
                    </li>
                    
                @else
                    <li class="nav-item">
                        <a class="nav-link sds-navbar-link" href="{{ route('auth') }}">Login</a>
                    </li>
                @endif


            </ul>
        </div>
    </div>
</nav>