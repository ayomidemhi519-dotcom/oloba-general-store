
<template>
    <div class="space-y-6">
        <!-- Page heading -->
        <div>
            <p class="text-sm text-slate-500">Inventory / Products</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Products
            </h1>
            <p class="mt-2 text-slate-500">
                Manage the products in your store.
            </p>
        </div>

        <!-- Search -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <input
                v-model="search"
                type="text"
                placeholder="Search by product name or SKU..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            />
        </div>

        <!-- Loading message -->
        <div
            v-if="loading"
            class="rounded-2xl bg-white p-8 text-center text-slate-500"
        >
            Loading products...
        </div>

        <!-- Error message -->
        <div
            v-else-if="error"
            class="rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700"
        >
            {{ error }}

            <button
                @click="getProducts"
                class="ml-3 font-semibold underline"
            >
                Try again
            </button>
        </div>

        <!-- Products table -->
        <div
            v-else
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
        >
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <h2 class="font-bold text-slate-900">
                    All Products
                </h2>

                <span class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">
                    {{ filteredProducts.length }} products
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Product Name</th>
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Selling Price</th>
                            <th class="px-6 py-4">Stock Quantity</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="product in filteredProducts"
                            :key="product.id"
                            class="border-t border-slate-100 hover:bg-slate-50"
                        >
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ product.name }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-500">
                                {{ product.sku || 'N/A' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-800">
                                {{ formatCurrency(product.selling_price) }}
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ product.stock_quantity }}
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    v-if="Number(product.stock_quantity) <= 0"
                                    class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                >
                                    Out of Stock
                                </span>

                                <span
                                    v-else-if="Number(product.stock_quantity) <= Number(product.low_stock_level)"
                                    class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700"
                                >
                                    Low Stock
                                </span>

                                <span
                                    v-else
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                >
                                    In Stock
                                </span>
                            </td>
                        </tr>

                        <tr v-if="filteredProducts.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                No products found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../services/api';

const products = ref([]);
const search = ref('');
const loading = ref(true);
const error = ref('');

const filteredProducts = computed(() => {
    return products.value.filter(product => {
        const name = (product.name || '').toLowerCase();
        const sku = (product.sku || '').toLowerCase();
        const searchText = search.value.toLowerCase();

        return name.includes(searchText) || sku.includes(searchText);
    });
});

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 2,
    }).format(Number(amount || 0));
}

async function getProducts() {
    loading.value = true;
    error.value = '';

    try {
        const response = await api.get('/products');
        const result = response.data.data;

        products.value = Array.isArray(result)
            ? result
            : result?.data || [];
    } catch (err) {
        console.error('Products error:', err);
        error.value = 'Unable to load products. Please check your Laravel server and API.';
    } finally {
        loading.value = false;
    }
}

onMounted(getProducts);
</script>
