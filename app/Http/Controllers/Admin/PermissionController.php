<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Permission;

class PermissionController extends AdminController
{
    public function index(): array
    {
        return [
            'data' => Permission::all(),
        ];
    }
}
