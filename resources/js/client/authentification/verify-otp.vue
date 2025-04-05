<template>
    <div id="verify-otp" role="main">
        <div class="container" role="region" aria-label="Vérification OTP">
            <div class="verify-otp-card" role="form">
                <h1 class="text-center">Vérification OTP</h1>
                <form @submit.prevent="verifyOtp" ref="form">
                    <input type="hidden" name="user_id" :value="user_id">
                    <div class="form-group">
                        <div class="input-with-icon">
                            <i class="fas fa-key"></i>
                            <input
                                type="text"
                                class="form-control"
                                id="otp"
                                v-model="otp"
                                placeholder="Code OTP"
                                :class="{'error-border': errors.otp}"
                                aria-label="Code OTP"
                                @keyup.enter="verifyOtp"
                            >
                        </div>
                        <p class="error-message" v-if="errors.otp" aria-live="assertive">{{ errors.otp }}</p>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                            <span v-if="loading">
                                <i class="fas fa-spinner fa-spin"></i> Vérification en cours...
                            </span>
                            <span v-else>Vérifier</span>
                        </button>
                        <div class="mt-2">
                            <a href="#" @click.prevent="resendOtp" class="link">Renvoyer le code OTP</a>
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

export default {
    name: 'VerifyOtp',
    data() {
        return {
            otp: '',
            user_id: this.$route.query.user_id,
            errors: {},
            loading: false,
        };
    },
    methods: {
        async verifyOtp() {
            this.loading = true;
            try {
                const response = await axios.post('/api/verify-otp', {
                    user_id: this.user_id,
                    otp: this.otp,
                });

                await Swal.fire({
                    icon: 'success',
                    title: 'Vérification réussie !',
                    text: 'Vous pouvez maintenant vous connecter.',
                    timer: 2000,
                    showConfirmButton: false,
                });

                this.$router.push('/login');
            } catch (error) {
                console.error('Erreur lors de la vérification OTP:', error);
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
                        text: 'Une erreur est survenue lors de la vérification OTP.',
                    });
                }
            } finally {
                this.loading = false;
            }
        },
        async resendOtp() {
            try {
                const response = await axios.post('/api/resend-otp', {
                    user_id: this.user_id,
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Code OTP renvoyé !',
                    text: 'Un nouveau code OTP a été envoyé à votre adresse e-mail.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            } catch (error) {
                console.error('Erreur lors du renvoi de l\'OTP:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors du renvoi de l\'OTP.',
                });
            }
        },
    },
};
</script>

<style scoped>
#verify-otp {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-image: url('C:\laravel\pfe\resources\images\registerbg.jpg');
    background-size: cover;
    background-position: center;
    padding: 20px;
}

.verify-otp-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    max-width: 400px; /* Réduire la largeur maximale */
    width: 100%; /* S'assurer que la carte s'adapte à la largeur maximale */
    margin: 0 auto; /* Centrer la carte horizontalement */
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