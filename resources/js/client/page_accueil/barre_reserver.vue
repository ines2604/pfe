<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-md-12 col-sm-12">
        <div class="card shadow custom-card">
          <div class="card-body p-4">
            <div class="booking-form">
              <div class="form-group booking-item">
                <label for="destination" class="form-label">
                  <i class="fas fa-map-marker-alt"></i>
                  Destination
                </label>
                <select id="destination" class="form-select custom-select" v-model="selectedDestination">
                  <option disabled value="">Choisir votre Destination</option>
                  <option v-for="hotel in hotels" :key="hotel.id" :value="hotel.id">
                    {{ hotel.nom }}
                  </option>
                </select>
              </div>

              <div class="form-group booking-item">
                <label for="dateRange" class="form-label">
                  <i class="fas fa-calendar-alt"></i>
                  Dates de séjour
                </label>
                <div class="input-container">
                  <input
                    type="text"
                    id="dateRange"
                    class="form-control custom-input"
                    placeholder="Choisir les dates"
                    ref="dateRangeInput"
                    readonly
                  />
                </div>
              </div>

              <div class="form-group booking-item">
                <button class="btn btn-primary btn-book" @click="reserver">
                  <i class="fas fa-search"></i>
                  rechercher
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { French } from "flatpickr/dist/l10n/fr";
import axios from 'axios';

export default {
  name: 'ReservationForm',
  data() {
    return {
      hotels: [],
      selectedDestination: '',
      dateRange: null,
    };
  },
  async mounted() {

    await axios.get('api/hotels')
      .then(response => {
        this.hotels = response.data;
      })
      .catch(error => {
        console.error('il y a eu une erreur lors de la récupération des hôtels :' , error);
      });

    const dateRangeInput = this.$refs.dateRangeInput;

    const flatpickrInstance = flatpickr(dateRangeInput, {
      mode: "range",
      dateFormat: "d/m/Y",
      locale: French,
      minDate: "today",
      showMonths: 2,
      onClose: (selectedDates, dateStr) => {
        if (selectedDates.length === 2) {
          this.dateRange = dateStr;
        }
      },
    });
  },
  methods: {
    reserver() {
      if (this.selectedDestination && this.dateRange) {
        this.$router.push({
          path: '/reservation',
          query: {
            destination: this.selectedDestination,
            dates: this.dateRange,
          },
        });
      } else {
        const elements = [];
        if (!this.selectedDestination) {
          elements.push('une destination');
        }
        if (!this.dateRange) {
          elements.push('une plage de dates');
        }
        const message = `Veuillez sélectionner ${elements.join(' et ')}.`;
        this.showError(message);
      }
    },
    showError(message) {
      alert(message);
    }
  },
};
</script>

<style scoped>
.custom-card {
  border-radius: 15px;
  transform: translateY(-100px);
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(20px);
  border: none;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.custom-card:hover {
  transform: translateY(-105px);
  box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
}

.booking-form {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
}

.booking-item {
  flex: 1;
  min-width: 280px;
}

.form-label {
  font-weight: 500;
  color: #2a90d9;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.1rem;
  transition: all 0.3s ease;
}

.form-label i {
  color: #2a90d9;
  font-size: 1.2rem;
}

.custom-select,
.custom-input {
  height: 55px;
  border-radius: 12px;
  border: 2px solid #e9ecef;
  padding: 0 20px;
  font-size: 1.05rem;
  transition: all 0.3s ease;
  background-color: white;
  color: #333;
  width: 100%;
}

.custom-select:hover,
.custom-input:hover {
  border-color: #2a90d9;
  background-color: #f8f9fa;
}

.custom-select:focus,
.custom-input:focus {
  border-color: #2a90d9;
  box-shadow: 0 0 0 3px rgba(42, 144, 217, 0.1);
  outline: none;
}

.input-container {
  position: relative;
  width: 100%;
}

.btn-book {
  width: 100%;
  height: 55px;
  border-radius: 12px;
  background: linear-gradient(45deg, #2a90d9, #1e6ca8);
  border: none;
  font-weight: 600;
  font-size: 1.1rem;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  color: white;
  text-transform: uppercase;
  padding: 0 30px;
}

.btn-book:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(42, 144, 217, 0.3);
  background: linear-gradient(45deg, #1e6ca8, #155785);
}

.btn-book:active {
  transform: translateY(0);
}

.btn-book i {
  font-size: 1.2rem;
  transition: transform 0.3s ease;
}

.btn-book:hover i {
  transform: scale(1.1);
}

/* Animation pour le focus des champs */
@keyframes focusAnimation {
  0% { transform: scale(1); }
  50% { transform: scale(1.02); }
  100% { transform: scale(1); }
}

.custom-select:focus,
.custom-input:focus {
  animation: focusAnimation 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
  .custom-card {
    transform: translateY(-50px);
  }

  .custom-card:hover {
    transform: translateY(-55px);
  }

  .booking-form {
    gap: 20px;
  }

  .booking-item {
    min-width: 100%;
  }

  .form-label {
    font-size: 1rem;
  }

  .custom-select,
  .custom-input {
    height: 50px;
    font-size: 1rem;
  }

  .btn-book {
    height: 50px;
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .custom-card {
    transform: translateY(-30px);
  }

  .custom-card:hover {
    transform: translateY(-35px);
  }

  .booking-form {
    gap: 15px;
    padding: 5px;
  }
}
</style>
