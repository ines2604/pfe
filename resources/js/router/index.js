import { createRouter, createWebHistory } from "vue-router";

const routes = [
    { path: '/', component: () => import('../App.vue') },
    { path: '/login', component: () => import('../client/authentification/login.vue'),name: 'login' },
    { path: '/register', component: () => import('../client/authentification/register.vue') },
    { path: '/forgot-password', component: () => import('../client/authentification/forgotPassword.vue') },
    {path: '/verify-otp', name: 'VerifyOtp', component: () =>import('../client/authentification/verify-otp.vue'), props: (route)=> ({user_id : route.query.user_id})},
    { path: '/home', component: () => import('../client/page_accueil/Home.vue') },
    { path: '/reset-password/:token', component: () => import('../client/authentification/resetPassword.vue'), props: true },
    { path: '/profile', component: () => import('../client/authentification/Profile.vue'), name: 'profile'},
    { path: '/:pathMatch(.*)*', component: () => import('../NotFound.vue') },
    {path: '/home/aboutus' , component: ()=> import('../client/page_accueil/about_us.vue') , name: 'aboutUs'},
    {path: '/:hotel_nom/chambre/:id', name: 'DétailsChambre', component: () => import ('../client/hotel/détailChambre.vue'), props: true},
    {path: '/:hotel_nom/offre/:id', name: 'DétailsOffre', component: () =>import ('../client/hotel/détailOffre.vue') , props: true},
    {path: '/:hotel_nom/service/:id' , name: 'DétailsService' , component: ()=> import ('../client/hotel/détailService.vue'), props : true},
    {path: '/:hotel_nom', name: 'PageAcceuilHotel', component: ()=> import('../client/hotel/hotel.vue') , props: true},
    {path: '/:hotel_nom/listeChambres' , component: () =>import('../client/hotel/listeChambres.vue'), name: 'listeChambres'},
    {path: '/:hotel_nom/contactus' , component: () => import('../client/hotel/contact.vue'), name: 'contactUs'},
    {path: '/:hotel_nom/listeOffres', component:()=> import ('../client/hotel/liste_offres.vue') ,name: 'listeOffres'},
    {path: '/:hotel_nom/listeServices' ,component: ()=> import ('../client/hotel/liste_services.vue'),name: 'listeServices'},
    {path: '/home/listeHotels' , component: ()=> import('../client/page_accueil/listeHotels.vue')},
    {path: '/home/listeChambres' , component: ()=> import ('../client/page_accueil/listeChambres.vue')},
    {path: '/home/listeServices' , component: () => import('../client/page_accueil/listeServices.vue')},
    {path: '/home/listeOffres' , component: ()=> import('../client/page_accueil/listeOffres.vue')},
    {path: '/:hotel_nom/aboutus' , component: () => import('../client/hotel/aboutus.vue')},
    {path: '/:hotel_nom/galerie' , component: ()=> import('../client/hotel/galerie.vue')},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else if (to.hash) {
            return {
                el: to.hash,
                behavior: "smooth",
            };
        } else {
            return { top: 0 };
        }
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