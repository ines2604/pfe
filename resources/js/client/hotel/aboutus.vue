<template>
    <Navbar id="navbar" />
    <div class="about-container">
        <section class="hero-section" style="margin-top: 80px">
            <h1 class="main-title">À propos</h1>
            <p class="subtitle">Découvrez notre histoire et nos valeurs</p>
        </section>
    
        <div v-if="isLoading" class="loading-container">
            <div class="loading-animation">
                <div class="loading-circle"></div>
                <div class="loading-circle"></div>
                <div class="loading-circle"></div>
            </div>
            <p aria-live="polite" class="loading-text">Chargement...</p>
        </div>
    
        <div v-else class="about-content">
            <div class="hotel-description">
                <div 
                    v-for="(paragraph, index) in descriptionParagraphs" 
                    :key="index" 
                    class="description-paragraph"
                >
                    <div class="paragraph-container" :class="{ 'reverse': index % 2 === 0 }">
                        <div class="text-content">
                            <p>{{ paragraph }}</p>
                        </div>
                        <div class="image-content" v-if="hotel.galerie[index]">
                            <img 
                                :src="hotel.galerie[index]" 
                                :alt="'Image de l\'hôtel'"
                                class="paragraph-image"
                                loading="lazy"
                            />
                        </div>
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
            isLoading: true,
            hotel: {
                nom: '',
                description: '',
                galerie: []
            },
        };
    },
    computed: {
        descriptionParagraphs() {
            return this.hotel.description.split('\n').filter(p => p.trim() !== '');
        }
    },
    methods: {
        async fetchHotelData() {
            this.isLoading = true;
            try {
                const hotelNom = this.$route.params.hotel_nom;
                const response = await axios.get(`/api/hotels/${hotelNom}`);
                
                this.hotel = {
                    nom: response.data.nom,
                    description: response.data.description || "Aucune description disponible",
                    galerie: response.data.galerie || []
                };
                
            } catch (error) {
                console.error("Erreur lors du chargement des données:", error);
            } finally {
                this.isLoading = false;
            }
        }
    },
    mounted() {
        this.fetchHotelData();
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.about-container {
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

#navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}

.hero-section {
    text-align: center;
    padding: 1rem 1rem;
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
        url("https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80");
    background-size: cover;
    background-position: center;
    border-radius: 15px;
    margin-bottom: 1rem;
    color: white;
    animation: fadeIn 1s ease-in-out;
}

.main-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
    font-family: 'Playfair Display', serif;
}

.subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    font-family: 'Poppins', sans-serif;
}

.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
}

.loading-animation {
    display: flex;
    gap: 8px;
}

.loading-circle {
    width: 12px;
    height: 12px;
    background-color: #2a90d9;
    border-radius: 50%;
    animation: bounce 0.5s ease-in-out infinite;
}

.loading-circle:nth-child(2) {
    animation-delay: 0.1s;
}

.loading-circle:nth-child(3) {
    animation-delay: 0.2s;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.loading-text {
    margin-top: 1.5rem;
    color: #64748b;
    font-size: 1.1rem;
}

.about-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.hotel-description {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.description-paragraph {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #f0f0f0;
}

.description-paragraph:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.description-paragraph p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
    font-family: 'Poppins', sans-serif;
    margin: 0;
}

.paragraph-container {
    display: flex;
    align-items: center;
    gap: 3rem;
}

.reverse {
    flex-direction: row-reverse;
}

.text-content {
    flex: 3;
    min-width: 50%;
}

.image-content {
    flex: 2;
    min-height: 200px;
    max-height: 300px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    background: #f8f8f8;
}

.paragraph-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.paragraph-image:hover {
    transform: scale(1.03);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .about-container {
        padding: 1rem;
    }

    .hero-section {
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
    }

    .main-title {
        font-size: 2.2rem;
    }

    .subtitle {
        font-size: 1rem;
    }

    .hotel-description {
        padding: 1.5rem;
    }

    .paragraph-container {
        flex-direction: column;
        gap: 1.5rem;
    }

    .reverse {
        flex-direction: column;
    }

    .text-content,
    .image-content {
        width: 100%;
        flex: none;
    }

    .image-content {
        height: 250px;
        max-height: none;
        min-height: auto;
        margin-top: 0.5rem;
    }

    .description-paragraph {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .main-title {
        font-size: 1.8rem;
    }
    
    .description-paragraph p {
        font-size: 1rem;
    }
    
    .image-content {
        height: 200px;
    }
}
</style>