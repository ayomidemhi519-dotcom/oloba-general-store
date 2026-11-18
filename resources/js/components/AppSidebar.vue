
<template>
   <aside
    class="fixed inset-y-0 left-0 z-40 flex min-h-screen w-64 shrink-0 flex-col bg-slate-950 text-white transition-transform duration-300 md:sticky md:top-0 md:translate-x-0"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
>
        <!-- Brand -->
        <div class="border-b border-slate-800 px-6 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600">
                    <Store :size="23" :stroke-width="1.8" />
                </div>

                <div>
                    <h1 class="text-xl font-bold tracking-wide">
                        OLOBA<span class="text-blue-400">.</span>
                    </h1>
                    <p class="mt-1 text-xs text-slate-400">
                        General Store
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav
    class="flex-1 space-y-2 overflow-y-auto px-4 py-6"
    @click="emit('close')"
>
            <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                Main Menu
            </p>

            <router-link
                to="/"
                exact-active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <LayoutDashboard :size="19" />
                Dashboard
            </router-link>

            <router-link
                to="/products"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <Package :size="19" />
                Products
            </router-link>


            <router-link
                to="/pos"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <ShoppingCart :size="19" />
                Point of Sale
            </router-link>


            <router-link
                to="/categories"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <Tags :size="19" />
                Categories
            </router-link>

            <router-link
                to="/suppliers"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <Truck :size="19" />
                Suppliers
            </router-link>

            <div class="my-6 border-t border-slate-800"></div>

            <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                Sales & Reports
            </p>

            <router-link
                to="/sales"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <ShoppingCart :size="19" />
                Sales
            </router-link>

            <router-link
                to="/stock-movements"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <ArrowLeftRight :size="19" />
                Stock Movements
            </router-link>

            <router-link
                to="/reports"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-950/30"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
            >
                <ChartNoAxesCombined :size="19" />
                Reports
            </router-link>
        </nav>

        <!-- Footer -->
        <div class="border-t border-slate-800 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-300">
                    <UserRound :size="18" />
                </div>

                <div>
                    <p class="text-sm font-semibold">Store Admin</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Inventory Management
                    </p>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup>
const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);
import {
    Store,
    LayoutDashboard,
    Package,
    Tags,
    Truck,
    ShoppingCart,
    ArrowLeftRight,
    ChartNoAxesCombined,
    UserRound,
} from 'lucide-vue-next';
</script>
