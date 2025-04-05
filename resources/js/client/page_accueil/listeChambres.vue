<template>
    <Navbar id="navbar"/>
    <div class="hotel-rooms" style="margin-top: 80px">
      <section class="hero-section">
        <h1 class="main-title">Chambres et suites</h1>
        <p class="subtitle">Trouvez votre hébergement idéal</p>
      </section>
  
      <div class="search-filter-section">
        <div class="filter-grid">
          <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input
              type="text"
              id="searchQuery"
              class="form-control"
              v-model="searchQuery"
              placeholder="Rechercher"
              :disabled="isLoading"
            />
          </div>
  
          <div class="date-picker">
            <i class="fas fa-calendar-alt date-icon"></i>
            <flat-pickr
              id="dateRange"
              v-model="dateRange"
              :config="datePickerConfig"
              class="form-control"
              placeholder="Sélectionnez une période"
              :disabled="isLoading"
            />
          </div>
  
          <div class="select-wrapper">
            <i class="fas fa-filter filter-icon"></i>
            <select id="priceFilter" class="form-control"  v-model="priceFilter" :disabled="isLoading">
              <option value="">Prix (Tous)</option>
              <option value="0-100">Moins de 100€</option>
              <option value="100-200">100€ - 200€</option>
              <option value="200-300">200€ - 300€</option>
              <option value="300+">Plus de 300€</option>
            </select>
          </div>
  
          <div class="select-wrapper">
            <i class="fas fa-filter filter-icon"></i>
            <select id="capacityFilter" class="form-control" v-model="capacityFilter" :disabled="isLoading">
              <option value="">Capacité (Tous)</option>
              <option value="1-2">1-2 personnes</option>
              <option value="3-4">3-4 personnes</option>
              <option value="5+">5+ personnes</option>
            </select>
          </div>
  
          <div class="select-wrapper">
            <i class="fas fa-filter filter-icon"></i>
            <select id="sortBy" class="form-control" v-model="sortBy" :disabled="isLoading">
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
        <p aria-live="polite" class="loading-text">Chargement des chambres...</p>
      </div>
  
      <template v-else>
        <p class="results-count" v-if="filteredRooms.length > 0">
          {{ filteredRooms.length }} chambres trouvées
        </p>
        <p class="results-count" v-else>
          Aucune chambre ne correspond à vos critères
        </p>
  
        <div class="room-grid">
          <div
            v-for="(room, index) in displayedRooms"
            :key="room.id"
            class="room-card"
          >
            <div class="room-image-wrapper">
              <img :src="room.images[0]" class="room-image" alt="image de chambre"  loading="lazy" />
            </div>
  
            <div class="room-content">
              <h2 class="room-title">{{ room.nom }}</h2>
              <div class="adresse"><i class="fas fa-map-marker-alt"></i>{{ room.hotel_nom }}<br /></div>
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
                  <span class="price-amount">à partir de {{ room.prix }}€/nuit</span>
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
  
        <div class="text-center mt-5" v-if="filteredRooms.length > 0">
          <button
            class="load-more-btn"
            @click="loadMore"
            v-if="hasMoreRooms"
            :disabled="isLoadingMore"
          >
            <span v-if="!isLoadingMore">Voir plus de chambres</span>
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
  import Swal from 'sweetalert2';
  import flatPickr from "vue-flatpickr-component";
  import "flatpickr/dist/flatpickr.css";
  import { French } from "flatpickr/dist/l10n/fr";
  import axios from 'axios';
  import Navbar from './Navbar.vue';
  import Footer from './footer.vue';
  
  export default {
    components: {
      flatPickr,
      Navbar,
      Footer
    },
    data() {
      return {
        isLoading: false,
        isLoadingMore: false,
        searchQuery: "",
        priceFilter: "",
        capacityFilter: "",
        sortBy: "price-asc",
        itemsPerPage: 6,
        currentPage: 1,
        dateRange: null,
        datePickerConfig: {
          mode: "range",
          dateFormat: "d-m-Y",
          locale: French,
          minDate: "today",
        },
        hotel_nom: '',
        rooms: [],
      };
    },
    mounted() {
      this.hotel_nom = this.$route.params.hotel_nom;
      this.fetchRooms();
    },
    computed: {
      filteredRooms() {
        let result = [...this.rooms];
  
        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase();
          result = result.filter(room => 
            room.nom.toLowerCase().includes(query)
          );
        }
  
        if (this.priceFilter) {
          if (this.priceFilter.includes("+")) { 
            const minPrice = parseFloat(this.priceFilter.replace("+", ""));
            result = result.filter(room => room.prix >= minPrice);
          } else {
            const [min, max] = this.priceFilter.split("-").map(Number);
            result = result.filter(room => room.prix >= min && room.prix <= max);
          }
        }
  
        // Filtre par capacité
        if (this.capacityFilter) {
          const [min, max] = this.capacityFilter.split(/-|\+/);
          const minCapacity = parseInt(min);
          const maxCapacity = max === '+' ? Infinity : parseInt(max);
          
          result = result.filter(room => {
            return room.capacite >= minCapacity && 
              (max === '+' || room.capacite <= maxCapacity);
          });
        }
  
        switch (this.sortBy) {
          case "price-asc":
            result.sort((a, b) => a.prix - b.prix);
            break;
          case "price-desc":
            result.sort((a, b) => b.prix - a.prix);
            break;
        }
  
        return result;
      },
      displayedRooms() {
        const start = 0;
        const end = this.currentPage * this.itemsPerPage;
        return this.filteredRooms.slice(start, end);
      },
      hasMoreRooms() {
        return this.displayedRooms.length < this.filteredRooms.length;
      },
      dateParams() {
        if (!this.dateRange || this.dateRange.length !== 2) return null;
        
        const [start, end] = this.dateRange;
        return {
          start_date: this.formatDateForAPI(start),
          end_date: this.formatDateForAPI(end)
        };
      }
    },
    methods: {
      truncateDescription(text, length = 100) {
        return text.length > length ? text.substring(0, length) + '...' : text;
      },
      formatDateForAPI(date) {
        if (!date) return null;
        const d = new Date(date);
        return d.toISOString().split('T')[0];
      },
      
      viewMore(room) {
        this.$router.push({ 
          name: 'DétailsChambre', 
          params: { 
            hotel_nom: room.hotel_nom ,
            id: room.id,
          } 
        });
      },
      
      bookRoom(room) {
        
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
      },
      
      async fetchRooms() {
        this.isLoading = true;
        try {
          const response = await axios.get(`/api/chambres`);
          this.rooms = response.data.map(room => ({
            ...room,
            prix: Number(room.prix),
            capacite: Number(room.capacite)
          }));
        } catch (error) {
          console.error("Erreur:", error);
          this.showError(error);
        } finally {
          this.isLoading = false;
        }
      },
      
      getPriceFilterParams() {
        if (!this.priceFilter) return {};
        
        const [min, max] = this.priceFilter.split(/-|\+/);
        const params = {
          min_price: parseFloat(min)
        };
        
        if (max !== '+') {
          params.max_price = parseFloat(max);
        }
        
        return params;
      },
      
      getCapacityFilterParams() {
        if (!this.capacityFilter) return {};
        
        const [min, max] = this.capacityFilter.split(/-|\+/);
        const params = {
          min_capacity: parseInt(min)
        };
        
        if (max !== '+') {
          params.max_capacity = parseInt(max);
        }
        
        return params;
      },
      
      showError(error) {
        let message = 'Une erreur est survenue';
        
        if (error.response) {
          switch (error.response.status) {
            case 404:
              message = 'Hôtel non trouvé';
              break;
            case 400:
              message = 'Paramètres de recherche invalides';
              break;
            case 500:
              message = 'Erreur serveur - Veuillez réessayer plus tard';
              break;
          }
        } else if (error.request) {
          message = 'Problème de connexion - Vérifiez votre réseau';
        }
        
        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: message,
          confirmButtonColor: '#2a90d9'
        });
      },
      
      debounceFetch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
          this.currentPage = 1;
          this.fetchRooms();
        }, 500);
      }
    },
    watch: {
      searchQuery() {
        this.currentPage = 1;
        this.debounceFetch();
      },
      priceFilter() {
        this.currentPage = 1;
        this.fetchRooms();
      },
      capacityFilter() {
        this.currentPage = 1;
        this.fetchRooms();
      },
      dateRange() {
        this.currentPage = 1;
        this.fetchRooms();
      },
      sortBy() {
        this.currentPage = 1;
      }
    },
    beforeDestroy() {
      clearTimeout(this.searchTimeout);
      if (this.cancelToken) {
        this.cancelToken.cancel('Composant détruit');
      }
    }
  };
  </script>
  
  <style scoped>
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap");
  
  #navbar{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
  }

  .hotel-rooms {
    margin: 0 auto;
    padding: 2rem;
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
    font-family: 'Playfair Display' , serif;
    animation: slideIn 1s ease-in-out;
  }
  
  .subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    font-family: 'Poppins' , sans-serif;
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
  .date-picker,
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
  .date-icon,
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
  
  .room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
  }
  
  .room-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  
  .room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
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
  
  .room-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.9);
    color: #f59e0b;
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-weight: 600;
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
  
  .room-features {
    display: flex;
    font-family: 'Poppins', sans-serif;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    color: #64748b;
  }

  .adresse{
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    color: #64748b;
    margin-bottom: 0.5rem;
  }

  .adresse i{
    color: #2a90d9;
  }
  
  .feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    color: #64748b;
  }

  .feature i{
    color: #2a90d9;
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
  
  .price-period {
    color: #94a3b8;
    font-size: 1rem;
  }
  
  .button-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  
  .btn-quick-view,
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
  }
  
  .btn-quick-view {
    background: #f1f5f9;
    color: #475569;
  }
  
  .btn-quick-view:hover {
    background: #e2e8f0;
  }
  
  .btn-book {
    background: linear-gradient(135deg, #2a90d9, #1e6ca8);
    color: white;
    box-shadow: 0 4px 15px rgba(42, 144, 217, 0.2);
    transition: all 0.3s ease;
  }
  
  .btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(42, 144, 217, 0.3);
  }
  
  .premium {
    border: 2px solid rgba(245, 158, 11, 0.1);
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
  
  @media (max-width: 768px) {
    .hotel-rooms {
      padding: 1rem;
    }
  
    .hero-section {
      padding: 3rem 1rem;
    }
  
    .main-title {
      font-size: 2.5rem;
    }
  
    .subtitle {
      font-size: 1.2rem;
    }
  
    .room-grid {
      grid-template-columns: 1fr;
    }
  
    .button-group {
      grid-template-columns: 1fr;
    }
  }
  </style>