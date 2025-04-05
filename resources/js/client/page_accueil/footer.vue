<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-header">
        <a href="/home" class="logo">
          <span class="logo-text">Alliance Hospitality</span>
        </a>

        <nav class="footer-nav">
          <ul>
            <li v-for="(page, i) in pages" :key="i">
              <a :href="page.link" class="nav-link">
                <span class="link-text">{{ page.name }}</span>
              </a>
            </li>
          </ul>
        </nav>
        
        <div class="social-header">
          <span class="follow-text">
            SUIVEZ-NOUS <span class="follow-icon">→</span>
          </span>
          <div class="social-icons">
            <a v-for="(icon, index) in socialIcons" :key="index" :href="icon.link" target="_blank" class="social-link">
              <i :class="icon.class"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="copyright">
        Site officiel - Tous droits réservés - Alliance Hospitality © 2025<br>
        Conception & réalisation: <span class="creator">Elite Council Consulting : sirine&ines</span>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      pages: [
        { name: 'Nos hôtels', link: '/home/listeHotels' },
        { name: 'Nos chambres', link: '/home/listeChambres' },
        { name: 'Nos services', link: '/home/listeServices' },
        { name: 'Nos offres', link: '/home/listeOffres' },
        { name: 'À propos', link: '/home/aboutus' }
      ],
      socialIcons: [
        { class: 'bi bi-facebook', link: '' },
        { class: 'bi bi-instagram', link: '' },
      ]
    };
  },
  async mounted() {
    try {
      const response = await axios.get('/api/chaine_hoteliere');
      this.socialIcons[0].link = response.data.facebook;
      this.socialIcons[1].link = response.data.instagram;
    } catch (error) {
      console.error('Erreur lors de la récupération des informations de la chaîne hôtelière: ', error);
    }
  }
};
</script>

<style scoped>
.footer {
  background-color: #f5f7fa; 
  color: #333; 
  padding: 3rem 0;
  font-family: 'Montserrat', sans-serif;
  border-top: 1px solid #e1e5eb; 
}

.container {
  margin: 0 auto;
  padding: 0 20px;
}

.footer-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e1e5eb; 
}

.logo {
  text-decoration: none;
}

.logo-text {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem;
  font-weight: 600;
  color: #2A90D9; 
  letter-spacing: 1px;
}

.social-header {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.follow-text {
  font-size: 0.9rem;
  font-weight: 600;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: #6c757d; 
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.follow-icon {
  font-size: 1.2rem;
  color: #2A90D9; 
}

.social-icons {
  display: flex;
  gap: 1rem;
}

.social-link {
  color: #6c757d;
  font-size: 1.2rem;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(42, 144, 217, 0.1); 
  transition: all 0.3s ease;
}

.social-link:hover {
  background: #2A90D9;
  color: white;
  transform: translateY(-3px);
}

.footer-nav ul {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1.5rem;
  list-style: none;
  padding: 0;
  margin: 0 0 1rem 0;
}

.nav-link {
  color: #495057; 
  text-decoration: none;
  font-weight: 500;
  position: relative;
  padding: 0.5rem 0;
  transition: color 0.3s ease;
}

.link-text {
  position: relative;
}

.nav-link:hover {
  color: #2A90D9; 
}

.nav-link:hover .link-text::after {
  width: 100%;
}

.link-text::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 2px;
  background: #2A90D9;
  transition: width 0.3s ease;
}

.copyright {
  text-align: center;
  font-size: 0.85rem;
  color: #6c757d; 
  line-height: 1.6;
}

.creator {
  color: #2A90D9;
  font-weight: 500;
}

@media (max-width: 768px) {
  .footer-header {
    flex-direction: column;
    gap: 1.5rem;
    text-align: center;
  }
  
  .footer-nav ul {
    gap: 1rem;
  }
  
  .nav-link {
    font-size: 0.9rem;
  }
}
</style>