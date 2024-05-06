<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    #[Rule('required|min:5')]
    public ?string $body;

    protected $rules = [
        'body' => 'required|string|max:255',
    ];

    public function save(): void
    {
        $comment = (new Comment())->fill($this->validate());
        $comment->author_id = auth()->user()->id;

        $this->post->comments()->save($comment);
        $this->post->touch('updated_at');

        $this->reset('body');
        $this->emit('refresh-comments');
    }
}; ?>

<div class="lg:flex gap-5 mt-10">
    <div class="hidden lg:block">
        @foreach (auth()->user()->authProviders as $user)
            <x-avatar :image="$user->avatar" class="!w-8 lg:!w-16" />
        @endforeach
    </div>

    <x-form wire:submit="save" class="flex-1" @keydown.meta.enter="$wire.save()">

        <x-textarea wire:model="body" placeholder="Write a comment..." />

        <x-slot:actions>
            <x-button label="Comentar" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
