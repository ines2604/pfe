<template>
  <Navbar id="navbar"/>
  <div class="hotel-offers" style="margin-top: 80px">
      <section class="hero-section">
          <h1 class="main-title">Offres spéciales</h1>
          <p class="subtitle">Découvrez nos promotions exclusives</p>
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
                      <option value="séjour">Séjour</option>
                      <option value="romantique">Package Romantique</option>
                      <option value="famille">Famille</option>
                      <option value="affaire">Affaires</option>
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
          <p aria-live="polite" class="loading-text">Chargement des offres...</p>
      </div>
  
      <template v-else>
          <p class="results-count" v-if="filteredOffers.length > 0">
              {{ filteredOffers.length }} offres trouvées
          </p>
          <p class="results-count" v-else>
              Aucune offre ne correspond à vos critères
          </p>
  
          <div class="offer-grid">
              <div
                  v-for="offer in displayedOffers"
                  :key="offer.id"
                  class="hotel-card-wrapper"
              >
                  <div class="card hotel-card h-100">
                      <div class="image-container">
                          <img :src="offer.images[0]" class="card-img-top" :alt="offer.nom">
                          <div class="category-tag" :class="getCategoryClass(offer.categorie)">
                              <i :class="getCategoryIcon(offer.categorie)"></i>
                              {{ getCategoryName(offer.categorie) }}
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="d-flex justify-content-between align-items-center">
                              <h5 class="card-title">{{ offer.nom }}</h5>
                              <span v-if="offer.reduction_pourcentage" class="discount-badge">
                                  -{{ offer.reduction_pourcentage }}%
                              </span>
                          </div>
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
                          <button class="btn-action btn-details" @click="viewOfferDetails(offer)" aria-label="Voir détails">
                              <i class="fas fa-info-circle"></i>
                              Détails
                          </button>
                          <button class="btn-action btn-book" @click="bookOffer(offer)" aria-label="Réserver">
                              <i class="fas fa-calendar-check"></i>
                              Réserver
                          </button>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="text-center mt-5" v-if="filteredOffers.length > 0">
              <button
                  class="load-more-btn"
                  @click="loadMore"
                  v-if="hasMoreOffers"
                  :disabled="isLoadingMore"
              >
                  <span v-if="!isLoadingMore">Voir plus d'offres</span>
                  <div v-else class="button-loader">
                      <div class="dot"></div>
                      <div class="dot"></div>
                      <div class="dot"></div>
                  </div>
              </button>
              <button
                  class="load-less-btn"
                  @click="showLess"
                  v-if="currentPage > 1"
                  :disabled="isLoadingMore"
              >
                  Voir moins
              </button>
          </div>
      </template>
  </div>
  <Footer />
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import Navbar from './Navbar.vue';
import Footer from './footer.vue';

