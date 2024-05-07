<div class="max-w-sm lg:ml-40 min-h-64 flex flex-col items-center justify-center">
    <img src="/salida.png" width="200" class="mx-auto">
    <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center justify-center mt-10">
        @csrf

        <a :href="route('logout')" class="text-5xl flex items-center cursor-pointer hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 rounded-lg px-5 py-2.5 text-center  dark:focus:ring-[#1da1f2]/55 mr-2 mb-2 hover:shadow-lg transition-all duration-200 ease-in-out hover-scale gap-x-2 p-4 font-bold"
            onclick="event.preventDefault(); this.closest('form').submit();">
            Cerrar Sesión
        </a>
    </form>
</div>