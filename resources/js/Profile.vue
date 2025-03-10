<script setup lang="js">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import EditProfile from './editProfile.vue';
import EditPassword from './editPassword.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

defineOptions({
    metaInfo: {
    title: 'Profil - Alliance Hospitality',
    meta: [
        { name: 'description', content: 'Gérez votre profil, modifiez votre mot de passe et consultez vos réservations.' },
        { name: 'keywords', content: 'profil, mot de passe, réservations, Alliance Hospitality' },
        { name: 'author', content: 'Alliance Hospitality' }
    ]
    }
});

const activeTab = ref('editProfile');
const router = useRouter();

const logout = async () => {
    try {
        const token = localStorage.getItem('token'); 

        if (!token) {
            throw new Error('Token not found');
        }

        await axios.post('/api/logout', null, {
            headers: {
                'Authorization': `Bearer ${token}`, 
            }
        });

        localStorage.removeItem('token');

        await Swal.fire({
            icon: 'success',
            title: 'Vous êtes déconnecté avec succès !',
            timer: 2000,
            showConfirmButton: false
        });

        router.push('/home');
        
    } catch (error) {
        await Swal.fire({
            icon: 'error',
            title: 'Erreur',
            timer: 2000,
            showConfirmButton: false
        });
    }
};

const setActiveTab = (tab) => {
    activeTab.value = tab;
};
</script>

<template>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <section class="container p-0" role="region" aria-label="informations du profil">
        <div class="row">
            <div class="col-md-5 col-xl-4">
                <div class="card shadow-lg rounded">
                    <div class="list-group list-group-flush" role="tablist">
                        <button 
                            class="list-group-item list-group-item-action" 
                            :class="{'active': activeTab === 'editProfile'}" 
                            @click="setActiveTab('editProfile')">
                            <i class="fas fa-user-edit" aria-hidden="true"></i> Modifier mon profil
                        </button>

                        <button 
                            class="list-group-item list-group-item-action" 
                            :class="{'active': activeTab === 'editPassword'}" 
                            @click="setActiveTab('editPassword')"
                            aria-label="Modifier mon profil"
                            aria-selected="activeTab === 'editProfile'">
                            <i class="fas fa-key" aria-hidden="true"></i> Modifier mot de passe
                        </button>
                        
                        <button 
                            class="list-group-item list-group-item-action" 
                            :class="{'active': activeTab === 'reservations'}" 
                            @click="setActiveTab('reservations')"
                            aria-label="Mes réservations"
                            aria-selected="activeTab === 'reservations'">
                            <i class="fas fa-calendar-check" aria-hidden="true"></i> Mes réservations
                        </button>

                        <button 
                            class="list-group-item list-group-item-action logout" 
                            @click="logout"
                            aria-label="déconnexion">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    <div 
                        class="tab-pane fade" 
                        :class="{'show active': activeTab === 'editProfile'}" 
                        id="editProfile" role="tabpanel">
                        <EditProfile />
                    </div>
                    <div 
                        class="tab-pane fade" 
                        :class="{'show active': activeTab === 'editPassword'}" 
                        id="editPassword" role="tabpanel">
                        <EditPassword />
                    </div>
                    <div 
                        class="tab-pane fade" 
                        :class="{'show active': activeTab === 'reservations'}" 
                        id="reservations" role="tabpanel">
                        <p>Vos réservations ici...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.card {
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    margin-top: 20px;
    border: none;
}

.list-group-item {
    font-size: 1.1rem;
    padding: 15px 20px;
    background-color: #ffffff;
    transition: all 0.3s ease-in-out;
    border: none;
    margin-bottom: 5px;
    border-radius: 10px;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.list-group-item-action {
    color: #495057;
    text-decoration: none;
}

.list-group-item-action.active {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

.fa-user-edit,
.fa-calendar-check,
.fa-sign-out-alt {
    margin-right: 10px;
    color: inherit;
}

.tab-content {
    margin-top: 20px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

button {
    cursor: pointer;
    background: none;
    border: none;
    text-align: left;
    width: 100%;
    padding: 0;
}

.list-group-item.logout {
    color: #dc3545;
}

.list-group-item.logout:hover {
    background-color: #f8d7da;
    color: #dc3545;
}

.tab-pane.fade {
    transition: opacity 0.3s ease-in-out;
}

.tab-pane.fade:not(.show) {
    opacity: 0;
    display: none;
}

@media (max-width: 768px) {
    .col-md-5, .col-md-7 {
        width: 100%;
    }

    .list-group-item {
        font-size: 1rem;
        padding: 10px 15px;
    }

    .tab-content {
        margin-top: 10px;
    }
}
</style>