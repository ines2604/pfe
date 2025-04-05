<template>
    <div class="container-fluid py-5">
        <button class="btn-back mb-4" @click="goBack">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </button>
    
        <div v-if="loading" class="loading-state">
            <div class="spinner"></div>
            <p>Chargement de la chambre...</p>
        </div>
    
        <div class="service-detail-card" v-else-if="room">
            <div class="row g-0">
            <div class="col-md-6 image-section">
                <div class="image-carousel">
                <img :src="currentImage" :alt="room.nom" class="service-image">
                
                <button v-if="room.images.length > 1" 
                        class="carousel-control prev" 
                        @click="prevImage"
                        aria-label="Image précédente">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <button v-if="room.images.length > 1" 
                        class="carousel-control next" 
                        @click="nextImage"
                        aria-label="Image suivante">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div v-if="room.images.length > 1" class="image-indicators">
                    <span v-for="(img, index) in room.images" 
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
                    <h1 class="service-title">{{ room.nom }}</h1>
                    <div class="price-display">
                        <span class="current-price">{{ formatPrice(room.prix) }}</span>
                        <span class="price-info">/nuit</span>
                    </div>
                    </div>
                    
                    <div class="room-features">
                    <div class="feature">
                        <i class="fas fa-bed"></i>
                        {{ room.nb_lits }} lits
                    </div>
                    <div class="feature">
                        <i class="fas fa-expand"></i>
                        {{ room.surface }} m²
                    </div>
                    <div class="feature">
                        <i class="fas fa-user-friends"></i>
                        {{ room.capacite }} personnes
                    </div>
                    </div>
                </div>
    
                <button class="btn-reserve mb-4" 
                        @click="bookRoom(room)">
                    <i class="fas fa-calendar-check"></i>
                    Réserver maintenant
                </button>
    
                <div class="service-description">
                    {{ room.description }}
                </div>
    
                <div class="service-features" v-if="room.points_forts && room.points_forts.length">
                    <h3>Équipements inclus :</h3>
                    <ul>
                    <li v-for="(feature, index) in room.points_forts" :key="index">
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
        name: 'ChambreDetail',
        data() {
        return {
            room: null,
            loading: true,
            error: null,
            currentImageIndex: 0
        }
        },
        computed: {
        currentImage() {
            if (!this.room || !this.room.images || this.room.images.length === 0) {
            return '/images/default-chambre.jpg';
            }
            return this.room.images[this.currentImageIndex];
        }
        },
        methods: {
        formatPrice(price) {
            if (price == null) return 'Sur demande';
            if (typeof price === 'string') return price;
            if (typeof price === 'number') return `${price} $`;
            return 'Sur demande';
        },
        async fetchRoomDetails() {
            this.loading = true;
            this.error = null;
            const roomId = this.$route.params.id;
            try {
            const response = await axios.get(`/api/chambre/${roomId}`);
            this.room = response.data;
            if (!this.room.images || !Array.isArray(this.room.images)) {
                this.room.images = ['/images/default-chambre.jpg'];
            }
            } catch (error) {
            console.error('Erreur lors de la récupération de la chambre:', error);
            this.error = 'Impossible de charger les détails de la chambre. Veuillez réessayer plus tard.';
            } finally {
            this.loading = false;
            }
        },
        bookRoom(room) {
            alert(`Réservation de la chambre : ${room.nom}`);
        },
        goBack() {
            this.$router.go(-1);
        },
        prevImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.room.images.length) % this.room.images.length;
        },
        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.room.images.length;
        },
        goToImage(index) {
            this.currentImageIndex = index;
        }
        },
        created() {
        this.fetchRoomDetails();
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
    
    .price-display {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }
    
    .current-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2ecc71;
    }
    
    .price-info {
        font-size: 1rem;
        color: #64748b;
    }
    
    .room-features {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        color: #475569;
    }
    
    .feature i {
        color: #2a90d9;
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
    
        .room-features {
        gap: 1rem;
        }
    }
    
    @media (max-width: 768px) {
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
    
        .current-price {
        font-size: 1.5rem;
        }
        
        .price-info {
        font-size: 0.9rem;
        }
    
        .room-features {
        flex-direction: column;
        gap: 0.5rem;
        }
    }
</style>