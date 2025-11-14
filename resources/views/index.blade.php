@extends('layouts.all')
@extends('essencials.header')

@section('video2')
@endsection

<!-- ESTRUCTURA INSPIRADA EN FRUTEEC.CL -->
@section('video')
@if(session('admin_authenticated'))
<div id="visual-edit-banner" style="position: fixed; top: 0; left: 0; right: 0; background: linear-gradient(90deg, #e74c3c, #c0392b); color: white; padding: 8px 20px; z-index: 10000; text-align: center; font-weight: bold; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
    <i class="fas fa-edit"></i> MODO EDICIÓN VISUAL ACTIVO
    <a href="{{ route('admin.slider.index') }}" target="_blank" class="btn btn-light btn-sm ms-3">
        <i class="fas fa-sliders-h me-1"></i>Gestionar Slider
    </a>
    <button onclick="toggleEditMode()" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 4px 8px; border-radius: 4px; margin-left: 15px; cursor: pointer;">
        <i class="fas fa-eye-slash"></i> Ocultar
    </button>
</div>
<div style="height: 50px;"></div>
@endif

<!-- HERO SECTION - Con Video de Fondo -->
<section class="hero-fruteec">
    <video class="hero-video-background" autoplay muted loop playsinline>
        <source src="{{ asset('video/Cofrupa.mp4') }}" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content-fruteec">
        <p class="hero-subtitle-fruteec">{{ __('messages.hero_subtitle') }}</p>
        <h1 class="hero-title-fruteec">{{ __('messages.hero_title') }}</h1>
        <p class="hero-description-fruteec">{{ __('messages.hero_description') }}</p>
        <a href="#contacto" class="hero-btn-fruteec">{{ __('messages.hero_button') }}</a>
    </div>
</section>

<!-- VENTAJAS COMPETITIVAS - Estilo Fruteec -->
<section class="why-us-fruteec" id="ventajas-section">
    <h2 class="section-title-fruteec animate-title">{{ __('messages.why_us_title') }}</h2>
    <div class="why-us-cards">
        <div class="why-card animate-card" data-delay="0">
            <div class="card-number">01</div>
            <h3>{{ __('messages.why_us_card1_title') }}</h3>
            <p>{{ __('messages.why_us_card1_desc') }}</p>
        </div>
        <div class="why-card animate-card" data-delay="1">
            <div class="card-number">02</div>
            <h3>{{ __('messages.why_us_card2_title') }}</h3>
            <p>{{ __('messages.why_us_card2_desc') }}</p>
        </div>
        <div class="why-card animate-card" data-delay="2">
            <div class="card-number">03</div>
            <h3>{{ __('messages.why_us_card3_title') }}</h3>
            <p>{{ __('messages.why_us_card3_desc') }}</p>
        </div>
        <div class="why-card animate-card" data-delay="3">
            <div class="card-number">04</div>
            <h3>{{ __('messages.why_us_card4_title') }}</h3>
            <p>{{ __('messages.why_us_card4_desc') }}</p>
        </div>
        <div class="why-card animate-card" data-delay="4">
            <div class="card-number">05</div>
            <h3>{{ __('messages.why_us_card5_title') }}</h3>
            <p>{{ __('messages.why_us_card5_desc') }}</p>
        </div>
    </div>
</section>

<script>
// Animación de entrada para las ventajas competitivas
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Animar título
                const title = entry.target.querySelector('.animate-title');
                if (title) {
                    title.classList.add('visible');
                }
                
                // Animar tarjetas secuencialmente
                const cards = entry.target.querySelectorAll('.animate-card');
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, index * 150); // 150ms de delay entre cada tarjeta
                });
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    const section = document.getElementById('ventajas-section');
    if (section) {
        observer.observe(section);
    }
});
</script>

