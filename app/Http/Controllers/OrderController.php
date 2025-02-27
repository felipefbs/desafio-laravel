<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return response()->json(data: $orders, status: 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(rules: [
            'client_name' => 'required|string',
            'order_date' => 'required|date',
        ]);

        $validatedData['status'] = 'pending';
        $order = Order::create(attributes: $validatedData);

        return response()->json(data: $order, status: 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'client_name' => 'sometimes|required|string',
            'order_date' => 'sometimes|required|date',
            'delivery_date' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,delivered,canceled',
        ]);

        $order = Order::findOrFail(id: $id);

        if (in_array($order->status, ['delivered', 'canceled']) && isset($validatedData['status'])) {
            return response()->json(
                ['error' => 'Não é possível alterar o status de um pedido que já foi finalizada (entregue ou cancelada).'],
                400
            );
        }

        if ($order->status === 'delivered' && isset($validatedData['delivery_date'])) {
            if ($validatedData['delivery_date'] != $order->delivery_date) {
                return response()->json(
                    ['error' => 'Não é possível alterar a data de entrega de um pedido entregue.'],
                    400
                );
            }
        }

        $order->update($validatedData);

        return response()->json(data: $order, status: 200);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }
}
