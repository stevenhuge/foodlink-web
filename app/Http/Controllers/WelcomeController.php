<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller
{
    public function index() {
        $filePath = storage_path('app/visitor_count.txt');
        
        $visitorCount = 0;
        if (File::exists($filePath)) {
            $visitorCount = (int) File::get($filePath);
        }
        
        $visitorCount++;
        File::put($filePath, $visitorCount);

        return view('welcome', compact('visitorCount'));
    }
}