<!-- PRODUCTOS -->
<section class="productos-fruteec" id="productos">
    <div class="productos-background-image"></div>
    <div class="productos-content-wrapper">
        <h2 class="section-title-fruteec">{{ __('messages.products_title') }}</h2>
        <div class="productos-grid">
        <div class="producto-item">
            {!! editableImage('product_pitted', './image/productos/ciruelas.webp', 'Pitted Prunes', 'productos') !!}
            <div class="producto-info">
                <h3>{{ __('messages.product1_name') }}</h3>
                <p>{{ __('messages.product1_desc') }}</p>
            </div>
        </div>
        <div class="producto-item">
            {!! editableImage('product_unpitted', './image/productos/ciruelas.webp', 'Unpitted Prunes', 'productos') !!}
            <div class="producto-info">
                <h3>{{ __('messages.product2_name') }}</h3>
                <p>{{ __('messages.product2_desc') }}</p>
            </div>
        </div>
        <div class="producto-item">
            {!! editableImage('product_natural', './image/productos/ciruelas.webp', 'Natural Prunes', 'productos') !!}
            <div class="producto-info">
                <h3>{{ __('messages.product3_name') }}</h3>
                <p>{{ __('messages.product3_desc') }}</p>
            </div>
        </div>
        <div class="producto-item">
            {!! editableImage('product_puree', './image/productos/ciruelas.webp', 'Prune Puree', 'productos') !!}
            <div class="producto-info">
                <h3>{{ __('messages.product4_name') }}</h3>
                <p>{{ __('messages.product4_desc') }}</p>
            </div>
        </div>
        <div class="producto-item">
            {!! editableImage('product_juice', './image/productos/ciruelas.webp', 'Concentrated Juice', 'productos') !!}
            <div class="producto-info">
                <h3>{{ __('messages.product5_name') }}</h3>
                <p>{{ __('messages.product5_desc') }}</p>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

<!-- QUIÉNES SOMOS -->
@section('nosotros')
<section class="about-fruteec" id="nosotros">
    <div class="about-container">
        <div class="about-text">
            <h2>{{ __('messages.about_us') }}</h2>
            <p>{!! editableContent('about_description', 'nosotros', __('messages.about_us_description'), 'textarea') !!}</p>
            
            <h3 class="about-subtitle">{{ __('messages.our_mission') }}</h3>
            <p>{!! editableContent('mission_description', 'nosotros', __('messages.mission_description'), 'textarea') !!}</p>
            
            <h3 class="about-subtitle">{{ __('messages.our_vision') }}</h3>
            <p>{!! editableContent('vision_description', 'nosotros', __('messages.vision_description'), 'textarea') !!}</p>
            
            <h3 class="about-subtitle">{{ __('messages.who_we_are') }}</h3>
            <p>{!! editableContent('who_we_are_description', 'nosotros', __('messages.who_we_are_description'), 'textarea') !!}</p>
            
            <h3 class="about-subtitle">{{ __('messages.logistics_title') }}</h3>
            <p>{!! editableContent('logistics_description', 'nosotros', __('messages.logistics_description'), 'textarea') !!}</p>
        </div>
        <div class="about-images">
            <!-- Video permanente en reproducción -->
            <div class="about-video-container">
                <video class="about-video-permanent" autoplay muted loop playsinline>
                    <source src="{{ asset('video/Cofrupa.mp4') }}" type="video/mp4">
                    Tu navegador no soporta la reproducción de videos.
                </video>
            </div>
        </div>
    </div>
</section>
@endsection

<!-- CERTIFICACIONES -->
@section('productos')
<section class="certifications-fruteec">
    <h2 class="section-title-fruteec">{{ __('messages.certifications_main_title') }}</h2>
    <p class="certifications-subtitle">{{ __('messages.certifications_description') }}</p>
    
    <div class="certifications-carousel">
        <div class="cert-carousel-container">
            <button class="cert-carousel-btn prev" onclick="prevCert()">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="cert-carousel-track">
                <div class="cert-carousel-slide active">
                    {!! editableImage('cert_1', './image/icon/certificaciones.png', 'Certificación 1', 'certificaciones', 'cert-carousel-img') !!}
                </div>
                <div class="cert-carousel-slide">
                    {!! editableImage('cert_2', './image/icon/certificaciones.png', 'Certificación 2', 'certificaciones', 'cert-carousel-img') !!}
                </div>
                <div class="cert-carousel-slide">
                    {!! editableImage('cert_3', './image/icon/certificaciones.png', 'Certificación 3', 'certificaciones', 'cert-carousel-img') !!}
                </div>
                <div class="cert-carousel-slide">
                    {!! editableImage('cert_4', './image/icon/certificaciones.png', 'Certificación 4', 'certificaciones', 'cert-carousel-img') !!}
        </div>
                    </div>
            
            <button class="cert-carousel-btn next" onclick="nextCert()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <div class="cert-carousel-dots" id="cert-dots"></div>
        </div>
    </section>

