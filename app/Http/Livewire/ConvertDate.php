<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ConvertDate extends Component
{
    public $dateString;

    public function mount($date)
    {
        $dateString = $this->convertTime(strtotime($date));

        $this->dateString = $dateString;
    }

    public function render()
    {
        return view('livewire.convert-date', [
            'dateString' => $this->dateString,
        ]);
    }

    public function convertTime( $time )
    {
        $time_difference = time() - $time; 
    
        if( $time_difference < 1 ) { return 'kurang dari 1 detik'; }
        $condition = array( 12 * 30 * 24 * 60 * 60 =>  'tahun',
                    30 * 24 * 60 * 60       =>  'bulan',
                    24 * 60 * 60            =>  'hari',
                    60 * 60                 =>  'jam',
                    60                      =>  'menit',
                    1                       =>  'detik'
        );
    
        foreach( $condition as $secs => $str )
        {
            $d = $time_difference / $secs;
    
            if( $d >= 1 )
            {
                $t = round( $d );
                return ' ' . $t . ' ' . $str . ( $t > 1 ? '' : '' ) . ' yang lalu';
            }
        }
    }    
}
