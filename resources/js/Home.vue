<template>
    <div>
        <h1>Bienvenue sur la page d'accueil</h1>
        <button @click="goToProfile" v-if="isAuthenticated">Mon Profil</button>
        <p v-else>Veuillez vous connecter pour accéder à cette fonctionnalité.</p>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isAuthenticated: false,
        };
    },
    mounted() {
        this.checkAuthentication();
    },
    methods: {
        checkAuthentication() {
            const token = localStorage.getItem('token');
            this.isAuthenticated = !!token;
        },
        goToProfile() {
            if (this.isAuthenticated) {
                this.$router.push({ name: 'profile'}); 
            } else {
                alert("Veuillez vous connecter.");
                this.$router.push({name: 'login'});
            }
        }
    }
};
</script>