@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- SLIDER / CARRUSEL --}}
    <div class="swiper mySwiper mb-12 rounded-xl overflow-hidden shadow-lg">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-[400px] md:h-[500px] flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h2 class="text-3xl md:text-5xl font-bold mb-4">Entrena como un campeón</h2>
                        <p class="text-lg md:text-xl mb-6">Las mejores instalaciones de la ciudad</p>
                        <a href="{{ route('register.show') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Inscríbete ahora
                        </a>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 h-[400px] md:h-[500px] flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h2 class="text-3xl md:text-5xl font-bold mb-4">Clases grupales</h2>
                        <p class="text-lg md:text-xl mb-6">Yoga, Spinning, CrossFit y más</p>
                        <a href="{{ route('register.show') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Ver horarios
                        </a>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="bg-gradient-to-r from-orange-600 to-red-600 h-[400px] md:h-[500px] flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h2 class="text-3xl md:text-5xl font-bold mb-4">Entrenadores expertos</h2>
                        <p class="text-lg md:text-xl mb-6">Planificación personalizada para tus objetivos</p>
                        <a href="{{ route('register.show') }}" class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Conócenos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    {{-- INFORMACIÓN DEL GIMNASIO --}}
    <div class="bg-white rounded-xl shadow-md p-8 mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-8">🏆 Sobre GYM TONIC</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-4xl mb-3">🏋️</div>
                <h3 class="text-xl font-bold mb-2">Equipamiento de última generación</h3>
                <p class="text-gray-600">Más de 200 máquinas y accesorios para todos los niveles.</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">👨‍🏫</div>
                <h3 class="text-xl font-bold mb-2">Entrenadores cualificados</h3>
                <p class="text-gray-600">Equipo de profesionales a tu disposición.</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">🕒</div>
                <h3 class="text-xl font-bold mb-2">Horario ininterrumpido</h3>
                <p class="text-gray-600">Abierto de 6:00 a 23:00, 365 días al año.</p>
            </div>
        </div>
    </div>

    {{-- RESEÑAS --}}
    <div class="bg-gray-100 rounded-xl p-8 mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 mb-8">⭐ Lo que dicen nuestros socios</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6 shadow-md">
                <div class="flex text-yellow-500 mb-3">★★★★★</div>
                <p class="text-gray-700 mb-3">"Excelente gimnasio, equipamiento nuevo y entrenadores muy atentos."</p>
                <p class="text-sm text-gray-500 font-semibold">— María García</p>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-md">
                <div class="flex text-yellow-500 mb-3">★★★★★</div>
                <p class="text-gray-700 mb-3">"Las clases de spinning son increíbles. El ambiente es muy motivador."</p>
                <p class="text-sm text-gray-500 font-semibold">— Juan López</p>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-md">
                <div class="flex text-yellow-500 mb-3">★★★★☆</div>
                <p class="text-gray-700 mb-3">"Muy completo, lo único que falta es más aparcamiento."</p>
                <p class="text-sm text-gray-500 font-semibold">— Ana Martínez</p>
            </div>
        </div>
    </div>

</div>

<script>
    var swiper = new Swiper('.mySwiper', {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
@endsection
