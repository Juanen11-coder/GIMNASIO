@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="min-h-screen">

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#0a0a0a] to-[#1a1a1a] pt-20 pb-32">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-[#00E676]/10 px-4 py-2 rounded-full mb-6">
                    <span class="w-2 h-2 bg-[#00E676] rounded-full animate-pulse"></span>
                    <span class="text-[#00E676] text-sm font-semibold">#1 EN ESPAÑA</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                    TRANSFORMA<br>
                    <span class="text-[#00E676]">TU CUERPO</span>
                </h1>
                <p class="text-gray-400 text-lg mb-8 max-w-2xl mx-auto">
                    Entrena sin límites. Conecta con amigos. Supera tus marcas. La comunidad fitness más grande de España te espera.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register.show') }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-8 py-4 rounded-xl transition transform hover:scale-105 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-dumbbell"></i> Empieza gratis
                    </a>
                    <a href="{{ route('activities.index') }}" class="border border-[#00E676] text-[#00E676] hover:bg-[#00E676] hover:text-black font-bold px-8 py-4 rounded-xl transition inline-flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-alt"></i> Ver actividades
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-[#00E676] rounded-full blur-[100px]"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#00E676] rounded-full blur-[120px]"></div>
        </div>
    </section>

    {{-- ESTADÍSTICAS --}}
    <section class="bg-[#0a0a0a] py-12 border-y border-[#2A2A2A]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-black text-[#00E676]">500+</div>
                    <p class="text-gray-500 text-sm mt-1">Miembros activos</p>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-[#00E676]">12</div>
                    <p class="text-gray-500 text-sm mt-1">Entrenadores expertos</p>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-[#00E676]">20+</div>
                    <p class="text-gray-500 text-sm mt-1">Clases semanales</p>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-[#00E676]">24/7</div>
                    <p class="text-gray-500 text-sm mt-1">Acceso al gimnasio</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICIOS DESTACADOS --}}
    <section class="py-20 bg-[#121212]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">¿POR QUÉ ELEGIRNOS?</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Todo lo que necesitas para alcanzar tus objetivos fitness</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#1E1E1E] rounded-2xl p-6 text-center border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="w-16 h-16 bg-[#00E676]/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-dumbbell text-3xl text-[#00E676]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Equipamiento premium</h3>
                    <p class="text-gray-400">Máquinas de última generación para todos los niveles</p>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl p-6 text-center border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="w-16 h-16 bg-[#00E676]/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-chalkboard-user text-3xl text-[#00E676]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Clases grupales</h3>
                    <p class="text-gray-400">Yoga, Spinning, CrossFit, y muchas más</p>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl p-6 text-center border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="w-16 h-16 bg-[#00E676]/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-users text-3xl text-[#00E676]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Comunidad activa</h3>
                    <p class="text-gray-400">Comparte tus logros y compite con amigos</p>
                </div>
            </div>
        </div>
    </section>

    {{-- PRÓXIMAS ACTIVIDADES --}}
    <section class="py-20 bg-[#0a0a0a]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">PRÓXIMAS ACTIVIDADES</h2>
                    <p class="text-gray-400">No te pierdas las clases más populares de la semana</p>
                </div>
                <a href="{{ route('activities.index') }}" class="text-[#00E676] hover:text-[#00c853] font-semibold">
                    Ver todas →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#1E1E1E] rounded-2xl overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="h-32 bg-gradient-to-r from-[#00E676] to-[#00c853] relative">
                        <i class="fas fa-running absolute bottom-3 right-3 text-white text-4xl opacity-30"></i>
                    </div>
                    <div class="p-5">
                        <div class="text-[#00E676] text-sm font-semibold mb-2">🔥 Alta intensidad</div>
                        <h3 class="text-xl font-bold text-white mb-2">CrossFit</h3>
                        <p class="text-gray-400 text-sm mb-3">Lunes y Miércoles | 19:00 - 20:00</p>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-sm"><i class="fas fa-user mr-1"></i> 12/20 plazas</span>
                            <a href="{{ route('activities.index') }}" class="text-[#00E676] text-sm font-semibold hover:underline">Apuntarse →</a>
                        </div>
                    </div>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="h-32 bg-gradient-to-r from-[#00E676] to-[#00c853] relative">
                        <i class="fas fa-bicycle absolute bottom-3 right-3 text-white text-4xl opacity-30"></i>
                    </div>
                    <div class="p-5">
                        <div class="text-[#00E676] text-sm font-semibold mb-2">🚴 Cardio</div>
                        <h3 class="text-xl font-bold text-white mb-2">Spinning</h3>
                        <p class="text-gray-400 text-sm mb-3">Martes y Jueves | 18:00 - 19:00</p>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-sm"><i class="fas fa-user mr-1"></i> 8/15 plazas</span>
                            <a href="{{ route('activities.index') }}" class="text-[#00E676] text-sm font-semibold hover:underline">Apuntarse →</a>
                        </div>
                    </div>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                    <div class="h-32 bg-gradient-to-r from-[#00E676] to-[#00c853] relative">
                        <i class="fas fa-hand-peace absolute bottom-3 right-3 text-white text-4xl opacity-30"></i>
                    </div>
                    <div class="p-5">
                        <div class="text-[#00E676] text-sm font-semibold mb-2">🧘 Relajación</div>
                        <h3 class="text-xl font-bold text-white mb-2">Yoga</h3>
                        <p class="text-gray-400 text-sm mb-3">Viernes y Sábado | 09:00 - 10:30</p>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-sm"><i class="fas fa-user mr-1"></i> 5/20 plazas</span>
                            <a href="{{ route('activities.index') }}" class="text-[#00E676] text-sm font-semibold hover:underline">Apuntarse →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIOS --}}
    <section class="py-20 bg-[#121212]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">LO QUE DICEN NUESTROS <span class="text-[#00E676]">SOCIOS</span></h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Miles de personas ya confían en nosotros</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#1E1E1E] rounded-2xl p-6 border border-[#2A2A2A]">
                    <div class="flex text-[#00E676] mb-3">★★★★★</div>
                    <p class="text-gray-300 mb-4">"El mejor gimnasio al que he ido. El ambiente es increíble y los entrenadores son super profesionales."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#00E676]/30 flex items-center justify-center text-[#00E676] font-bold">M</div>
                        <div>
                            <p class="text-white font-semibold">María G.</p>
                            <p class="text-gray-500 text-xs">Socia desde 2024</p>
                        </div>
                    </div>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl p-6 border border-[#2A2A2A]">
                    <div class="flex text-[#00E676] mb-3">★★★★★</div>
                    <p class="text-gray-300 mb-4">"La app está genial para seguir mi progreso y compartir entrenamientos con amigos."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#00E676]/30 flex items-center justify-center text-[#00E676] font-bold">C</div>
                        <div>
                            <p class="text-white font-semibold">Carlos R.</p>
                            <p class="text-gray-500 text-xs">Socio desde 2023</p>
                        </div>
                    </div>
                </div>
                <div class="bg-[#1E1E1E] rounded-2xl p-6 border border-[#2A2A2A]">
                    <div class="flex text-[#00E676] mb-3">★★★★★</div>
                    <p class="text-gray-300 mb-4">"Las clases de spinning son espectaculares. Volvería a apuntarme mil veces."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#00E676]/30 flex items-center justify-center text-[#00E676] font-bold">A</div>
                        <div>
                            <p class="text-white font-semibold">Ana L.</p>
                            <p class="text-gray-500 text-xs">Socia desde 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-20 bg-gradient-to-r from-[#00E676] to-[#00c853]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-5xl font-black text-black mb-4">¿LISTO PARA EMPEZAR?</h2>
            <p class="text-black/80 text-lg mb-8 max-w-2xl mx-auto">Únete a la mejor comunidad fitness y alcanza tus metas con nosotros</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register.show') }}" class="bg-black hover:bg-gray-900 text-white font-bold px-8 py-4 rounded-xl transition transform hover:scale-105 inline-flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i> Registrarse gratis
                </a>
                <a href="{{ route('activities.index') }}" class="bg-transparent border-2 border-black text-black hover:bg-black hover:text-white font-bold px-8 py-4 rounded-xl transition inline-flex items-center justify-center gap-2">
                    <i class="fas fa-info-circle"></i> Más información
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
