@extends('pos_views.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Order Details</h1>
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Order Information</h4>
                        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4>Customer Information</h4>
                        @if ($order->customer_name)
                            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                        @endif
                        @if ($order->customer_phone)
                            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        @endif
                    </div>
                </div>

                <h4>Order Items</h4>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variation</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach (json_decode($order->items, true) as $index => $item)
                            @php
                                $total = $item['price'] * $item['quantity'];
                                $grandTotal += $total;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['product_name'] ?? '-' }}</td>
                                <td>{{ $item['variation_name'] ?? '-' }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price'], 2) }}</td>
                                <td>{{ number_format($total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Grand Total</th>
                            <th>{{ number_format($grandTotal, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
