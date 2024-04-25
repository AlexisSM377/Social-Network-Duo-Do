<div>
    <h1 class="text-xl font-weight-bold">
        Dashboard
    </h1>
    <div>
    
   
    <div>
        @foreach (auth()->user()->authProviders as $user)
            <p>Name: {{$user->nickname}}</p>
            <img src="{{ $user->avatar }}" alt="avatar" class="w-10 h-10 rounded-full block">
            <p>{{ $user->login_at }}</p>
        @endforeach

        <a href="/">
            <button class="btn btn-primary">Logout</button>
        </a>
    </div>
</div>