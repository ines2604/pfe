<template>
    <Navbar id="navbar"/>
    <div class="container-fluid listeHotels" style="margin-top: 80px">
        <section class="hero-section">
            <h1 class="main-title">Nos hotels</h1>
            <p class="subtitle">Trouvez votre hébergement idéal</p>
        </section>
        <div class="row">
            <div v-for="(hotel, index) in hotels" :key="index" class="col-lg-4 col-md-6 mb-4">
            <div class="card hotel-card h-100">
                <img :src="hotel.galerie[0]" class="card-img-top" :alt="hotel.nom">
                <div class="card-body">
                <div class="hotel-rating mb-2">
                    <i v-for="star in hotel.nb_etoiles" :key="star" class="fas fa-star"></i>
                </div>
                <h5 class="card-title">{{ hotel.nom }}</h5>
                <p class="card-text location"><i class="fas fa-map-marker-alt"></i> {{ hotel.adresse }}</p>
                <p class="card-text description">
                    {{ truncateDescription(hotel.description) }}
                </p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center" @click="viewMore(hotel)">
                <button class="btn btn-primary">Voir plus</button>
                </div>
            </div>
            </div>
        </div>
        </div>
        <Footer />
    </template>
    
    <script>
    import axios from 'axios';
    import Navbar from './Navbar.vue';
    import Footer from './footer.vue';
    
    export default {
        components: {
            Navbar,
            Footer
        },
        data() {
        return {
            hotels: [],
        }
        },
        methods: {
        truncateDescription(text, length = 100) {
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
        viewMore(hotel) {
            this.$router.push({
                name: 'PageAcceuilHotel',
                params: {
                hotel_nom: hotel.nom, 
                }
            });
        },
        },
        async mounted() {
        await axios.get('/api/hotels')
            .then(response => {
                this.hotels= response.data;
            })
            .catch(error =>{
                console.error('il y a eu une erreur lors de la récupération des hôtels :' , error);
            });
    },
};
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');

    .listeHotels{
        margin: 0 auto;
        padding: 2rem;
    }

    #navbar{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
    .hero-section {
        text-align: center;
        padding: 1rem 1rem;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url("https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80");
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        margin-bottom: 1rem;
        color: white;
        animation: fadeIn 1s ease-in-out;
    }

    .main-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        font-family: 'Playfair Display' , serif;
        animation: slideIn 1s ease-in-out;
    }

    .subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        font-family: 'Poppins' , sans-serif;
        animation: slideIn 1.2s ease-in-out;
    }

    .hotel-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .card-img-top {
        height: 250px;
        object-fit: cover;
    }
    
    .hotel-rating {
        color: #ffd700;
    }
    
    .hotel-rating .fa-star {
        margin-right: 2px;
    }
    
    .hotel-rating .fa-star.active {
        color: #ffd700;
    }
    
    .location {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .location i {
        color: #2a90d9;
        margin-right: 5px;
    }
    
    .description {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #555;
    }
    
    .card-footer.bg-transparent.border-0.text-center {
        padding: 1rem;
    }
    
    .btn-primary {
        background-color: #2a90d9;
        border-color: #2a90d9;
        padding: 8px 25px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #1e6ca8;
        border-color: #1e6ca8;
        transform: translateY(-2px);
    }
    
    
    
    @media (max-width: 992px) {
        .card-img-top {
        height: 200px;
        }
    }
    
    @media (max-width: 768px) {
        .card-footer.bg-transparent.border-0.text-center {
        padding: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .card-img-top {
        height: 180px;
        }
        
        .description {
        font-size: 0.9rem;
        }
    }
</style>