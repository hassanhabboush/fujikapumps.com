<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Orders have no Eloquent model yet, so these stay on the query builder.
 * Introducing an Order model is the right fix if this area grows.
 */
class OrderController extends Controller
{
    public function data(): JsonResponse
    {
        return response()->json(DB::table('orders')->get());
    }

    public function show(int $order): JsonResponse
    {
        return response()->json(DB::table('orders')->where('id', $order)->first());
    }

    /**
     * Replaces order_details() and readorderdetails(), which were identical.
     */
    public function items(int $order): JsonResponse
    {
        return response()->json(DB::table('order_items')->where('order_id', $order)->get());
    }

    /**
     * Replaces user_order() and readuserorder(), which were identical.
     */
    public function byUser(int $user): JsonResponse
    {
        return response()->json(DB::table('orders')->where('user_id', $user)->get());
    }

    public function userDetails(int $user): JsonResponse
    {
        return response()->json(DB::table('users')->where('id', $user)->first());
    }

    public function updateStatus(UpdateOrderStatusRequest $request, int $order): JsonResponse
    {
        $updated = DB::table('orders')
            ->where('id', $order)
            ->update(['status' => $request->validated('status')]);

        return response()->json(['success' => $updated > 0]);
    }
}
