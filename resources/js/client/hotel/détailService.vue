<template>
    <div class="container-fluid py-5">
        <button class="btn-back mb-4" @click="goBack">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </button>
    
        <div v-if="loading" class="loading-state">
            <div class="spinner"></div>
            <p>Chargement du service...</p>
        </div>
    
        <div class="service-detail-card" v-else-if="service">
            <div class="row g-0">
                <div class="col-md-6 image-section">
                    <div class="type-badge" :class="getCategoryClass(service.categorie)">
                        <i :class="getTypeIcon(service.categorie)"></i>
                        <span class="badge-text">{{ service.categorie }}</span>
                    </div>
                    
                    <div class="image-carousel">
                        <img :src="currentImage" :alt="service.nom" class="service-image">
                        
                        <button v-if="service.images.length > 1" 
                                class="carousel-control prev" 
                                @click="prevImage"
                                aria-label="Image précédente">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <button v-if="service.images.length > 1" 
                                class="carousel-control next" 
                                @click="nextImage"
                                aria-label="Image suivante">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        <div v-if="service.images.length > 1" class="image-indicators">
                            <span v-for="(img, index) in service.images" 
                                    :key="index" 
                                    :class="{ active: currentImageIndex === index }"
                                    @click="goToImage(index)"></span>
                        </div>
                    </div>
                </div>
            
                <!-- Content Section -->
                <div class="col-md-6 content-section">
                    <div class="content-wrapper">
                        <div class="service-header">
                            <div class="header-top">
                                <h1 class="service-title">{{ service.nom }}</h1>
                                <div class="price-tag">
                                    <span class="amount">{{ formatPrice(service.prix) }}</span>
                                </div>
                            </div>
                        </div>
    
                        <button class="btn-reserve mb-4" 
                                :disabled="!service.disponibilite"
                                @click="handleReservation">
                            <i class="fas fa-calendar-plus"></i>
                            {{ service.disponibilite ? 'Réserver maintenant' : 'Non disponible' }}
                        </button>
    
                        <div class="service-description">
                            {{ service.description }}
                        </div>
    
                        <div class="service-features" v-if="service.points_forts && service.points_forts.length">
                            <h3>Ce qui est inclus :</h3>
                            <ul>
                                <li v-for="feature in service.points_forts" :key="feature">
                                    <i class="fas fa-check"></i>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div v-else-if="error" class="error-state">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ error }}
            </div>
        </div>
    </div>
    </template>

    <script>
    import axios from 'axios';
    
    export default {
    name: 'ServiceDetail',
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            service: null,
            loading: true,
            error: null,
            currentImageIndex: 0
        }
    },
    computed: {
        currentImage() {
            if (!this.service || !this.service.images || this.service.images.length === 0) {
                return '/images/default-service.jpg';
            }
            return this.service.images[this.currentImageIndex];
        }
    },
    methods: {
        getTypeIcon(category) {
            const icons = {
                'petit-déjeuner': 'fas fa-coffee',
                'restaurant et room service': 'fas fa-utensils',
                'restaurant': 'fas fa-utensils',
                'bar': 'fas fa-glass-martini-alt',
                'café': 'fas fa-mug-hot',
                'bar et café': 'fas fa-glass-martini-alt',
                'piscine': 'fas fa-swimming-pool',
                'spa': 'fas fa-spa',
                'piscine & spa': 'fas fa-spa',
                'salle de sport': 'fas fa-dumbbell',
                'parking': 'fas fa-parking',
                'services familiaux': 'fas fa-baby-carriage',
                'services pmr': 'fas fa-wheelchair',
                'plage privative': 'fas fa-umbrella-beach',
                'espaces de réunion': 'fas fa-business-time',
                'accés animaux': 'fas fa-paw',
                'accès animaux': 'fas fa-paw',
            };
            return icons[category?.toLowerCase()] || 'fas fa-star';
        },
        getCategoryClass(category) {
            return 'category-' + (category?.toLowerCase().replace(/[^a-z0-9]/g, '-') || 'default');
        },
        formatPrice(price) {
            if (price == null) return 'Sur demande';
            if (typeof price === 'string') return price;
            if (typeof price === 'number') return `${price} $`;
            return 'Sur demande';
        },
        async fetchServiceDetails() {
            this.loading = true;
            this.error = null;
            const serviceId = this.$route.params.id;
            try {
                const response = await axios.get(`/api/service/${serviceId}`);
                this.service = response.data;
                if (!this.service.images || !Array.isArray(this.service.images)) {
                    this.service.images = ['/images/default-service.jpg'];
                }
            } catch (error) {
                console.error('Erreur lors de la récupération du service:', error);
                this.error = 'Impossible de charger les détails du service. Veuillez réessayer plus tard.';
            } finally {
                this.loading = false;
            }
        },
        handleReservation() {
            alert('La fonctionnalité de réservation sera bientôt disponible!');
        },
        goBack() {
            this.$router.go(-1);
        },
        prevImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.service.images.length) % this.service.images.length;
        },
        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.service.images.length;
        },
        goToImage(index) {
            this.currentImageIndex = index;
        }
    },
    created() {
        this.fetchServiceDetails();
    }
    }
    </script>

    <style scoped>
    .container-fluid {
    max-width: 1400px;
    padding: 2rem;
    }

    .btn-back {
    background: none;
    border: none;
    color: #666;
    font-size: 1.1rem;
    padding: 0.5rem 1rem;
    transition: color 0.3s;
    }

    .btn-back:hover {
    color: #333;
    }

    .loading-state {
    text-align: center;
    padding: 3rem;
    }

    .spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }

    .service-detail-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
    height: 600px;
    display: flex;
    }

    .row.g-0 {
    display: flex;
    flex: 1;
    height: 100%;
    margin: 0;
    }

    .image-section {
    height: 100%;
    position: relative;
    overflow: hidden;
    flex: 0 0 50%;
    }

    .image-carousel {
    position: relative;
    height: 100%;
    width: 100%;
    }

    .service-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    }

    .carousel-control {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    }

    .carousel-control:hover {
    background-color: rgba(255, 255, 255, 1);
    }

    .carousel-control.prev {
    left: 15px;
    }

    .carousel-control.next {
    right: 15px;
    }

    .image-indicators {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 8px;
    z-index: 10;
    }

    .image-indicators span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
    }

    .image-indicators span.active {
    background-color: white;
    transform: scale(1.2);
    }

    .type-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 10;
    display: flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(4px);
    background-color: rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    }

    .type-badge i {
    margin-right: 8px;
    font-size: 1rem;
    }

    .badge-text {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .category-default {
    background-color: rgba(52, 152, 219, 0.8);
    }

    .category-petit-d-jeuner {
    background-color: rgba(230, 126, 34, 0.8);
    }

    .category-restaurant-et-room-service,
    .category-restaurant {
    background-color: rgba(231, 76, 60, 0.8);
    }

    .category-bar,
    .category-bar-et-caf-,
    .category-caf- {
    background-color: rgba(155, 89, 182, 0.8);
    }

    .category-piscine {
    background-color: rgba(41, 128, 185, 0.8);
    }

    .category-spa,
    .category-piscine-spa {
    background-color: rgba(22, 160, 133, 0.8);
    }

    .category-salle-de-sport {
    background-color: rgba(39, 174, 96, 0.8);
    }

    .category-parking {
    background-color: rgba(243, 156, 18, 0.8);
    }

    .category-services-familiaux {
    background-color: rgba(241, 196, 15, 0.8);
    }

    .category-services-pmr {
    background-color: rgba(211, 84, 0, 0.8);
    }

    .category-plage-privative {
    background-color: rgba(26, 188, 156, 0.8);
    }

    .category-espaces-de-r-union {
    background-color: rgba(52, 73, 94, 0.8);
    }

    .category-acc-s-animaux,
    .category-acc-s-animaux {
    background-color: rgba(149, 165, 166, 0.8);
    }

    .content-section {
    padding: 2rem;
    height: 100%;
    flex: 0 0 50%; 
    overflow-y: auto; 
    display: flex;
    flex-direction: column;
    }

    .content-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: min-content;
    }

    .service-header {
    margin-bottom: 2rem;
    }

    .header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    }

    .service-title {
    font-size: 2.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    flex: 1;
    }

    .price-tag {
    background: #2ecc71;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    margin-left: 1rem;
    }

    .btn-reserve {
    background: #3498db;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    transition: background-color 0.3s;
    width: 100%;
    }

    .btn-reserve:hover:not(:disabled) {
    background: #2980b9;
    }

    .btn-reserve:disabled {
    background: #bdc3c7;
    cursor: not-allowed;
    }

    .btn-reserve i {
    margin-right: 0.5rem;
    }

    .service-description {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #666;
    margin-bottom: 2rem;
    flex: 1;
    overflow-y: auto;
    }

    .service-features {
    border-top: 1px solid #eee;
    padding-top: 2rem;
    }

    .service-features h3 {
    font-size: 1.3rem;
    color: #2c3e50;
    margin-bottom: 1rem;
    }

    .service-features ul {
    list-style: none;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    }

    .service-features li {
    display: flex;
    align-items: center;
    color: #666;
    }

    .service-features li i {
    margin-right: 0.5rem;
    color: #2ecc71;
    }

    .error-state {
    text-align: center;
    padding: 3rem;
    }

    .alert {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2rem;
    border-radius: 8px;
    }

    .alert i {
    margin-right: 0.5rem;
    }

    @media (max-width: 991px) {
    .container-fluid {
    padding: 1rem;
    }

    .service-detail-card {
    height: auto;
    flex-direction: column;
    }

    .image-section {
    height: 350px;
    flex: 0 0 auto;
    }

    .service-title {
    font-size: 2rem;
    }

    .content-section {
    padding: 1.5rem;
    flex: 0 0 auto;
    overflow-y: visible;
    }
    }

    @media (max-width: 767px) {
    .service-features ul {
    grid-template-columns: 1fr;
    }

    .type-badge {
    font-size: 0.8rem;
    padding: 6px 12px;
    }

    .type-badge i {
    font-size: 0.9rem;
    }
    }
</style>