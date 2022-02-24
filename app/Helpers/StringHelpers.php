<?php

namespace App\Helpers;

class StringHelpers {
    public function getImageUrl(String $url) {
        $explode = explode('/', $url);
        if(count($explode) > 1) {
            $url_2 = $explode[0] . '//' . $explode[2] . '/' . $explode[3];
            // dd($explode);
            // dd($url_2);
            if ($explode[0] == 'http:' || $explode[0] == 'https:') {
                if($url_2 == url('/')){
                    return $url;
                } else {
                    return url('/') . '/' . $explode[3] . '/' . $explode[4] . '/' . $explode[5];
                }
            } else {
                return url('/') . $url;
            }
        } else {
            return $url;
        }
    }
}


?>