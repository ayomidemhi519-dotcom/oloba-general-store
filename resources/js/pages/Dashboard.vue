
<template>
    <div class="dashboard-shell space-y-8">
        <!-- Page heading -->



<section class="space-y-4">
    <div class="flex items-start justify-between gap-3">
        <!-- Welcome message -->
        <div class="min-w-0">
            <p class="text-sm text-slate-500">
                Overview / Dashboard
            </p>

            <h1 class="mt-2 text-xl font-bold text-slate-900 sm:text-2xl md:text-3xl">
                Welcome back!
            </h1>

            <p class="mt-2 text-xs text-slate-500 sm:text-sm">
                Here's what's happening at your store today.
            </p>
        </div>

        <!-- Refresh button -->
        <button
            type="button"
            @click="loadDashboard"
            :disabled="loading"
            class="mt-7 inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-3 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60 sm:px-4 sm:text-sm"
        >
            <RefreshCw
                :size="16"
                :class="{ 'animate-spin': loading }"
            />
            <span class="hidden sm:inline">
                {{ loading ? 'Refreshing...' : 'Refresh Data' }}
            </span>
            <span class="sm:hidden">Refresh</span>
        </button>
    </div>

    <!-- Date and time -->
    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 sm:text-sm">
        <span>{{ formattedDate }}</span>
        <span class="text-slate-300">•</span>
        <span class="font-semibold text-slate-700">
            {{ formattedTime }}
        </span>
    </div>
</section>



        <!-- Loading state -->
        <div
            v-if="loading && !hasLoaded"
            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-8 text-slate-600"
        >
            <RefreshCw :size="20" class="animate-spin text-blue-600" />
            Loading your store data...
        </div>

        <!-- Error state -->
        <div
            v-else-if="error"
            class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
        >
            <div class="flex items-start gap-3">
                <TriangleAlert :size="22" class="mt-1 shrink-0" />

                <div>
                    <h2 class="font-semibold">
                        Unable to load dashboard data
                    </h2>

                    <p class="mt-1 text-sm">{{ error }}</p>

                    <button
                        type="button"
                        @click="loadDashboard"
                        class="mt-4 rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800"
                    >
                        Try Again
                    </button>
                </div>
            </div>
        </div>

        <template v-else>
            <!-- Statistics cards -->
            <section class="grid grid-cols-2 gap-3 sm:gap-5 xl:grid-cols-4">
                <!-- Products -->
                <article class="dashboard-stat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="rounded-xl bg-blue-50 p-3 text-blue-600">
                            <Package :size="25" :stroke-width="1.8" />
                        </div>

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            Inventory
                        </span>
                    </div>

                    <p class="mt-5 text-sm font-medium text-slate-500">
                        Total Products
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ products.length }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500">
                        Products recorded in your store
                    </p>
                </article>

                <!-- Revenue -->
                <article class="dashboard-stat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                            <Wallet :size="25" :stroke-width="1.8" />
                        </div>

                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Revenue
                        </span>
                    </div>

                    <p class="mt-5 text-sm font-medium text-slate-500">
                        Total Revenue
                    </p>

                    <p class="mt-2 break-words text-2xl font-bold text-slate-900">
                        {{ formatCurrency(totalRevenue) }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500">
                        Sum of recorded sale totals
                    </p>
                </article>

                <!-- Sales -->
                <article class="dashboard-stat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="rounded-xl bg-violet-50 p-3 text-violet-600">
                            <ShoppingCart :size="25" :stroke-width="1.8" />
                        </div>

                        <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">
                            Transactions
                        </span>
                    </div>

                    <p class="mt-5 text-sm font-medium text-slate-500">
                        Total Sales
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ sales.length }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500">
                        Recorded sales transactions
                    </p>
                </article>

                <!-- Low stock -->
                <article class="dashboard-stat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                            <TriangleAlert :size="25" :stroke-width="1.8" />
                        </div>

                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                            Attention
                        </span>
                    </div>

                    <p class="mt-5 text-sm font-medium text-slate-500">
                        Low-stock Products
                    </p>

                    <p class="mt-2 text-3xl font-bold text-amber-600">
                        {{ lowStockProducts.length }}
                    </p>

                    <p class="mt-2 text-xs text-slate-500">
                        Products needing attention
                    </p>
                </article>
            </section>

            <!-- Sales Performance Chart -->
