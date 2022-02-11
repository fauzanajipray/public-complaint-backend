<?php

namespace App\Http\Livewire\Admin\Position;

use Livewire\Component;

class DeletePositionModal extends Component
{
    public $position;

    public function mount($position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('livewire.admin.position.delete-position-modal', [
            'position' => $this->position,
        ]);
    }
}
