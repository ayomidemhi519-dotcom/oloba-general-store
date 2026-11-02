
<script setup>
import { ref, computed } from 'vue';
import api from '../services/api';

const barcode = ref('');
const cart = ref([]);
const message = ref('');
const paymentMethod = ref('cash');
const loading = ref(false);
const completedSale = ref(null);
const searchResults = ref([]);

function addToCart(product) {
    const existingProduct = cart.value.find(
        item => item.id === product.id
    );

    const currentQuantity = existingProduct
        ? existingProduct.quantity
        : 0;

    if (currentQuantity >= product.stock_quantity) {
        message.value = 'Not enough stock available.';
        return;
    }

    if (existingProduct) {
        existingProduct.quantity++;
        message.value = `${product.name} quantity increased.`;
    } else {
        cart.value.push({
            ...product,
            quantity: 1,
        });

        message.value = `${product.name} added to cart.`;
    }

    searchResults.value = [];
    barcode.value = '';
}

async function addProduct() {
    const searchTerm = barcode.value.trim();

    if (!searchTerm) {
        message.value = 'Please enter a product name, SKU or barcode.';
        return;
    }

    message.value = '';
    searchResults.value = [];

    try {
        // First, check whether the entry is an exact barcode.
        try {
            const response = await api.get(
                `/products/barcode/${encodeURIComponent(searchTerm)}`
            );

            addToCart(response.data.data);
            return;
        } catch (error) {
            // If it is not a barcode, search by name, SKU or barcode.
        }

        const response = await api.get(
            `/products?search=${encodeURIComponent(searchTerm)}`
        );

        const products = response.data.data;

        if (products.length === 0) {
            message.value = 'No matching product found.';
        } else if (products.length === 1) {
            addToCart(products[0]);
        } else {
            searchResults.value = products;
            message.value = 'Select the product you want to add.';
        }
    } catch (error) {
        message.value =
            error.response?.data?.message ||
            'Unable to search for products.';
    }
}

function removeProduct(productId) {
    cart.value = cart.value.filter(item => item.id !== productId);
}

function printReceipt() {
    window.print();
}

function paymentLabel(method) {
    const methods = {
        cash: 'Cash',
        transfer: 'Bank Transfer',
        card: 'Card',
    };

    return methods[method] || method;
}

const total = computed(() => {
    return cart.value.reduce(
        (sum, item) =>
            sum + Number(item.selling_price) * item.quantity,
        0
    );
});

async function completeSale() {
    if (cart.value.length === 0) {
        message.value = 'Please add products before completing the sale.';
        return;
    }

    loading.value = true;
    message.value = '';

    try {
        const response = await api.post('/sales', {
            payment_method: paymentMethod.value,
            items: cart.value.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
            })),
        });

        completedSale.value = response.data.data;
        message.value =
            `${response.data.message} Invoice: ` +
            response.data.data.invoice_number;

        cart.value = [];
        paymentMethod.value = 'cash';
    } catch (error) {
        message.value =
            error.response?.data?.message ||
            'Sale failed. Please check the stock and try again.';
    } finally {
        loading.value = false;
    }
}

</script>

<template>
    <div class="min-h-screen bg-slate-100 p-6">
        <h1 class="mb-2 text-3xl font-bold text-slate-800">
            Point of Sale
        </h1>

        <p class="mb-6 text-slate-500">
            Scan products or enter a barcode to begin checkout.
        </p>


<div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
    <form @submit.prevent="addProduct" class="flex gap-3">
        <input
            v-model="barcode"
            type="text"
            placeholder="Search product name, SKU or scan barcode"
            class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500"
            autofocus
        >

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
        >
            Search Product
        </button>
    </form>

    <p v-if="message" class="mt-3 break-words text-sm text-slate-600">
        {{ message }}
    </p>

    <div v-if="searchResults.length > 0" class="mt-4 space-y-2">
        <div
            v-for="product in searchResults"
            :key="product.id"
            class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 p-3"
        >
            <div>
                <p class="font-semibold text-slate-800">
                    {{ product.name }}
                </p>

                <p class="text-sm text-slate-500">
                    SKU: {{ product.sku }} |
                    Stock: {{ product.stock_quantity }} |
                    ₦{{ Number(product.selling_price).toLocaleString() }}
                </p>
            </div>

            <button
                @click="addToCart(product)"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Add
            </button>
        </div>
    </div>
