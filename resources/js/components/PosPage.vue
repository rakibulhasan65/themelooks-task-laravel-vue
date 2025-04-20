<template>
    <div class="container">
        <!-- Main POS Layout -->
         <div v-if="orderSuccessMessage" class="alert alert-success mb-3 p-3">
                Order placed successfully!
            </div>
        <div class="pos-wrapper">
            <!-- Products Section -->
            
            <section class="product-section">
                 <!-- order success message -->
            
                <div class="section-header">
                    <h2>Product List</h2>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <input
                        type="text"
                        class="search-input"
                        placeholder="Search products..."
                        v-model="searchQuery"
                    />
                </div>

                <!-- Product Grid -->
                <div class="product-grid" v-if="products.length">
                    <!-- Product 1 -->
                    <div
                        class="product-card"
                        v-for="product in products"
                        :key="product.id"
                    >
                        <div class="product-image">
                            <img :src="product.image" :alt="product.name" />
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ product.name }}</h3>
                            <p class="product-price">
                                ৳{{ product.selling_price }}
                            </p>
                            <button
                                class="btn btn-primary btn-block"
                                @click="addToCart(product)"
                            >
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center mt-5">
                    <i class="fas fa-shopping-cart fa-3x"></i>
                    <p class="mt-2">No products found</p>
                </div>
                <div>
                    <pagination
                        :data="products"
                        @pagination-change-page="fetchProducts"
                    />
                </div>
            </section>

            <!-- Cart Section -->
            <section class="cart-section">
                <div class="section-header">
                    <h2>Cart</h2>
                </div>

                <div class="cart-container">
                    <div class="cart-items" v-if="cartItems.length">
                        <!-- Sample Cart Items -->
                        <div
                            class="cart-item"
                            v-for="item in cartItems"
                            :key="item.id"
                        >
                            <div class="item-details">
                                <div class="item-name">{{ item.name }}</div>
                                <div class="item-price">
                                    {{ item.quantity }} × ৳{{
                                        item.selling_price
                                    }}
                                </div>
                            </div>
                            <div class="item-actions">
                                <div class="item-total">
                                    ৳{{ totalPrice(item) }}
                                </div>
                                <div class="item-quantity">
                                    <button
                                        class="btn btn-danger btn-sm quantity-btn"
                                        @click="quantityDecrement(item)"
                                    >
                                        -
                                    </button>
                                    <span
                                        class="quantity-value"
                                        v-text="item.quantity"
                                    ></span>
                                    <button
                                        class="btn btn-success btn-sm quantity-btn"
                                        @click="quantityIncrement(item)"
                                    >
                                        +
                                    </button>
                                </div>
                                <button
                                    class="btn btn-danger btn-sm rounded-circle"
                                    @click="removeItem(item)"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="empty-cart" v-else>
                        <p>Your cart is empty</p>
                    </div>

                    <div class="mt-4 p-3">
                        <div class="">
                            <h5 class="card-title mb-3">Cart Summary</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <strong>৳{{ subtotalPrice }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax:</span>
                                <strong>৳{{ totalTax }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Discount:</span>
                                <strong>৳{{ totalDiscount }}</strong>
                            </div>

                            <hr />

                            <div class="d-flex justify-content-between mb-3">
                                <span class="h6">Total:</span>
                                <span class="h6 text-success"
                                    >৳{{ grandTotal }}</span
                                >
                            </div>

                            <button
                                class="btn btn-success w-100"
                                @click="placeOrder"
                            >
                                <i class="fa-solid fa-check"></i>
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from "vue";

const products = ref([]);
const searchQuery = ref("");
const currentPage = ref(1);
const cartItems = ref([]);
const customerName = ref("");
const customerPhone = ref("");
const orderSuccessMessage = ref(false);

const totalPrice = (item) => {
    // Calculate the subtotal price of all items in the cart
    const price = parseFloat(item.selling_price) || 0;
    const tax = parseFloat(item.tax) || 0;
    const discount = parseFloat(item.discount) || 0;
    const quantity = parseInt(item.quantity) || 1;

    return price * quantity + tax - discount;
};

const subtotalPrice = computed(() => {
    return cartItems.value.reduce((total, item) => {
        const price = parseFloat(item.selling_price) || 0;
        const quantity = parseInt(item.quantity) || 1;
        return total + price * quantity;
    }, 0);
});

const totalTax = computed(() => {
    return cartItems.value.reduce((total, item) => {
        return total + (parseFloat(item.tax) || 0);
    }, 0);
});

const totalDiscount = computed(() => {
    return cartItems.value.reduce((total, item) => {
        return total + (parseFloat(item.discount) || 0);
    }, 0);
});

const grandTotal = computed(() => {
    return cartItems.value.reduce((total, item) => {
        return total + totalPrice(item);
    }, 0);
});

const quantityIncrement = (item) => {
    item.quantity++;
};

const quantityDecrement = (item) => {
    if (item.quantity > 1) {
        item.quantity--;
    }
};

const removeItem = (item) => {
    const index = cartItems.value.indexOf(item);
    if (index > -1) {
        cartItems.value.splice(index, 1);
    }
};

const addToCart = (product) => {
    const existingItem = cartItems.value.find((item) => item.id === product.id);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cartItems.value.push({ ...product, quantity: 1 });
    }
};

const fetchProducts = async () => {
    try {
        const response = await fetch(
            `api/pos/products?search=${searchQuery.value}&page={$currentPage.value}`
        );
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const data = await response.json();
        // console.log(data);
        products.value = data.data;
    } catch (error) {
        console.error("Error fetching products:", error);
    }
};

watch(searchQuery, () => {
    currentPage.value = 1; // Reset to the first page when search query changes
    if (searchQuery.value.length > 2 || searchQuery.value.length === 0) {
        fetchProducts();
    }
});

// order place function
const placeOrder = async () => {
    try {
        const payload = {
            items: cartItems.value.map((item) => ({
                product_id: item.id,
                variation_id: item.variation_id || null,
                quantity: item.quantity,
                price: item.selling_price,
            })),
            subtotal: subtotalPrice.value,
            tax: totalTax.value,
            discount: totalDiscount.value,
            total: grandTotal.value,
            customer_name: customerName.value || null,
            customer_phone: customerPhone.value || null,
        };

        const response = await fetch("/api/pos/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(payload),
        });

        // const data = await response.json();

        if (response.ok) {
            orderSuccessMessage.value = true;
            cartItems.value = []; // Clear the cart after successful order
        }
    } catch (error) {
        console.error("Error placing order:", error);
    }
};

