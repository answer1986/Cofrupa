@section('header')
<header class="header-fruteec">
    <div class="header-container-fruteec">
        <div class="header-logo-fruteec">
            <a href="{{ url('/') }}">
                <img src="{{ asset('image/LOGO-sin fonfopng.png') }}" alt="Cofrupa Logo">
            </a>
        </div>
        <nav class="nav-fruteec">
            <ul>
                <li><a href="{{ url('/') }}">{{ __('messages.inicio') }}</a></li>
                <li><a href="#nosotros">{{ __('messages.nosotros') }}</a></li>
                <li><a href="#productos">{{ __('messages.productos') }}</a></li>
                <li><a href="#mercados">{{ __('messages.mercados') }}</a></li>
                <li><a href="#contacto">{{ __('messages.contacto') }}</a></li>
                <li class="language-switch">
                    <a href="{{ route('lang.switch', ['lang' => 'es']) }}" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}" title="Español">
                        <img src="{{ asset('image/icon/spain.webp') }}" alt="Español">
                    </a>
                    <a href="{{ route('lang.switch', ['lang' => 'en']) }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}" title="English">
                        <img src="{{ asset('image/icon/british.webp') }}" alt="English">
                    </a>
                    <a href="{{ route('lang.switch', ['lang' => 'zh']) }}" class="{{ app()->getLocale() == 'zh' ? 'active' : '' }}" title="中文">
                        <img src="{{ asset('image/icon/China.avif') }}" alt="中文">
                    </a>
                </li>
            </ul>
        </nav>
        <div class="mobile-menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.nav-fruteec');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }

    // Cerrar menú al hacer clic en un enlace
    const navLinks = document.querySelectorAll('.nav-fruteec a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            nav.classList.remove('active');
        });
    });
});
</script>
@endsection
