<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="fw-bold">{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm">
                Déconnexion
            </button>
        </form>
    </div>
</nav>
