<div>
    @foreach (auth()->user()->authProviders as $user)
        <p>Name: {{ $user->nickname }}</p>
        <img src="{{ $user->avatar }}" alt="avatar" class="w-10 h-10 rounded-full block">
        <p>{{ $user->login_at }}</p>
    @endforeach

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <a :href="route('logout')" class="text-sm text-gray-800 cursor-pointer bg-orange-400" onclick="event.preventDefault(); this.closest('form').submit();">
            Cerrar Sesión
        </a>
      </form>
</div>
