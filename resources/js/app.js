// Inicialización del carrusel
const initCarousel = () => {
    const images = document.querySelectorAll('.carousel-image');
    if (!images.length) return;

    let currentImageIndex = 0;
    const showNextImage = () => {
        images[currentImageIndex].classList.remove('active');
        currentImageIndex = (currentImageIndex + 1) % images.length;
        images[currentImageIndex].classList.add('active');
    };

    images[currentImageIndex].classList.add('active');
    setInterval(showNextImage, 3000);
};

// Inicialización del slider
const initSlider = () => {
    const mainSlider = document.querySelector('.main-slider');
    if (!mainSlider) return;

    const slides = document.querySelectorAll('.slider-item');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentIndex = 0;
    let sliderInterval;

    const showSlide = (index) => {
        if (index < 0 || index >= slides.length) return;
        slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
        thumbnails.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
        currentIndex = index;
    };

    const nextSlide = () => showSlide((currentIndex + 1) % slides.length);
    const prevSlide = () => showSlide((currentIndex - 1 + slides.length) % slides.length);
    
    const startSlider = () => {
        if (slides.length > 1) sliderInterval = setInterval(nextSlide, 7000);
    };
    
    const stopSlider = () => clearInterval(sliderInterval);

    // Event Listeners
    if (prevBtn) prevBtn.addEventListener('click', () => { stopSlider(); prevSlide(); startSlider(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { stopSlider(); nextSlide(); startSlider(); });

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => { stopSlider(); showSlide(index); startSlider(); });
    });

    mainSlider.addEventListener('mouseenter', stopSlider);
    mainSlider.addEventListener('mouseleave', startSlider);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') { stopSlider(); prevSlide(); startSlider(); }
        else if (e.key === 'ArrowRight') { stopSlider(); nextSlide(); startSlider(); }
    });

    showSlide(0);
    startSlider();
};

// Inicialización del mapa interactivo
const initMap = () => {
    const circulos = document.querySelectorAll('.circulo-pais');
    const hoverCircle = document.getElementById('hover-circle');
    const hoverText = document.getElementById('hover-text');
    
    if (!circulos.length || !hoverCircle || !hoverText) return;

    circulos.forEach(circulo => {
        circulo.addEventListener('mouseenter', function(e) {
            const pais = this.getAttribute('data-pais');
            const cx = this.getAttribute('cx');
            const cy = this.getAttribute('cy');
            
            hoverCircle.setAttribute('cx', cx);
            hoverCircle.setAttribute('cy', cy);
            hoverCircle.classList.add('visible');
            
            hoverText.setAttribute('x', cx);
            hoverText.setAttribute('y', cy);
            hoverText.textContent = pais;
            hoverText.classList.add('visible');
        });
        
        circulo.addEventListener('mouseleave', () => {
            hoverCircle.classList.remove('visible');
            hoverText.classList.remove('visible');
        });
        
        circulo.addEventListener('click', function() {
            const pais = this.getAttribute('data-pais');
            alert(`¡Has seleccionado ${pais}! 🌍\n\nEste país forma parte de nuestra red global.`);
        });
    });
};

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
    initCarousel();
    initSlider();
    initMap();
});