export default {
  components: {
      Navbar,
      Footer
  },
  data() {
      return {
          isLoading: false,
          isLoadingMore: false,
          searchQuery: '',
          categoryFilter: '',
          priceFilter: '',
          sortBy: 'price-asc',
          currentPage: 1,
          itemsPerPage: 12,
          offers: [],
          hotelNom: ''
      };
  },
  computed: {
      filteredOffers() {
          let result = [...this.offers];

          if (this.searchQuery) {
              const query = this.searchQuery.toLowerCase();
              result = result.filter(offer => 
                  offer.nom.toLowerCase().includes(query) ||
                  (offer.description && offer.description.toLowerCase().includes(query))
              );
          }

          if (this.categoryFilter) {
              result = result.filter(offer => 
                  offer.categorie.toLowerCase().includes(this.categoryFilter.toLowerCase())
              );
          }

          if (this.priceFilter) {
              if (this.priceFilter.includes('+')) {
                  const minPrice = parseFloat(this.priceFilter.replace('+', ''));
                  result = result.filter(offer => this.parsePrice(offer.prix) >= minPrice);
              } else {
                  const [min, max] = this.priceFilter.split('-').map(Number);
                  result = result.filter(offer => {
                      const price = this.parsePrice(offer.prix);
                      return price >= min && (max ? price <= max : true);
                  });
              }
          }

          switch (this.sortBy) {
              case 'price-asc':
                  result.sort((a, b) => this.parsePrice(a.prix) - this.parsePrice(b.prix));
                  break;
              case 'price-desc':
                  result.sort((a, b) => this.parsePrice(b.prix) - this.parsePrice(a.prix));
                  break;
          }

          return result;
      },
      displayedOffers() {
          const start = 0;
          const end = this.currentPage * this.itemsPerPage;
          return this.filteredOffers.slice(start, end);
      },
      hasMoreOffers() {
          return this.displayedOffers.length < this.filteredOffers.length;
      }
  },
  methods: {
      getCategoryClass(category) {
          if (!category) return 'default-category';
          const lowerCategory = category.toLowerCase();
          if (lowerCategory.includes('romantique')) return 'romantic-category';
          if (lowerCategory.includes('affaire')) return 'business-category';
          if (lowerCategory.includes('famille')) return 'family-category';
          if (lowerCategory.includes('séjour')) return 'family-category';
          return 'default-category';
      },
      
      getCategoryIcon(category) {
          if (!category) return 'fas fa-star';
          const lowerCategory = category.toLowerCase();
          if (lowerCategory.includes('romantique')) return 'fas fa-heart';
          if (lowerCategory.includes('affaire')) return 'fas fa-briefcase';
          if (lowerCategory.includes('famille')) return 'fas fa-users';
          if (lowerCategory.includes('séjour')) return 'fas fa-users';
          return 'fas fa-star';
      },
      
      truncateDescription(text, length = 100) {
          if (!text) return 'Aucune description disponible';
          return text.length > length ? text.substring(0, length) + '...' : text;
      },
      
      getCategoryName(category) {
          const categories = {
              'sejour': 'Séjour',
              'romantique': 'Romantique',
              'famille': 'Famille',
              'affaires': 'Affaires'
          };
          return categories[category?.toLowerCase()] || category;
      },
      
      parsePrice(price) {
          if (price === null || price === undefined) return 0;
          if (typeof price === 'number') return price;
          if (typeof price === 'string') {
              const parsed = parseFloat(price.replace(',', '.').replace(/[^\d.-]/g, ''));
              return isNaN(parsed) ? 0 : parsed;
          }
          return 0;
      },
      
      formatPrice(price) {
          const numericPrice = this.parsePrice(price);
          return `${numericPrice.toFixed(2)}€`;
      },
      
      async fetchOffers() {
          this.isLoading = true;
          try {
              this.hotelNom = this.$route.params.hotel_nom;
              const response = await axios.get(`/api/hotels/${this.hotelNom}/offres`);
              
              this.offers = response.data.map(offer => ({
                  ...offer,
                  prix: this.parsePrice(offer.prix),
                  ancien_prix: offer.ancien_prix ? this.parsePrice(offer.ancien_prix) : null
              }));
          } catch (error) {
              console.error("Erreur lors du chargement des offres:", error);
              Swal.fire({
                  icon: 'error',
                  title: 'Erreur',
                  text: 'Impossible de charger les offres. Veuillez réessayer plus tard.',
                  confirmButtonColor: '#2a90d9'
              });
          } finally {
              this.isLoading = false;
          }
      },
      
      viewOfferDetails(offer) {
          this.$router.push({
              name: 'DétailsOffre',
              params: { 
                  hotel_nom: this.hotelNom,
                  id: offer.id 
              }
          });
      },
      
      bookOffer(offer) {
          // Logique de réservation
          console.log('Réservation de l\'offre:', offer.id);
          this.$emit('book-offer', offer);
      },
      
      applyFilters() {
          this.currentPage = 1;
      },
      
      async loadMore() {
          this.isLoadingMore = true;
          try {
              await new Promise(resolve => setTimeout(resolve, 300));
              this.currentPage++;
          } finally {
              this.isLoadingMore = false;
          }
      },
      
      showLess() {
          this.currentPage = Math.max(1, this.currentPage - 1);
      }
  },
  mounted() {
      this.fetchOffers();
  }
};
</script>

<style scoped>
/* Les styles restent exactement les mêmes que dans votre code précédent */
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap");

.hotel-offers {
  margin: 0 auto;
  padding: 2rem;
  max-width: 1800px;
}

#navbar{
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

.results-count {
  font-size: 1.1rem;
  color: #64748b;
  margin-bottom: 2rem;
  padding-left: 0.5rem;
}

.offer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 2rem;
  margin: 0 -15px;
}

.hotel-card-wrapper {
  scroll-snap-align: start;
  transition: transform 0.5s ease-in-out;
  padding: 0 15px;
}

.card.hotel-card {
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  border: none;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  height: 100%;
}

.card.hotel-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.image-container {
  position: relative;
}

.card-img-top {
  transition: transform 0.3s ease-in-out;
  height: 250px;
  object-fit: cover;
  width: 100%;
}

.card.hotel-card:hover .card-img-top {
  transform: scale(1.1);
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
  margin-top: 1rem;
}

.old-price {
  text-decoration: line-through;
  color: #999;
  font-size: 0.9rem;
}

.current-price {
  margin: 0;
  font-size: 1.2rem;
  color: #2a90d9 !important;
}

.card-body {
  padding: 1.5rem;
}

.card-title {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.description {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #555;
  margin-bottom: 1rem;
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

.load-more-btn, .load-less-btn {
  padding: 1rem 2rem;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1rem;
  margin: 0 0.5rem;
}

.load-more-btn {
  background: linear-gradient(135deg, #2a90d9, #1e6ca8);
  color: white;
  min-width: 200px;
}

.load-more-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(42, 144, 217, 0.3);
}

.load-less-btn {
  background: #f1f5f9;
  color: #475569;
}

.load-less-btn:hover:not(:disabled) {
  background: #e2e8f0;
}

.button-loader {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}

.dot {
  width: 6px;
  height: 6px;
  background-color: white;
  border-radius: 50%;
  animation: pulse 0.8s ease-in-out infinite;
}

.dot:nth-child(2) {
  animation-delay: 0.2s;
}

.dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes pulse {
  0%, 100% {
      transform: scale(1);
      opacity: 1;
  }
  50% {
      transform: scale(0.5);
      opacity: 0.5;
  }
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.form-control:disabled {
  background: #f1f5f9;
  cursor: not-allowed;
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
  .hotel-offers {
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

  .offer-grid {
      grid-template-columns: 1fr;
      margin: 0;
  }
  
  .hotel-card-wrapper {
      padding: 0;
  }
  
  .card-img-top {
      height: 200px;
  }
  
  .card-actions {
      flex-direction: column;
      gap: 0.5rem;
  }

  .filter-grid {
      grid-template-columns: 1fr;
  }

  .form-control {
      height: 45px;
  }
}
</style>