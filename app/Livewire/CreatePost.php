<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\On; 


class CreatePost extends Component
{
    public function render()
    {
        return view('livewire.comments.index');
    }

    #[On('comment-done')] 
    public function refreshComponent()
    {
        $this->emit('comment-done');
    }
}
