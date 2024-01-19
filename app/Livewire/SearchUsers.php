<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class SearchUsers extends Component
{
    public $search = '';

    public function render()
    {
        $users = User::all();
        return view('livewire.search-users', ['users' => $users]);
    }
}
