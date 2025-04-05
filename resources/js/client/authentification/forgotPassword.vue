<template>
    <div id="reset-password">
        <div class="container">
            <div class="card shadow-lg login-card">
                <div class="card-body">
                    <h1 class="text-center">Réinitialisation du mot de passe</h1>
                    <p class="text-center">Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
                    <form @submit.prevent="sendResetLink">
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
                                >
                            </div>
                            <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                            <span v-if="loading">
                                <i class="fas fa-spinner fa-spin"></i> Envoi en cours...
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
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'forgotPassword',
    metaInfo: {
        title: 'Réinitialisation du mot de passe - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Réinitialisez votre mot de passe pour accéder à votre compte Alliance Hospitality.' },
            { name: 'keywords', content: 'réinitialisation mot de passe, compte, sécurité, Alliance Hospitality' },
            { name: 'author', content: 'Alliance Hospitality' }
        ]
    },
    data() {
        return {
            email: "",
            errors: {},
            loading: false 
        };
    },
    methods: {
        validateForm() {
            this.errors = {};

            // Validation de l'email
            if (!this.email) {
                this.errors.email = "L'adresse e-mail est obligatoire.";
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = "L'adresse e-mail est invalide.";
            }

            return Object.keys(this.errors).length === 0;
        },

        async sendResetLink() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true; 

            try {
                const response = await axios.post('/api/forgot-password', {
                    email: this.email
                });

                Swal.fire({
                    title: 'Succès!',
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

                this.email = "";
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    if (errors.email) {
                        this.errors.email = errors.email[0];
                    }
                } else if (error.response && error.response.status === 404) {
                    Swal.fire({
                        title: 'Erreur!',
                        text: error.response.data.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                } else {
                    Swal.fire({
                        title: 'Erreur!',
                        text: error.response ? error.response.data.error : "Une erreur est survenue, veuillez réessayer.",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } finally {
                this.loading = false; 
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
    min-height: 100vh;
    background-image: url('C:\laravel\pfe\resources\images\forgotpassbg.jpg'); 
    background-size: cover;
    background-position: center;
    animation: backgroundAnimation 20s infinite alternate;
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
    width: 100%;
    background: rgba(255, 255, 255, 0.9); 
    border-radius: 15px;
    padding: 20px;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px); 
    border: 1px solid rgba(255, 255, 255, 0.18);
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

p {
    text-align: center;
    color: #555;
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
}

.input-with-icon input {
    padding-left: 35px;
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
    font-size: 14px;
    transition: color 0.3s ease;
}

.link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.link i {
    margin-right: 5px;
}

.error-border {
    border-color: red !important;
}

.error-message {
    color: red;
    font-size: 0.9rem;
    margin-top: 5px;
}

.fa-spinner {
    margin-right: 8px;
}
</style>