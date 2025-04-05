<template>
    <div id="app" style="margin: 0;">
        <div id="navbar">
            <Navbar />
        </div>
        <Carousel />
        <div id="service">
            <cards_service />
        </div>
        <div id="chambres">
            <cards_chambre />
        </div>
        <div id="services">
            <cards_services />
        </div>
        <div id="offres">
            <cards_offres />
        </div>
        <avis_client />
        <Footer />
    </div>
</template>
<script>
    import Navbar from './Navbar.vue';
    import Carousel from './carrousel.vue';
    import cards_service from './cards_service.vue';
    import cards_chambre from './cards_chambre.vue';
    import cards_services from './cards_services.vue';
    import cards_offres from './cards_offres.vue';
    import avis_client from './avis_client.vue';
    import Footer from './footer.vue';
    
    export default {
        name: 'App',
        components: {
            Navbar,
            Carousel,
            cards_service,
            cards_chambre,
            cards_services,
            cards_offres,
            avis_client,
            Footer,
        }
    };
</script>

<style>
    #app{
        position:relative;
    }
    #navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
</style>