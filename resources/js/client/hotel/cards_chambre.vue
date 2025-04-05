<template>
    <div class="container-fluid py-5" v-if="rooms.length > 0">
        <h2 class="text-center mb-4">Nos chambres et suites</h2>
    
        <div class="position-relative">
            <button class="nav-btn prev-btn" @click="prev" :disabled="currentIndex === 0">
            <i class="fas fa-chevron-left"></i>
            </button>
    
            <div class="cards-container">
            <div class="cards-wrapper" :style="{ transform: `translateX(-${currentIndex * (100 / visibleCards)}%)` }">
                <div 
                v-for="(room, index) in rooms" 
                :key="index" 
                class="room-card-wrapper"
                :style="{ flex: `0 0 ${100 / visibleCards}%` }"
                >
                <div class="card room-card h-100">
                    <div class="room-image-wrapper">
                    <img :src="room.images[0]" class="room-image" alt="image de chambre" loading="lazy" />
                    </div>
    
                    <div class="room-content">
                    <h2 class="room-title">{{ room.nom }}</h2>
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
    
                    <p class="offer-description">{{ truncateDescription(room.description) }}</p>
    
                    <div class="room-footer">
                        <div class="room-price">
                        <span class="price-amount">{{formatPrice(room.prix)}}</span>
                        </div>
    
                        <div class="button-group">
                        <button class="btn-quick-view" @click="viewMore(room)">
                            <i class="fas fa-info-circle"></i>
                            Détails
                        </button>
                        <button class="btn-book" @click="bookRoom(room)">
                            <i class="fas fa-calendar-check"></i>
                            Réserver
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
    
            <button class="nav-btn next-btn" @click="next" :disabled="currentIndex >= rooms.length - visibleCards">
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
            rooms: [],
        };
        },
        methods: {
        truncateDescription(text, length = 100) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
        prev() {
            if (this.currentIndex > 0) {
            this.currentIndex--;
            }
        },
        next() {
            if (this.currentIndex < this.rooms.length - this.visibleCards) {
            this.currentIndex++;
            }
        },
        viewMore(room) {
            this.$router.push({
            name: 'DétailsChambre',
            params: {
                hotel_nom: room.hotel_nom,
                id: room.id
            }
            });
        },
        formatPrice(price) {
            if (price == null) return 'Prix sur demande';
            if (typeof price === 'string') return price.includes('$') ? price : `${price}`;
            if (typeof price === 'number') return `À partir de ${price}$`;
            return 'Prix sur demande';
        },
        bookRoom(room) {
            console.log('Réservation de la chambre:', room.id);
        },
        updateVisibleCards() {
            if (window.innerWidth >= 1200) {
            this.visibleCards = 3;
            } else if (window.innerWidth >= 768) {
            this.visibleCards = 2;
            } else {
            this.visibleCards = 1;
            }
        },
        },
        async mounted() {
        this.updateVisibleCards();
        window.addEventListener("resize", this.updateVisibleCards);
        this.hotel_nom = this.$route.params.hotel_nom;

        await axios.get(`/api/hotels/${this.hotel_nom}/chambres`)
            .then(response => {
            this.rooms = response.data;
            })
            .catch(error => {
            console.error('Erreur lors de la récupération des chambres :', error);
            });
        },
        beforeUnmount() {
        window.removeEventListener("resize", this.updateVisibleCards);
        },
    };
    </script>
    
    <style scoped>
    @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap");
    
    .container-fluid {
        padding: 2rem;
    }
    
    h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        color: #2a90d9;
        margin-bottom: 2rem;
        position: relative;
    }
    
    h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: #2a90d9;
    }
    
    .position-relative {
        position: relative;
        padding: 0 50px;
    }
    
    .cards-container {
        width: 100%;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        overflow: hidden;
        margin: 0 -15px;
    }
    
    .cards-wrapper {
        display: flex;
        scroll-snap-align: start;
        transition: transform 0.5s ease;
    }
    
    .room-card-wrapper {
        scroll-snap-align: start;
        transition: transform 0.5s ease-in-out;
        flex: 0 0 calc(100% / var(--visible-cards));
        padding: 0 15px;
    }
    
    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 1px solid #2a90d9;
        color: #2a90d9;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .nav-btn:hover:not(:disabled) {
        background-color: #2a90d9;
        color: #fff;
    }
    
    .nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .prev-btn {
        left: 0;
    }
    
    .next-btn {
        right: 0;
    }
    
    .room-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .room-image-wrapper {
        position: relative;
        padding-top: 66.67%;
        overflow: hidden;
    }
    
    .room-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .room-card:hover .room-image {
        transform: scale(1.05);
    }
    
    .room-content {
        padding: 1.5rem;
    }
    
    .room-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        font-family: 'Playfair Display', serif;
    }
    
    .adresse {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    
    .adresse i {
        color: #2a90d9;
    }
    
    .room-features {
        display: flex;
        font-family: 'Poppins', sans-serif;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        color: #64748b;
    }
    
    .feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        color: #64748b;
    }
    
    .feature i {
        color: #2a90d9;
    }
    
    .offer-description {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #555;
        margin-bottom: 1.5rem;
    }
    
    .room-footer {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .room-price {
        display: flex;
        align-items: baseline;
        gap: 0.3rem;
    }
    
    .price-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2a90d9;
    }
    
    .button-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .btn-quick-view {
        padding: 0.8rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-quick-view:hover {
        background: #e2e8f0;
    }
    
    .btn-book {
        padding: 0.8rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        background: linear-gradient(135deg, #2a90d9, #1e6ca8);
        color: white;
        box-shadow: 0 4px 15px rgba(42, 144, 217, 0.2);
    }
    
    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(42, 144, 217, 0.3);
    }
    
    @media (max-width: 1200px) {
        .room-card-wrapper {
        flex: 0 0 50%;
        }
    }
    
    @media (max-width: 768px) {
        .room-card-wrapper {
        flex: 0 0 100% !important;
        padding: 0 !important;
        }
    
        .position-relative {
        padding: 0 40px;
        }
    
        .nav-btn {
        width: 35px;
        height: 35px;
        }
    
        .button-group {
        grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
        padding: 1rem;
        }
    
        .position-relative {
        padding: 0 30px;
        }
    
        .room-features {
        flex-direction: column;
        gap: 0.5rem;
        }
    }
</style>