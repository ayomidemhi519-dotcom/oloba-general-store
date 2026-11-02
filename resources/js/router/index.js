import POS from '../pages/POS.vue';
import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from '../pages/Dashboard.vue';
import Products from '../pages/Products.vue';
import DashboardLayout from '../layouts/DashboardLayout.vue';

const routes = [
    {
        path: '/',
        component: DashboardLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard,
            },
            {
                path: '/products',
                name: 'products',
                component: Products,
            },
            {
                path: '/pos',
                name: 'pos',
                component: POS,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
