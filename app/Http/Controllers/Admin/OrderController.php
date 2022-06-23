<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class OrderController extends AdminController
{
    /**
     * @OA\Get(path="/orders",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     * @OA\Response(response="200",
     *     description="Order Collection",
     *   )
     * )
     * @throws                      \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('view', 'orders');

        $order = Order::paginate();

        return OrderResource::collection($order);
    }

    /**
     * @OA\Get(path="/orders/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     * @OA\Response(response="200",
     *     description="User",
     *   ),
     * @OA\Parameter(
     *     name="id",
     *     description="Order ID",
     *     in="path",
     *     required=true,
     * @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     * @throws                      \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id): OrderResource
    {
        Gate::authorize('view', 'orders');

        return new OrderResource(Order::find($id));
    }

    /**
     * @OA\Get(path="/export",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     * @OA\Response(response="200",
     *     description="Order Export",
     *   )
     * )
     * @throws                      \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        Gate::authorize('view', 'orders');

        $headers = [
            'Content-type' => 'text/csv',
            // предупредить пользователя о необходимости сохранить пересылаемые данные, такие как сгенерированный файл
            'Content-Disposition' => 'attachment; filename=orders.csv',
            // То же, что и Cache-Control: no-cache. Заставляет кеши отправлять запрос на исходный сервер для проверки перед выпуском кешированной копии.
            'Pragma' => 'no-cache',
            // must revalidate - Кеш должен проверить статус устаревших ресурсов перед их использованием. Просроченные ресурсы не должны быть использованы.
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = static function () {
            $orders = Order::all();
            $file = fopen('php://output', 'w');

            //Header Row
            fputcsv($file, ['ID', 'Name', 'Email', 'Order Title', 'Price', 'Quantity']);

            //Body
            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);

                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }
}
