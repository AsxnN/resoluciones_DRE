<?php
// filepath: app/Http/Controllers/Cliente/QuejaController.php

namespace app\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\EstadoQueja;
use App\Models\Queja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuejaController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            abort(403);
        }

        $query = Queja::where('id_cliente', $cliente->id_cliente);

        // Filtro por estado (es un ENUM, no FK)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);  // ← Cambiar id_estado_queja por estado
        }

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('descripcion', 'like', "%{$search}%");  // ← Cambiar a descripcion (campo real)
        }

        $quejas = $query->orderBy('fecha_creacion', 'desc')  // ← Cambiar fecha_queja por fecha_creacion
            ->paginate(15)
            ->withQueryString();

        // Los estados son valores ENUM, no una tabla
        $estados = [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'resuelta' => 'Resuelta',
            'rechazada' => 'Rechazada'
        ];

        return view('cliente.quejas.index', compact('quejas', 'estados'));
    }

    public function create()
    {
        return view('cliente.quejas.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        if (!$cliente) {
            abort(403);
        }

        $validated = $request->validate([
            'asunto_queja' => 'required|string|max:255',
            'descripcion_queja' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $estadoPendiente = EstadoQueja::where('nombre_estado', 'Pendiente')->first();

            $validated['id_cliente'] = $cliente->id_cliente;
            $validated['id_estado_queja'] = $estadoPendiente?->id_estado_queja;
            $validated['fecha_queja'] = now();

            Queja::create($validated);

            DB::commit();

            return redirect()->route('cliente.quejas.index')
                ->with('success', '✅ Queja registrada exitosamente. Será atendida a la brevedad.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al registrar queja: ' . $e->getMessage());
        }
    }
}