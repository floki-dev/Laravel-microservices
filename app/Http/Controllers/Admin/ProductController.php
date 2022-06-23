<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AdminController
{
    /**
     * @OA\Get(path="/products",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="200",
     *     description="Product Collection",
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    /**
     * @OA\Get(path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id): ProductResource
    {
        \Gate::authorize('view', 'products');

        return new ProductResource(Product::find($id));
    }

    /**
     * @OA\Post(
     *   path="/products",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="201",
     *     description="Product Create",
     *   )
     * )
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProductCreateRequest $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        Gate::authorize('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *   path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="202",
     *     description="Product Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
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
        Gate::authorize('edit', 'products');

        $product = Product::find($id);

        $product->update($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="204",
     *     description="Product Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
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
        Gate::authorize('edit', 'products');

        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
