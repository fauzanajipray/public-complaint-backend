<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class Header extends Component
{
    public $username;

    public function mount()
    {
        $this->username = User::find(session("admin_id"))->name;
    }  

    public function render()
    {
        return view('livewire.admin.header', [
            'username' => $this->username,
        ]);
    }
}