<script>
let currentCertIndex = 0;
const certSlides = document.querySelectorAll('.cert-carousel-slide');
const certDotsContainer = document.getElementById('cert-dots');

// Crear dots
certSlides.forEach((_, index) => {
    const dot = document.createElement('span');
    dot.classList.add('cert-dot');
    if (index === 0) dot.classList.add('active');
    dot.onclick = () => goToCert(index);
    certDotsContainer.appendChild(dot);
});

const certDots = document.querySelectorAll('.cert-dot');

function showCert(index) {
    certSlides.forEach(slide => slide.classList.remove('active'));
    certDots.forEach(dot => dot.classList.remove('active'));
    
    currentCertIndex = (index + certSlides.length) % certSlides.length;
    
    certSlides[currentCertIndex].classList.add('active');
    certDots[currentCertIndex].classList.add('active');
}

function nextCert() {
    showCert(currentCertIndex + 1);
}

function prevCert() {
    showCert(currentCertIndex - 1);
}

function goToCert(index) {
    showCert(index);
}

// Auto-play
setInterval(nextCert, 5000);
</script>
@endsection

<!-- MERCADOS -->
@section('mercados')
<section class="markets-fruteec" id="mercados">
    <h2 class="section-title-fruteec">{{ __('messages.markets_title_main') }}</h2>
    <p class="markets-subtitle">{!! editableContent('markets_subtitle', 'mercados', __('messages.markets_subtitle'), 'textarea') !!}</p>
    <div class="markets-content">
        <div id="world-map-interactive"></div>
        <div class="map-legend">
            <div class="legend-item">
                <span class="legend-icon route-line"></span>
                <span>{{ __('messages.export_routes') ?? 'Rutas de Exportación' }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-icon origin-point"></span>
                <span>{{ __('messages.origin_chile') ?? 'Chile (Origen)' }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-icon destination-point"></span>
                <span>{{ __('messages.destination_markets') ?? 'Mercados de Destino' }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Scripts para el mapa interactivo -->
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://d3js.org/topojson.v3.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- 1. Configuración del Mapa ---
    const container = document.getElementById('world-map-interactive');
    const containerWidth = container.offsetWidth;
    const width = Math.min(containerWidth, 1200);
    const height = Math.min(width * 0.6, 600);

    // Creamos el SVG
    const svg = d3.select("#world-map-interactive").append("svg")
        .attr("width", "100%")
        .attr("height", height)
        .attr("viewBox", `0 0 ${width} ${height}`)
        .attr("preserveAspectRatio", "xMidYMid meet");

    // Agregar océano azul de fondo
    svg.append("rect")
        .attr("width", width)
        .attr("height", height)
        .attr("fill", "#4A90E2")
        .attr("class", "ocean-background");

    // Definir patrón de la bandera chilena
    const defs = svg.append("defs");
    
    // Patrón de la bandera de Chile
    const banderaPattern = defs.append("pattern")
        .attr("id", "bandera-chile")
        .attr("patternUnits", "objectBoundingBox")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", 1)
        .attr("height", 1)
        .attr("viewBox", "0 0 100 100");
    
    // Mitad inferior roja (50% inferior)
    banderaPattern.append("rect")
        .attr("x", 0)
        .attr("y", 50)
        .attr("width", 100)
        .attr("height", 50)
        .attr("fill", "#5F1A37");
    
    // Cuadrante superior izquierdo azul (33.33% izquierdo, 50% superior)
    banderaPattern.append("rect")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", 33.33)
        .attr("height", 50)
        .attr("fill", "#0039A6");
    
    // Estrella blanca en el cuadrante azul
    const starPoints = "16.67,12.5 18.5,18.5 24.5,18.5 19.8,22 21.5,28 16.67,24.5 11.83,28 13.5,22 8.8,18.5 14.8,18.5";
    banderaPattern.append("polygon")
        .attr("points", starPoints)
        .attr("fill", "white");
    
    // Parte superior derecha blanca (66.67% derecho, 50% superior)
    banderaPattern.append("rect")
        .attr("x", 33.33)
        .attr("y", 0)
        .attr("width", 66.67)
        .attr("height", 50)
        .attr("fill", "white");

    // Proyección del mapa
    const projection = d3.geoMercator()
        .scale(width / 6.5)
        .translate([width / 2, height / 1.4]);

    const path = d3.geoPath().projection(projection);

    // --- 2. Coordenadas de Rutas (Chile a múltiples países) ---
    const chileCoords = [-70.6693, -33.4489]; // Santiago, Chile
    
    const destinos = [
        { nombre: "Germany", coords: [10.4515, 51.1657], continente: "Europa" },
        { nombre: "Poland", coords: [19.1451, 51.9194], continente: "Europa" },
        { nombre: "United Kingdom", coords: [-0.1276, 51.5074], continente: "Europa" },
        { nombre: "France", coords: [2.3522, 48.8566], continente: "Europa" },
        { nombre: "Spain", coords: [-3.7038, 40.4168], continente: "Europa" },
        { nombre: "Netherlands", coords: [5.2913, 52.1326], continente: "Europa" },
        { nombre: "Italy", coords: [12.4964, 41.9028], continente: "Europa" },
        { nombre: "Croatia", coords: [15.9819, 45.1], continente: "Europa" },
        { nombre: "Turkey", coords: [35.2433, 38.9637], continente: "Europa/Asia" },
        { nombre: "Lithuania", coords: [23.8813, 55.1694], continente: "Europa" },
        { nombre: "China", coords: [104.1954, 35.8617], continente: "Asia" },
        { nombre: "Mexico", coords: [-102.5528, 23.6345], continente: "América" },
        { nombre: "Brazil", coords: [-51.9253, -14.2350], continente: "América del Sur" },
        { nombre: "Morocco", coords: [-7.0926, 31.7917], continente: "África" }
    ];
    
    let currentRouteIndex = 0;
    let animatingRoutes = [];

    // --- 3. Cargar y Dibujar el Mapa ---
    d3.json("https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json")
        .then(worldData => {
            const countries = topojson.feature(worldData, worldData.objects.countries);

            // Dibujamos los países
            svg.selectAll(".country")
                .data(countries.features)
                .enter().append("path")
                .attr("class", "country-map")
                .attr("id", d => d.properties.name ? d.properties.name.replace(/\s+/g, '-') : '')
                .attr("d", path);

            // Marcar Chile como origen
            svg.select("#Chile")
                .attr("class", "country-map country-origin");

            // --- 4. Función para Animar un Barco ---
            function animarBarco(destinoIndex) {
                const destino = destinos[destinoIndex];
                const rutaGeoJSON = {
                    type: "LineString",
                    coordinates: [chileCoords, destino.coords]
                };

                // Dibujamos la ruta (si no existe ya)
                let rutaPath = svg.select(`.ruta-${destinoIndex}`);
                if (rutaPath.empty()) {
                    rutaPath = svg.append("path")
                        .datum(rutaGeoJSON)
                        .attr("class", `ruta-barco ruta-${destinoIndex}`)
                        .attr("d", path)
                        .style("opacity", 0.3);
                }

                // Dibujamos el "barco"
                const barco = svg.append("circle")
                    .attr("class", "barco-animado")
                    .attr("r", 5)
                    .attr("transform", "translate(" + projection(chileCoords) + ")")
                    .style("opacity", 0);

                // Obtenemos la longitud de la ruta
                const totalLength = rutaPath.node().getTotalLength();

                // Animamos el barco
                barco.transition()
                    .duration(200)
                    .style("opacity", 1)
                    .transition()
                    .duration(4000)
                    .ease(d3.easeLinear)
                    .attrTween("transform", function() {
                        return function(t) {
                            const point = rutaPath.node().getPointAtLength(t * totalLength);
                            return "translate(" + point.x + "," + point.y + ")";
                        }
                    })
                    .on("end", function() {
                        // Al llegar, pintamos el país con la bandera chilena
                        const paisId = "#" + destino.nombre.replace(/\s+/g, '-');
                        const paisElement = svg.select(paisId);
                        
                        if (!paisElement.empty() && !paisElement.classed("country-destination-flag")) {
                            // Primero un flash blanco
                            paisElement
                                .transition()
                                .duration(200)
                                .style("fill", "white")
                                .transition()
                                .duration(300)
                                .style("fill", "url(#bandera-chile)")
                                .attr("class", "country-map country-destination-flag");
                            
                            // Agregar efecto de onda expansiva
                            const coordsDestino = projection(destino.coords);
                            svg.append("circle")
                                .attr("cx", coordsDestino[0])
                                .attr("cy", coordsDestino[1])
                                .attr("r", 5)
                                .attr("fill", "none")
                                .attr("stroke", "#5F1A37")
                                .attr("stroke-width", 3)
                                .transition()
                                .duration(1000)
                                .attr("r", 40)
                                .attr("stroke-width", 0.5)
                                .style("opacity", 0)
                                .remove();
                        }
                        
                        // Desaparecemos el barco
                        d3.select(this).transition().duration(500).style("opacity", 0).remove();
                    });
            }

            // --- 5. Animar Barcos de Forma Continua ---
            function enviarSiguienteBarco() {
                animarBarco(currentRouteIndex);
                currentRouteIndex = (currentRouteIndex + 1) % destinos.length;
                
                // Enviar el siguiente barco después de 2.5 segundos
                setTimeout(enviarSiguienteBarco, 2500);
            }
            
            // Iniciar la animación continua después de 1 segundo
            setTimeout(enviarSiguienteBarco, 1000);

            // Punto de origen (Chile) - círculo permanente
            svg.append("circle")
                .attr("class", "punto-origen")
                .attr("cx", projection(chileCoords)[0])
                .attr("cy", projection(chileCoords)[1])
                .attr("r", 6);

        }).catch(error => {
            console.error("Error al cargar el mapa:", error);
            // Fallback: mostrar imagen estática
            d3.select("#world-map-interactive")
                .html('<img src="/image/Mapa-Onizzo.png" alt="Mapa Mundial" style="width:100%; max-width: 1200px; height: auto;">');
        });
});
</script>
@endsection

<!-- CONTACTO -->
@section('contacto')
<section class="contact-fruteec" id="contacto">
    <h2 class="section-title-fruteec">{{ __('messages.contact_title') }}</h2>
    <p class="contact-subtitle">{{ __('messages.contact_subtitle') }}</p>
    
    <div class="contact-team-grid">
        <!-- Persona 1: Marcelo Espinoza -->
        <div class="contact-person-card">
            <div class="contact-icon">
                <i class="fas fa-industry"></i>
            </div>
            <h3>{{ __('messages.contact_person1_name') }}</h3>
            <p class="contact-role">{{ __('messages.contact_person1_role') }}</p>
            <div class="contact-info-item">
                <a href="mailto:{{ __('messages.contact_person1_email') }}" class="contact-email-link">
                    <i class="fas fa-envelope"></i>
                    {{ __('messages.contact_person1_email') }}
                </a>
            </div>
        </div>
        
        <!-- Persona 2: Luis González Ojeda -->
        <div class="contact-person-card contact-highlight">
            <div class="contact-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3>{{ __('messages.contact_person2_name') }}</h3>
            <p class="contact-role">{{ __('messages.contact_person2_role') }}</p>
            <div class="contact-info-item">
                <a href="mailto:{{ __('messages.contact_person2_email') }}" class="contact-email-link">
                    <i class="fas fa-envelope"></i>
                    {{ __('messages.contact_person2_email') }}
                </a>
            </div>
        </div>
        
        <!-- Persona 3: Enrique González -->
        <div class="contact-person-card">
            <div class="contact-icon">
                <i class="fas fa-ship"></i>
            </div>
            <h3>{{ __('messages.contact_person3_name') }}</h3>
            <p class="contact-role">{{ __('messages.contact_person3_role') }}</p>
            <div class="contact-info-item">
                <a href="mailto:{{ __('messages.contact_person3_email') }}" class="contact-email-link">
                    <i class="fas fa-envelope"></i>
                    {{ __('messages.contact_person3_email') }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Información general de contacto -->
    <div class="contact-general-info">
        <div class="general-info-item">
            <i class="fas fa-envelope-open-text"></i>
            <div>
                <strong>Email General:</strong>
                <a href="mailto:{{ __('messages.contact_email_general') }}">{{ __('messages.contact_email_general') }}</a>
                <span class="availability">{{ __('messages.contact_availability_email') }}</span>
            </div>
        </div>
        <div class="general-info-item">
            <i class="fas fa-phone-alt"></i>
            <div>
                <strong>Teléfono:</strong>
                <a href="tel:{{ __('messages.contact_phone_general') }}">{{ __('messages.contact_phone_general') }}</a>
                <span class="availability">{{ __('messages.contact_timezone') }}</span>
            </div>
        </div>
    </div>
            
        <div class="contact-form-section">
            <h3>{!! editableContent('contact_form_title', 'contacto', 'CONTÁCTANOS', 'text') !!}</h3>
            <form class="contact-form-fruteec" action="https://formspree.io/f/xnnaekdr" method="POST" id="contactForm" novalidate>
                <div class="form-group">
                    <input type="text" 
                           name="empresa" 
                           id="empresa" 
                           placeholder="{{ __('messages.form_company') }}" 
                           required 
                           minlength="2"
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                           title="{{ __('messages.form_company') }}">
                    <span class="error-message"></span>
                </div>
                
                <div class="form-group">
                    <input type="text" 
                           name="pais" 
                           id="pais" 
                           list="paises" 
                           placeholder="{{ __('messages.form_country') }}" 
                           required
                           autocomplete="country">
                    <datalist id="paises">
                        <option value="Chile">
                        <option value="Argentina">
                        <option value="Brasil">
                        <option value="China">
                        <option value="Alemania">
                        <option value="España">
                        <option value="Estados Unidos">
                        <option value="Francia">
                        <option value="Italia">
                        <option value="Reino Unido">
                        <option value="Japón">
                        <option value="Corea del Sur">
                        <option value="México">
                        <option value="Perú">
                        <option value="Colombia">
                        <option value="Canadá">
                        <option value="Australia">
                        <option value="Nueva Zelanda">
                        <option value="Países Bajos">
                        <option value="Bélgica">
                    </datalist>
                    <span class="error-message"></span>
                </div>
                
                <div class="form-group">
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           placeholder="{{ __('messages.form_name') }}" 
                           required 
                           minlength="2"
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                           autocomplete="name"
                           title="{{ __('messages.form_name') }}">
                    <span class="error-message"></span>
                </div>
                
                <div class="form-group">
                    <input type="email" 
                           name="email" 
                           id="email" 
                           placeholder="{{ __('messages.form_email') }}" 
                           required
                           autocomplete="email"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                           title="ejemplo@dominio.com">
                    <span class="error-message"></span>
                </div>
                
                <div class="form-group">
                    <textarea name="mensaje" 
                              id="mensaje" 
                              placeholder="{{ __('messages.form_message') }}" 
                              rows="6" 
                              required
                              minlength="10"
                              maxlength="1000"></textarea>
                    <span class="error-message"></span>
                    <span class="char-counter">0/1000</span>
                </div>
                
                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="6LfYourSiteKeyHere"></div>
                </div>
                
                <button type="submit" class="btn-submit-fruteec" id="submitBtn">
                    <span class="btn-text">{{ __('messages.form_submit') }}</span>
                    <span class="btn-ship">🚢</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Script de Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const inputs = form.querySelectorAll('input, textarea');
    
    // Contador de caracteres para el textarea
    const mensajeField = document.getElementById('mensaje');
    const charCounter = document.querySelector('.char-counter');
    
    mensajeField.addEventListener('input', function() {
        charCounter.textContent = this.value.length + '/1000';
        if (this.value.length > 900) {
            charCounter.style.color = '#e74c3c';
        } else {
            charCounter.style.color = '#7f8c8d';
        }
    });
    
    // Validación en tiempo real para cada campo
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('invalid')) {
                validateField(this);
            }
        });
    });
    
    // Función de validación
    function validateField(field) {
        const formGroup = field.closest('.form-group');
        const errorMessage = formGroup.querySelector('.error-message');
        const lang = document.documentElement.lang || 'es';
        
        // Limpiar errores previos
        field.classList.remove('invalid', 'valid');
        errorMessage.textContent = '';
        
        // Validar si está vacío
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('invalid');
            errorMessage.textContent = lang === 'es' ? 'Este campo es obligatorio' : 
                                       (lang === 'en' ? 'This field is required' : '此字段为必填项');
            return false;
        }
        
        // Validar email
        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                field.classList.add('invalid');
                errorMessage.textContent = lang === 'es' ? 'Email inválido (ejemplo: nombre@dominio.com)' : 
                                          (lang === 'en' ? 'Invalid email (example: name@domain.com)' : '无效的电子邮件');
                return false;
            }
        }
        
        // Validar longitud mínima
        if (field.hasAttribute('minlength')) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (field.value.length < minLength) {
                field.classList.add('invalid');
                errorMessage.textContent = lang === 'es' ? `Mínimo ${minLength} caracteres` : 
                                          (lang === 'en' ? `Minimum ${minLength} characters` : `最少${minLength}个字符`);
                return false;
            }
        }
        
        // Validar patrón (solo letras para nombre y empresa)
        if (field.hasAttribute('pattern') && field.id !== 'email') {
            const pattern = new RegExp(field.getAttribute('pattern'));
            if (!pattern.test(field.value)) {
                field.classList.add('invalid');
                errorMessage.textContent = lang === 'es' ? 'Solo se permiten letras' : 
                                          (lang === 'en' ? 'Only letters allowed' : '仅允许字母');
                return false;
            }
        }
        
        // Campo válido
        field.classList.add('valid');
        return true;
    }
    
    // Validar formulario completo antes de enviar
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            const lang = document.documentElement.lang || 'es';
            alert(lang === 'es' ? 'Por favor, corrija los errores en el formulario' : 
                  (lang === 'en' ? 'Please correct the errors in the form' : '请更正表单中的错误'));
            return;
        }
        
        // Validar captcha
        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            const lang = document.documentElement.lang || 'es';
            alert(lang === 'es' ? 'Por favor, complete el captcha' : 
                  (lang === 'en' ? 'Please complete the captcha' : '请完成验证码'));
            return;
        }
        
        // Animación del barco zarpando
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnShip = submitBtn.querySelector('.btn-ship');
    
    // Deshabilitar el botón para evitar múltiples envíos
    submitBtn.disabled = true;
    
    // Ocultar el texto y mostrar el barco
    btnText.style.opacity = '0';
    setTimeout(() => {
        btnText.style.display = 'none';
        btnShip.style.display = 'block';
        submitBtn.classList.add('ship-sailing');
        
        // Crear olas
        const wave1 = document.createElement('span');
        wave1.className = 'wave wave-1';
        wave1.textContent = '〰️';
        submitBtn.appendChild(wave1);
        
        const wave2 = document.createElement('span');
        wave2.className = 'wave wave-2';
        wave2.textContent = '〰️';
        submitBtn.appendChild(wave2);
        
    }, 300);
    
    // Mensaje de éxito después de que el barco "zarpe"
    setTimeout(() => {
        const lang = document.documentElement.lang || '{{ app()->getLocale() }}';
        let successMessage = '¡Mensaje enviado exitosamente! 🚢';
        
        if (lang === 'en') {
            successMessage = 'Message sent successfully! 🚢';
        } else if (lang === 'zh') {
            successMessage = '消息发送成功！🚢';
        }
        
        btnShip.style.opacity = '0';
        submitBtn.innerHTML = '<span style="font-size: 14px;">' + successMessage + '</span>';
        submitBtn.classList.remove('ship-sailing');
        submitBtn.classList.add('success-sent');
        
        // Restaurar el botón después de 3 segundos
        setTimeout(() => {
            submitBtn.innerHTML = '<span class="btn-text">{{ __("messages.form_submit") }}</span><span class="btn-ship">🚢</span>';
            submitBtn.classList.remove('success-sent');
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').style.display = 'block';
            submitBtn.querySelector('.btn-text').style.opacity = '1';
            submitBtn.querySelector('.btn-ship').style.display = 'none';
        }, 3000);
        
    }, 2500);
});
</script>

@endsection

@extends('essencials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carrusel automático
    const slides = document.querySelectorAll('.carousel-slide');
    let currentSlide = 0;

    function showSlide(n) {
        slides.forEach(slide => slide.classList.remove('active'));
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    if (slides.length > 1) {
        setInterval(nextSlide, 3000);
    }
    });
</script>

@push('scripts')
@if(session('admin_authenticated'))
<script>
function toggleEditMode() {
    const banner = document.getElementById('visual-edit-banner');
    const spacer = banner.nextElementSibling;
    
    if (banner.style.display === 'none') {
        banner.style.display = 'block';
        spacer.style.display = 'block';
    } else {
        banner.style.display = 'none';
        spacer.style.display = 'none';
    }
}
</script>
@endif
@endpush
