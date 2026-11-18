
<template>
    <div class="flex min-h-screen bg-slate-100">
        <!-- Mobile overlay -->
        <button
            v-if="isSidebarOpen"
            type="button"
            aria-label="Close navigation menu"
            class="fixed inset-0 z-30 bg-black/50 md:hidden"
            @click="isSidebarOpen = false"
        ></button>

        <AppSidebar
            :is-open="isSidebarOpen"
            @close="isSidebarOpen = false"
        />

        <div class="flex min-w-0 flex-1 flex-col">
            <!-- Header -->

   <header
    class="sticky top-0 z-20 border-b border-slate-200 bg-white px-3 py-3 sm:px-5 md:px-8 md:py-4"
   >
    <div class="flex items-center justify-between gap-2">
        <!-- Left: Menu and brand -->
        <div class="flex min-w-0 items-center gap-2 sm:gap-3">
            <button
                type="button"
                aria-label="Toggle navigation menu"
                class="shrink-0 rounded-lg border border-slate-200 p-2 text-slate-700 hover:bg-slate-100 md:hidden"
                @click="isSidebarOpen = !isSidebarOpen"
            >
                <Menu v-if="!isSidebarOpen" :size="21" />
                <X v-else :size="21" />
            </button>

            <div class="min-w-0">
                <p class="truncate text-[10px] font-semibold uppercase tracking-wider text-blue-600 sm:text-xs">
                    OLOBA GENERAL STORE
                </p>
                <h2 class="mt-1 truncate text-sm font-bold text-slate-900 sm:text-lg">
                    Inventory Dashboard
                </h2>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex shrink-0 items-center gap-2">
            <button
                type="button"
                @click="$router.push('/sales')"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 p-2.5 text-white shadow-sm transition hover:bg-blue-700 sm:px-4 sm:py-2.5"
                aria-label="New Sale"
            >
                <Plus :size="18" />
                <span class="hidden text-sm font-semibold sm:inline">
                    New Sale
                </span>
            </button>

            <button
                type="button"
                aria-label="Notifications"
                class="relative rounded-xl border border-slate-200 bg-white p-2.5 text-slate-600 transition hover:bg-slate-50"
            >
                <Bell :size="19" />
            </button>
        </div>
    </div>
</header>


            <!-- Current page -->
            <main class="min-w-0 flex-1 p-4 sm:p-5 md:p-8">
                <router-view />
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white px-4 py-4">
                <p class="text-center text-xs text-slate-500">
                    © {{ new Date().getFullYear() }} OLOBA General Store.
                    Inventory Management System.
                </p>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import AppSidebar from '../components/AppSidebar.vue';

import {
    Search,
    Bell,
    Plus,
    Menu,
    X,
} from 'lucide-vue-next';

const isSidebarOpen = ref(false);
</script>
