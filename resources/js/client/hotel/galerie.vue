<template>
    <Navbar id="navbar" />
    <div class="gallery-container" style="margin-top: 80px">
        <section class="hero-section">
            <h1 class="main-title">Galerie</h1>
            <p class="subtitle">Découvrez notre établissement en images</p>
        </section>
    
        <div v-if="isLoading" class="loading-container">
            <div class="loading-animation">
                <div class="loading-circle"></div>
                <div class="loading-circle"></div>
                <div class="loading-circle"></div>
            </div>
            <p aria-live="polite" class="loading-text">Chargement des images...</p>
        </div>
    
        <div v-else class="gallery-grid">
            <div 
                v-for="(image, index) in hotel.images" 
                :key="index" 
                class="gallery-item"
                :style="`--row-span: ${rowSpans[index] || 20}`"
                @click="openLightbox(index)"
            >
                <img 
                    :src="image" 
                    :alt="'Image ' + (index + 1) + ' de ' + hotel.nom"
                    class="gallery-image"
                    loading="lazy"
                    @load="calculateRowSpan(index, $event)"
                />
            </div>
        </div>
    
        <div v-if="lightboxVisible" class="lightbox" @click.self="closeLightbox">
            <button class="lightbox-close" @click="closeLightbox">
                <i class="fas fa-times"></i>
            </button>
            <button class="lightbox-nav prev" @click="prevImage" :disabled="currentImageIndex === 0">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="lightbox-content">
                <img 
                    :src="hotel.images[currentImageIndex]" 
                    :alt="'Image ' + (currentImageIndex + 1) + ' de ' + hotel.nom"
                    class="lightbox-image"
                />
                <div class="image-counter">
                    {{ currentImageIndex + 1 }} / {{ hotel.images.length }}
                </div>
            </div>
            
            <button 
                class="lightbox-nav next" 
                @click="nextImage" 
                :disabled="currentImageIndex === hotel.images.length - 1"
            >
                <i class="fas fa-chevron-right"></i>
            </button>
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
                images: []
            },
            lightboxVisible: false,
            currentImageIndex: 0,
            rowSpans: [] // Stocke les hauteurs calculées pour chaque image
        };
    },
    methods: {
        async fetchHotelImages() {
            this.isLoading = true;
            try {
                const hotelNom = this.$route.params.hotel_nom;
                const response = await axios.get(`/api/hotels/${hotelNom}`);
                
                this.hotel = {
                    nom: response.data.nom,
                    images: response.data.galerie || []
                };
                
            } catch (error) {
                console.error("Erreur lors du chargement des images:", error);
            } finally {
                this.isLoading = false;
            }
        },
        
        calculateRowSpan(index, event) {
            this.$nextTick(() => {
                const img = event.target;
                const aspectRatio = img.naturalHeight / img.naturalWidth;
                // Base row span sur le ratio d'aspect (ajustez le multiplicateur selon vos besoins)
                const rowSpan = Math.ceil(aspectRatio * 25);
                this.rowSpans[index] = rowSpan;
                this.$forceUpdate(); // Force le recalcul du rendu
            });
        },
        
        openLightbox(index) {
            this.currentImageIndex = index;
            this.lightboxVisible = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeLightbox() {
            this.lightboxVisible = false;
            document.body.style.overflow = '';
        },
        
        nextImage() {
            if (this.currentImageIndex < this.hotel.images.length - 1) {
                this.currentImageIndex++;
            }
        },
        
        prevImage() {
            if (this.currentImageIndex > 0) {
                this.currentImageIndex--;
            }
        },
        
        handleKeydown(e) {
            if (this.lightboxVisible) {
                if (e.key === 'Escape') {
                    this.closeLightbox();
                } else if (e.key === 'ArrowRight') {
                    this.nextImage();
                } else if (e.key === 'ArrowLeft') {
                    this.prevImage();
                }
            }
        }
    },
    mounted() {
        this.fetchHotelImages();
        window.addEventListener('keydown', this.handleKeydown);
    },
    beforeUnmount() {
        window.removeEventListener('keydown', this.handleKeydown);
        document.body.style.overflow = '';
    }
};
</script>
    
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');

.gallery-container {
    margin: 0 auto;
    padding: 2rem;
    max-width: 1800px;
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
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
    url("https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80");
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    margin-bottom: 2rem;
    color: white;
    animation: fadeIn 1s ease-in-out;
}

.main-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    font-family: 'Playfair Display', serif;
    animation: slideIn 1s ease-in-out;
}

.subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    font-family: 'Poppins', sans-serif;
    animation: slideIn 1.2s ease-in-out;
}

.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    width: 100%;
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
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.loading-text {
    margin-top: 1.5rem;
    color: #64748b;
    font-size: 1.1rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    grid-auto-rows: 10px; /* Base unit for row calculation */
    gap: 15px;
}

.gallery-item {
    cursor: pointer;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    grid-row-end: span var(--row-span);
}

.gallery-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.3) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: 1;
}

.gallery-item:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.gallery-item:hover::before {
    opacity: 1;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1001;
    padding: 2rem;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 4px;
}

.lightbox-close {
    position: absolute;
    top: 2rem;
    right: 2rem;
    background: transparent;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.lightbox-close:hover {
    transform: scale(1.2);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.lightbox-nav:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.4);
}

.lightbox-nav:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.lightbox-nav.prev {
    left: 2rem;
}

.lightbox-nav.next {
    right: 2rem;
}

.image-counter {
    color: white;
    margin-top: 1rem;
    font-size: 1.2rem;
    font-family: 'Poppins', sans-serif;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@media (max-width: 768px) {
    .gallery-container {
        padding: 1rem;
    }

    .hero-section {
        padding: 2rem 1rem;
    }

    .main-title {
        font-size: 2.5rem;
    }

    .subtitle {
        font-size: 1rem;
    }

    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }

    .lightbox-nav {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }

    .lightbox-nav.prev {
        left: 1rem;
    }

    .lightbox-nav.next {
        right: 1rem;
    }

    .lightbox-close {
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
    }
}
</style>