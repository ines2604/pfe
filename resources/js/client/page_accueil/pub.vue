<template>
  <div class="container-fluid py-5" v-if="offers.length > 0">
    <h2 class="text-center mb-4">Offres Spéciales</h2>
    <div class="position-relative">
      <button class="nav-btn prev-btn" @click="prev" :disabled="currentIndex === 0">
        <i class="fas fa-chevron-left"></i>
      </button>

      <div class="cards-container">
        <div class="cards-wrapper" :style="{ transform: `translateX(-${currentIndex * (100 / visibleCards)}%)` }">
          <div v-for="(offer, index) in offers" :key="index" class="hotel-card-wrapper" :style="{ flex: `0 0 ${100 / visibleCards}%` }">
            <div class="card hotel-card h-100">
              <div class="image-container">
                <img :src="offer.images[0]" class="card-img-top" :alt="offer.nom">
                <div class="category-tag" :class="getCategoryClass(offer.categorie)">
                  <i :class="getCategoryIcon(offer.categorie)"></i>
                  {{ offer.categorie }}
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">{{ offer.nom }}</h5>
                  <span v-if="offer.reduction_pourcentage" class="discount-badge">
                    -{{ offer.reduction_pourcentage }}%
                  </span>
                </div>
                <p class="card-text location"><i class="fas fa-map-marker-alt"></i> {{ offer.hotel_nom }}</p>
                <p class="card-text description">{{ truncateDescription(offer.description) }}</p>
                <div class="price-container">
                  <span v-if="offer.ancien_prix" class="old-price">
                    {{ formatPrice(offer.ancien_prix) }}
                  </span>
                  <p class="card-text text-success current-price">
                    <strong>{{ formatPrice(offer.prix) }}</strong>
                  </p>
                </div>
              </div>
              <div class="card-actions">
                <button class="btn-action btn-details" @click="viewMore(offer)" aria-label="Voir détails">
                  <i class="fas fa-info-circle"></i>
                  Détails
                </button>
                <button class="btn-action btn-book" @click="bookRoom(offer)" aria-label="Réserver">
                  <i class="fas fa-calendar-check"></i>
                  Réserver
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button class="nav-btn next-btn" @click="next" :disabled="currentIndex >= maxIndex">
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
      offers: [],
    };
  },
  computed: {
    maxIndex() {
      return Math.max(this.offers.length - this.visibleCards, 0);
    },
  },
  methods: {
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
    getCategoryIcon(category) {
      if (!category) return 'fas fa-star';
      const lowerCategory = category.toLowerCase();
      if (lowerCategory.includes('romantique')) return 'fas fa-heart';
      if (lowerCategory.includes('affaire')) return 'fas fa-briefcase';
      if (lowerCategory.includes('famille')) return 'fas fa-users';
      if (lowerCategory.includes('séjour')) return 'fas fa-users';
      if (lowerCategory.includes('luxe')) return 'fas fa-gem';
      return 'fas fa-star';
    },
    formatPrice(price) {
      if (price == null) return 'Prix sur demande';
      if (typeof price === 'string') return price.includes('$') ? price : `${price}`;
      if (typeof price === 'number') return `À partir de ${price}$`;
      return 'Prix sur demande';
    },
    truncateDescription(text, length = 100) {
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
    prev() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
      }
    },
    next() {
      if (this.currentIndex < this.maxIndex) {
        this.currentIndex++;
      }
    },
    updateVisibleCards() {
      if (window.innerWidth >= 1200) {
        this.visibleCards = 3;
      } else if (window.innerWidth >= 768) {
        this.visibleCards = 2;
      } else {
        this.visibleCards = 1;
      }
      this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
    },
    viewMore(offre) {
      this.$router.push({
        name: 'DétailsOffre', 
        params: {
            hotel_nom: offre.hotel_nom, 
            id: offre.id 
        }
      });
    },
  },
  async mounted() {
    this.updateVisibleCards();
    window.addEventListener("resize", this.updateVisibleCards);

    await axios.get('api/offres')
    .then(response => {
      this.offers =response.data;
    })
    .catch(error =>{
      console.error('il y a eu une erreur lors de la récupérations des offres : ', error);
    });
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.updateVisibleCards);
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');

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

.image-container {
  position: relative;
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

.discount-badge {
  background-color: #ff7675;
  color: white;
  padding: 3px 8px;
  border-radius: 10px;
  font-size: 0.8rem;
  font-weight: bold;
}

.price-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.old-price {
  text-decoration: line-through;
  color: #999;
  font-size: 0.9rem;
}

.current-price {
  margin: 0;
  font-size: 1.2rem;
}

.cards-wrapper {
  display: flex;
  scroll-snap-align: start;
  transition: transform 0.5s ease;
}

.hotel-card-wrapper {
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

.hotel-card {
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  border: none;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  height: 100%;
}

.hotel-card:hover{
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.hotel-card:hover .card-img-top {
  transform: scale(1.1);
}

.card-img-top {
  transition: transform 0.3s ease-in-out;
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

.text-success {
  color: #2a90d9 !important;
  font-size: 1.2rem;
  font-weight: 600;
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

.card-actions {
  display: flex;
  gap: 0.75rem;
  padding: 0 1.5rem 1.5rem;
}

.btn-action {
  flex: 1;
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
  transform: translateY(-2px);
}

.btn-book {
  background: linear-gradient(135deg, #2a90d9, #1e6ca8);
  color: white;
  box-shadow: 0 2px 8px rgba(42, 144, 217, 0.2);
}

.btn-book:hover {
  background: linear-gradient(135deg, #1e6ca8, #155f9e);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(42, 144, 217, 0.3);
}

@media (max-width: 992px) {
  .card-img-top {
    height: 200px;
  }

  .hotel-card-wrapper {
    flex: 0 0 100%;  
  }

  .nav-btn {
    width: 35px;
    height: 35px;
  }

  .position-relative {
    padding: 0 20px; 
  }
}

@media (max-width: 1200px) {
  .hotel-card-wrapper {
    flex: 0 0 calc(100% / 2); 
  }
}

@media (max-width: 768px) {
  .category-tag {
    font-size: 0.65rem;
    padding: 3px 8px;
  }
  
  .discount-badge {
    font-size: 0.7rem;
    padding: 2px 6px;
  }
  
  .old-price {
    font-size: 0.8rem;
  }
  
  .current-price {
    font-size: 1rem;
  }

  .hotel-card-wrapper {
    flex: 0 0 100% !important; 
    padding: 0 !important;
  }

  .cards-container {
    margin-left: 10px;
    margin-right: 10px;
  }

  .nav-btn {
    width: 35px;
    height: 35px;
  }

  .card-img-top {
    height: 180px;
  }

  .nav-btn {
    width: 30px;
    height: 30px; 
  }

  .card-footer.bg-transparent.border-0.text-center {
    padding: 0.5rem;
  }

  .description {
    font-size: 0.9rem;
  }

  .card-actions {
    flex-direction: column;
    gap: 0.5rem;
  }

  .position-relative {
    padding: 0 15px;
  }
}

</style>