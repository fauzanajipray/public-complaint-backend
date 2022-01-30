<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class Header extends Component
{
    public $user;

    public function mount()
    {
        $this->user = User::find(session("admin_id"));
    }  

    public function render()
    {
        return view('livewire.admin.header', [
            'user' => $this->user,
        ]);
    }
}
