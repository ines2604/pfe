<template>
    <div id="register" role="main">
        <div class="container" role="region" aria-label="Formulaire d'inscription">
            <div class="register-card" role="form">
            <h1 class="text-center">Inscription</h1>
            <form @submit.prevent="register" ref="form">
                <!-- Nom et Prénom -->
                <div class="form-row">
                <div class="form-group">
                    <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input
                        type="text"
                        class="form-control"
                        id="nom"
                        v-model="nom"
                        placeholder="Nom"
                        :class="{'error-border': errors.nom}"
                        aria-label="Nom"
                        @keyup.enter="register"
                    >
                    </div>
                    <p class="error-message" v-if="errors.nom" aria-live="assertive">{{ errors.nom }}</p>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input
                        type="text"
                        class="form-control"
                        id="prenom"
                        v-model="prenom"
                        placeholder="Prénom"
                        :class="{'error-border': errors.prenom}"
                        aria-label="Prenom"
                        @keyup.enter="register"
                    >
                    </div>
                    <p class="error-message" v-if="errors.prenom" aria-live="assertive">{{ errors.prenom }}</p>
                </div>
                </div>
    
                <!-- Email et Date de naissance -->
                <div class="form-row">
                <div class="form-group">
                    <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        v-model="email"
                        placeholder="Adresse e-mail"
                        :class="{'error-border': errors.email}"
                        aria-label="Adresse e-mail"
                        @keyup.enter="register"
                    >
                    </div>
                    <p class="error-message" v-if="errors.email" aria-live="assertive">{{ errors.email }}</p>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                    <i class="fas fa-calendar"></i>
                    <input
                        type="date"
                        class="form-control"
                        id="naissance"
                        v-model="naissance"
                        :class="{'error-border': errors.naissance}"
                        aria-label="Date de naissance"
                        @keyup.enter="register"
                    >
                    </div>
                    <p class="error-message" v-if="errors.naissance" aria-live="assertive">{{ errors.naissance }}</p>
                </div>
                </div>
    
                <!-- Pays et Téléphone -->
                <div class="form-row">
                <div class="form-group">
                    <div class="input-with-icon">
                    <img v-if="selectedCountry" :src="selectedCountry.flagUrl" alt="Drapeau" class="country-flag">
                    <i v-else class="fas fa-globe"></i>
                    <select
                        class="form-control"
                        id="pays"
                        v-model="selectedCountry"
                        :class="{'error-border': errors.pays}"
                        aria-label="Pays"
                        @keyup.enter="register"
                        @change="updateCountryCode"
                    >
                        <option value="" disabled selected>Choisissez un pays</option>
                        <option v-for="country in countries" :key="country.cca2" :value="country">
                        {{ country.flag }} {{ country.name.common }}
                        </option>
                    </select>
                    </div>
                    <p class="error-message" v-if="errors.pays" aria-live="assertive">{{ errors.pays }}</p>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                            <div v-if="selectedCountry" class="country-code-display">
                                <img :src="selectedCountry.flagUrl" alt="Drapeau" class="country-flag">
                                    {{ selectedCountry.idd.root }}{{ selectedCountry.idd.suffixes?.[0] || '' }}
                            </div>
                            <i  v-else class="fas fa-phone"></i>
                        <input
                            type="tel"
                            class="form-control"
                            id="tel"
                            v-model="tel"
                            placeholder="Numéro de téléphone"
                            :class="{'error-border': errors.tel}"
                            aria-label="Numéro de téléphone"
                            @keyup.enter="register"
                        >
                        </div>
                        <p class="error-message" v-if="errors.tel" aria-live="assertive">{{ errors.tel }}</p>
                    </div>
                </div>
    
                <!-- Mot de passe et Confirmation -->
                <div class="form-row">
                <div class="form-group position-relative">
                    <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input
                        :type="passwordVisible ? 'text' : 'password'"
                        class="form-control"
                        id="password"
                        v-model="password"
                        placeholder="Mot de passe"
                        :class="{'error-border': errors.password}"
                        aria-label="mot de passe"
                        @keyup.enter="register"
                    >
                    <button type="button" class="password-toggle" @click="togglePasswordVisibility('password')" aria-label="Afficher ou masquer le mot de passe">
                        <i class="fas" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                    </div>
                    <small class="form-text text-muted">Minimum de 8 caractères</small>
                    <p class="error-message" v-if="errors.password" aria-live="assertive">{{ errors.password }}</p>
                </div>
                <div class="form-group position-relative">
                    <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input
                        :type="confirmPasswordVisible ? 'text' : 'password'"
                        class="form-control"
                        id="confirmPassword"
                        v-model="confirmPassword"
                        placeholder="Confirmer le mot de passe"
                        :class="{'error-border': errors.confirmPassword}"
                        aria-label="confirmer le mot de passe"
                        @keyup.enter="register"
                    >
                    <button type="button" class="password-toggle" @click="togglePasswordVisibility('confirmPassword')" aria-label="Afficher ou masquer le mot de passe">
                        <i class="fas" :class="confirmPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                    </div>
                    <p class="error-message" v-if="errors.confirmPassword" aria-live="assertive">{{ errors.confirmPassword }}</p>
                </div>
                </div>
    
                <!-- Bouton d'inscription -->
                <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                    <span v-if="loading">
                    <i class="fas fa-spinner fa-spin"></i> Inscription en cours...
                    </span>
                    <span v-else>S'inscrire</span>
                </button>
                <div class="mt-2">
                    Vous avez déjà un compte ? <router-link to="/login" class="link">Se connecter</router-link>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import countries from 'world-countries';

