<template>
    <div class="container-fluid py-5">
        <button class="btn-back mb-4" @click="goBack">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </button>
    
        <div v-if="loading" class="loading-state">
            <div class="spinner"></div>
            <p>Chargement de l'offre...</p>
        </div>
    
        <div class="service-detail-card" v-else-if="offre">
            <div class="row g-0">
            <div class="col-md-6 image-section">
                <div class="type-badge" :class="getCategoryClass(offre.categorie)">
                <i :class="getTypeIcon(offre.categorie)"></i>
                <span class="badge-text">{{ offre.categorie }}</span>
                </div>
                
                <div class="image-carousel">
                <img :src="currentImage" :alt="offre.nom" class="service-image">
                
                <button v-if="offre.images.length > 1" 
                        class="carousel-control prev" 
                        @click="prevImage"
                        aria-label="Image précédente">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <button v-if="offre.images.length > 1" 
                        class="carousel-control next" 
                        @click="nextImage"
                        aria-label="Image suivante">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div v-if="offre.images.length > 1" class="image-indicators">
                    <span v-for="(img, index) in offre.images" 
                        :key="index" 
                        :class="{ active: currentImageIndex === index }"
                        @click="goToImage(index)"></span>
                </div>
                </div>
            </div>

            <div class="col-md-6 content-section">
                <div class="content-wrapper">
                <div class="service-header">
                    <div class="header-top">
                    <h1 class="service-title">{{ offre.nom }}</h1>
                    <div class="price-display">
                        <span v-if="offre.reduction_pourcentage" class="discount-badge">
                        -{{ offre.reduction_pourcentage }}%
                        </span>
                        <div class="price-content">
                        <span class="current-price">{{ formatPrice(offre.prix) }}</span>
                        <span v-if="offre.ancien_prix" class="old-price">
                            {{ formatPrice(offre.ancien_prix) }}
                        </span>
                        </div>
                    </div>
                    </div>
                    
                    <div class="date-range">
                    <i class="fas fa-calendar-alt"></i>
                    Du {{ formatDate(offre.date_debut) }} au {{ formatDate(offre.date_fin) }}
                    </div>
                </div>
    
                <button class="btn-reserve mb-4" 
                        @click="bookOffre(offre)">
                    <i class="fas fa-calendar-check"></i>
                    Réserver maintenant
                </button>
    
                <div class="service-description">
                    {{ offre.description }}
                </div>
    
                <div class="service-features" v-if="offre.programme && offre.programme.length">
                    <h3>Programme inclus :</h3>
                    <ul>
                    <li v-for="(item, index) in offre.programme" :key="index">
                        <i class="fas fa-check"></i>
                        {{ item }}
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
        name: 'OffreDetail',
        data() {
        return {
            offre: null,
            loading: true,
            error: null,
            currentImageIndex: 0
        }
        },
        computed: {
        currentImage() {
            if (!this.offre || !this.offre.images || this.offre.images.length === 0) {
            return '/images/default-offre.jpg';
            }
            return this.offre.images[this.currentImageIndex];
        }
        },
        methods: {
        getTypeIcon(category) {
            if (!category) return 'fas fa-star';
            const lowerCategory = category.toLowerCase();
            if (lowerCategory.includes('romantique')) return 'fas fa-heart';
            if (lowerCategory.includes('affaire')) return 'fas fa-briefcase';
            if (lowerCategory.includes('famille')) return 'fas fa-users';
            if (lowerCategory.includes('séjour')) return 'fas fa-users';
            if (lowerCategory.includes('luxe')) return 'fas fa-gem';
            return 'fas fa-star';
        },
        getCategoryClass(category) {
            if (!category) return 'default-category';
            const lowerCategory = category.toLowerCase();
            if (lowerCategory.includes('romantique')) return 'romantic-category';
            if (lowerCategory.includes('affaire')) return 'business-category';
            if (lowerCategory.includes('famille')) return 'family-category';
            if (lowerCategory.includes('séjour')) return 'family-category';
            if (lowerCategory.includes('luxe')) return 'luxury-category';
            return 'default-category';
        },
        formatPrice(price) {
            if (price == null) return 'Sur demande';
            if (typeof price === 'string') return price;
            if (typeof price === 'number') return `${price} $`;
            return 'Sur demande';
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
            });
        },
        async fetchOffreDetails() {
            this.loading = true;
            this.error = null;
            const offreId = this.$route.params.id;
            try {
            const response = await axios.get(`/api/offre/${offreId}`);
            this.offre = response.data;
            if (!this.offre.images || !Array.isArray(this.offre.images)) {
                this.offre.images = ['/images/default-offre.jpg'];
            }
            } catch (error) {
            console.error('Erreur lors de la récupération de l\'offre:', error);
            this.error = 'Impossible de charger les détails de l\'offre. Veuillez réessayer plus tard.';
            } finally {
            this.loading = false;
            }
        },
        bookOffre(offre) {
            alert(`Réservation de l'offre : ${offre.nom}`);
        },
        goBack() {
            this.$router.go(-1);
        },
        prevImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.offre.images.length) % this.offre.images.length;
        },
        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.offre.images.length;
        },
        goToImage(index) {
            this.currentImageIndex = index;
        }
        },
        created() {
        this.fetchOffreDetails();
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
    
    .category-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 5px;
        z-index: 2;
        }

        .default-category {
        background-color: #2a90d9;
        }

        .romantic-category {
        background-color: #e84393;
        }

        .business-category {
        background-color: #0984e3;
        }

        .family-category {
        background-color: #00b894;
        }

        .luxury-category {
        background-color: #6c5ce7;
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
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }
    
    .price-content {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
    }
    
    .discount-badge {
        background-color: #ff7675;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: bold;
    }
    
    .current-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2ecc71;
    }
    
    .old-price {
        font-size: 1rem;
        color: #999;
        text-decoration: line-through;
        position: relative;
    }
    
    .old-price::after {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 2px;
        background-color: #ff7675;
        transform: translateY(-50%);
    }
    
    .date-range {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 0.95rem;
        margin-top: 0.5rem;
    }
    
    .date-range i {
        margin-right: 0.5rem;
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
        font-size: 1.3rem;
        }
        
        .old-price {
        font-size: 0.9rem;
        }
        
        .discount-badge {
        font-size: 0.8rem;
        padding: 0.2rem 0.6rem;
        }
    }
</style>