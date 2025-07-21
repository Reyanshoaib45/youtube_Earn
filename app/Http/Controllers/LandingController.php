<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('price')->take(5)->get();
        
        return view('landing', compact('packages'));
    }
}
