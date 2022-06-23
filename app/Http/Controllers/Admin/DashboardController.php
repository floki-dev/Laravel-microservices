<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ChartResource;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class DashboardController extends AdminController
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function chart(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Gate::authorize('view', 'orders');

        $orders = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as date, sum(order_items.quantity*order_items.price) as sum")
            ->groupBy('date')
            ->get();

        return ChartResource::collection($orders);
    }
}
