<template>
  <Navbar id="navbar"/>
  <div class="contact-section" style="margin-top: 80px">
    <div class="container-fluid">
      <div class="section-header">
        <h2 class="section-title">Contactez-nous</h2>
      </div>
      <div class="contact-container">
        <div class="contact-form-container" data-aos="fade-right">
          <form class="contact-form" @submit.prevent="submitForm">
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">Nom complet</label>
                <div class="input-group">
                  <span class="input-icon">
                    <i class="fas fa-user"></i>
                  </span>
                  <input 
                    type="text" 
                    class="form-input" 
                    v-model="form.nom"
                    :class="{'error-border': errors.nom}"
                    aria-label="Nom"
                    aria-describedby="nom-error"
                    placeholder="Nom"
                  >
                </div>
                <p class="error-message" v-if="errors.nom" id="nom-error" aria-live="assertive">{{ errors.nom }}</p>
              </div>
              <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-icon">
                    <i class="fas fa-envelope"></i>
                  </span>
                  <input 
                    type="email" 
                    class="form-input" 
                    v-model="form.email"
                    placeholder="Adresse e-mail"
                    :class="{'error-border': errors.email}"
                    aria-label="Adresse e-mail"
                    aria-describedby="email-error"
                  >
                </div>
                <p class="error-message" v-if="errors.email" id="email-error" aria-live="assertive">{{ errors.email }}</p>
              </div>
              <div class="form-group">
                <label class="form-label">Téléphone</label>
                <div class="input-group">
                  <span class="input-icon">
                    <i class="fas fa-phone"></i>
                  </span>
                  <input 
                    type="tel" 
                    class="form-input" 
                    v-model="form.tel"
                    placeholder="Numéro de téléphone"
                    :class="{'error-border': errors.tel}"
                    aria-label="Numéro de téléphone"
                    aria-describedby="tel-error"
                  >
                </div>
                <p class="error-message" v-if="errors.tel" id="tel-error" aria-live="assertive">{{ errors.tel }}</p>
              </div>
              <div class="form-group">
                <label class="form-label">Sujet</label>
                <div class="input-group">
                  <span class="input-icon">
                    <i class="fas fa-tag"></i>
                  </span>
                  <select 
                    class="form-input" 
                    v-model="form.sujet"
                    :class="{'error-border': errors.sujet}"
                    aria-label="Sujet"
                    aria-describedby="sujet-error"
                  >
                    <option value="">Sélectionnez un sujet</option>
                    <option value="reservation">Réservation</option>
                    <option value="information">Information</option>
                    <option value="reclamation">Réclamation</option>
                    <option value="autre">Autre</option>
                  </select>
                </div>
                <p class="error-message" v-if="errors.sujet" id="sujet-error" aria-live="assertive">{{ errors.sujet }}</p>
              </div>
              <div class="form-group full-width">
                <label class="form-label">Message</label>
                <div class="input-group">
                  <span class="input-icon">
                    <i class="fas fa-comment"></i>
                  </span>
                  <textarea 
                    class="form-input" 
                    v-model="form.message"
                    rows="4"
                    placeholder="Votre message..."
                    :class="{'error-border': errors.message}"
                    aria-label="message"
                    aria-describedby="message-error"
                  ></textarea>
                </div>
                <p class="error-message" v-if="errors.message" id="message-error" aria-live="assertive">{{ errors.message }}</p>
              </div>

              <div class="form-group full-width">
                <button 
                  type="submit" 
                  class="submit-button"
                  :class="{ 'is-loading': isSubmitting }"
                  :disabled="isSubmitting"
                >
                  <span v-if="!isSubmitting">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer le message
                  </span>
                  <span v-else>
                    <i class="fas fa-spinner fa-spin"></i>
                    Envoi en cours...
                  </span>
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="info-map-container" data-aos="fade-left">
          <div class="map-container">
            <h3 class="map-title">Notre localisation</h3>
            <div class="map-wrapper">
              <iframe 
                :src="`https://maps.google.com/maps?q=${encodeURIComponent(hotel.adresse)}&output=embed`" 
                width="100%" 
                height="250" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
              <a 
                :href="`https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(hotel.adresse)}`"  
                target="_blank" 
                class="directions-link"
              >
                <i class="fas fa-directions"></i>
                Obtenir l'itinéraire
              </a>
            </div>
          </div>

          <div class="contact-info">
            <div class="info-grid">
              <div class="info-card">
                <div class="info-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                  <h3>Notre adresse</h3>
                  <p>{{ hotel.adresse }}</p>
                </div>
              </div>
              <div class="info-card">
                <div class="info-icon">
                  <i class="fas fa-phone-alt"></i>
                </div>
                <div class="info-content">
                  <h3>Téléphone</h3>
                  <p>
                    <a class="contact-link" :href="'tel:' + hotel.telephone">{{ hotel.telephone }}</a><br />
                    <span class="availability">Lun-Ven, 9h-18h</span>
                  </p>
                </div>
              </div>
              <div class="info-card">
                <div class="info-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                  <h3>Email</h3>
                  <p>
                    <a :href="'mailto:' + hotel.email" class="contact-link">{{ hotel.email }}</a><br />
                    <span class="availability">Réponse sous 24h</span>
                  </p>
                </div>
              </div>
              <div class="social-links">
                <a :href="hotel.facebook" class="social-link" title="Facebook" target="_blank">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a :href="hotel.instagram" class="social-link" title="Instagram" target="_blank">
                  <i class="fab fa-instagram"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script>
