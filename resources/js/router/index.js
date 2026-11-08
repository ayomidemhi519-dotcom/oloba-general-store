
import POS from '../pages/POS.vue';
import { createRouter, createWebHistory } from 'vue-router';

import Register from '../pages/Register.vue';
import Login from '../pages/Login.vue';
import Dashboard from '../pages/Dashboard.vue';
import Products from '../pages/Products.vue';
import DashboardLayout from '../layouts/DashboardLayout.vue';

const routes = [
    // Dashboard pages (with sidebar and navbar)
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
                path: 'products',
                name: 'products',
                component: Products,
            },
            {
                path: 'pos',
                name: 'pos',
                component: POS,
            },
        ],
    },

    // Authentication pages (without sidebar and navbar)
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
