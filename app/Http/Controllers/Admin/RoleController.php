<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends AdminController
{
    /**
     * @OA\Get(path="/roles",
     *   security={{"bearerAuth":{}}},
     *   tags={"Roles"},
     *   @OA\Response(response="200",
     *     description="Role Collection",
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('view', 'roles');

        return RoleResource::collection(Role::all());
    }

    /**
     * @OA\Post(
     *   path="/roles",
     *   security={{"bearerAuth":{}}},
     *   tags={"Roles"},
     *   @OA\Response(response="201",
     *     description="Role Create",
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        Gate::authorize('edit', 'roles');

        $role = Role::create($request->only('name'));

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(path="/roles/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Roles"},
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Role ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id): RoleResource
    {
        Gate::authorize('view', 'roles');

        return new RoleResource(Role::find($id));
    }

    /**
     * @OA\Put(
     *   path="/roles/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Roles"},
     *   @OA\Response(response="202",
     *     description="Role Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Role ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        Gate::authorize('edit', 'roles');

        $role = Role::find($id);

        $role->update($request->only('name'));

        DB::table('role_permission')->where('role_id', $role->id)->delete();

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(path="/roles/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Roles"},
     *   @OA\Response(response="204",
     *     description="Role Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Role ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        Gate::authorize('edit', 'roles');

        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
