<template>
    <div class="container-fluid mt-5" v-if="reviews.length > 0">
        <h2 class="text-center mb-4">Avis Clients</h2>
        <div class="d-flex align-items-center justify-content-center">
            <button class="btn btn-light border mx-2" @click="prev" :disabled="currentIndex === 0">
            <i class="fas fa-chevron-left"></i>
            </button>
    
            <div class="cards-container">
            <div
                class="cards-wrapper d-flex"
                :style="{ transform: `translateX(-${currentIndex * (100 / visibleCards)}%)` }"
            >
                <div
                v-for="(review, index) in reviews"
                :key="index"
                class="card mx-2"
                :style="{ flex: `0 0 ${100 / visibleCards}%` }"
                >
                <div class="card-body text-center">
                    <div class="client-info mb-2">
                    <strong>{{ review.client_nom }} {{ review.client_prenom }}</strong>
                    <span class="review-date">{{ formatDate(review.date_publication) }}</span>
                    </div>
                    <div class="rating mb-3">
                    <i v-for="star in 5" :key="star" class="fas fa-star"
                    :class="['fas', 'fa-star', star <= review.note ? 'filled' : '']"></i>
                    </div>
                    <p class="card-text">"{{ review.commentaire }}"</p>
                </div>
                </div>
            </div>
            </div>
    
            <button
            class="btn btn-light border mx-2"
            @click="next"
            :disabled="currentIndex >= reviews.length - visibleCards">
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
            visibleCards: 4, 
            reviews: [],
        };
        },
        methods: {
        next() {
            const container = this.$el.querySelector('.cards-container');
            const cardWidth = container.clientWidth;
            this.currentIndex = Math.min(this.currentIndex + 1, this.reviews.length - this.visibleCards);
            this.scrollToCard(cardWidth);
        },
        prev() {
            const container = this.$el.querySelector('.cards-container');
            const cardWidth = container.clientWidth;
            this.currentIndex = Math.max(this.currentIndex - 1, 0);
            this.scrollToCard(cardWidth);
        },
        scrollToCard(cardWidth) {
            const offset = -this.currentIndex * cardWidth;
            const wrapper = this.$el.querySelector('.cards-wrapper');
            wrapper.style.transform = `translateX(${offset}px)`;
        },
        updateVisibleCards() {
            if (window.innerWidth >= 1200) {
            this.visibleCards = 3;
            } else if (window.innerWidth >= 768) {
            this.visibleCards = 2;
            } else {
            this.visibleCards = 1;
            }
            this.currentIndex = 0; 
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0'); // Jour (jj)
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Mois (mm)
            const year = date.getFullYear(); // Année (aaaa)
            const hours = String(date.getHours()).padStart(2, '0'); // Heures (hh)
            const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutes (mm)
    
            return `${day}/${month}/${year} à ${hours}:${minutes}`;
        },
        },
        async mounted() {
        this.updateVisibleCards();
        window.addEventListener("resize", this.updateVisibleCards);
        this.hotel_nom = this.$route.params.hotel_nom;

        await axios.get(`/api/hotels/${this.hotel_nom}/avis_clients`)
        .then(response => {
            this.reviews = response.data;
        })
        .catch(error => {
            console.error('Il y a eu une erreur lors de la récupération des avis: ', error);
        });
        },
        beforeUnmount() {
        window.removeEventListener("resize", this.updateVisibleCards);
        },
    };
    </script>
    
    <style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');
    
    .container-fluid {
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
    }
    
    h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        color: #2a90d9;
        margin-bottom: 3.5rem;
        position: relative;
    }
    
    h2::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(45deg, #2a90d9, #1e6ca8);
    }
    
    .cards-container {
        width: 100%;
        overflow: hidden;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        margin: 0 auto 2rem;
        padding: 1rem 0;
    }
    
    .cards-wrapper {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
        scroll-snap-align: start;
    }
    
    .card {
        flex: 0 0 100%;
        scroll-snap-align: start;
        max-width: calc(100% / var(--visible-cards));
        background: white;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(42, 144, 217, 0.1);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin: 1rem;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-10px) rotateX(2deg);
        box-shadow: 0 20px 40px rgba(42, 144, 217, 0.15);
    }
    
    .card-body {
        padding: 2rem;
        text-align: center;
        position : relative;
    }
    
    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(42, 144, 217, 0.2);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .card:hover .profile-img {
        transform: scale(1.1);
        box-shadow: 0 12px 25px rgba(42, 144, 217, 0.3);
    }
    
    .rating {
        color: #ffd700;
        font-size: 1.5rem;
    }
    
    .rating .fa-star {
        margin: 0 2px;
        opacity: 0.3;
    }
    
    .rating .fa-star.filled {
        opacity: 1;
        color: #ffd700;
    }
    
    .card-text {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        color: #4a5568;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .card-text::before,
    .card-text::after {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        color: #2a90d9;
        opacity: 0.2;
        position: absolute;
    }
    
    .card-text::before {
        top: -1rem;
        left: -0.5rem;
    }
    
    .card-text::after {
        bottom: -2rem;
        right: -0.5rem;
    }
    
    .blockquote-footer {
        font-family: 'Poppins', sans-serif;
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .blockquote-footer i {
        margin-right: 0.5rem;
        color: #2a90d9;
    }
    
    .blockquote-footer strong {
        color: #2d3748;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .blockquote-footer cite {
        color: #64748b;
        font-style: normal;
    }
    
    .btn-light {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid rgba(42, 144, 217, 0.2);
        transition: all 0.3s ease;
        margin: 0 1rem;
    }
    
    .btn-light:hover:not(:disabled) {
        background: #2a90d9;
        color: white;
        border-color: #2a90d9;
        transform: scale(1.1);
    }
    
    .btn-light:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-light i {
        font-size: 1rem;
        color: inherit;
    }
    
    @media (max-width: 1200px) {
        .container-fluid {
        padding: 4rem 1.5rem;
        }
    
        h2 {
        font-size: 2.4rem;
        }
    
        .cards-container {
        width: 95%;
        }
    
        .profile-img {
        width: 90px;
        height: 90px;
        }
        .rating {
        font-size: 1.1rem;
        }
    }
    
    @media (max-width: 768px) {
        .container-fluid {
        padding: 3rem 1rem;
        }
    
        .client-info {
        flex-direction: column;
        align-items: flex-start;
        }
    
    
        h2 {
        font-size: 2rem;
        margin-bottom: 2.5rem;
        }
    
        .cards-container {
        width: 100%;
        margin-bottom: 1.5rem;
        }
    
        .card {
        flex: 0 0 100% !important;
        margin: 0 !important;
        max-width: 100% !important;
        }
    
        .card-body {
        padding: 1.5rem;
        }
    
        .profile-img {
        width: 80px;
        height: 80px;
        }
        .rating {
        font-size: 1rem;
        }
    
        .card-text {
        font-size: 1rem;
        }
        .review-date {
        margin-top: 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .container-fluid {
        padding: 2rem 0.5rem;
        }
    
        h2 {
        font-size: 1.8rem;
        }
    
        .profile-img {
        width: 70px;
        height: 70px;
        }
        .rating {
        font-size: 0.9rem;
        }
    
        .card-text {
        font-size: 0.9rem;
        }
    
        .blockquote-footer strong {
        font-size: 1rem;
        }
    
        .btn-light {
        width: 40px;
        height: 40px;
        margin: 0 0.5rem;
        }
    
        .card{
        flex: 0 0 100%;
        }
    }
    
    .client-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        color: #2d3748;
    }
    
    .review-date {
        font-size: 0.9rem;
        color: #64748b;
    }
</style>