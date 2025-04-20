@extends('pos_views.layouts.app')

@push('styles')
    <style>
        .variation-row {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Product</h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                    id="productForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $product->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label">Product SKU <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sku" name="sku"
                                    value="{{ old('sku', $product->sku) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="unit" class="form-label">Product Unit <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="unit" name="unit"
                                    value="{{ old('unit', $product->unit) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="unit_value" class="form-label">Unit Value <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="unit_value" name="unit_value"
                                    value="{{ old('unit_value', $product->unit_value) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="purchase_price"
                                    name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label">Selling Price <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="selling_price"
                                    name="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" step="0.01" class="form-control" id="discount" name="discount"
                                    value="{{ old('discount', $product->discount) }}" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tax" class="form-label">Tax (%)</label>
                                <input type="number" step="0.01" class="form-control" id="tax" name="tax"
                                    value="{{ old('tax', $product->tax) }}" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*">
                                @if ($product->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="img-thumbnail" width="100">
                                        <div class="small text-muted">Current image. Upload a new one to replace it.</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4>Product Variations</h4>
                        <p class="text-muted">Edit existing variations or add new ones.</p>

                        <div id="variations-container">
                            @foreach ($product->variations as $index => $variation)
                                <div class="variation-item variation-row">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <h5>Variation {{ $index + 1 }}</h5>
                                            <button type="button"
                                                class="btn btn-sm btn-danger remove-variation float-end">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div id="attributes-container-{{ $index }}" class="mb-3">
                                                <label class="form-label">Variation Attributes <span
                                                        class="text-danger">*</span></label>
                                                @php
                                                    $attributes = is_string($variation->attributes)
                                                        ? json_decode($variation->attributes, true)
                                                        : $variation->attributes;
                                                @endphp
                                                @foreach ($attributes as $key => $value)
                                                    <div class="attribute-pair mb-2">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control attribute-key"
                                                                name="variations[{{ $index }}][attributes][key][]"
                                                                value="{{ $key }}" placeholder="e.g. Color"
                                                                required>
                                                            <span class="input-group-text">:</span>
                                                            <input type="text" class="form-control attribute-value"
                                                                name="variations[{{ $index }}][attributes][value][]"
                                                                value="{{ $value }}" placeholder="e.g. Red"
                                                                required>
                                                            @if ($loop->first)
                                                                <button type="button"
                                                                    class="btn btn-outline-success add-attribute"
                                                                    data-index="{{ $index }}">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            @else
                                                                <button type="button"
                                                                    class="btn btn-outline-danger remove-attribute">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Purchase Price <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="variations[{{ $index }}][purchase_price]"
                                                    value="{{ $variation->purchase_price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Selling Price <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="variations[{{ $index }}][selling_price]"
                                                    value="{{ $variation->selling_price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Stock</label>
                                                <input type="number" class="form-control"
                                                    name="variations[{{ $index }}][stock]"
                                                    value="{{ $variation->stock }}" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-primary mt-2" id="add-variation-btn">
                            <i class="fas fa-plus"></i> Add Variation
                        </button>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Variation Template -->
   <!-- Variation Row Hidden (Template Alternative) -->
<div id="variation-template" class="d-none">
    <div class="variation-row variation-item">
        <div class="row">
            <div class="col-md-12 mb-2">
                <h5>Variation <span class="variation-number"></span></h5>
                <button type="button" class="btn btn-sm btn-danger remove-variation float-end">
                    <i class="fas fa-times"></i> Remove
                </button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3 attributes-container">
                    <label class="form-label">Variation Attributes <span class="text-danger">*</span></label>
                    <div class="attribute-pair mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control attribute-key"
                                name="variations[{index}][attributes][key][]" placeholder="e.g. Color" required>
                            <span class="input-group-text">:</span>
                            <input type="text" class="form-control attribute-value"
                                name="variations[{index}][attributes][value][]" placeholder="e.g. Red" required>
                            <button type="button" class="btn btn-outline-success add-attribute">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control"
                        name="variations[{index}][purchase_price]" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control"
                        name="variations[{index}][selling_price]" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="variations[{index}][stock]" value="0" min="0">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let variationCount = {{ $product->variations->count() }};

            // Add variation button
            document.getElementById('add-variation-btn').addEventListener('click', function() {
                addVariation();
            });

            // Function to add variation
            function addVariation() {
                const template = document.getElementById('variation-template').innerHTML;
                const varContainer = document.getElementById('variations-container');

                // Replace placeholder index with actual index
                const newVariation = template.replace(/{index}/g, variationCount);

                // Create a div element to hold the variation
                const div = document.createElement('div');
                div.className = 'variation-item';
                div.innerHTML = newVariation;

                // Update variation number
                div.querySelector('.variation-number').textContent = variationCount + 1;

                // Add event listener to remove button
                div.querySelector('.remove-variation').addEventListener('click', function() {
                    div.remove();
                });

                // Add event listener to add attribute button
                div.querySelector('.add-attribute').addEventListener('click', function() {
                    addAttributePair(div, variationCount);
                });

                // Append the new variation to the container
                varContainer.appendChild(div);

                // Increment variation count
                variationCount++;
            }

            // Function to add attribute pair
            function addAttributePair(variationDiv, variationIndex) {
                const attributesContainer = variationDiv.querySelector('#attributes-container') ||
                    document.getElementById('attributes-container-' + variationIndex);

                // Create new attribute pair
                const attributePair = document.createElement('div');
                attributePair.className = 'attribute-pair mb-2';
                attributePair.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control attribute-key" 
                           name="variations[${variationIndex}][attributes][key][]" placeholder="e.g. Size" required>
                    <span class="input-group-text">:</span>
                    <input type="text" class="form-control attribute-value" 
                           name="variations[${variationIndex}][attributes][value][]" placeholder="e.g. XL" required>
                    <button type="button" class="btn btn-outline-danger remove-attribute">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;

                // Add event listener to remove attribute button
                attributePair.querySelector('.remove-attribute').addEventListener('click', function() {
                    attributePair.remove();
                });

                // Append to attributes container
                attributesContainer.appendChild(attributePair);
            }

            // Add event listener to existing add attribute buttons
            document.querySelectorAll('.add-attribute[data-index]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const container = document.getElementById('attributes-container-' + index);
                    const parentItem = container.closest('.variation-item');
                    addAttributePair(parentItem, index);
                });
            });

            // Add event listener to existing remove attribute buttons
            document.querySelectorAll('.remove-attribute').forEach(function(button) {
                button.addEventListener('click', function() {
                    const attributePair = this.closest('.attribute-pair');
                    attributePair.remove();
                });
            });

            // Add event listener to existing remove variation buttons
            document.querySelectorAll('.remove-variation').forEach(function(button) {
                button.addEventListener('click', function() {
                    const variationItem = this.closest('.variation-item');
                    variationItem.remove();
                });
            });

            // Process form before submission to convert attribute inputs to JSON
            document.getElementById('productForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Process each variation
                document.querySelectorAll('.variation-item').forEach(function(variation, index) {
                    const keys = Array.from(variation.querySelectorAll('.attribute-key')).map(
                        input => input.value);
                    const values = Array.from(variation.querySelectorAll('.attribute-value')).map(
                        input => input.value);

                    // Create attributes object
                    const attributes = {};
                    keys.forEach((key, i) => {
                        if (key && values[i]) {
                            attributes[key] = values[i];
                        }
                    });

                    // Create hidden input with JSON attributes
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `variations[${index}][attributes]`;
                    hiddenInput.value = JSON.stringify(attributes);

                    variation.appendChild(hiddenInput);
                });

                // Submit the form
                this.submit();
            });
        });
    </script>
@endpush
