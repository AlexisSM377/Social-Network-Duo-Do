<?php

use App\Models\Post;
use Livewire\Attributes\Renderless;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public Post $post;

    public function archive(): void
    {
        $this->post->update(['published_at' => now()]);
        $this->warning('Post keep successfully');
    }

    public function unarchive(): void
    {
        $this->post->update(['published_at' => null]);
        $this->success('Post unarchived successfully');
    }
}; ?>

<div>
    <span class="font-semibold text-base">{{ $post->title }}</span>

    <div class="mt-3 flex flex-wrap gap-3 lg:gap-8 items-center justify-between">
        <livewire:categories.tag :category="$post->category" />

        @if ($post->author->isMyself())
            <div>
                @if (!$post->published_at)
                    <x-button label="Guardar" wire:click="archive" icon="o-archive-box" class="btn-sm btn-ghost" />
                    <x-button label="Edit" link="/posts/{{ $post->id }}/edit" icon="o-archive-box"
                        class="btn-sm btn-ghost" />
                @else
                    <x-button label="Unarchive" wire:click="unarchive" icon="o-archive-box" class="btn-sm btn-ghost" />
                @endif
            </div>
        @endif
    </div>
    <hr class="my-2" />

    <x-card class="leading-7 mb-10 border">
        <x-slot:title class="!text-sm flex gap-2 items-center">
            @foreach (auth()->user()->authProviders as $user)
                <x-avatar :image="$user->avatar" :title="$user->nickname" />
            @endforeach
            <livewire:timestamp :dateTime="$post->created_at" />
        </x-slot:title>

        {!! nl2br($post->body) !!}
    </x-card>

    @if ($post->published_at)
        <x-alert title="The post was archived {{ $post->published_at->diffForHumans() }}" icon="o-archive-box"
            class="alert-warning mb-10" />
    @endif

    <livewire:comments.index :post="$post" lazy wire:key="comments-{{ $post->updated_at }}" />
</div>
