<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ConvertDateIndo extends Component
{
    public $dateString;

    public function mount($date)
    {   
        $date = strtotime($date);
        $dateString = $this->convertTglIndo(date('d m Y', $date));
        $this->dateString = $dateString.', '.date('H:i', $date). ' WIB';

    }

    public function render()
    {
        return view('livewire.convert-date-indo');
    }

    public function convertTglIndo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode(' ', $tanggal);
        
        return ltrim($pecahkan[0], '0') . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
    }
}
