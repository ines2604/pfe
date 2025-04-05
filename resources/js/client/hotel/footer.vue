<template>
    <footer class="footer">
        <div class="container">
            <div class="footer-header">
                <a :href="'/' + hotel.nom" class="logo">
                    <span class="logo-text">{{ hotel.nom }}</span>
                </a>

                <nav class="footer-nav">
                    <ul>
                        <li v-for="(page, i) in pages" :key="i">
                            <a :href="'/' + hotel.nom + page.link" class="nav-link">
                                <span class="link-text">{{ page.name }}</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <div class="social-header">
                    <span class="follow-text">
                        SUIVEZ-NOUS <span class="follow-icon">→</span>
                    </span>
                    <div class="social-icons">
                        <a v-for="(icon, index) in hotel.socialMedia || []" :key="index" 
                            :href="icon.link" 
                            target="_blank" 
                            class="social-link">
                            <i :class="icon.class"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="copyright">
                Site officiel - Tous droits réservés - Alliance Hospitality © 2025<br>
                Conception & réalisation: <span class="creator">Elite Council Consulting : sirine&ines</span>
            </div>
        </div>
    </footer>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            hotel: {
                nom: '',
                socialMedia: []
            },
            pages: [
                { name: 'Page accueil', link: '/' },
                { name: 'Chambres', link: '/listeChambres' },
                { name: 'Services', link: '/listeServices' },
                { name: 'Offres', link: '/listeOffres'},
                { name: 'À propos', link: '/aboutus' },
                { name: 'Galerie', link: '/galerie' },
                { name: 'Contact', link: '/contactus' }
            ]
        };
    },
    async mounted() {
        try {
            const hotelNom = this.$route.params.hotel_nom;
            const response = await axios.get(`/api/hotels/${hotelNom}`);
            
            this.hotel = {
                nom: response.data.nom,
                socialMedia: [
                    { class: 'bi bi-facebook', link: response.data.facebook || '#' },
                    { class: 'bi bi-instagram', link: response.data.instagram || '#' }
                ]
            };
        } catch (error) {
            console.error('Erreur lors de la récupération des informations de l\'hôtel: ', error);
        }
    }
};
</script>

<style scoped>
.footer {
    background-color: #f5f7fa;
    color: #333;
    padding: 3rem 0;
    font-family: 'Montserrat', sans-serif;
    border-top: 1px solid #e1e5eb;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e1e5eb;
    gap: 1.5rem;
}

.logo {
    flex: 1 1 100%;
    text-decoration: none;
    margin-bottom: 0.5rem;
}

.logo-text {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: #2A90D9;
    letter-spacing: 1px;
    white-space: normal;
    word-break: break-word;
    display: inline-block;
    max-width: 100%;
}

.footer-nav {
    flex: 1 1 auto;
    min-width: 0;
    order: 2;
}

.footer-nav ul {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    gap: 1.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-x: auto;
    scrollbar-width: none;
}

.footer-nav ul::-webkit-scrollbar {
    display: none; 
}

.nav-link {
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    position: relative;
    padding: 0.5rem 0;
    transition: color 0.3s ease;
    white-space: nowrap;
}

.link-text {
    position: relative;
}

.nav-link:hover {
    color: #2A90D9;
}

.nav-link:hover .link-text::after {
    width: 100%;
}

.link-text::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: #2A90D9;
    transition: width 0.3s ease;
}

.social-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    order: 3;
    flex-shrink: 0;
}

.follow-text {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.follow-icon {
    font-size: 1.2rem;
    color: #2A90D9;
}

.social-icons {
    display: flex;
    gap: 1rem;
}

.social-link {
    color: #6c757d;
    font-size: 1.2rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(42, 144, 217, 0.1);
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #2A90D9;
    color: white;
    transform: translateY(-3px);
}

.copyright {
    text-align: center;
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.6;
}

.creator {
    color: #2A90D9;
    font-weight: 500;
}

@media (max-width: 768px) {
    .footer-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .logo {
        order: 1;
        margin-bottom: 0;
    }
    
    .footer-nav {
        order: 2;
        width: 100%;
    }
    
    .footer-nav ul {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        overflow-x: visible;
    }
    
    .social-header {
        order: 3;
        margin-top: 0.5rem;
    }
    
    .nav-link {
        font-size: 0.9rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .footer-header {
        gap: 1rem;
    }
    
    .footer-nav ul {
        gap: 1rem;
    }
}
</style>