<template>
    <div id="login" role="main">
        <section class="container" role="region" aria-label="Formulaire de connexion">
            <article class="login-card" role="form">
                <h1 class="text-center">Connexion à votre compte</h1>
                <form @submit.prevent="login">
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
                                @keyup.enter="login"
                            >
                        </div>
                        <p class="error-message" v-if="errors.email" role="alert" aria-live="assertive">{{ errors.email }}</p>
                    </div>
                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input 
                                :type="passwordVisible ? 'text' : 'password'" 
                                class="form-control" 
                                id="password" 
                                v-model="password" 
                                placeholder="Mot de passe" 
                                :class="{'error-border': errors.password}"
                                @keyup.enter="login"
                                aria-label="Mot de passe"
                            >
                            <button type="button" class="password-toggle" @click="togglePasswordVisibility" aria-label="Afficher/Masquer le mot de passe" tabindex="0">
                                <i class="fas" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <p class="error-message" v-if="errors.password" aria-live="assertive">{{ errors.password }}</p>
                    </div>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Connexion en cours...</span>
                        <span v-else>Se connecter</span>
                    </button>
                </form>
                <div class="links">
                    <router-link to="/forgot-password" class="link">Mot de passe oublié</router-link>
                    <router-link to="/register" class="link">Inscrivez-vous</router-link>
                </div>
            </article>
        </section>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2'; 

export default {
    name: 'Login',
    metaInfo: {
        title: 'Connexion - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Connectez-vous à votre compte pour accéder à vos services personnalisés.' },
            { name: 'keywords', content: 'connexion, compte, authentification, sécurité, services, Alliance Hospitality' },
            { name: 'author', content: 'Alliance Hospitality' }
        ],
    },
    data() {
        return {
            email: '',
            password: '',
            passwordVisible: false,
            errors: {},
            loading: false
        };
    },
    methods: {
        togglePasswordVisibility() {
            this.passwordVisible = !this.passwordVisible;
        },
        validateForm() {
            this.errors = {};

            if (!this.email) {
                this.errors.email = "L'adresse e-mail est obligatoire.";
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = "L'adresse e-mail est invalide.";
            }

            if (!this.password) {
                this.errors.password = "Le mot de passe est obligatoire.";
            }

            return Object.keys(this.errors).length === 0;
        },
        async login() {
            if (this.validateForm()) {
                this.loading = true;
                try {
                    const response = await axios.post('api/login', {
                        email: this.email,
                        password: this.password
                    });
                    const token = response.data.token;
                    localStorage.setItem('token', token);
                    await Swal.fire({
                        icon: 'success',
                        title: 'Connexion réussite !',
                        text: 'Vous allez être redirigé vers la page principale.',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    setTimeout(() => {
                        this.$router.push('/home');
                    }, 1000);
                } catch (error) {
                    if (error.response) {
                        if (error.response.status === 401) {
                            this.errors.email = "Adresse e-mail ou mot de passe incorrect.";
                        } else if (error.response.status === 404) {
                            this.errors.email = "Adresse e-mail introuvable.";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                timer: 2000,
                                text: error.response.data.message || 'Une erreur est survenue.',
                            });
                        }
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
#login {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-image: url('../images/loginbg.jpg');
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
    max-width: 400px; 
    padding: 20px;
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
    padding-right: 40px;
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
    color: #fff;
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

.links {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
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

</style>