<template>
  <Navbar id="navbar"/>
  <div class="listeServices" style="margin-top: 80px">
      <section class="hero-section">
          <h1 class="main-title">Services</h1>
          <p class="subtitle">Services premium pour séjour inoubliable</p>
      </section>

      <div class="search-filter-section">
          <div class="filter-grid">
              <div class="search-box">
                  <i class="fas fa-search search-icon"></i>
                  <input
                      type="text"
                      class="form-control"
                      v-model="searchQuery"
                      placeholder="Rechercher"
                      :disabled="isLoading"
                      @input="applyFilters"
                  />
              </div>

              <div class="select-wrapper">
                  <i class="fas fa-filter filter-icon"></i>
                  <select class="form-control" v-model="categoryFilter" :disabled="isLoading" @change="applyFilters">
                      <option value="">Toutes catégories</option>
                      <option value="petit-déjeuner">Petit-déjeuner</option>
                      <option value="restaurant">Restaurant</option>
                      <option value="bar">Bar</option>
                      <option value="café">Café</option>
                      <option value="piscine">Piscine</option>
                      <option value="salle de sport">Salle de sport</option>
                      <option value="parking">Parking</option>
                      <option value="familiaux">Services familiaux</option>
                      <option value="pmr">Services PMR</option>
                      <option value="plage">Plage privative</option>
                      <option value="réunion">Espaces de réunion</option>
                  </select>
              </div>

              <div class="select-wrapper">
                  <i class="fas fa-filter filter-icon"></i>
                  <select class="form-control" v-model="priceFilter" :disabled="isLoading" @change="applyFilters">
                      <option value="">Tous les prix</option>
                      <option value="0-100">Moins de 100€</option>
                      <option value="100-200">100€ - 200€</option>
                      <option value="200-300">200€ - 300€</option>
                      <option value="300+">Plus de 300€</option>
                  </select>
              </div>

              <div class="select-wrapper">
                  <i class="fas fa-filter filter-icon"></i>
                  <select class="form-control" v-model="sortBy" :disabled="isLoading" @change="applyFilters">
                      <option value="price-asc">Prix croissant</option>
                      <option value="price-desc">Prix décroissant</option>
                  </select>
              </div>
          </div>
      </div>

      <div v-if="isLoading" class="loading-container">
          <div class="loading-animation">
              <div class="loading-circle"></div>
              <div class="loading-circle"></div>
              <div class="loading-circle"></div>
          </div>
          <p aria-live="polite" class="loading-text">Chargement des services...</p>
      </div>

      <div class="services-by-category" v-else>
          <div v-if="error" class="error-message">{{ error }}</div>
          <div v-else-if="filteredServices.length === 0" class="no-results">
              <i class="fas fa-search fa-2x"></i>
              <p>Aucun service ne correspond à vos critères de recherche.</p>
              <button class="btn-reset" @click="resetFilters">Réinitialiser les filtres</button>
          </div>
          <div v-else>
              <div v-for="(services, category) in servicesByCategory" :key="category" class="category-section">
                  <h2 class="category-title">{{ category }}</h2>
                  <div class="position-relative">
                      <button 
                          class="nav-btn prev-btn" 
                          @click="prevCategory(category)" 
                          :disabled="(currentIndices[category] || 0) === 0"
                          aria-label="Précédent"
                      >
                          <i class="fas fa-chevron-left"></i>
                      </button>

                      <div class="cards-container">
                          <div 
                              class="cards-wrapper" 
                              :style="{ transform: `translateX(calc(-${(currentIndices[category] || 0)} * (100% / ${getVisibleCards()}))` }"
                          >
                              <div 
                                  v-for="service in services" 
                                  :key="service.id" 
                                  class="service-card-wrapper"
                                  :style="{ flex: `0 0 calc(100% / ${getVisibleCards()})` }"
                              >
                                  <div class="card service-card h-100">
                                      <div class="img-container" v-if="service.images?.length">
                                          <img :src="service.images[0]" class="card-img-top" :alt="`Image de ${service.nom}`" loading="lazy">
                                          <div class="category-tag" :class="getCategoryClass(service.categorie)">
                                              <i :class="getCategoryIcon(service.categorie)"></i>
                                              <span>{{ service.categorie }}</span>
                                          </div>
                                      </div>
                                      
                                      <div class="card-body">
                                          <h3 class="card-title">{{ service.nom }}</h3>

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
                                          <button class="btn-action btn-details" @click="viewMore(service)" aria-label="Voir détails">
                                              <i class="fas fa-info-circle"></i>
                                              Détails
                                          </button>
                                          <button class="btn-action btn-book" @click="bookService(service)" aria-label="Réserver">
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
                          @click="nextCategory(category)" 
                          :disabled="(currentIndices[category] || 0) >= getMaxIndex(services.length)"
                          aria-label="Suivant"
                      >
                          <i class="fas fa-chevron-right"></i>
                      </button>
                  </div>
              </div>
          </div>
      </div>
      <Footer />
  </div>
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
          allServices: [],
          services: [],
          currentIndices: {},
          windowWidth: window.innerWidth,
          isLoading: true,
          error: null,
          searchQuery: '',
          categoryFilter: '',
          priceFilter: '',
          sortBy: 'price-asc',
          hotelNom: ''
      };
  },
  computed: {
      filteredServices() {
          return this.services;
      },
      servicesByCategory() {
          const grouped = {};
          
          this.filteredServices.forEach(service => {
              const rawCategory = service.categorie || 'Autres';
              const category = rawCategory.charAt(0).toUpperCase() + rawCategory.slice(1).toLowerCase();
              
              if (!grouped[category]) {
                  grouped[category] = [];
              }
              grouped[category].push(service);
          });

          return grouped;
      }
  },
  methods: {
      getCategoryIcon(category) {
          const icons = {
              'Petit-déjeuner': 'fas fa-coffee',
              'Restaurant': 'fas fa-utensils',
              'Restaurant et room service': 'fas fa-utensils',
              'Bar': 'fas fa-glass-martini-alt',
              'Café': 'fas fa-mug-hot',
              'Bar et café': 'fas fa-glass-martini-alt',
              'Piscine': 'fas fa-swimming-pool',
              'Spa': 'fas fa-spa',
              'Piscine & spa': 'fas fa-spa',
              'Salle de sport': 'fas fa-dumbbell',
              'Parking': 'fas fa-parking',
              'Services familiaux': 'fas fa-baby-carriage',
              'Services PMR': 'fas fa-wheelchair',
              'Plage privative': 'fas fa-umbrella-beach',
              'Espaces de réunion': 'fas fa-business-time',
              'Accès animaux': 'fas fa-paw'
          };
          return icons[category] || 'fas fa-star';
      },
      
      getCategoryClass(category) {
          return 'category-' + (category?.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'default');
      },
      
      truncateDescription(text, length = 100) {
          if (!text) return 'Aucune description disponible';
          return text.length > length ? `${text.substring(0, length)}...` : text;
      },
      
      parsePrice(price) {
          if (price === null || price === undefined) return null;
          if (typeof price === 'number') return price;
          if (typeof price === 'string') {
              const parsed = parseFloat(price.replace(',', '.').replace(/[^\d.-]/g, ''));
              return isNaN(parsed) ? null : parsed;
          }
          return null;
      },
      
      formatPrice(price) {
          if (typeof price === 'string' && price.trim() !== '') {
              return price;
          }

          const numericPrice = this.parsePrice(price);
          
          if (numericPrice === null) return 'Prix sur demande';
          
          return `À partir de ${numericPrice.toFixed(2)}€`;
      },
      
      getVisibleCards() {
          if (this.windowWidth >= 1200) return 3;
          if (this.windowWidth >= 768) return 2;
          return 1;
      },
      
      getMaxIndex(servicesLength) {
          return Math.max(servicesLength - this.getVisibleCards(), 0);
      },
      
      prevCategory(category) {
          const newIndex = Math.max(0, (this.currentIndices[category] || 0) - 1);
          this.currentIndices = {
              ...this.currentIndices,
              [category]: newIndex
          };
      },
      
      nextCategory(category) {
          const currentIndex = this.currentIndices[category] || 0;
          const maxIndex = this.getMaxIndex(this.servicesByCategory[category].length);
          const newIndex = Math.min(maxIndex, currentIndex + 1);
          
          this.currentIndices = {
              ...this.currentIndices,
              [category]: newIndex
          };
      },
      
      handleResize() {
          this.windowWidth = window.innerWidth;
          const newIndices = {...this.currentIndices};
          
          Object.keys(this.servicesByCategory).forEach(category => {
              newIndices[category] = Math.min(
                  newIndices[category] || 0,
                  this.getMaxIndex(this.servicesByCategory[category].length)
              );
          });
          
          this.currentIndices = newIndices;
      },
      
      viewMore(service) {
          this.$router.push({
              name: 'DétailsService',
              params: { 
                  hotel_nom: this.hotelNom || 'non-specifie', 
                  id: service.id 
              },
              query: { 
                  category: service.categorie || 'autres'
              }
          });
      },
      
      bookService(service) {
          console.log('Réservation du service:', service.id);
          this.$emit('book-service', service);
      },
      
      applyFilters() {
          let filtered = [...this.allServices];

          if (this.searchQuery) {
              const query = this.searchQuery.toLowerCase();
              filtered = filtered.filter(service => 
                  service.nom.toLowerCase().includes(query)
              );
          }

          if (this.categoryFilter) {
              filtered = filtered.filter(service => 
                  service.categorie.toLowerCase().includes(this.categoryFilter.toLowerCase())
              );
          }

          if (this.priceFilter) {
              filtered = filtered.filter(service => {
                  if (typeof service.prix === 'string') return false;
                  
                  const price = this.parsePrice(service.prix);
                  if (price === null) return false;
                  
                  if (this.priceFilter === '300+') {
                      return price >= 300;
                  } else {
                      const [min, max] = this.priceFilter.split('-').map(Number);
                      return price >= min && (max ? price <= max : true);
                  }
              });
          }

          filtered.sort((a, b) => {
              const priceA = this.parsePrice(a.prix);
              const priceB = this.parsePrice(b.prix);
              
              const aIsString = typeof a.prix === 'string';
              const bIsString = typeof b.prix === 'string';
              
              if (aIsString && !bIsString) return 1;
              if (!aIsString && bIsString) return -1;
              if (aIsString && bIsString) return 0;
              
              if (priceA === null && priceB === null) return 0;
              if (priceA === null) return 1;
              if (priceB === null) return -1;
              
              if (this.sortBy === 'price-asc') {
                  return priceA - priceB;
              } else {
                  return priceB - priceA;
              }
          });

          this.services = filtered;
      },
      
      resetFilters() {
          this.searchQuery = '';
          this.categoryFilter = '';
          this.priceFilter = '';
          this.sortBy = 'price-asc';
          this.applyFilters();
      },
      
      async fetchServices() {
          this.isLoading = true;
          this.error = null;
          
          try {
              this.hotelNom = this.$route.params.hotel_nom;
              const response = await axios.get(`/api/hotels/${this.hotelNom}/services`);
              
              if (!response.data) {
                  this.services = [];
                  this.allServices = [];
                  return;
              }
              
              let servicesData = [];
              if (Array.isArray(response.data)) {
                  servicesData = response.data;
              } else if (typeof response.data === 'object' && response.data !== null) {
                  servicesData = Object.values(response.data).filter(service => service !== null);
              }
              
              this.allServices = servicesData;
              this.services = servicesData;
              
          } catch (err) {
              console.error('Erreur lors du chargement des services:', err);
              this.error = 'Impossible de charger les services. Veuillez réessayer plus tard.';
              this.services = [];
              this.allServices = [];
          } finally {
              this.isLoading = false;
          }
      }
  },
  async mounted() {
      window.addEventListener('resize', this.handleResize);
      await this.fetchServices();
      
      const initialIndices = {};
      Object.keys(this.servicesByCategory).forEach(category => {
          initialIndices[category] = 0;
      });
      this.currentIndices = initialIndices;
  },
  beforeUnmount() {
      window.removeEventListener('resize', this.handleResize);
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');

.listeServices{
  margin: 0 auto;
  padding: 2rem;
}

#navbar{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.services-by-category {
  padding: 2rem 1.5rem;
}

