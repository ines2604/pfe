<template>
  <div class="carousel-container">
    <div 
      id="mainCarousel" 
      class="carousel slide" 
      data-bs-ride="carousel"
      @mouseover="pauseCarousel"
      @mouseleave="resumeCarousel"
    >
      <div class="carousel-inner">
        <div 
          v-for="(slide, index) in slides" 
          :key="index"
          :class="['carousel-item', { active: index === activeSlide }]"
        >
          <img :src="slide.image" :alt="slide.title" class="carousel-image">
          <div class="carousel-overlay"></div>
          <div class="carousel-caption">
            <h2 :class="{ 'animate': index === activeSlide }">{{ slide.subtitle }}</h2>
            <h1 :class="{ 'animate': index === activeSlide }">{{ slide.title }}</h1>
            <p :class="{ 'animate': index === activeSlide }">{{ slide.description }}</p>
            <router-link to="/home/aboutus">
              <button 
                class="explore-btn" 
                :class="{ 'animate': index === activeSlide }"
                @click="exploreMore"
              >
                Découvrir Plus
              </button>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Contrôles de navigation -->
      <button 
        class="carousel-control-prev custom-control" 
        @click="prevSlide"
      >
        <i class="bi bi-chevron-left"></i>
      </button>
      <button 
        class="carousel-control-next custom-control" 
        @click="nextSlide"
      >
        <i class="bi bi-chevron-right"></i>
      </button>

      <!-- Indicateurs personnalisés -->
      <div class="carousel-indicators custom-indicators">
        <button 
          v-for="(slide, index) in slides" 
          :key="index"
          :class="['custom-indicator', { active: index === activeSlide }]"
          @click="goToSlide(index)"
        >
          <span class="indicator-content"></span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeSlide: 0,
      autoplayInterval: null,
      slides: [
        {
          image: 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80',
          title: 'Votre séjour, notre priorité !',
          subtitle: 'Alliance Hospitality',
          description: 'Découvrez le luxe et le confort dans notre établissement d\'exception'
        },
        {
          image: 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
          title: 'Une expérience unique',
          subtitle: 'Chambres & Suites',
          description: 'Des espaces élégants conçus pour votre bien-être'
        },
        {
          image: 'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80',
          title: 'Détente & Bien-être',
          subtitle: 'Nos Services',
          description: 'Profitez de nos installations haut de gamme'
        }
      ]
    }
  },
  mounted() {
    this.startAutoplay()
  },
  beforeDestroy() {
    this.stopAutoplay()
  },
  methods: {
    startAutoplay() {
      this.autoplayInterval = setInterval(this.nextSlide, 2000)
    },
    stopAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval)
      }
    },
    pauseCarousel() {
      this.stopAutoplay()
    },
    resumeCarousel() {
      this.startAutoplay()
    },
    nextSlide() {
      this.activeSlide = (this.activeSlide + 1) % this.slides.length
    },
    prevSlide() {
      this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length
    },
    goToSlide(index) {
      this.activeSlide = index
    },
    exploreMore() {
      console.log('Exploring more...')
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap');

.carousel-container {
  position: relative;
  width: 100%;
  overflow: hidden;
}

.carousel {
  position: relative;
  margin-bottom: 0;
}

.carousel-image {
  width: 100%;      
  height: 90vh;    
  object-fit: cover;
  transform: scale(1.1);
  transition: transform 0.7s ease-in-out;
}

.carousel-item.active .carousel-image {
  transform: scale(1);
}

.carousel-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    rgba(0, 0, 0, 0.2),
    rgba(0, 0, 0, 0.4)
  );
  opacity: 0;
  transition: opacity 0.5s ease;
}

.carousel-item.active .carousel-overlay {
  opacity: 1;
}

.carousel-caption {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  width: 90%;
  max-width: 1000px;
  padding: 0;
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.carousel-caption h1 {
  font-family: 'Playfair Display', serif;
  font-size: 3.5rem;
  font-weight: 600;
  margin: 0.5rem 0;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.7s ease;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
}

.carousel-caption h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.8rem;
  font-weight: 400;
  margin: 0;
  opacity: 0;
  transform: translateY(-20px);
  transition: all 0.7s ease;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
}

.carousel-caption p {
  font-family: 'Poppins', sans-serif;
  font-size: 1.2rem;
  margin: 1rem 0;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.7s ease;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
}

.carousel-caption .animate {
  opacity: 1;
  transform: translateY(0);
}

.explore-btn {
  background: transparent;
  border: 2px solid white;
  color: white;
  padding: 0.8rem 2rem;
  font-size: 1.1rem;
  font-family: 'Poppins', sans-serif;
  cursor: pointer;
  transition: all 0.3s ease;
  opacity: 0;
  transform: translateY(20px);
}

.explore-btn.animate {
  opacity: 1;
  transform: translateY(0);
}

.explore-btn:hover {
  background: white;
  color: #000;
}

.explore-btn i {
  margin-left: 0.5rem;
  transition: transform 0.3s ease;
}

.explore-btn:hover i {
  transform: translateX(5px);
}

.custom-control {
  width: 5%;
  background: transparent;
  border: none;
  opacity: 0;
  transition: all 0.3s ease;
}

.custom-control i {
  font-size: 2rem;
  color: white;
}

.carousel:hover .custom-control {
  opacity: 0.8;
}

.custom-control:hover {
  opacity: 1 !important;
}

.custom-indicators {
  margin-bottom: 2rem;
  gap: 1rem;
}

.custom-indicator {
  width: 3rem;
  height: 3px;
  border: none;
  padding: 0;
  margin: 0 0.25rem;
  background: transparent;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.indicator-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  transform: scaleX(0.3);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.custom-indicator.active .indicator-content {
  background: white;
  transform: scaleX(1);
}

@media (max-width: 768px) {
  .carousel-caption h1 {
    font-size: 2.5rem;
  }
  .carousel-caption h2 {
    font-size: 1.4rem;
  }
  .carousel-caption p {
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .carousel-caption h1 {
    font-size: 2rem;
  }
  .carousel-caption h2 {
    font-size: 1.2rem;
  }
  .carousel-caption p {
    font-size: 0.9rem;
  }
}
</style>