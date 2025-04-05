<template>
    <div class="container-fluid py-5" v-if="services.length > 0">
        <h2 class="text-center mb-4">Nos Services</h2>
        <div class="position-relative">
            <button 
            class="nav-btn prev-btn" 
            @click="prev" 
            :disabled="currentIndex === 0" 
            aria-label="Précédent"
            >
            <i class="fas fa-chevron-left"></i>
            </button>
    
            <div class="cards-container" ref="cardsContainer">
                <div class="cards-wrapper" :style="{ transform: `translateX(-${currentIndex * 100 / visibleCards}%)` }">
                <div 
                v-for="service in services" 
                :key="service.id" 
                class="service-card-wrapper"
                
                >
                <div class="card service-card h-100">
                    <div class="img-container" v-if="service.images?.length">
                    <img 
                        :src="service.images[0]" 
                        class="card-img-top" 
                        :alt="`Image de ${service.nom}`" 
                        loading="lazy"
                    >
                    <div class="category-tag" :class="getCategoryClass(service.categorie)">
                        <i :class="getCategoryIcon(service.categorie)"></i>
                        <span>{{ service.categorie }}</span>
                    </div>
                    </div>
                    
                    <div class="card-body">
                    <h3 class="card-title">{{ service.nom }}</h3>
                    
                    <div class="service-meta">
                        <p class="meta-item">
                        <i class="fas fa-map-marker-alt"></i> 
                        <span>{{ service.hotel_nom || 'Non spécifié' }}</span>
                        </p>
                    </div>
    
                    <p v-if="service.description" class="service-description">
                        {{ truncateDescription(service.description) }}
                    </p>
                    <p v-else class="no-description">
                        Aucune description disponible
                    </p>
                    
                    <p class="price-display">
                        <strong>{{ formatPrice(service.prix) }}</strong>
                    </p>
                    </div>
                    
                    <div class="card-actions">
                    <button 
                        class="btn-action btn-details" 
                        @click="viewMore(service)" 
                        aria-label="Voir détails"
                    >
                        <i class="fas fa-info-circle"></i>
                        Détails
                    </button>
                    <button 
                        class="btn-action btn-book" 
                        @click="bookRoom(service)" 
                        aria-label="Réserver"
                    >
                        <i class="fas fa-calendar-check"></i>
                        Réserver
                    </button>
                    </div>
                </div>
                </div>
            </div>
            </div>
    
            <button 
            class="nav-btn next-btn" 
            @click="next" 
            :disabled="currentIndex >= maxIndex" 
            aria-label="Suivant"
            >
            <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        </div>
    </template>
    
    <script>
    import axios from 'axios';
    
    export default {
        data() {
        return {
            currentIndex: 0,
            visibleCards: 3,
            services: [],
            windowWidth: window.innerWidth
        };
        },
        computed: {
            maxIndex() {
                return Math.max(Math.ceil(this.services.length / this.visibleCards) - 1, 0);
            }
        },
        methods: {
        getCategoryIcon(category) {
            const icons = {
            'petit-déjeuner': 'fas fa-coffee',
            'restaurant et Room service': 'fas fa-utensils',
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
            'services PMR': 'fas fa-wheelchair',
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
        truncateDescription(text, length = 100) {
            return text?.length > length ? `${text.substring(0, length)}...` : text || '';
        },
        formatPrice(price) {
            if (price == null) return 'Prix sur demande';
            if (typeof price === 'string') return price.includes('$') ? price : `${price}`;
            if (typeof price === 'number') return `À partir de ${price}$`;
            return 'Prix sur demande';
        },
        next() {
            if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
            }
        },
        prev() {
            if (this.currentIndex > 0) {
            this.currentIndex--;
            }
        },
        updateVisibleCards() {
            if (this.windowWidth >= 1200) {
                this.visibleCards = 3;
            } else if (this.windowWidth >= 768) {
                this.visibleCards = 2;
            } else {
                this.visibleCards = 1;
            }
            if (this.currentIndex > this.maxIndex) {
                this.currentIndex = this.maxIndex;
            }
        },
        handleResize() {
            this.windowWidth = window.innerWidth;
            this.updateVisibleCards();
        },
        viewMore(service) {
            this.$router.push({
            name: 'DétailsService',
            params: { hotel_nom: service.hotel_nom, id: service.id }
            });
        },
        bookRoom(service) {
            console.log('Réservation du service:', service.id);
        },
        },
        async mounted() {
            window.addEventListener('resize', this.handleResize);
            this.updateVisibleCards();
            try {
                const response = await axios.get('/api/services');
                this.services = Object.values(response.data);
            } catch (error) {
                console.error('Erreur API:', error);
                this.services = [];
            }
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.handleResize);
        }
    };
    </script>
    
    <style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');
    
    .container-fluid {
        padding: 2rem 1.5rem;
    }
    
    h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: #2a90d9;
        margin-bottom: 2.5rem;
        position: relative;
        font-size: 2.25rem;
    }
    
    h2::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #2a90d9, #1e6ca8);
    }
    
    .position-relative {
        position: relative;
        padding: 0 3.5rem;
    }
    
    .cards-container {
        width: 100%;
        overflow: hidden;
        margin: 0 -12px;
        position: relative; 
    }
    
    .cards-wrapper {
        display: flex;
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform;
        width: 100%;
    }
    
    .service-card-wrapper {
        padding: 0 12px;
        flex: 0 0 calc(100% / var(--visible-cards, 3));
        min-width: 0;
        box-sizing: border-box;
        flex-shrink: 0;
        width: 100%;
    }
    
    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: white;
        border: 1px solid rgba(42, 144, 217, 0.2);
        color: #2a90d9;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .nav-btn:hover:not(:disabled) {
        background: #2a90d9;
        color: white;
        transform: translateY(-50%) scale(1.05);
    }
    
    .nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: translateY(-50%) !important;
        box-shadow: none !important;
    }
    
    .prev-btn {
        left: 0.5rem;
    }
    
    .next-btn {
        right: 0.5rem;
    }
    
    .service-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    .img-container {
        position: relative;
        padding-top: 65%;
        overflow: hidden;
    }
    
    .category-tag {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 12px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .service-card:hover .category-tag {
        background: rgba(42, 144, 217, 0.9);
        color: white;
    }
    
    .category-tag i {
        font-size: 0.9rem;
    }
    
    .category-petit-d-jeuner {
        background: rgba(198, 152, 68, 0.9);
        color: white;
    }
    
    .category-restaurant {
        background: rgba(217, 83, 79, 0.9);
        color: white;
    }
    
    .category-room-service {
        background: rgba(91, 192, 222, 0.9);
        color: white;
    }
    
    .category-bar {
        background: rgba(92, 184, 92, 0.9);
        color: white;
    }
    
    .category-caf- {
        background: rgba(129, 78, 40, 0.9);
        color: white;
    }
    
    .category-piscine {
        background: rgba(66, 139, 202, 0.9);
        color: white;
    }
    
    .category-spa {
        background: rgba(217, 83, 129, 0.9);
        color: white;
    }
    
    .category-salle-de-sport {
        background: rgba(240, 173, 78, 0.9);
        color: white;
    }
    
    .category-parking {
        background: rgba(119, 119, 119, 0.9);
        color: white;
    }
    
    .category-services-familiaux {
        background: rgba(153, 102, 204, 0.9);
        color: white;
    }
    
    .category-services-pmr {
        background: rgba(51, 122, 183, 0.9);
        color: white;
    }
    
    .category-plage-privative {
        background: rgba(244, 204, 84, 0.9);
        color: #333;
    }
    
    .category-espaces-de-r-unions {
        background: rgba(47, 79, 79, 0.9);
        color: white;
    }
    
    .card-img-top {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .service-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        line-height: 1.3;
        font-family: 'Poppins', sans-serif;
    }
    
    .service-meta {
        margin-bottom: 0.5rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    
    .meta-item i {
        color: #2a90d9;
        width: 16px;
        text-align: center;
    }
    
    .service-description {
        font-size: 0.92rem;
        line-height: 1.6;
        color: #555;
        margin: 0.2rem 0 0.2rem;
        flex: 1;
    }
    
    .no-description {
        font-size: 0.9rem;
        color: #94a3b8;
        font-style: italic;
        margin: 0.2rem 0 0.2rem;
    }
    
    .price-display {
        color: #2a90d9;
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0.5rem 0 0.5rem;
    }
    
    .card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        padding: 0 1.5rem 1.5rem;
    }
    
    .btn-action {
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-details {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-details:hover {
        background: #e2e8f0;
    }
    
    .btn-book {
        background: linear-gradient(135deg, #2a90d9, #1e6ca8);
        color: white;
        box-shadow: 0 2px 8px rgba(42, 144, 217, 0.3);
    }
    
    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42, 144, 217, 0.4);
    }
    
    @media (max-width: 1200px) {
        .service-card-wrapper {
        flex: 0 0 50% !important;
        }
    }
    
    @media (max-width: 768px) {
        .position-relative {
        padding: 0 2.5rem;
        }
    
        .service-card-wrapper {
        flex: 0 0 100% !important;
        }
    
        .card-actions {
        grid-template-columns: 1fr;
        }
    
        .nav-btn {
        width: 36px;
        height: 36px;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
        padding: 1.5rem 1rem;
        }
    
        h2 {
        font-size: 1.8rem;
        }
    
        .position-relative {
        padding: 0 2rem;
        }
    
        .card-body {
        padding: 1.25rem;
        }
    
        .card-title {
        font-size: 1.2rem;
        }
    }
</style>