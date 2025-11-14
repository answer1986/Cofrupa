@section('footer')
<footer class="footer-fruteec">
    <div class="footer-container-fruteec">
        <div class="footer-top">
            <div class="footer-logo-section">
                <img src="{{ asset('image/LOGO-sin fonfopng.png') }}" alt="Cofrupa Logo" class="footer-logo-fruteec">
            </div>
            
            <div class="footer-links">
                <h4>{{ __('messages.our_company') }}</h4>
                <ul>
                    <li><a href="#nosotros">{{ __('messages.nosotros') }}</a></li>
                    <li><a href="#productos">{{ __('messages.productos') }}</a></li>
                    <li><a href="#mercados">{{ __('messages.mercados') }}</a></li>
                </ul>
            </div>
            
            <div class="footer-contact">
                <h4>{{ __('messages.contact') }}</h4>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{!! strip_tags(editableContent('footer_email', 'footer', 'cofrupa@cofrupa.cl', 'text')) !!}">
                        {!! editableContent('footer_email', 'footer', 'cofrupa@cofrupa.cl', 'text') !!}
                    </a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:+56992395293">
                        {!! editableContent('footer_phone', 'footer', '(+56 9) 9 239 5293', 'text') !!}
                    </a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{!! editableContent('footer_address', 'footer', 'Santiago, Chile', 'text') !!}</span>
                </div>
            </div>
            
            <div class="footer-certifications">
                <h4>{{ __('messages.footer_cert_title') }}</h4>
                <div class="footer-cert-images-container">
                    <div class="footer-cert-images">
                        {!! editableImage('footer_cert_image_1', './image/icon/certificaciones.png', 'Certificación BRC', 'footer', '', 'max-height: 80px;') !!}
                    </div>
                    <div class="footer-cert-images">
                        {!! editableImage('footer_cert_image_2', './image/icon/certificaciones.png', 'Certificación FDA', 'footer', '', 'max-height: 80px;') !!}
                    </div>
                </div>
                
                <!-- Reloj de Chile -->
                <div class="footer-chile-clock">
                    <h5>{{ __('messages.chile_time') }}</h5>
                    <div class="clock-display" id="chileClockDisplay">
                        <i class="fas fa-clock"></i>
                        <span id="chileTime">--:--:--</span>
                    </div>
                    <p class="clock-location">{{ __('messages.santiago_chile') }}</p>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Cofrupa. Todos los derechos reservados.</p>
            <p>
                Desarrollo hecho por <a href="https://r3q.cl" target="_blank" rel="noopener noreferrer" class="r3q-neon">R3Q</a>
            </p>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reloj de Chile en tiempo real
    function updateChileClock() {
    const now = new Date();
    
    // Obtener hora de Chile (America/Santiago)
    const chileTime = new Date(now.toLocaleString('en-US', { 
        timeZone: 'America/Santiago' 
    }));
    
    // Obtener el idioma actual de la página
    const currentLang = document.documentElement.lang || '{{ app()->getLocale() }}';
    
    let formattedTime;
    
    // Formatear según el idioma
    switch(currentLang) {
        case 'en':
            // Formato inglés: 12:30:45 PM
            formattedTime = chileTime.toLocaleString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            break;
        case 'zh':
            // Formato chino: 12:30:45
            formattedTime = chileTime.toLocaleString('zh-CN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            break;
        default: // 'es'
            // Formato español: 12:30:45
            formattedTime = chileTime.toLocaleString('es-CL', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            break;
    }
    
    document.getElementById('chileTime').textContent = formattedTime;
}

    // Actualizar el reloj inmediatamente
    updateChileClock();

    // Actualizar cada segundo
    setInterval(updateChileClock, 1000);
});
</script>

@endsection
