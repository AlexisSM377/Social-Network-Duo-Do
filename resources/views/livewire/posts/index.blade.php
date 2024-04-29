<?php

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    public string $search = '';
    public ?int $category_id = 0;
    public string $sort = 'updated_at';

    public function sorts(): array
    {
        return [
            [
                'id' => 'updated_at',
                'name' => 'Recientes',
            ],
            [
                'id' => 'created_at',
                'name' => 'Antiguos',
            ],
        ];
    }

    public function clear(): void
    {
        $this->reset();
    }

    public function categories(): Collection
    {
        return Category::withCount('posts')->get();
    }

    public function posts(): Collection
    {
        return Post::query()
            ->with(['category', 'author', 'latestComment'])
            ->withCount('comments')
            ->when($this->category_id, fn(Builder $q) => $q->where('category_id', $this->category_id))
            ->where('title', 'like', "%$this->search%")
            ->take(10)
            ->latest($this->sort)
            ->get();
    }

    public function with(): array
    {
        return [
            'categories' => $this->categories(),
            'posts' => $this->posts(),
            'sorts' => $this->sorts(),
        ];
    }
}; ?>



<div class="flex-col items-center justify-center">

    <x-header separator progress-indicator>
        @if ($user = auth()->user())
            <x-slot name="title"
                class="text-xl bg-gradient-to-r from-slate-300 to-slate-500 bg-clip-text text-transparent">
                Bienvenido
                <span
                    class="text-2xl bg-gradient-to-r from-stone-500 via-stone-400 to-stone-700 bg-clip-text text-transparent px-2">
                    {{ $user->name }}
                </span>
            </x-slot>
        @endif
    </x-header>
    <x-header>
        <x-slot:title>
            <x-input label="Buscar post" placeholder="Search..." class="text-xs" wire:model.live.debounce="search"
                icon="o-magnifying-glass">
                <x-slot:prepend>
                    <x-select wire:model.live="category_id" :options="$categories" placeholder="All" placeholder-value="0"
                        icon="o-tag" class="bg-gray-800 rounded-r-none" />
                </x-slot:prepend>
            </x-input>
        </x-slot:title>

        <x-slot:actions>
            <x-radio wire:model.live="sort" :options="$sorts" class="text-xs" />
        </x-slot:actions>
    </x-header>

    <main>
        <div class="flex items-center justify-between">
            <p class="text-2xl font-bold">Posts</p>
            <div
                class="flex-row justify-center text-white cursor-pointer hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#1da1f2]/55 mr-2 mb-2 hover:shadow-lg transition-all duration-200 ease-in-out hover-scale gap-x-2 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 5l0 14" />
                    <path d="M5 12l14 0" />
                </svg>
                <a href="/posts/create">Nuevo post</a>
            </div>
        </div>

        <x-card class="!p-0 sm:!p-2" shadow>
            @forelse ($posts as $post)
            <div class="flex items-center gap-2 mt-2 pl-2">
                @foreach (auth()->user()->authProviders as $user)
                    <img src="{{ $user->avatar }}" alt="avatar" class="w-8 h-8 rounded-full block ">
                    <span class="bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent font-bold text-base">{{ $user->nickname }}</span>
                @endforeach
            </div>
            <x-list-item :item="$post" value="title" sub-value="body" avatar="author.avatar" link="/posts/{{ $post->id }}">
                    <x-slot:subValue class="flex items-center gap-3 pt-0.5">
                        @if ($post->latestComment)
                            <x-icon name="o-arrow-uturn-left" class="w-4 h-4 font-bold"
                                label="{{ $post->latestComment?->author->username }}" />
                            <livewire:timestamp :dateTime="$post->latestComment?->created_at" wire:key="time-{{ $post->id }}" />
                        @else
                            <livewire:timestamp :dateTime="$post->updated_at" wire:key="time-{{ $post->id }}" />
                        @endif

                        @if ($post->published_at)
                            <x-icon name="o-archive-box" class="w-4 h-4 font-bold" label="Archivado" />
                        @endif
                    </x-slot:subValue>

                    <x-slot:actions>
                        <livewire:categories.tag :category="$post->category" class="hidden lg:inline-flex"
                            wire:key="tag-{{ $post->id }}-{{ $post->category_id }}" />
                        <x-icon name="o-chat-bubble-left" class="w-4 h-4 font-bold text-sm"
                            label="{{ $post->comments_count }}" />
                    </x-slot:actions>
                </x-list-item>
            @empty
                <x-alert title="Nothing here!" description="Try remove some filters." icon="o-exclamation-triangle"
                    class="bg-base-100 border-none">
                    <x-slot:actions>
                        <x-button label="Clear filters" wire:click="clear" icon="o-x-mark" class="text-xs" spinner />
                    </x-slot:actions>
                </x-alert>
            @endforelse
        </x-card>
    </main>
</div>
