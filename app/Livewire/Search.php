<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Search extends Component
{
    public function render()
    {
        $users = User::all();
        return view('livewire.search', ['users' => $users]);
    }
}
