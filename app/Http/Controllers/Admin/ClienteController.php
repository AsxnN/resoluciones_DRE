<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Persona::with('user')
            ->where('tipo_persona', 'cliente')
            ->orderBy('apellido_paterno');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                  ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                  ->orWhere('num_documento', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('i_active', $request->estado === 'activo');
        }

        if ($request->filled('tiene_cuenta')) {
            if ($request->tiene_cuenta === 'si') {
                $query->whereHas('user');
            } else {
                $query->whereDoesntHave('user');
            }
        }

        $clientes = $query->paginate(20)->withQueryString();

        $totales = [
            'total'       => Persona::where('tipo_persona', 'cliente')->count(),
            'activos'     => Persona::where('tipo_persona', 'cliente')->where('i_active', true)->count(),
            'con_cuenta'  => Persona::where('tipo_persona', 'cliente')->whereHas('user')->count(),
            'sin_cuenta'  => Persona::where('tipo_persona', 'cliente')->whereDoesntHave('user')->count(),
        ];

        return view('admin.clientes.index', compact('clientes', 'totales'));
    }
}