.hero-section {
  text-align: center;
  padding: 1rem 1rem;
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
  url("https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80");
  background-size: cover;
  background-position: center;
  border-radius: 20px;
  margin-bottom: 1rem;
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
  padding: 2rem;
}

.loading-animation {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.loading-circle {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: #2a90d9;
  animation: bounce 1.4s infinite ease-in-out;
}

.loading-circle:nth-child(1) {
  animation-delay: -0.32s;
}

.loading-circle:nth-child(2) {
  animation-delay: -0.16s;
}

@keyframes bounce {
  0%, 80%, 100% { 
      transform: scale(0);
  } 40% { 
      transform: scale(1);
  }
}

.loading-text {
  font-size: 1.1rem;
  color: #2a90d9;
}

.error-message {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
  color: #e53e3e;
  background: #fee2e2;
  border-radius: 8px;
  margin: 1rem;
}

.no-results {
  text-align: center;
  padding: 3rem;
  color: #64748b;
}

.no-results i {
  color: #94a3b8;
  margin-bottom: 1rem;
}

.btn-reset {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #2a90d9;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-reset:hover {
  background: #1e6ca8;
  transform: translateY(-2px);
}

.category-section {
  margin-bottom: 3rem;
}

.search-filter-section {
  background: white;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  margin-bottom: 2rem;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.search-box,
.select-wrapper {
  position: relative;
}

.form-control {
  width: 100%;
  padding-left: 3rem;
  padding-right: 1.5rem;
  border: 2px solid #eef2f7;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8fafc;
  color: #1e293b;
  height: 50px;
}

select.form-control option {
  color: #1e293b;
  background: white;
}

.form-control:focus {
  border-color: #2a90d9;
  box-shadow: 0 0 0 3px rgba(42, 144, 217, 0.1);
  outline: none;
}

.search-icon,
.filter-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 1.1rem;
}

.category-title {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  color: #2a90d9;
  margin-bottom: 2rem;
  position: relative;
  font-size: 1.8rem;
  padding-left: 1rem;
  border-left: 4px solid #2a90d9;
}

.position-relative {
  position: relative;
  padding: 0 3.5rem;
}

.cards-container {
  width: 100%;
  overflow: hidden;
  margin: 0 -0.75rem;
  scroll-behavior: smooth;
}

.cards-wrapper {
  display: flex;
  transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  will-change: transform;
}

.service-card-wrapper {
  padding: 0 0.75rem;
  flex: 0 0 calc(100% / var(--visible-cards, 3)));
  min-width: 0;
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
  margin: 0.5rem 0 0.5rem;
  flex: 1;
}

.no-description {
  font-size: 0.9rem;
  color: #94a3b8;
  font-style: italic;
  margin: 0.5rem 0 1.5rem;
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
  .services-by-category {
      padding: 1.5rem 1rem;
  }

  .category-title {
      font-size: 1.5rem;
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

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>