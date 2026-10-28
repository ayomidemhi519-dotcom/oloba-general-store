
<template>
    <div class="flex min-h-screen bg-slate-100">
        <AppSidebar />

        <div class="flex min-w-0 flex-1 flex-col">
            <!-- Top header -->
            <header
                class="sticky top-0 z-10 flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-4 md:px-8"
            >
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                        OLOBA GENERAL STORE
                    </p>

                    <h2 class="mt-1 text-lg font-bold text-slate-900">
                        Inventory Dashboard
                    </h2>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search placeholder -->
                    <label
                        class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 md:flex"
                    >
                        <Search :size="18" class="text-slate-400" />

                        <input
                            type="search"
                            placeholder="Search inventory..."
                            class="w-40 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400"
                        />
                    </label>

                    <!-- Notification placeholder -->
                    <button
                        type="button"
                        aria-label="Notifications"
                        class="relative rounded-xl border border-slate-200 bg-white p-2.5 text-slate-600 transition hover:bg-slate-50"
                    >
                        <Bell :size="19" />
                    </button>

                    <!-- New sale -->
                    <button
                        type="button"
                        @click="$router.push('/sales')"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        <Plus :size="18" />
                        New Sale
                    </button>
                </div>
            </header>

            <!-- Current route page -->
            <main class="flex-1 p-5 md:p-8">
                <router-view />
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white px-6 py-4">
                <p class="text-center text-xs text-slate-500">
                    © {{ new Date().getFullYear() }} OLOBA General Store. Inventory Management System.
                </p>
            </footer>
        </div>
    </div>
</template>

<script setup>
import AppSidebar from '../components/AppSidebar.vue';

import {
    Search,
    Bell,
    Plus,
} from 'lucide-vue-next';
</script>
