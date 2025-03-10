<template>
    <div class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light text-center" role="main">
        <h1 class="display-1 fw-bold text-primary" aria-label="Erreur 404">404</h1>
        <p class="fs-4 text-muted" aria-label="Oups ! Page non trouvée.">Oups ! Page non trouvée.</p>
        <p class="text-secondary" aria-label="La page que vous cherchez n'existe pas.">La page que vous cherchez n'existe pas.</p>
        <router-link to="/" class="btn btn-primary mt-3" aria-label="Retour à l'accueil">
            Retour à l'accueil
        </router-link>
    </div>
</template>

<script>
export default{
    name: 'notFound',
    metaInfo:{
        title: 'Page non trouvée - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'La page que vous cherchez n\'existe pas. Retournez à l\'accueil pour continuer votre navigation.' },
            { name: 'robots', content: 'noindex, nofollow' } 
        ]
    }
}
</script>

<style scoped>
h1 {
    text-shadow: 3px 3px 10px #007bff;
}
.btn-primary {
    padding: 10px;
    background: linear-gradient(135deg, #007bff, #00bfff); 
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
}
</style>
