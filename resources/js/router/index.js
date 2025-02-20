import { createRouter,createWebHistory } from "vue-router";
import home from '../HomePage.vue';
import about from '../AboutPage.vue';
import NotFoundPage from '../NotFoundPage.vue';

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/about',
        component: about
    },
    {
        path:'/:pathMatch(.*)*',
        component: NotFoundPage
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router