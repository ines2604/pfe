<template>
  <div class="container-fluid py-5">
    <h2 class="text-center mb-4">Nos Hôtels</h2>
    <div class="position-relative">
      <button class="nav-btn prev-btn" @click="prevSlide" :disabled="currentIndex === 0">
        <i class="fas fa-chevron-left"></i>
      </button>

      <div class="cards-container">
        <div class="cards-wrapper" :style="{ transform: `translateX(-${currentIndex * (100 / visibleCards)}%)` }">
          <div v-for="(hotel, index) in hotels" :key="index" class="hotel-card-wrapper">
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

      <button class="nav-btn next-btn" @click="nextSlide" :disabled="currentIndex >= maxIndex">
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
      visibleCards: 6,
      hotels: [],
    }
  },
  computed: {
    maxIndex() {
      return Math.max(this.hotels.length - this.visibleCards, 0);
    },
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
    nextSlide() {
      if (this.currentIndex < this.maxIndex) {
        this.currentIndex++;
      }
    },
    prevSlide() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
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
  },
  async mounted() {
    this.updateVisibleCards();
    window.addEventListener("resize", this.updateVisibleCards);

    await axios.get('api/hotels')
      .then(response => {
        this.hotels= response.data;
      })
      .catch(error =>{
        console.error('il y a eu une erreur lors de la récupération des hôtels :' , error);
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
  overflow: hidden;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  margin: 0 -15px;
}

.cards-wrapper {
  display: flex;
  scroll-snap-align: start;
  transition: transform 0.5s ease;
}

.hotel-card-wrapper {
  flex: 0 0 33.333%;
  padding: 0 15px;
  transition: transform 0.5s ease-in-out;
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
  z-index: 10;
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

@media (max-width: 992px) {
  .card-img-top {
    height: 200px;
  }
}

@media (max-width: 768px) {
  .card-footer.bg-transparent.border-0.text-center {
    padding: 0.5rem;
  }
  .hotel-card-wrapper {
    flex: 0 0 100%; 
    padding: 0 !important;
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

@media (max-width: 992px) {
  .hotel-card-wrapper {
    flex: 0 0 50%;
  }
}

@media (max-width: 768px) {
  .hotel-card-wrapper {
    flex: 0 0 100%;
  }
  
  .position-relative {
    padding: 0 40px;
  }
  
  .nav-btn {
    width: 35px;
    height: 35px;
  }
}

.hotel-card-container {
  display: flex;
  flex-wrap: nowrap; 
  overflow: hidden;
}
</style>
