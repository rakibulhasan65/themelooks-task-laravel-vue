@extends('pos_views.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Unit</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Discount</th>
                                <th>Tax</th>
                                <th>Final Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="img-thumbnail" width="50">
                                        @else
                                            <img src="https://via.placeholder.com/50" alt="No Image" class="img-thumbnail">
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->unit_value }} {{ $product->unit }}</td>
                                    <td>${{ number_format($product->purchase_price, 2) }}</td>
                                    <td>${{ number_format($product->selling_price, 2) }}</td>
                                    <td>{{ $product->discount }}%</td>
                                    <td>{{ $product->tax }}%</td>
                                    <td>${{ number_format($product->final_price, 2) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if ($product->variations->count() > 0)
                                    <tr>
                                        <td colspan="10">
                                            <div class="ms-4">
                                                <strong>Variations:</strong>
                                                <table class="table table-sm mt-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Attributes</th>
                                                            <th>Purchase Price</th>
                                                            <th>Selling Price</th>
                                                            <th>Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->variations as $variation)
                                                            <tr>
                                                                <td>
                                                                    @php
                                                                        $attributes = is_string($variation->attributes)
                                                                            ? json_decode($variation->attributes, true)
                                                                            : $variation->attributes;
                                                                    @endphp
                                                                    @foreach ($attributes as $key => $value)
                                                                        <span
                                                                            class="badge bg-secondary">{{ ucfirst($key) }}:
                                                                            {{ $value }}</span>
                                                                    @endforeach
                                                                </td>
                                                                <td>${{ number_format($variation->purchase_price, 2) }}
                                                                </td>
                                                                <td>${{ number_format($variation->selling_price, 2) }}</td>
                                                                <td>{{ $variation->stock }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
