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

        $query = Queja::with('estadoQueja')
            ->where('id_cliente', $cliente->id_cliente);

        if ($request->filled('estado')) {
            $query->where('id_estado_queja', $request->estado);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asunto_queja', 'like', "%{$search}%")
                  ->orWhere('descripcion_queja', 'like', "%{$search}%");
            });
        }

        $quejas = $query->orderBy('fecha_queja', 'desc')
            ->paginate(15)
            ->withQueryString();

        $estados = EstadoQueja::all();

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