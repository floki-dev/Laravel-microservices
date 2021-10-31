<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/login",
     *   tags={"Public"},
     *   @OA\Response(response="200",
     *     description="Login",
     *   )
     * )
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $scope = $request->input('scope');

            $token = $user->createToken($scope, [$scope])->accessToken;

            $cookie = cookie('jwt', $token, 3600);

            return response(['token' => $token])->withCookie($cookie);
        }

        return response(['error' => 'Invalid Credentials!'], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'success'])->withCookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
            + [
                'password' => Hash::make($request->input('password')),
                'is_influencer' => 1
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(path="/user",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\Response(response="200",
     *     description="Authenticated User",
     *   )
     * )
     */
    public function user()
    {
        $user = Auth::user();

        $resource = new UserResource($user);

        if ($user->isInfluencer()) {
            return $resource;
        }

        return $resource->additional([
            'data' => [
                'permissions' => $user->permissions(),
            ],
        ]);
    }

    /**
     * @OA\Put(
     *   path="/users/info",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\Response(response="202",
     *     description="User Info Update",
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateInfoRequest")
     *   )
     * )
     */
    public function updateInfo(UpdateInfoRequest $request) // login update info
    {
        $user = Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Put(
     *   path="/users/password",
     *   security={{"bearerAuth":{}}},
     *   tags={"Profile"},
     *   @OA\Response(response="202",
     *     description="User Password Update",
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdatePasswordRequest")
     *   )
     * )
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
