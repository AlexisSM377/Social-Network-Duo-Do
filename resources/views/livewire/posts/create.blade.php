<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Post $post;
    public string $title;
    public string $content;
    public string $category_id;

    public function categories(): Collection
    {
        return Category::all();
    }

    public function save()
    {
        $post = new Post();
        $post->title = $this->title;
        $post->content = $this->content;
        $post->category_id = $this->category_id;
        $post->author_id = auth()->user()->id;
        $post->save();

        $this->success('Post created.', redirectTo: "/dashboard");
    }

    public function with(): array
    {
        return ['categories' => $this->categories()];
    }
}; ?>

<div>
    <x-header title="Crear post" separator
        class="bg-gradient-to-br from-rose-400 via-stone-400 to-red-500 bg-clip-text text-transparent" />
    <div class="grid lg:grid-cols-4 gap-10">
        <x-form wire:submit="save" class="col-span-3">
            <x-input label="Title" wire:model="title" />

            <x-select label="Category" wire:model="category_id" placeholder="Selecciona una categoria" :options="$categories" />

            <x-textarea label="Body" wire:model="content" rows="5" @keydown.meta.enter="$wire.save()" />

            <x-slot:actions>
                <x-button label="Cancel" link="/dashboard" />
                <x-button label="Create" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </div>
</div>
