<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;

class Influencer extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::all();
    }
}
