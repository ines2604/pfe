<template>
    <main>
        <section class="password-edit-container" role="region" aria-label="modifier le mot de passe">
            <h1 class="text-start mb-4">Modifier mot de passe</h1>
            <form @submit.prevent="updatePassword" class="password-form" role="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ancienPassword" class="sr-only">Ancien mot de passe</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                <input
                                    :type="ancienPasswordVisible ? 'text' : 'password'"
                                    class="form-control"
                                    id="ancienPassword"
                                    placeholder="Ancien mot de passe"
                                    v-model="ancienPassword"
                                    :class="{'error-border' : errors.ancienPassword}"
                                    aria-label="Ancien mot de passe"
                                    :aria-describedby="errors.ancienPassword ? 'ancienPassword-error' : null"
                                    @keyup.enter="updatePassword"
                                >
                                <button type="button" class="password-toggle" @click="togglePasswordVisibility('ancienPassword')" aria-label="Afficher ou masquer le mot de passe">
                                    <i class="fas" :class="ancienPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <p class="error-message" v-if="errors.ancienPassword" id="ancienPassword-error" aria-live="assertive">{{ errors.ancienPassword }}</p>
                        </div>

                        <div class="form-group">
                            <label for="nouveauPassword" class="sr-only">Nouveau mot de passe</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                <input
                                    :type="nouveauPasswordVisible ? 'text' : 'password'"
                                    class="form-control"
                                    id="nouveauPassword"
                                    placeholder="Nouveau mot de passe"
                                    v-model="nouveauPassword"
                                    :class="{'error-border' : errors.nouveauPassword}"
                                    aria-label="Nouveau mot de passe"
                                    :aria-describedby="errors.nouveauPassword ? 'nouveauPassword-error' : null"
                                    @keyup.enter="updatePassword"
                                >
                                <button type="button" class="password-toggle" @click="togglePasswordVisibility('nouveauPassword')" aria-label="Afficher ou masquer le mot de passe">
                                    <i class="fas" :class="nouveauPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <p class="error-message" v-if="errors.nouveauPassword" id="nouveauPassword-error" aria-live="assertive">{{ errors.nouveauPassword }}</p>
                        </div>

                        <div class="form-group">
                            <label for="confirmerPassword" class="sr-only">Confirmer mot de passe</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                <input
                                    :type="confirmerPasswordVisible ? 'text' : 'password'"
                                    class="form-control"
                                    id="confirmerPassword"
                                    placeholder="Confirmer mot de passe"
                                    v-model="confirmerPassword"
                                    :class="{'error-border' : errors.confirmerPassword}"
                                    aria-label="Confirmer mot de passe"
                                    :aria-describedby="errors.confirmerPassword ? 'confirmerPassword-error' : null"
                                    @keyup.enter="updatePassword"
                                >
                                <button type="button" class="password-toggle" @click="togglePasswordVisibility('confirmerPassword')" aria-label="Afficher ou masquer le mot de passe">
                                    <i class="fas" :class="confirmerPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <p class="error-message" v-if="errors.confirmerPassword" id="confirmerPassword-error" aria-live="assertive">{{ errors.confirmerPassword }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin"></i> Enregistrement en cours...
                        </span>
                        <span v-else>Enregistrer</span>
                    </button>
                    <button type="button" class="btn btn-secondary" @click="annuler">Annuler</button>
                </div>
            </form>
        </section>
    </main>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'editPassword',
    metaInfo:{
        title: 'Modifier mon mot de passe - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Modifiez votre mot de passe pour sécuriser votre compte Alliance Hospitality.' },
            { name: 'keywords', content: 'modifier mot de passe, sécurité, compte, Alliance Hospitality' },
            { name: 'author', content: 'Alliance Hospitality' }
        ]
    },
    data() {
        return {
            ancienPassword: '',
            nouveauPassword: '',
            confirmerPassword: '',
            ancienPasswordVisible: false,
            nouveauPasswordVisible: false,
            confirmerPasswordVisible: false,
            errors: {},
            loading: false
        };
    },
    methods: {
        togglePasswordVisibility(field) {
            if (field === 'ancienPassword') {
                this.ancienPasswordVisible = !this.ancienPasswordVisible;
            } else if (field === 'nouveauPassword') {
                this.nouveauPasswordVisible = !this.nouveauPasswordVisible;
            } else if (field === 'confirmerPassword') {
                this.confirmerPasswordVisible = !this.confirmerPasswordVisible;
            }
        },
        validateForm() {
            this.errors = {};

            if (!this.ancienPassword) {
                this.errors.ancienPassword = 'Veuillez entrer votre ancien mot de passe.';
            }

            if (!this.nouveauPassword) {
                this.errors.nouveauPassword = 'Veuillez entrer un nouveau mot de passe.';
            } else if (this.nouveauPassword.length < 8) {
                this.errors.nouveauPassword = 'Le mot de passe doit contenir au moins 8 caractères.';
            } else if(
                !/[a-z]/.test(this.nouveauPassword) || 
                !/[A-Z]/.test(this.nouveauPassword) || 
                !/[0-9]/.test(this.nouveauPassword) ||
                !/[^a-zA-Z0-9]/.test(this.nouveauPassword) 
            ){
                this.errors.password = "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.";
            }

            if (!this.confirmerPassword) {
                this.errors.confirmerPassword = 'Veuillez confirmer votre nouveau mot de passe.';
            } else if (this.confirmerPassword !== this.nouveauPassword) {
                this.errors.confirmerPassword = 'Les mots de passe ne correspondent pas.';
            }
            
            return Object.keys(this.errors).length ===0;
        },
        async updatePassword() {
            if (this.validateForm()) {
                this.loading= true;
                try{
                    const token =localStorage.getItem('token');
                    const response = await axios.patch('/api/update-password',{
                        ancienPassword: this.ancienPassword,
                        nouveauPassword: this.nouveauPassword,
                        confirmerPassword: this.confirmerPassword,
                    },{
                        headers: {
                            Authorization: `Bearer ${token}`,  
                        }
                    });
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: response.data.message,
                    });
                    this.annuler();
                }catch(error){
                    if(error.response && error.response.data.errors){
                        this.errors= error.response.data.errors;
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text:' Une erreur s\'est produite lors de la mise à jour du mot de passe.',
                        });
                    }
                }finally{
                    this.loading= false;
                }
            }
        },
        annuler(){
            this.ancienPassword='';
            this.nouveauPassword='';
            this.confirmerPassword='';
            this.errors= {};
        }
    }
};
</script>

<style scoped>
.password-edit-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0px 8px 32px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    max-width: 800px;
    margin: 0 auto;
}
h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
    text-align: center;
}
.password-form {
    margin-top: 20px;
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
.input-with-icon input,
.input-with-icon select {
    padding-left: 35px; 
    padding-right: 10px; 
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
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px; 
}
.btn-primary {
    padding: 10px 20px;
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
.btn-secondary {
    padding: 10px 20px;
    background: #6c757d; 
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
}
.btn-secondary:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px rgba(108, 117, 125, 0.2);
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
</style>