</div>


        <div class="rounded-xl bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-xl font-bold text-slate-800">
                Checkout Cart
            </h2>

            <div v-if="cart.length === 0" class="py-10 text-center text-slate-500">
                No products added yet. Search for a product to begin.
                
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b text-sm text-slate-500">
                            <th class="p-3">Product</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Subtotal</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="item in cart" :key="item.id" class="border-b">
                            <td class="p-3 font-medium">{{ item.name }}</td>
                            <td class="p-3">
                                ₦{{ Number(item.selling_price).toLocaleString() }}
                            </td>
                            <td class="p-3">{{ item.quantity }}</td>
                            <td class="p-3">
                                ₦{{ (Number(item.selling_price) * item.quantity).toLocaleString() }}
                            </td>
                            <td class="p-3">
                                <button
                                    @click="removeProduct(item.id)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t pt-5">
                <div class="mb-5 flex items-center justify-between">
                    <span class="text-lg font-semibold text-slate-700">
                        Total Amount
                    </span>

                    <span class="text-2xl font-bold text-blue-700">
                        ₦{{ total.toLocaleString() }}
                    </span>
                </div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Payment Method
                </label>

                <select
                    v-model="paymentMethod"
                    class="mb-5 w-full rounded-lg border border-slate-300 bg-white px-4 py-3"
                >
                    <option value="cash">Cash</option>
                    <option value="transfer">Bank Transfer</option>
                    <option value="card">Card</option>
                </select>

                <button
                    @click="completeSale"
                    :disabled="loading || cart.length === 0"
                    class="w-full rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ loading ? 'Processing Sale...' : 'Complete Sale' }}
                </button>
            </div>
        </div>
    </div>
    <!-- Customer Receipt -->
<div
    v-if="completedSale"
    class="receipt-print mx-auto mt-6 max-w-md rounded-xl bg-white p-6 text-slate-900 shadow-lg"
>
    <div class="text-center">
        <h2 class="text-2xl font-bold">OLOBA GENERAL STORE</h2>
        <p class="mt-1 text-sm text-slate-500">
            Customer Sales Receipt
        </p>
        <div class="my-4 border-t border-dashed border-slate-400"></div>
    </div>

    <div class="space-y-2 text-sm">
        <p>
            <strong>Invoice:</strong>
            {{ completedSale.invoice_number }}
        </p>

        <p>
            <strong>Date:</strong>
            {{ new Date(completedSale.created_at).toLocaleString() }}
        </p>

        <p>
            <strong>Payment:</strong>
            {{ paymentLabel(completedSale.payment_method) }}
        </p>
    </div>

    <div class="my-4 border-t border-dashed border-slate-400"></div>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-300">
                <th class="py-2">Product</th>
                <th class="py-2 text-center">Qty</th>
                <th class="py-2 text-right">Price</th>
                <th class="py-2 text-right">Amount</th>
            </tr>
        </thead>

        <tbody>
            <tr
                v-for="item in completedSale.items"
                :key="item.id"
                class="border-b border-slate-100"
            >
                <td class="py-2">{{ item.product.name }}</td>
                <td class="py-2 text-center">{{ item.quantity }}</td>
                <td class="py-2 text-right">
                    ₦{{ Number(item.unit_price).toLocaleString() }}
                </td>
                <td class="py-2 text-right">
                    ₦{{ Number(item.subtotal).toLocaleString() }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="my-4 border-t border-dashed border-slate-400"></div>

    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span>Subtotal</span>
            <span>
                ₦{{ Number(completedSale.subtotal).toLocaleString() }}
            </span>
        </div>

        <div
            v-if="Number(completedSale.discount) > 0"
            class="flex justify-between"
        >
            <span>Discount</span>
            <span>
                ₦{{ Number(completedSale.discount).toLocaleString() }}
            </span>
        </div>

        <div class="flex justify-between border-t border-slate-300 pt-3 text-lg font-bold">
            <span>Total</span>
            <span>
                ₦{{ Number(completedSale.total).toLocaleString() }}
            </span>
        </div>
    </div>

    <div class="mt-6 text-center">
        <p class="font-semibold">Thank you for shopping with us!</p>
        <p class="mt-1 text-xs text-slate-500">
            Please keep this receipt for your records.
        </p>

        <button
            @click="printReceipt"
            class="no-print mt-5 w-full rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700"
        >
            Print Receipt
        </button>
    </div>
</div>
</template>
<style>
@media print {
    body * {
        visibility: hidden;
    }

    .receipt-print,
    .receipt-print * {
        visibility: visible;
    }

    .receipt-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 20px;
        background: white !important;
        color: black !important;
        box-shadow: none !important;
    }

    .no-print {
        display: none !important;
    }
}
</style>