export default {
    name:'inscription',
    metaInfo :{
        title: 'Inscription - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Inscrivez-vous pour accéder à vos services personnalisés chez Alliance Hospitality.' },
            { name: 'keywords', content: 'inscription, compte, Alliance Hospitality, services' },
            { name: 'author', content: 'Alliance Hospitality' }
        ]
    },
    data() {
        return {
            nom: '',
            prenom: '',
            email: '',
            tel: '',
            naissance: '',
            password: '',
            confirmPassword: '',
            passwordVisible: false,
            confirmPasswordVisible: false,
            selectedCountry: '',
            countries: countries.map(country => ({
                ...country,
                flag: this.getFlagEmoji(country.cca2),
                flagUrl: `https://flagcdn.com/w40/${country.cca2.toLowerCase()}.png`
            })),
            errors: {},
            loading: false
        };
    },
    methods: {
        togglePasswordVisibility(field) {
            if (field === 'password') {
                this.passwordVisible = !this.passwordVisible;
            } else {
                this.confirmPasswordVisible = !this.confirmPasswordVisible;
            }
        },
        getFlagEmoji(countryCode) {
            return String.fromCodePoint(...Array.from(countryCode.toUpperCase()).map(char => 127397 + char.charCodeAt()));
        },
        updateCountryCode() {
            this.tel = '';
        },
        validateForm() {
            this.errors = {};

            // Validation du nom
            if (!this.nom) {
                this.errors.nom = "Le nom est obligatoire.";
            } else if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(this.nom)) {
                this.errors.nom = "Le nom ne doit contenir que des lettres, des espaces, des tirets et des caractères accentués.";
            } else if (this.nom.length < 2) {
                this.errors.nom = "Le nom doit contenir au moins 2 caractères.";
            }

            // Validation du prénom
            if (!this.prenom) {
                this.errors.prenom = "Le prénom est obligatoire.";
            } else if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(this.prenom)) {
                this.errors.prenom = "Le prénom ne doit contenir que des lettres, des espaces, des tirets et des caractères accentués.";
            } else if (this.prenom.length < 2) {
                this.errors.prenom = "Le prénom doit contenir au moins 2 caractères.";
            }

            // Validation de l'email
            if (!this.email) {
                this.errors.email = "L'adresse e-mail est obligatoire.";
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = "L'adresse e-mail est invalide.";
            }

            // Validation du téléphone
            if (!this.tel) {
                this.errors.tel = "Le numéro de téléphone est obligatoire.";
            } else if (!/^[0-9+\-\s]+$/.test(this.tel)) {
                this.errors.tel = "Le numéro de téléphone ne doit contenir que des chiffres, des espaces, des tirets et des signes +.";
            }

            // Validation de la date de naissance
            if (!this.naissance) {
                this.errors.naissance = "La date de naissance est obligatoire.";
            } else {
                const birthDate = new Date(this.naissance);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                if (age < 18) {
                    this.errors.naissance = "Vous devez avoir au moins 18 ans pour vous inscrire.";
                }
            }

            // Validation du mot de passe
            if (!this.password) {
                this.errors.password = "Le mot de passe est obligatoire.";
            } else if (this.password.length < 8) {
                this.errors.password = "Le mot de passe doit contenir au moins 8 caractères.";
            } else if (
                !/[a-z]/.test(this.password) || 
                !/[A-Z]/.test(this.password) || 
                !/[0-9]/.test(this.password) ||
                !/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.password)   
            ) {
                this.errors.password = "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.";
            }

            // Validation de la confirmation du mot de passe
            if (!this.confirmPassword) {
                this.errors.confirmPassword = "La confirmation du mot de passe est obligatoire.";
            } else if (this.password !== this.confirmPassword) {
                this.errors.confirmPassword = "Les mots de passe ne correspondent pas.";
            }

            // Validation du pays
            if (!this.selectedCountry) {
                this.errors.pays = "Le pays est obligatoire.";
            }

            // Faire défiler vers le haut en cas d'erreur
            if (Object.keys(this.errors).length > 0) {
                this.$nextTick(() => {
                    this.$refs.form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }

            return Object.keys(this.errors).length === 0;
        },
        async register() {
            if (this.validateForm()) {
                this.loading = true;
                try {
                    const response = await axios.post('/api/register', {
                        nom: this.nom,
                        prenom: this.prenom,
                        email: this.email,
                        tel: `${this.selectedCountry.idd.root}${this.selectedCountry.idd.suffixes[0]} ${this.tel}`,
                        naissance: this.naissance,
                        password: this.password,
                        pays: this.selectedCountry.name.common,
                    });
                    await Swal.fire({
                        icon: 'success',
                        title: 'Inscription réussie !',
                        text: 'Un code OTP a été envoyé à votre adresse e-mail.',
                        timer: 2000,
                        showConfirmButton: false,
                    });

                    this.nom = '';
                    this.prenom = '';
                    this.email = '';
                    this.tel = '';
                    this.naissance = '';
                    this.password = '';
                    this.confirmPassword = '';
                    this.selectedCountry = '';

                    setTimeout(() => {
                        this.$router.push({ path: '/verify-otp', query: { user_id: response.data.user_id } });
                    }, 1000);
                } catch (error) {
                    console.error('Erreur lors de l\'inscription:', error);
                    if (error.response && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else if (error.response && error.response.data.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: error.response.data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            timer: 2000,
                            text: 'Une erreur est survenue lors de l\'inscription.',
                        });
                    }
                } finally {
                    this.loading = false;
                }
            }
        }
    }
};
</script>

