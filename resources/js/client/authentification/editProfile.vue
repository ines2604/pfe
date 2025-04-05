<template>
    <main>
        <section class="profile-edit-container" role="region" aria-label="modifier les informations du profil">
            <h1 class="text-start mb-4">Modifier mon profil</h1>
            <form @submit.prevent="updateProfile" class="profile-form" role="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom" class="sr-only">Nom</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="nom"
                                    v-model="user.nom"
                                    :class="{'error-border': errors.nom}"
                                    aria-label="Nom"
                                    :aria-describedby="errors.nom ? 'nom-error' : null"
                                    @keyup.enter="updateProfile"
                                >
                            </div>
                            <p class="error-message" v-if="errors.nom" id="nom-error" aria-live="assertive">{{ errors.nom }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prenom" class="sr-only">Prénom</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="prenom"
                                    v-model="user.prenom"
                                    :class="{'error-border': errors.prenom}"
                                    aria-label="Prénom"
                                    :aria-describedby="errors.prenom ? 'prenom-error' : null"
                                    @keyup.enter="updateProfile"
                                >
                            </div>
                            <p class="error-message" v-if="errors.prenom" id="prenom-error" aria-live="assertive">{{ errors.prenom }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="sr-only">Adresse e-mail</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            v-model="user.email"
                            readonly
                            disabled
                            aria-label="Adresse e-mail"
                            :aria-describedby="email-description"
                        >
                    </div>
                    <p id="email-description" class="text-muted small">L'adresse email ne peut pas être modifiée</p>
                </div>

                <div class="form-group">
                    <label for="tel" class="sr-only">Numéro de téléphone</label>
                    <div class="input-with-icon">
                        <div v-if="user.selectedCountry" class="country-code-display">
                            <img :src="user.selectedCountry.flagUrl" alt="Drapeau" class="country-flag">
                            {{ user.selectedCountry.idd.root }}{{ user.selectedCountry.idd.suffixes?.[0] || '' }}
                        </div>
                        <i v-else class="fas fa-phone" aria-hidden="true"></i>
                        <input
                            type="tel"
                            class="form-control"
                            id="tel"
                            v-model="user.tel"
                            :class="{'error-border': errors.tel}"
                            aria-label="Numéro de téléphone"
                            :aria-describedby="errors.tel ? 'tel-error' : null"
                            @keyup.enter="updateProfile"
                        >
                    </div>
                    <p class="error-message" v-if="errors.tel" id="tel-error" aria-live="assertive">{{ errors.tel }}</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="naissance" class="sr-only">Date de naissance</label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar" aria-hidden="true"></i>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="naissance"
                                    v-model="user.naissance"
                                    :class="{'error-border': errors.naissance}"
                                    aria-label="Date de naissance"
                                    :aria-describedby="errors.naissance ? 'naissance-error' : null"
                                    @keyup.enter="updateProfile"
                                >
                            </div>
                            <p class="error-message" v-if="errors.naissance" id="naissance-error" aria-live="assertive">{{ errors.naissance }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pays" class="sr-only">Pays</label>
                            <div class="input-with-icon">
                                <img v-if="user.selectedCountry" :src="user.selectedCountry.flagUrl" alt="Drapeau" class="country-flag">
                                <i v-else class="fas fa-globe" aria-hidden="true"></i>
                                <select
                                    class="form-control"
                                    id="pays"
                                    v-model="user.selectedCountry"
                                    :class="{'error-border': errors.pays}"
                                    aria-label="Pays"
                                    :aria-describedby="errors.pays ? 'pays-error' : null"
                                    @keyup.enter="updateProfile"
                                    @change="updateCountryCode"
                                >
                                <option v-for="country in countries" :key="country.cca2" :value="country">
                                    {{ country.flag }} {{ country.name.common }}
                                </option>
                                </select>
                            </div>
                            <p class="error-message" v-if="errors.pays" id="pays-error" aria-live="assertive">{{ errors.pays }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Enregistrement en cours...
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
import countries from 'world-countries';

export default {
    name: 'editProfile',
    metaInfo: {
        title: 'Modifier mon profil - Alliance Hospitality',
        meta: [
            { name: 'description', content: 'Modifiez vos informations personnelles et gérez votre compte Alliance Hospitality.' },
            { name: 'keywords', content: 'modifier profil, compte, informations personnelles, Alliance Hospitality' },
            { name: 'author', content: 'Alliance Hospitality' }
        ]
    },
    data() {
        return {
            user: {
                nom: '',
                prenom: '',
                email: '',
                tel: '',
                naissance: '',
                selectedCountry: null,
            },
            originalUser: {},
                countries: countries.map(country => ({
                ...country,
                flag: this.getFlagEmoji(country.cca2),
                flagUrl: `https://flagcdn.com/w40/${country.cca2.toLowerCase()}.png`,
            })),
            errors: {},
            loading: false
        };
    },
    async mounted(){
        await this.fetchUserData();

    },
    methods: {
        getFlagEmoji(countryCode) {
            return String.fromCodePoint(...Array.from(countryCode.toUpperCase()).map(char => 127397 + char.charCodeAt()));
        },
        updateCountryCode() {
            this.user.tel = ''; 
        },
        cleanPhoneNumber(phoneNumber, countryCode) {
            if (phoneNumber.startsWith(countryCode)) {
                return phoneNumber.slice(countryCode.length).trim();
            }
            return phoneNumber;
        },
        formatDate(dateString) {
            const datePart = dateString.split('T')[0];
            return datePart;  
        },
        async fetchUserData() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    this.$router.push({ name: 'login' }); 
                    return;
                }

                const response = await axios.get('/api/user', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });

                const selectedCountry = this.countries.find(
                    country => country.name.common === response.data.pays
                );

                let tel= response.data.tel;
                if (selectedCountry) {
                    const countryCode = `${selectedCountry.idd.root}${selectedCountry.idd.suffixes[0]}`;
                    tel = this.cleanPhoneNumber(response.data.tel, countryCode);
                }

                this.originalUser = {
                    nom: response.data.nom,
                    prenom: response.data.prenom,
                    email: response.data.email,
                    tel: tel,
                    naissance: this.formatDate(response.data.naissance),
                    selectedCountry: selectedCountry,
                };
                this.user= { ...this.originalUser };
            } catch (error) {
                console.error('Erreur lors de la récupération des données utilisateur:', error);
                this.$router.push({ name: 'login' });
            }
        },
        annuler(){
            this.user = { ...this.originalUser };
            this.errors= {};
        },
        validateForm() {
            this.errors = {};

            // Validation du nom
            if (!this.user.nom) {
                this.errors.nom = "Le nom est obligatoire.";
            } else if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(this.user.nom)) {
                this.errors.nom = "Le nom ne doit contenir que des lettres, des espaces, des tirets et des caractères accentués.";
            } else if (this.user.nom.length < 2) {
                this.errors.nom = "Le nom doit contenir au moins 2 caractères.";
            }

            // Validation du prénom
            if (!this.user.prenom) {
                this.errors.prenom = "Le prénom est obligatoire.";
            } else if (!/^[a-zA-ZÀ-ÿ\s\-]+$/.test(this.user.prenom)) {
                this.errors.prenom = "Le prénom ne doit contenir que des lettres, des espaces, des tirets et des caractères accentués.";
            } else if (this.user.prenom.length < 2) {
                this.errors.prenom = "Le prénom doit contenir au moins 2 caractères.";
            }

            // Validation du téléphone
            if (!this.user.tel) {
                this.errors.tel = "Le numéro de téléphone est obligatoire.";
            } else if (!/^[0-9+\-\s]+$/.test(this.user.tel)) {
                this.errors.tel = "Le numéro de téléphone ne doit contenir que des chiffres, des espaces, des tirets et des signes +.";
            }

            // Validation de la date de naissance
            if (!this.user.naissance) {
                this.errors.naissance = "La date de naissance est obligatoire.";
            } else {
                const birthDate = new Date(this.user.naissance);
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
            // Validation du pays
            if (!this.user.selectedCountry) {
                this.errors.pays = "Le pays est obligatoire.";
            }

            return Object.keys(this.errors).length === 0;
        },
        async updateProfile() {
            if (this.validateForm()) {
                this.loading= true;
                try {
                    const payload = {
                        nom: this.user.nom,
                        prenom: this.user.prenom,
                        tel: `${this.user.selectedCountry.idd.root}${this.user.selectedCountry.idd.suffixes[0]} ${this.user.tel}`,
                        naissance: this.user.naissance,
                        pays: this.user.selectedCountry.name.common,
                    };
                    const token = localStorage.getItem('token');
                    const response = await axios.patch(
                        '/api/update-profile',
                        payload, 
                        {
                            headers: {
                                Authorization: `Bearer ${token}`
                            }
                        }
                    );
                    await Swal.fire({
                        icon: 'success',
                        title: 'Profil mis à jour avec succès !',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                } catch (error) {
                    if (error.response && error.response.data) {
                        this.errors = error.response.data.errors;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "Erreur",
                            timer: 2000,
                            text: 'Erreur lors de la mise à jour du profil.',
                        });
                    }
                }finally{
                    this.loading = false;
                }
            }
        }
    }
};
</script>

<style scoped>
.profile-edit-container {
    background: rgba(255, 255, 255, 0.9); 
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); 
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18); 
    max-width: 800px;
    margin: 0 auto; 
}

h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
    text-align: center;
}

.profile-form {
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

.input-with-icon input:disabled {
    background-color: #f8f9fa;
    opacity: 1;
    cursor: not-allowed;
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
</style>