onMounted(() => {
    fetchProducts();
});
</script>

<style scoped>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --danger: #f72585;
    --warning: #f8961e;
    --info: #560bad;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --border-radius: 0.375rem;
    --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --transition: all 0.2s ease-in-out;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f9f9f9;
    color: var(--dark);
    line-height: 1.5;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
}

/* Header */
.header {
    background: linear-gradient(135deg, var(--primary), var(--info));
    color: white;
    padding: 1.25rem 0;
    margin-bottom: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.header h1 {
    text-align: center;
    font-weight: 600;
    font-size: 1.75rem;
}

/* Main Layout */
.pos-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.product-section {
    flex: 2;
    min-width: 320px;
}

.cart-section {
    flex: 1;
    min-width: 280px;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.section-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.product-card {
    background-color: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    transition: var(--transition);
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.product-image {
    height: 160px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 1rem;
    text-align: center;
}

.product-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.product-price {
    color: var(--gray);
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.btn {
    display: inline-block;
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
    line-height: 1.5;
    border-radius: var(--border-radius);
    transition: var(--transition);
    cursor: pointer;
}

.btn-primary {
    color: white;
    /* background-color: var(--primary); */
    border-color: var(--primary);
}

.btn-primary:hover {
    /* background-color: var(--secondary); */
    border-color: var(--secondary);
}

.btn-success {
    color: white;
    /* background-color: var(--success); */
    border-color: var(--success);
}

.btn-success:hover {
    /* background-color: #38b8dd; */
    border-color: #38b8dd;
}

.btn-danger {
    color: white;
    /* background-color: var(--danger); */
    border-color: var(--danger);
}

.btn-danger:hover {
    /* background-color: #e61c77; */
    border-color: #e61c77;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.btn-block {
    display: block;
    width: 100%;
}

/* Cart Styles */
.cart-container {
    background-color: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    height: calc(100% - 2.5rem);
    display: flex;
    flex-direction: column;
}

.cart-items {
    flex-grow: 1;
    padding: 1rem;
    overflow-y: auto;
    max-height: 400px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-details {
    flex-grow: 1;
}

.item-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.item-price {
    color: var(--gray);
    font-size: 0.8rem;
}

.item-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.item-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-btn {
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
    padding: 0;
}

.quantity-value {
    font-weight: 600;
    min-width: 1rem;
    text-align: center;
}

.cart-summary {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.total-label {
    font-weight: 600;
}

.total-value {
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--primary);
}

.empty-cart {
    text-align: center;
    padding: 2rem;
    color: var(--gray);
}

.empty-cart i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Categories */
.categories {
    margin-bottom: 1.5rem;
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.category-btn {
    white-space: nowrap;
    background-color: white;
    border: 1px solid #dee2e6;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    border-radius: 2rem;
    cursor: pointer;
    transition: var(--transition);
}

.category-btn:hover,
.category-btn.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Search Bar */
.search-container {
    position: relative;
    margin-bottom: 1.5rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border-radius: var(--border-radius);
    border: 1px solid #dee2e6;
    transition: var(--transition);
    font-size: 0.9rem;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

/* Responsive */
@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .pos-wrapper {
        flex-direction: column;
    }

    .cart-section {
        order: -1;
    }

    .cart-items {
        max-height: 300px;
    }
}

/* Utilities */
.text-center {
    text-align: center;
}

.mb-1 {
    margin-bottom: 0.5rem;
}

.mb-2 {
    margin-bottom: 1rem;
}
</style>
