<?php

namespace App\Http\Controllers;

use App\Models\Product;

class Influencer extends Controller
{
    public function index()
    {
        return Product::all();
    }
}
