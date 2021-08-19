<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;

class PermissionController extends AdminController
{
    public function index()
    {
        return [
            'data' => Permission::all(),
        ];
    }
}
