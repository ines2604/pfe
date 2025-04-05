<template>
    <div id="app" style="margin: 0;">
        <div id="navbar">
            <Navbar />
        </div>
        <Carousel />
        <reserver />
        <div id="about">
            <about />
        </div>
        <div id="service">
            <cards_service />
        </div>
        <div id="hotels">
            <cards_hotels />
        </div>
        <div id="chambres">
            <cards_chambre />
        </div>
        <div id="services">
            <cards_services />
        </div>
        <div id="offres">
            <pub />
        </div>
        <video_pub />
        <avis_client />
        <Footer />
    </div>
</template>
<script>
    import Navbar from './Navbar.vue';
    import Carousel from './carrousel.vue';
    import reserver from './barre_reserver.vue';
    import about from './section_about.vue';
    import cards_service from './cards_service.vue';
    import cards_hotels from './cards_hotels.vue';
    import cards_chambre from './cards_chambre.vue';
    import cards_services from './cards_services.vue';
    import pub from './pub.vue';
    import video_pub from './video_pub.vue';
    import avis_client from './avis_client.vue';
    import Footer from './footer.vue';
    
    export default {
        name: 'App',
        components: {
            Navbar,
            Carousel,
            reserver,
            about,
            cards_service,
            cards_hotels,
            cards_chambre,
            cards_services,
            pub,
            video_pub,
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