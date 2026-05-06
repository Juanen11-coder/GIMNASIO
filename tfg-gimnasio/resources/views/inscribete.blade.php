@extends('layouts.app')

@section('title', 'Inscribete')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Elige tu plan</h1>
        <p class="text-gray-400">Empieza con GYM TONIC y combina clases, rutinas y comunidad en una sola cuenta.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        @foreach([
            ['name' => 'Basic', 'price' => '19,90', 'features' => ['Acceso sala fitness', 'Rutinas personales', 'Feed social']],
            ['name' => 'Plus', 'price' => '34,90', 'features' => ['Todo Basic', 'Clases ilimitadas', 'Chat con amigos']],
            ['name' => 'Pro', 'price' => '49,90', 'features' => ['Todo Plus', 'Seguimiento premium', 'Prioridad en lista de espera']],
        ] as $plan)
            <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-white mb-2">{{ $plan['name'] }}</h2>
                <p class="text-4xl font-black text-[#00E676] mb-6">{{ $plan['price'] }}€<span class="text-sm text-gray-500">/mes</span></p>
                <ul class="space-y-3 mb-6 text-gray-300">
                    @foreach($plan['features'] as $feature)
                        <li><i class="fas fa-check text-[#00E676] mr-2"></i>{{ $feature }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('register.show') }}" class="block text-center bg-[#00E676] hover:bg-[#00c853] text-black font-bold py-3 rounded-xl transition">Inscribirme</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
