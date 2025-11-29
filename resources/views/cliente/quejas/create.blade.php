{{-- filepath: resources/views/cliente/quejas/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Queja
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('cliente.quejas.store') }}">
                    @csrf

                    <!-- Seleccionar Resolución -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Resolución *
                        </label>
                        <select name="id_resolucion" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">Seleccione una resolución</option>
                            @foreach($resoluciones as $resolucion)
                                <option value="{{ $resolucion->id_resolucion }}">
                                    {{ $resolucion->num_resolucion }} - {{ $resolucion->asunto_resolucion }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_resolucion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Queja -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Tipo de Queja *
                        </label>
                        <select name="tipo_queja" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="error_datos">Error en datos</option>
                            <option value="retraso">Retraso en trámite</option>
                            <option value="incumplimiento">Incumplimiento</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Descripción de la Queja *
                        </label>
                        <textarea name="descripcion" rows="5" required
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                  placeholder="Describa detalladamente su queja o molestia..."></textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('cliente.quejas.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Enviar Queja
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>