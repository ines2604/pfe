<template id="aboutus">
  <Navbar id="navbar"/>
  <div class="about-us">
    <header class="hero-section position-relative mb-5">
      <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" class="w-100 hero-image" alt="Vue aérienne d'un hôtel luxueux" loading="lazy" role="img">
      <div class="overlay" aria-hidden="true"></div>
      <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="display-3 fw-bold">{{ informations.nom }}</h1>
        <p class="lead">Excellence dans l'hospitalité depuis {{ formatDate(informations.date_fondation) }}</p>
      </div>
    </header>

    <main class="container">
      <section class="mb-5">
        <h2 class="text-center mb-4 section-title">Notre Histoire</h2>
        <div class="row align-items-center">
          <div class="col-md-6">
            <p>{{ informations.histoire }}</p>
          </div>
          <div class="col-md-6">
            <figure class="image-container">
              <img src="https://images.unsplash.com/photo-1615460549969-36fa19521a4f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" class="img-fluid rounded shadow" alt="Lobby élégant d'un hôtel" role="img" loading="lazy">
            </figure>
          </div>
        </div>
      </section>

      <section class="mb-5 bg-light p-5 rounded">
        <h2 class="text-center mb-4 section-title">Notre Mission et Nos Valeurs</h2>
        <div class="row g-4">
          <div v-for="(mission, index) in informations.missions" :key="index" class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body text-center">
                <i class="bi bi-star-fill mb-3 text-primary fs-2"></i>
                <h5 class="card-title">{{ mission.titre }}</h5>
                <p class="card-text text-black">{{ mission.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="text-center mb-4 section-title">Chiffres Clés</h2>
        <div class="row g-4 text-center">
          <div class="col-md-3">
            <div class="stat-card">
              <i class="bi bi-building mb-3 fs-1 text-primary" aria-hidden="true"></i>
              <h3 class="fw-bold" ref="nbHotels">0</h3>
              <p>Hôtels</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <i class="bi bi-door-open mb-3 fs-1 text-primary" aria-hidden="true"></i>
              <h3 class="fw-bold" ref="nbChambres">0</h3>
              <p>Chambres</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <i class="bi bi-people mb-3 fs-1 text-primary" aria-hidden="true"></i>
              <h3 class="fw-bold" ref="nbEmployes">0</h3>
              <p>Employés</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <i class="bi bi-geo-alt mb-3 fs-1 text-primary" aria-hidden="true"></i>
              <h3 class="fw-bold" ref="nbPays">0</h3>
              <p>Pays</p>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-5">
        <h2 class="text-center mb-4 section-title">Notre Engagement</h2>
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="image-container">
              <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" class="img-fluid rounded shadow" alt="Notre équipe" role="img">
            </div>
          </div>
          <div class="col-md-6">
            <h3 class="mb-3">Responsabilité Sociale</h3>
            <p>Chez {{ informations.nom }}, nous croyons en un tourisme responsable et durable. Notre engagement se traduit par :</p>
            <ul class="list-unstyled">
              <li v-for="(engagement, index) in informations.engagements" :key="index" class="mb-2">
                <i class="fas fa-leaf text-success me-2" aria-hidden="true"></i>{{ engagement }}
              </li>
            </ul>
          </div>
        </div>
      </section>
    </main>
  </div>
  <Footer />
</template>

<script>
import axios from 'axios';
import Navbar from './Navbar.vue';
import Footer from './footer.vue';

export default {
  name: 'AboutUs',
  components: {
    Navbar,
    Footer
  },
  data() {
    return {
      informations: {},
    };
  },
  async mounted() {
    try {
      const response = await axios.get('/api/chaine_hoteliere');
      console.log(response.data);
      this.informations = response.data;
      this.animateNumbers(); 
    } catch (error) {
      console.error('Erreur lors de la récupération des informations de la chaîne hôtelière: ', error);
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
    },
    animateNumbers() {
      const duration = 6000; 
      const interval = 20; 

      const animate = (refName, targetValue) => {
        let currentValue = 0;
        const increment = Math.ceil(targetValue / (duration / interval));
        const element = this.$refs[refName];

        const timer = setInterval(() => {
          currentValue += increment;
          if (currentValue >= targetValue) {
            currentValue = targetValue;
            clearInterval(timer);
          }
          element.textContent = currentValue;
        }, interval);
      };

      animate('nbHotels', parseInt(this.informations.nb_hotels));
      animate('nbChambres', parseInt(this.informations.nb_chambres));
      animate('nbEmployes', parseInt(this.informations.nb_employes));
      animate('nbPays', parseInt(this.informations.nb_pays));
    },
  },
};
</script>

<style scoped>
#aboutus{
  position: relative;
}

#navbar{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.hero-section {
  position: relative;
  overflow: hidden;
  height: 80vh;
  background-color: #1a1a1a;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.9;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.6));
}

.image-container {
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.image-container:hover {
  transform: translateY(-5px);
}

.image-container img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.image-container:hover img {
  transform: scale(1.05);
}

.stat-card {
  padding: 2rem;
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
}

.stat-card h3 {
  color: #2a90d9;
  font-size: 2.5rem;
  margin: 1rem 0;
}

.service-card {
  padding: 2rem;
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  height: 100%;
}

.service-card:hover {
  transform: translateY(-5px);
}

.contact-info {
  font-size: 1.1rem;
}

.contact-info i {
  color: #2a90d9;
}

.section-title {
  color: #2a90d9;
  font-weight: 600;
  margin-bottom: 2rem;
  position: relative;
  padding-bottom: 1rem;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: #2a90d9;
}

@media (max-width: 768px) {
  .hero-section {
    height: 60vh;
  }

  .image-container img {
    height: 300px;
  }
}

@media (max-width: 480px) {
  .hero-section {
    height: 50vh;
  }

  .image-container img {
    height: 250px;
  }
}
</style>
