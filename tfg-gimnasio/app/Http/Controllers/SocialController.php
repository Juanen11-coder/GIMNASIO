<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    private function readJson($filename){
        $path = storage_path('app/public/data/'. $filename);
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return json_decode($content, true);
    }
        private function writeJson($filename, $data){
            
        $path = storage_path('app/public/data/' . $filename);
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function feed(){

    }
};


