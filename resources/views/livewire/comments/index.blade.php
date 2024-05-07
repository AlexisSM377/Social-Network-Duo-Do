<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;
use LiveWire\Attributes\On;
use LiveWire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;

    
    public function comments(): Collection
    {
        return Comment::query()
        ->with(['author', 'post'])
        ->whereBelongsTo($this->post)
        ->oldest()
        ->get();
    }
    
    #[On('refresh-comments')]
    public function updateDone(): void
    {
        $this->dispatch('$refresh-comments');
    }

    public function placeholder(): string
    {
        return <<<'HTML'
        <div>
            <div class="loading loading-spinner"></div>
        </div>
        HTML;
    }

    public function with(): array
    {
        return [
            'comments' => $this->comments(),
        ];
    }
}; ?>

<div>
    <div>
        Comentarios ({{ $comments->count() }} )
    </div>
    <hr class="mt-5">

    @foreach ($comments as $comment)
        <livewire:comments.card :$comment wire:key="comment-{{ $comment->id }}" class="mt-5" />
    @endforeach

    @if (! $post->published_at && auth()->user())
        <livewire:comments.create :post="$post" />
    @endif

    @if (! auth()->user())
        <x-button label="Login to comment" link="/?redirect_url=/posts/{{ $post->id }}" icon-right="o-arrow-right"
            class="btn-primary mt-10" />
    @endif
</div>
