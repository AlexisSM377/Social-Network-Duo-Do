<?php

use App\Models\Comment;
use App\Models\Post;
use App\Traits\HasCssClassAttribute;
use LiveWire\Attributes\On;
use LiveWire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    use HasCssClassAttribute;

    public Post $post;
    public Comment $comment;
    public bool $editing = false;

    #[Rule('required|min:5')]
    public ?string $body;

    public function mount(Comment $comment): void
    {
        $this->post = $comment->post;
        $this->body = $comment->body;
    }

    public function delete(Comment $comment): void
    {
        $comment = Comment::find($comment->id);
        $comment->delete();
        $this->emit('refresh-comments');   
    }

    public function save(Comment $comment): void
    {
        $comment = Comment::find($comment->id);
        $this->comment->update($this->validate([
            'body' => 'required|string',
        ]));
        $this->post->touch('updated_at');

        $this->editing = false;
    }
}; ?>

<div>
    <x-card @class([
        $class,
        'border border-primary/30' => $comment->author->isMyself(),
    ]) wire:loading.class="opacity-60 border-dashed !border-primary"
        wire:target="delete,save" shadow separator>

        <x-slot:title class="!text-sm flex gap-2 items-center">
            @foreach (auth()->user()->authProviders as $user)
                <x-avatar :image="$user->avatar" />
                <span class="font-bold text-xs">{{ $user->nickname }}</span>
            @endforeach
            <livewire:timestamp :dateTime="$comment->created_at" />
        </x-slot:title>

        <x-slot:menu>
            @if (!$post->published_at && $comment->author->isMyself())
                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="o-ellipsis-vertical" class="btn-sm btn-ghost btn-circle" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="o-pencil" @click="$wire.editing = true" />
                    <x-menu-item title="Remove" icon="o-trash" wire:click="delete({{ $comment->id }})"
                        class="text-error" />
                </x-dropdown>
            @endif
            {{-- <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="o-dots-vertical" class="btn-sm btn-ghost" />
                </x-slot:trigger>

                <x-dropdown.item wire:click="$set('editing', true)">
                    Edit
                </x-dropdown.item>

                <x-dropdown.item wire:click="delete">
                    Delete
                </x-dropdown.item>
            </x-dropdown> --}}
        </x-slot:menu>

        <div class="leading-7">
            <div x-show="!$wire.editing text-xs">
                {!! nl2br($comment->body) !!}
            </div>

            <x-form x-show="$wire.editing" wire:submit="save" class="flex-1" @keydown.meta.enter="$wire.save()">
                <x-textarea wire:model="body" placeholder="Reply..." />

                <x-slot:actions>
                    <x-button label="Cancel" @click="$wire.editing= false" />
                    <x-button label="Save" type="submit" icon="o-paper-airplane" class="btn-primary"
                        spinner="save" />
                </x-slot:actions>
            </x-form>
            {{-- @foreach ($comments as $comment)
                <div class="flex gap-3 items-center">
                    <x-avatar :image="$comment->author->avatar" :title="$comment->author->nickname" />
                    <div>
                        <span class="font-semibold">{{ $comment->author->nickname }}</span>
                        <livewire:timestamp :dateTime="$comment->created_at" />
                        <p>{{ $comment->body }}</p>
                    </div>
                </div>
                <hr class="my-3">
            @endforeach
    
            @if ($editing)
                <div class="flex gap-3 items-center">
                    <x-avatar :image="auth()->user()->avatar" :title="auth()->user()->nickname" />
                    <div>
                        <span class="font-semibold">{{ auth()->user()->nickname }}</span>
                        <textarea wire:model.defer="body" class="form-input" rows="3"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-3">
                    <x-button label="Save" wire:click="save" class="btn-sm btn-primary" />
                    <x-button label="Cancel" wire:click="$set('editing', false)" class="btn-sm btn-ghost" />
                </div>
            @else --}}
        </div>
    </x-card>

</div>
