<template>
  <div class="services-section">
    <h2 class="text-center mb-4">Nos Services</h2>

    <div class="services-grid top-services">
      <div v-for="(service, index) in informations.services" :key="index" class="service-card-wrapper">
        <div class="service-card">
          <div class="card-content">
            <div class="icon-wrapper">
              <i :class="getIcon(service.titre)" class="fas"></i>
            </div>
            <h3 class="service-title">{{ service.titre }}</h3>
            <p class="service-description">{{ service.description }}</p>
            <!--<div class="card-overlay">
              <router-link to="/home#service" class="learn-more">En savoir plus</router-link>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Services',
  data() {
    return {
      informations: [],
    };
  },
  async mounted(){
    try {
      const response = await axios.get('api/chaine_hoteliere');
      this.informations = response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des informations de la chaîne hôtelière: ', error);
    }
  },
  methods: {
    getIcon(titre) {
      const normalizedTitle = titre.toLowerCase().replace(/\s+/g, '');
      
      const iconMap = {
        'petit-déjeuner': 'fas fa-coffee',
        'restaurantetroomservice': 'fas fa-utensils',
        'restaurant': 'fas fa-utensils',
        'baretcafé': 'fas fa-cocktail',
        'bar': 'fas fa-cocktail',
        'café': 'fas fa-coffee',
        'piscineetspa': 'fas fa-swimmer',
        'piscine': 'fas fa-swimmer',
        'salledesport': 'fas fa-dumbbell',
        'parking': 'fas fa-parking',
        'servicesfamiliaux': 'fas fa-baby',
        'espacesderéunion': 'fas fa-users',
        'servicespmr': 'fas fa-wheelchair',
        'piscine&spa': 'fas fa-swimmer',
        'plageprivative' : 'fas fa-swimmer'
      };

      return iconMap[normalizedTitle] || 'fas fa-cogs';
    },
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap');

.services-section {
  background: linear-gradient(135deg, #ffffff, #f8f9fa);
  padding: 6rem 0;
}
.services-grid {
  display: grid;
  gap: 35px;
  max-width: 1300px;
  margin: 0 auto;
  padding: 0 2rem;
}

.top-services {
  grid-template-columns: repeat(4, 1fr);
  margin-bottom: 35px;
}

.bottom-services {
  grid-template-columns: repeat(3, 1fr);
}

.service-card {
  display: block;
  text-decoration: none;
  color: inherit;
  height: 100%;
  transition: all 0.3s ease;
}

.card-content {
  background: white;
  border-radius: 25px;
  padding: 2.5rem 2rem;
  height: 100%;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  border: 1px solid rgba(42, 144, 217, 0.1);
}

.service-card:hover .card-content {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(42, 144, 217, 0.15);
}

.icon-wrapper {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #2a90d9, #1e6ca8);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  transition: all 0.3s ease;
}

.service-card:hover .icon-wrapper {
  transform: scale(1.1) rotate(-5deg);
}

.icon-wrapper i {
  font-size: 1.8rem;
  color: white;
  transition: all 0.3s ease;
}

.service-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.4rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 1rem;
  transition: all 0.3s ease;
}

.service-description {
  font-family: 'Poppins', sans-serif;
  font-size: 1rem;
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

/*.card-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 1.5rem;
  background: linear-gradient(to top, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0));
  opacity: 0;
  transform: translateY(100%);
  transition: all 0.3s ease;
}*/

.service-card:hover .card-overlay {
  opacity: 1;
  transform: translateY(0);
}

/*.learn-more {
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  color: #2a90d9;
  display: flex;
  align-items: center;
  gap: 8px;
}

.learn-more::after {
  content: '→';
  transition: transform 0.3s ease;
}*/

.service-card:hover .learn-more::after {
  transform: translateX(5px);
}

@media (max-width: 1200px) {
  .top-services {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .bottom-services {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .services-section {
    padding: 4rem 0;
  }

  .section-title {
    font-size: 2.3rem;
  }

  .section-description {
    font-size: 1.1rem;
  }

  .top-services,
  .bottom-services {
    grid-template-columns: 1fr;
    gap: 25px;
  }

  .card-content {
    padding: 2rem 1.5rem;
  }

  .icon-wrapper {
    width: 60px;
    height: 60px;
  }

  .service-title {
    font-size: 1.3rem;
  }
}

@media (max-width: 480px) {
  .section-title {
    font-size: 2rem;
  }

  .btn-discover {
    padding: 12px 25px;
    font-size: 1rem;
  }
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
</style>