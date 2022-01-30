<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ShowModalComplaint extends Component
{
    public $complaint;

    public function mount($complaint)
    {
        $this->complaint = $complaint;
    }

    public function render()
    {
        return view('livewire.admin.show-modal-complaint', [
            'complaint' => $this->complaint,
        ]);
    }
}