import Swal from 'sweetalert2';
import AOS from 'aos';
import 'aos/dist/aos.css';
import axios from 'axios';
import Navbar from './navbar.vue';
import Footer from './footer.vue';

export default {
  components: {
    Navbar,
    Footer
  },
  data() {
    return {
      hotel:[],
      form: {
        hotel_nom: '',
        nom: '',
        email: '',
        tel: '',
        sujet: '',
        message: ''
      },
      isSubmitting: false,
      errors: {}
    };
  },
  async mounted() {
    this.form.hotel_nom = this.$route.params.hotel_nom;
    try {
      const response = await axios.get(`/api/${this.form.hotel_nom}/contactus`);
      this.hotel = response.data;
    } catch (error) {
      console.error("Erreur lors de la récupération des contacts :", error);
    }
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      offset: 50
    });
  },
  methods: {
    validateForm() {
      this.errors = {};
      let isValid = true;

      if (!this.form.nom) {
        this.errors.nom = "Le nom est obligatoire.";
        isValid = false;
      }
      if (!this.form.email) {
        this.errors.email = "L'adresse e-mail est obligatoire.";
        isValid = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
        this.errors.email = "L'adresse e-mail est invalide.";
        isValid = false;
      }
      if (!this.form.tel) {
        this.errors.tel = "Le numéro de téléphone est obligatoire.";
        isValid = false;
      }
      if (!this.form.sujet) {
        this.errors.sujet = "Le sujet est obligatoire.";
        isValid = false;
      }
      if (!this.form.message) {
        this.errors.message = "Le message est obligatoire.";
        isValid = false;
      }

      return isValid;
    },
    async submitForm() {
      if (!this.validateForm()) {
        return;
      }

      this.isSubmitting = true;

      try {
        const response = await axios.post('/api/demande_speciale', this.form);

        this.form = {
          hotel_nom: this.$route.params.hotel_nom,
          nom: '',
          email: '',
          tel: '',
          sujet: '',
          message: ''
        };

        await Swal.fire({
          icon: 'success',
          title: 'Succès !',
          text: 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
          confirmButtonColor: '#007bff'
        });
      } catch (error) {
        console.error('Erreur lors de l\'envoi du formulaire :', error);

        await Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: error.response?.data?.message || 'Une erreur est survenue. Veuillez réessayer.',
          confirmButtonColor: '#dc3545'
        });
      } finally {
        this.isSubmitting = false;
      }
    }
  }
};
</script>

