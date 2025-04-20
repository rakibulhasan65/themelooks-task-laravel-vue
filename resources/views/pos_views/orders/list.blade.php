@extends('pos_views.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Orders</h1>
            <a href="{{ route('pos') }}" class="btn btn-primary">
                <i class="fas fa-cash-register"></i> New Order
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Filter Orders</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date"
                            value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Subtotal</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if ($order->customer_name)
                                            {{ $order->customer_name }}
                                            @if ($order->customer_phone)
                                                <div class="small text-muted">{{ $order->customer_phone }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted">Walk-in Customer</span>
                                        @endif
                                    </td>
                                    <td>{{ count($order->items) }}</td>
                                    <td>${{ number_format($order->subtotal, 2) }}</td>
                                    <td>${{ number_format($order->tax, 2) }}</td>
                                    <td>${{ number_format($order->discount, 2) }}</td>
                                    <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
