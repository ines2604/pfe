<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <a class="navbar-brand alliance-logo" href="/home">Alliance Hospitality</a>
      <button 
        class="navbar-toggler" 
        type="button" 
        @click="isMenuOpen = !isMenuOpen"
        aria-controls="navbarSupportedContent" 
        aria-expanded="isMenuOpen.toString()" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" :class="{ 'show': isMenuOpen }" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a 
              class="nav-link" 
              href="/home/listeHotels" 
              :class="{ active: activeLink === 'hotels' }" 
              @click="setActiveLink('hotels')"
            >
              Hotels
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              href="/home/listeChambres" 
              :class="{ active: activeLink === 'chambres' }" 
              @click="setActiveLink('chambres')"
            >
              Chambres
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              href="/home/listeServices" 
              :class="{ active: activeLink === 'services' }" 
              @click="setActiveLink('services')"
            >
              Services
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              href="/home/listeOffres" 
              :class="{ active: activeLink === 'offres' }" 
              @click="setActiveLink('offres')"
            >
              Offres
            </a>
          </li>
          <li class="nav-item">
            <a 
              class="nav-link" 
              href="/home/aboutus" 
              :class="{ active: activeLink === 'about' }" 
              @click="setActiveLink('about')"
            >
              À propos de nous
            </a>
          </li>
        </ul>
        <div class="d-flex align-items-center">
          <router-link class="profile-link" to="/profile" v-if="isAuthenticated"><i class="fas fa-user"></i> mon profile</router-link>
          <div v-else>
            <router-link to="/login" class="btn custom-btn me-2">
              Se connecter
            </router-link>
            <router-link to="/register" class="btn custom-btn-outline d-block d-lg-inline-block">
              S'inscrire
            </router-link>
          </div>
          <div class="dropdown me-3 ml-4" @mouseleave="showDropdown = false">
            <button 
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              @click="showDropdown = !showDropdown"
            >
              <i :class="selectedFlag"></i> {{ selectedLanguage }}
            </button>
            <ul 
              class="dropdown-menu"
              :class="{ 'show': showDropdown }"
            >
              <li v-for="(lang, index) in languages" :key="index">
                <button 
                  class="dropdown-item" 
                  @click="selectLanguage(lang)"
                >
                  <i :class="lang.flag"></i> {{ lang.name }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      isAuthenticated: false,
      isMenuOpen: false,
      showDropdown: false,
      selectedLanguage: 'Français',
      selectedFlag: 'flag-france flag',
      activeLink: '',
      languages: [
        { name: 'Français', flag: 'flag-france flag' },
        { name: 'English', flag: 'flag-united-kingdom flag' },
        { name: 'Español', flag: 'flag-spain flag' },
        { name: 'Deutsch', flag: 'flag-germany flag' },
        { name: 'العربية', flag: 'flag-saudi-arabia flag' },
      ]
    };
  },
  mounted(){
    this.checkAuthentication();
  },
  methods: {
    checkAuthentication() {
      const token = localStorage.getItem('token');
      this.isAuthenticated = !!token;
    },
    selectLanguage(lang) {
      this.selectedLanguage = lang.name;
      this.selectedFlag = lang.flag;
      this.showDropdown = false;
    },
    setActiveLink(link) {
      this.activeLink = link;
    },
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Sail&display=swap');

.navbar {
  padding: 1rem 0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar-collapse {
  display: flex !important;
  flex-basis: auto;
}

@media (max-width: 991.98px) {
  .navbar-collapse {
    display: none !important;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    padding: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1050;
  }

  .navbar-collapse.show {
    display: block !important;
    z-index: 1050;
  }

  .navbar-nav {
    margin-bottom: 1rem !important;
  }

  .d-flex {
    flex-direction: column;
    align-items: stretch !important;
  }

  .dropdown {
    margin-bottom: 1rem;
  }

  .btn {
    margin-bottom: 0.5rem;
  }
}

.alliance-logo {
  font-family: 'Sail', cursive;
  font-size: 28px;
  color: #2a90d9;
  transition: color 0.3s ease;
  margin-right: 120px;
}

.nav-link {
  color: #333 !important;
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: all 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
  color: #2a90d9 !important;
}

.custom-btn {
  background-color: #2a90d9;
  color: white;
  border: none;
  padding: 0.5rem 1.5rem;
  transition: all 0.3s ease;
}

.custom-btn:hover {
  background-color: #1e6ca8;
  color: white;
}

.custom-btn-outline {
  background-color: transparent;
  color: #2a90d9;
  border: 2px solid #2a90d9;
  padding: 0.5rem 1.5rem;
  transition: all 0.3s ease;
}

.custom-btn-outline:hover {
  background-color: #2a90d9;
  color: white;
}

.dropdown-menu {
  display: none;
  min-width: 200px;
  padding: 0.5rem 0;
  margin: 0;
  border: none;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

.dropdown-menu.show {
  display: block;
}

.dropdown-item {
  padding: 0.5rem 1rem;
  color: #333;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #2a90d9;
}

.btn-outline-secondary {
  border-color: #dee2e6;
  color: #333;
  background: white;
  transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
  background-color: #f8f9fa;
  border-color: #dee2e6;
  color: #2a90d9;
}
.nav-link.active {
  color: #2a90d9 !important;
}

.profile-link{
  display: inline-flex;
    align-items: center;
    padding: 8px 15px;
    background-color: #2a90d9;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background 0.3s, transform 0.2s;
}

.profile-link i {
    margin-right: 8px;
}

.profile-link:hover {
    background-color: #1e6ca8;
    transform: scale(1.05);
}
</style>