<style scoped>

#navbar{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}
.contact-section {
  background: white;
  margin: 0 auto;
  padding: 2rem ;
  min-height: 100vh;
  display: flex;
  align-items: center;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.section-header {
  text-align: center;
  margin-bottom: 2rem;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 1rem;
}

.title-underline {
  width: 80px;
  height: 4px;
  background: linear-gradient(45deg, #007bff, #00bcd4);
  margin: 0 auto 1.5rem;
  border-radius: 2px;
}

.section-description {
  font-size: 1.1rem;
  color: #6c757d;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.8;
}

.contact-container {
  display: grid;
  grid-template-columns: minmax(300px, 500px) 1fr;
  gap: 4rem;
  align-items: start;
}

.contact-form-container {
  background: white;
  padding: 2.5rem;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.form-group {
  position: relative;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-label {
  display: block;
  font-weight: 500;
  color: #2c3e50;
  margin-bottom: 0.75rem;
}

.input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 1rem;
  color: #007bff;
  z-index: 1;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.form-input:focus {
  border-color: #007bff;
  background: white;
  outline: none;
  box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
}

textarea.form-input {
  min-height: 100px;
  resize: vertical;
}

.submit-button {
  width: 100%;
  padding: 1rem;
  border: none;
  border-radius: 10px;
  background: linear-gradient(45deg, #007bff, #00bcd4);
  color: white;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.submit-button:not(:disabled):hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.submit-button.is-loading {
  background: #6c757d;
  cursor: not-allowed;
}

.info-map-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.contact-info {
  background: white;
  padding: 1.5rem;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.info-card:last-child {
  border-bottom: none;
  padding-bottom: 0;
  margin-bottom: 0.5rem;
}

.info-icon {
  width: 35px;
  height: 35px;
  background: linear-gradient(45deg, rgba(0, 123, 255, 0.1), rgba(0, 188, 212, 0.1));
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  color: #007bff;
  font-size: 1rem;
}

.info-content h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 0.25rem;
}

.info-content p {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
}

.social-links {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
}

.info-grid {
  display: flex; 
  gap: 1.5rem; 
  flex-wrap: wrap; 
}

.info-card {
  flex: 1; 
  min-width: 200px; 
  background: white;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.social-links {
  flex: 1; 
  display: flex;
  gap: 0.75rem;
  justify-content: center;
  align-items: center;
  background: white;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.map-container {
  background: white;
  padding: 1.5rem;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.map-title {
  font-size: 1rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 0.75rem;
}

.map-wrapper iframe {
  height: 200px;
  border-radius: 8px;
}

.directions-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.75rem;
  padding: 0.4rem 0.75rem;
  background: linear-gradient(45deg, #007bff, #00bcd4);
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.85rem;
}

@media (max-width: 992px) {
  .contact-container {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .contact-form-container {
    order: 1;
  }

  .info-map-container {
    order: 2;
  }
}

@media (max-width: 768px) {
  .contact-section {
    padding: 4rem 0;
  }

  .section-title {
    font-size: 2rem;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-group.full-width {
    grid-column: span 1;
  }

  .contact-form-container,
  .contact-info,
  .map-container {
    padding: 1.5rem;
  }

  .map-wrapper iframe {
    height: 200px;
  }
}

@media (max-width: 480px) {
  .section-title {
    font-size: 1.75rem;
  }

  .info-grid {
    flex-direction: column; 
  }

  .info-card,
  .social-links {
    flex: none; 
    width: 100%; 
  }
}

.error-border {
    border-color: red !important;
}

.error-message {
    color: #dc3545;
    font-size: 0.9rem;
    margin-top: 5px;
    display: flex;
    align-items: center;
    animation: fadeIn 0.3s ease;
}

.error-message::before {
    content: '⚠️';
    margin-right: 5px;
}
</style>
