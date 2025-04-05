<template>
    <div class="about-section">
        <h2 class="section-title">À PROPOS DE NOUS</h2>
        <p class="section-description">Découvrez notre engagement envers l'excellence et le confort de nos clients à travers nos services haut de gamme et notre hospitalité exceptionnelle.</p>
        <router-link to="/home/aboutus">
            <button class="btn-discover">
                <span>Voir plus</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </router-link>
    </div>
</template>

<style>
.about-section {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 1rem;
    padding: 0 1.5rem;
}

.section-description {
    font-family: 'Poppins', sans-serif;
    font-size: 1.2rem;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 2.5rem;
}
.btn-discover {
    background: linear-gradient(45deg, #2a90d9, #1e6ca8);
    color: white;
    border: none;
    padding: 14px 35px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 15px rgba(42, 144, 217, 0.2);
}

.btn-discover:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(42, 144, 217, 0.3);
}

.btn-discover i {
    transition: transform 0.3s ease;
}

.btn-discover:hover i {
    transform: translateX(8px);
}

h2 {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    color: #2a90d9;
    margin-bottom: 2rem;
    position: relative;
}

h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #2a90d9;
}
</style>