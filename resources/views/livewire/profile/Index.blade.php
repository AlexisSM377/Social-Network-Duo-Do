<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    public ?User $userS;

    #[Url]
    public string $selectedTab = 'posts-tab';

    public function mount(): void
    {
        $this->userS = auth()->user();
    }

    public function posts(): Collection
    {
        return $this->userS->posts()->withCount('comments')->take(10)->latest()->get();
    }

    public function comments(): Collection
    {
        return $this->userS->comments()->with('post.author')->take(10)->latest()->get();
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'comments' => $this->comments(),
        ];
    }
}; ?>

<div>
    @foreach (auth()->user()->authProviders as $user)
        <x-avatar :image="$user->avatar" class="!w-20 ring ring-primary ring-offset-base-100 ring-offset-2">
            <x-slot:title class="text-2xl !font-black pl-2 mt-6">
                {{ $userS->name }}
                <a href="https://github.com/AlexisSM377" 
                    class="text-xs flex items-center cursor-pointer hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg px-5 py-2.5 text-center  dark:focus:ring-[#1da1f2]/55 mr-2 mb-2 hover:shadow-lg transition-all duration-200 ease-in-out hover-scale gap-x-2 p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-github">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" />
                    </svg>
                    Github: <span
                        class="bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">{{ $user->nickname }}</span>
                </a>
            </x-slot:title>
            <x-slot:subtitle class="text-gray-600 flex flex-col gap-2 mt-2 pl-2 mb-4">
                <x-icon name="o-paper-airplane" label="{{ $userS->posts()->count() }} posts" />
                <x-icon name="o-chat-bubble-left" label="{{ $userS->comments()->count() }} comments" />
            </x-slot:subtitle>
        </x-avatar>
    @endforeach

    <x-tabs wire:model.live="selectedTab" >
        {{-- Posts del usuario --}}
        <x-tab name="posts-tab" label="Posts" icon="o-paper-airplane">
            <x-card class="!p-0 sm:!p-2">
                @foreach ($posts as $post)
                    <x-list-item :item="$post" value="title" link="/posts/{{ $post->id }}" class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">
                        <x-slot:subValue class="flex items-center gap-3 pt-1">
                            {{ $post->created_at->diffForHumans() }}
                            <x-icon name="o-chat-bubble-left" class="w-4 h-4" :label="$post->comments_count" />
                        </x-slot:subValue>

                        <x-slot:actions>
                            @if ($post->published_at)
                                <x-icon name="o-archive-box" class="w-4 h-4 text-sm" label="Archived" />
                            @endif
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </x-card>
        </x-tab>

        {{-- Comentarios del usuario --}}
        <x-tab name="comments-tab" label="Comments" icon="o-chat-bubble-left">
            <x-card class="!p-0 sm:!p-2">
                @foreach ($comments as $comment)
                    <x-list-item :item="$comment" value="post.title" link="/posts/{{ $comment->post->id }}" class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">
                        <x-slot:subValue class="text-lg font-medium">
                            <span class="mr-2 text-[10px] ">{{ $comment->created_at->diffForHumans() }}</span>
                            {{ $comment->body }}
                        </x-slot:subValue>
                    </x-list-item>
                @endforeach
            </x-card>
        </x-tab>
    </x-tabs>
</div>
