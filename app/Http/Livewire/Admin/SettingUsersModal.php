<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class SettingUsersModal extends Component
{
    public $user, $positions;

    public function mount($user, $positions)
    {
        $this->user = $user;
        $this->positions = $positions;
    }

    public function render()
    {
        return view('livewire.admin.setting-users-modal', [
            'user' => $this->user,
            'positions' => $this->positions,
        ]);
    }
}
