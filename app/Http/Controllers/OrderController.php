<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Generate a printable view of an order
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function print(Order $order)
    {
        // Check if user is authorized to view this order
        if (!Auth::check() || (!Auth::user()->hasRole('admin') && Auth::id() !== $order->user_id)) {
            abort(403, 'Unauthorized action.');
        }

        $orderItems = $order->ordersItems()->with('product')->get();

        // Create PDF
        $pdf = Pdf::loadView('orders.print', [
            'order' => $order,
            'ordersItems' => $orderItems,
            'currency' => setting('site_currency', '$'),
        ]);

        // Return PDF for download or display in browser
        return $pdf->stream("order-{$order->uuid}.pdf");
    }
} 