<section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">
                Sales Performance
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Revenue generated over the last 7 days
            </p>
        </div>

        <div class="rounded-lg bg-blue-50 px-3 py-2">
            <span class="text-xs font-medium text-blue-700">
                Last 7 days
            </span>
        </div>
    </div>

    <div v-if="!loading" class="space-y-4">
        <!-- Chart -->
        <div class="flex h-64 items-end gap-2 sm:gap-4">
            <div
                v-for="(day, index) in salesChartData"
                :key="index"
                class="flex h-full min-w-0 flex-1 flex-col items-center justify-end"
            >
                <!-- Revenue -->
                <div class="mb-2 text-center">
                    <span
                        class="text-[9px] font-semibold text-slate-500 sm:text-xs"
                    >
                        {{ day.revenue > 0 ? formatCurrency(day.revenue) : '₦0' }}
                    </span>
                </div>

                <!-- Bar -->
                <div class="flex w-full flex-1 items-end justify-center">
                    <div
                        class="w-full max-w-12 rounded-t-lg bg-blue-500 transition-all duration-300 hover:bg-blue-600"
                        :style="{
                            height: `${Math.max(
                                day.revenue > 0
                                    ? (day.revenue / Math.max(...salesChartData.map(item => item.revenue), 1)) * 100
                                    : 0,
                                day.revenue > 0 ? 8 : 0
                            )}%`,
                        }"
                        :title="`${formatCurrency(day.revenue)} - ${day.count} sales`"
                    ></div>
                </div>

                <!-- Day -->
                <span class="mt-3 text-xs font-medium text-slate-500">
                    {{ day.label }}
                </span>
            </div>
        </div>

        <!-- Chart Bottom Info -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-sm bg-blue-500"></span>
                <span class="text-xs text-slate-500">Daily Revenue</span>
            </div>

            <div class="text-right">
                <p class="text-xs text-slate-500">7-day revenue</p>
                <p class="text-sm font-bold text-slate-900">
                    {{ formatCurrency(
                        salesChartData.reduce((total, day) => total + day.revenue, 0)
                    ) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div
        v-else
        class="flex h-64 items-center justify-center"
    >
        <p class="text-sm text-slate-500">
            Loading sales performance...
        </p>
    </div>
</section>

            <!-- Recent sales and low-stock alerts -->
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <!-- Recent sales -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5">
                        <div>
                            <h2 class="font-bold text-slate-900">
                                Recent Sales
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Latest recorded transactions
                            </p>
                        </div>

                        <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
                            {{ sales.length }} records
                        </span>
                    </div>

                    <div v-if="sales.length === 0" class="p-8 text-center">
                        <ShoppingCart :size="34" class="mx-auto text-slate-300" />

                        <p class="mt-3 font-medium text-slate-700">
                            No sales recorded yet
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Recorded transactions will appear here.
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Invoice</th>
                                    <th class="px-6 py-4 font-semibold">Date</th>
                                    <th class="px-6 py-4 font-semibold">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="sale in recentSales"
                                    :key="sale.id"
                                    class="border-t border-slate-100 transition hover:bg-slate-50"
                                >
                                    <td class="whitespace-nowrap px-3 py-4 font-medium text-slate-800 sm:px-6">
                                        {{ sale.invoice_number || `Sale #${sale.id}` }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-slate-500 sm:px-6">
                                        {{ formatDate(sale.created_at) }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 font-semibold text-emerald-600 sm:px-6">
                                        {{ formatCurrency(Number(sale.total || 0)) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-slate-100 px-6 py-4">
                        <p class="text-xs text-slate-500">
                            Showing up to 5 recent transactions.
                        </p>
                    </div>
                </div>

                <!-- Low-stock alerts -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5">
                        <div>
                            <h2 class="font-bold text-slate-900">
                                Low-stock Alerts
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Restocking reminders
                            </p>
                        </div>

                        <span class="rounded-lg bg-amber-50 px-3 py-2 text-sm font-bold text-amber-700">
                            {{ lowStockProducts.length }}
                        </span>
                    </div>

                    <div v-if="lowStockProducts.length === 0" class="p-6">
                        <div class="rounded-xl bg-emerald-50 p-4">
                            <CheckCircle :size="23" class="text-emerald-600" />

                            <p class="mt-2 font-semibold text-emerald-800">
                                All clear!
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">
                                No products are currently at or below their stock limits.
                            </p>
                        </div>
                    </div>

                    <div v-else class="divide-y divide-slate-100">
                        <div
                            v-for="product in lowStockProducts"
                            :key="product.id"
                            class="px-5 py-4"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800">
                                        {{ product.name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        SKU: {{ product.sku || 'N/A' }}
                                    </p>
                                </div>

                                <span class="shrink-0 rounded-lg bg-red-50 px-2 py-1 text-xs font-bold text-red-700">
                                    {{ product.stock_quantity }} left
                                </span>
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Stock limit: {{ product.low_stock_level }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Inventory overview -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-slate-900">
                            Inventory Overview
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            A quick look at your recorded inventory.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <Boxes :size="18" />
                        {{ products.length }} products
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl bg-slate-50 p-5">
                        <div class="flex items-center gap-2 text-slate-500">
                            <Boxes :size="18" />
                            <p class="text-sm">Total Stock Units</p>
                        </div>

                        <p class="mt-3 text-2xl font-bold text-slate-900">
                            {{ totalStockUnits.toLocaleString() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-emerald-50 p-5">
                        <div class="flex items-center gap-2 text-emerald-700">
                            <CheckCircle :size="18" />
                            <p class="text-sm">Products In Stock</p>
                        </div>

                        <p class="mt-3 text-2xl font-bold text-emerald-700">
                            {{ productsInStock }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-red-50 p-5">
                        <div class="flex items-center gap-2 text-red-700">
                            <XCircle :size="18" />
                            <p class="text-sm">Out-of-stock Products</p>
                        </div>

                        <p class="mt-3 text-2xl font-bold text-red-700">
                            {{ outOfStockProducts }}
                        </p>
                    </div>
                </div>
            </section>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

import {
    Package,
    Wallet,
    ShoppingCart,
    TriangleAlert,
    RefreshCw,
    Boxes,
    CheckCircle,
    XCircle,
} from 'lucide-vue-next';

import api from '../services/api';

const products = ref([]);
const sales = ref([]);
const lowStockProducts = ref([]);

const loading = ref(true);
const hasLoaded = ref(false);
const error = ref('');
const currentDateTime = ref(new Date());

let clockInterval;

// ==============================
// DATE & TIME
// ==============================

const formattedDate = computed(() => {
    return new Intl.DateTimeFormat('en-NG', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(currentDateTime.value);
});

const formattedTime = computed(() => {
    return new Intl.DateTimeFormat('en-NG', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    }).format(currentDateTime.value);
});

// ==============================
// DASHBOARD STATISTICS
// ==============================

const totalRevenue = computed(() => {
    return sales.value.reduce((total, sale) => {
        return total + Number(sale.total || 0);
    }, 0);
});

const recentSales = computed(() => {
    return [...sales.value]
        .sort((a, b) => {
            return new Date(b.created_at || 0) - new Date(a.created_at || 0);
        })
        .slice(0, 5);
});

const totalStockUnits = computed(() => {
    return products.value.reduce((total, product) => {
        return total + Number(product.stock_quantity || 0);
    }, 0);
});

const productsInStock = computed(() => {
    return products.value.filter(
        product => Number(product.stock_quantity) > 0
    ).length;
});

const outOfStockProducts = computed(() => {
    return products.value.filter(
        product => Number(product.stock_quantity) <= 0
    ).length;
});

// ==============================
// SALES PERFORMANCE CHART DATA
// ==============================

const salesChartData = computed(() => {
    const days = [];

    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        date.setHours(0, 0, 0, 0);

        const nextDay = new Date(date);
        nextDay.setDate(nextDay.getDate() + 1);

        const dailySales = sales.value.filter(sale => {
            if (!sale.created_at) return false;

            const saleDate = new Date(sale.created_at);

            return saleDate >= date && saleDate < nextDay;
        });

        const revenue = dailySales.reduce((total, sale) => {
            return total + Number(sale.total || 0);
        }, 0);

        days.push({
            label: new Intl.DateTimeFormat('en-NG', {
                weekday: 'short',
            }).format(date),

            date: date,

            revenue: revenue,

            count: dailySales.length,
        });
    }

    return days;
});

// ==============================
// TOP SELLING PRODUCTS
// ==============================

const topSellingProducts = computed(() => {
    const productMap = {};

    sales.value.forEach(sale => {
        (sale.items || []).forEach(item => {
            const product = item.product;

            if (!product) return;

            if (!productMap[product.id]) {
                productMap[product.id] = {
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    quantitySold: 0,
                    revenue: 0,
                };
            }

            productMap[product.id].quantitySold += Number(item.quantity || 0);

            productMap[product.id].revenue +=
                Number(item.quantity || 0) * Number(item.unit_price || 0);
        });
    });

    return Object.values(productMap)
        .sort((a, b) => b.quantitySold - a.quantitySold)
        .slice(0, 5);
});
// ==============================
// FORMATTING FUNCTIONS
// ==============================

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 2,
    }).format(amount);
}

function formatDate(date) {
    if (!date) return '—';

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) return '—';

    return new Intl.DateTimeFormat('en-NG', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(parsedDate);
}

// ==============================
// LOAD DASHBOARD DATA
// ==============================

async function loadDashboard() {
    loading.value = true;
    error.value = '';

    try {
        const [
            productsResponse,
            salesResponse,
            lowStockResponse,
        ] = await Promise.all([
            api.get('/products'),
            api.get('/sales'),
            api.get('/low-stock-products'),
        ]);

        products.value = productsResponse.data.data || [];
        sales.value = salesResponse.data.data || [];

        console.log('First sale:', JSON.stringify(sales.value[0], null, 2));

        lowStockProducts.value = lowStockResponse.data.data || [];

        hasLoaded.value = true;
    } catch (err) {
        console.error('Dashboard API error:', err);

        error.value =
            'Could not load store data. Check that Laravel is running and the API endpoints are working.';
    } finally {
        loading.value = false;
    }
}

// ==============================
// LIFECYCLE
// ==============================

onMounted(() => {
    loadDashboard();

    clockInterval = setInterval(() => {
        currentDateTime.value = new Date();
    }, 1000);
});

onUnmounted(() => {
    clearInterval(clockInterval);
});
</script>


<style scoped>
.dashboard-shell {
    position: relative;
}
</style>
