<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function saveOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'required|string',
            'address'       => 'required|string',
            'status'  => 'required|string',
            'totalPrice'   => 'required|numeric',
            'orderItem' => 'required|array',
            'orderItem.*.id' => 'required|integer',
            'orderItem.*.qty' => 'required|integer',
            'orderItem.*.price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create([
            'user_name' => $request->input('username'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'total_price' => $request->input('totalPrice'),
            'order_status' => $request->input('status'),
            'order_date' => now(), // or use $request->input('orderDate') if you're sending it
        ]);

        foreach ($request->input('orderItem') as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'food_id' => $item['id'], // mapped from 'id'
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['message' => 'Order saved successfully!']);
    }
    public function getOrderHistory(Request $request)
    {
        // Get the user's email from the request
        $email = $request->input('email');

        // Fetch orders for the user, eager loading orderDetails and related food
        $orders = Order::where('email', $email)
            ->with(['orderDetails.food:id,name']) // eager load food name and id
            ->get();

        // Format the data to your desired structure
        $orderHistory = $orders->map(function ($order) {
            return [
                'items' => $order->orderDetails->map(function ($detail) {
                    return [
                        'id' => $detail->food->id,
                        'name' => $detail->food->name,
                        'price' => $detail->price,
                        'quantity' => $detail->quantity,
                    ];
                }),
                'totalPrice' => $order->total_price,
                'orderDate' => date('Y-m-d H:i:s', strtotime($order->order_date)),
                'status' => $order->order_status,
            ];
        });

        return response()->json($orderHistory);
    }
}
