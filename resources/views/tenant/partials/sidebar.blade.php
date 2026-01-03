<div class="bg-dark text-white p-3 vh-100" style="width:260px">
    <h5 class="mb-4">{{ app('tenant')->name }}</h5>

    <ul class="nav flex-column gap-2">
        <li>
            <a href="/dashboard" class="nav-link text-white">🏠 Dashboard</a>
        </li>

        <li>
            <a href="/classes" class="nav-link text-white">📚 Classes</a>
        </li>

        <li>
            <a href="/students" class="nav-link text-white">🎓 Élèves</a>
        </li>

        <li>
            <a href="/users" class="nav-link text-white">👥 Utilisateurs</a>
        </li>

        <li>
            <a href="/school-years/create" class="nav-link text-white">📅 Années scolaires</a>
        </li>

        <li class="mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-danger w-100">🚪 Déconnexion</button>
            </form>
        </li>
    </ul>
</div>
