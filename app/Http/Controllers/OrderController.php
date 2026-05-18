<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('Pages.order');
    }

    public function readall()
    {
        $orders = DB::table('orders')->get();
        return response()->json($orders);
    }

    public function user_order($uid)
    {
        $orders = DB::table('orders')->where('user_id', $uid)->get();
        return response()->json($orders);
    }

    public function order_details($oid)
    {
        $items = DB::table('order_items')->where('order_id', $oid)->get();
        return response()->json($items);
    }

    public function readuserorder($uid)
    {
        $orders = DB::table('orders')->where('user_id', $uid)->get();
        return response()->json($orders);
    }

    public function readorderdetails($oid)
    {
        $items = DB::table('order_items')->where('order_id', $oid)->get();
        return response()->json($items);
    }

    public function changestatus(Request $request)
    {
        $id     = $request->input('id');
        $status = $request->input('status');
        DB::table('orders')->where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true]);
    }

    public function orderdetils($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        return response()->json($order);
    }

    public function user_details($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return response()->json($user);
    }
}