<style scoped>
#register {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-image: url('C:\laravel\pfe\resources\images\registerbg.jpg');
    background-size: cover;
    background-position: center;
    animation: backgroundAnimation 20s infinite alternate; 
    will-change: background-position; 
    padding: 20px;
}

@keyframes backgroundAnimation {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}

.container {
    width: 100%;
    max-width: 800px; 
    padding: 20px;
}

.register-card {
    background: rgba(255, 255, 255, 0.9); 
    border-radius: 15px; 
    padding: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); 
    backdrop-filter: blur(10px); 
    border: 1px solid rgba(255, 255, 255, 0.18); 
}

h1 {
    margin-bottom: 20px;
    font-size: 20px;
    color: #333;
    text-align: center;
}

.form-row {
    display: flex;
    gap: 20px; 
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column; 
        gap: 10px;
    }
}

.form-group {
    flex: 1; 
    border-radius: 50%;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #555;
    z-index: 2;
}

.password-toggle i {
    font-size: 16px;
}

.btn-primary {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #007bff, #00bfff); 
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
}

.link {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.link:hover {
    color: #0056b3;
    text-decoration: underline;
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.input-with-icon {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}


.input-with-icon i:not(.password-toggle i) {
    position: absolute;
    left: 10px;
    color: #555;
    z-index: 1;
}

.input-with-icon input,
.input-with-icon select {
    padding-left: 35px; 
    width: 100%;
    width: 100%;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.input-with-icon input:focus,
.input-with-icon select:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
}

.form-text {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
}

.country-code-display {
    display: flex;
    align-items: center;
    font-weight: bold;
    margin-right: 8px;
}

.country-flag {
    width: 25px;
    height: 18px;
    margin-right: 8px;
    border-radius: 2px;
}

.country-select {
    display: flex;
    align-items: center;
}
</style>