<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use JetBrains\PhpStorm\ArrayShape;

class PermissionController extends AdminController
{
    #[ArrayShape(['data' => "mixed"])] public function index(): array
    {
        return [
            'data' => Permission::all(),
        ];
    }
}
