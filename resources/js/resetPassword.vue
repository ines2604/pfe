<template>
    <div id="reset-password" role="main">
        <div class="container" role="region" aria-label="Formulaire pour réinitialiser le mot de passe">
            <div class="login-card" role="form">
                <h1 class="text-center">Réinitialisation du mot de passe</h1>
                <form @submit.prevent="resetPassword">
                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input
                                :type="passwordVisible ? 'text' : 'password'" 
                                class="form-control" 
                                id="password" 
                                v-model="password" 
                                placeholder="Mot de passe" 
                                aria-label="mot de passe"
                                :class="{'error-border': errors.password}"
                            >
                            <button 
                                type="button" 
                                class="password-toggle" 
                                @click="togglePasswordVisibility('password')"
                                aria-label="Afficher/Masquer le mot de passe" tabindex="0"
                            >
                                <i class="fas" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Minimum de 8 caractères</small>
                        <p class="error-message" v-if="errors.password" aria-live="assertive" >{{ errors.password }}</p>
                    </div>

                    <div class="form-group">
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
                            >
                            <button 
                                type="button" 
                                class="password-toggle" 
                                @click="togglePasswordVisibility('confirmPassword')"
                                aria-label="Afficher/Masquer le mot de passe" tabindex="0"
                            >
                                <i class="fas" :class="confirmPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <p class="error-message" v-if="errors.confirmPassword" aria-live="assertive">{{ errors.confirmPassword }}</p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin"></i> Réinitialisation en cours...
                        </span>
                        <span v-else>Réinitialiser le mot de passe</span>
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <i class="fas fa-arrow-left"></i>
                    <router-link to="/login" class="link">Retour à la connexion</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'resetPassword',
    metaInfo:{
        title: 'Réinitialisation du mot de passe - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Réinitialisez votre mot de passe pour accéder à votre compte Alliance Hospitality en toute sécurité.' },
            { name: 'keywords', content: 'réinitialisation mot de passe, compte, sécurité, Alliance Hospitality' },
            { name: 'author', content: 'Alliance Hospitality' }
        ]
    },
    data() {
        return {
            password: '',
            confirmPassword: '',
            passwordVisible: false,
            confirmPasswordVisible: false,
            errors: {},
            loading: false
        };
    },
    methods: {
        validateForm() {
            this.errors = {};

            // Validation du mot de passe
            if (!this.password) {
                this.errors.password = "Le mot de passe est obligatoire.";
            } else if (this.password.length < 8) {
                this.errors.password = "Le mot de passe doit contenir au moins 8 caractères.";
            } else if (
                !/[a-z]/.test(this.password) || 
                !/[A-Z]/.test(this.password) || 
                !/[0-9]/.test(this.password) ||
                !/[^a-zA-Z0-9]/.test(this.password)   
            ) {
                this.errors.password = "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.";
            }

            // Validation de la confirmation du mot de passe
            if (!this.confirmPassword) {
                this.errors.confirmPassword = "La confirmation du mot de passe est obligatoire.";
            } else if (this.password !== this.confirmPassword) {
                this.errors.confirmPassword = "Les mots de passe ne correspondent pas.";
            }

            return Object.keys(this.errors).length === 0;
        },
        async resetPassword() {
            if (this.validateForm()) {
                this.loading= true;
                try {
                    const response = await axios.post(`/api/reset-password/${this.$route.params.token}`, {
                        email: this.$route.query.email,
                        password: this.password,
                        password_confirmation: this.confirmPassword
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: response.data.message,
                    }).then(() => {
                        this.$router.push('/login');
                    });
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: "Une erreur est survenue. Veuillez réessayer.",
                    });
                }finally{
                    this.loading= false;
                }
            }
        },
        togglePasswordVisibility(field) {
            if (field === 'password') {
                this.passwordVisible = !this.passwordVisible;
            } else if (field === 'confirmPassword') {
                this.confirmPasswordVisible = !this.confirmPasswordVisible;
            }
        }
    }
};
</script>

<style scoped>
#reset-password {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-image: url('../images/resetpassbg.jpg'); 
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
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-card {
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

.mt-3 i {
    margin-right: 5px;
    color: #007bff;
}

h1 {
    margin-bottom: 20px;
    font-size: 20px;
    color: #333;
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

.input-with-icon {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.input-with-icon i {
    position: absolute;
    left: 10px;
    color: #555;
    z-index: 1;
}

.input-with-icon input {
    padding-left: 35px; 
    padding-right: 45px; 
    width: 100%;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.input-with-icon input:focus {
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
    width: 30px; 
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;

}

.password-toggle i {
    font-size: 16px;
}

.btn-primary {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #007bff, #00bfff); /* Dégradé */
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
.fa-spinner {
    margin-right: 8px;
}
</style>