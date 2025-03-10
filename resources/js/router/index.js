import { createRouter, createWebHistory } from "vue-router";

const routes = [
    { path: '/', component: () => import('../App.vue') },
    { path: '/login', component: () => import('../Login.vue'),name: 'login' },
    { path: '/register', component: () => import('../Register.vue') },
    { path: '/forgot-password', component: () => import('../ForgotPassword.vue') },
    { path: '/home', component: () => import('../Home.vue') },
    { path: '/reset-password/:token', component: () => import('../ResetPassword.vue'), props: true },
    { path: '/profile', component: () => import('../Profile.vue'), name: 'profile'},
    { path: '/:pathMatch(.*)*', component: () => import('../NotFound.vue') }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { top: 0 };
    }
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'login' });
    } else {
        next();
    }
});

export default router;