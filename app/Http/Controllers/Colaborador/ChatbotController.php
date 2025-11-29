<?php
// filepath: app/Http/Controllers/Colaborador/ChatbotController.php

namespace App\Http\Controllers\Colaborador;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionIa;
use App\Models\ConsultaAsistente;
use App\Models\DocumentoChunk;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:usar_asistente_ia');
    }

    /**
     * Mostrar interfaz del chatbot
     */
    public function index()
    {
        // Obtener configuración activa
        $config = ConfiguracionIa::where('i_active', true)->first();

        if (!$config) {
            return view('colaborador.chatbot.index')->with('error', '⚠️ El asistente IA no está configurado');
        }

        // Consultas recientes del usuario
        $consultasRecientes = ConsultaAsistente::where('id_usuario', Auth::id())
            ->with('resolucion')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('colaborador.chatbot.index', compact('config', 'consultasRecientes'));
    }

    /**
     * Procesar consulta del usuario
     */
    public function consultar(Request $request)
    {
        $request->validate([
            'pregunta' => 'required|string|max:1000',
            'id_resolucion' => 'nullable|exists:resolucion,id_resolucion',
            'tipo_consulta' => 'required|in:general,busqueda_resolucion,explicacion,resumen',
        ]);

        $config = ConfiguracionIa::where('i_active', true)->first();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'El asistente IA no está configurado',
            ], 503);
        }

        try {
            $tiempoInicio = microtime(true);

            // Obtener contexto según tipo de consulta
            $contexto = $this->obtenerContexto($request);

            // Generar respuesta con IA
            $respuesta = $this->generarRespuestaIA($request->pregunta, $contexto, $config);

            $tiempoFin = microtime(true);
            $tiempoMs = ($tiempoFin - $tiempoInicio) * 1000;

            // Guardar consulta
            $consulta = ConsultaAsistente::create([
                'id_usuario' => Auth::id(),
                'id_resolucion' => $request->id_resolucion,
                'tipo_consulta' => $request->tipo_consulta,
                'pregunta' => $request->pregunta,
                'respuesta' => $respuesta['respuesta'],
                'contexto_usado' => $contexto,
                'chunks_usados' => $respuesta['chunks_ids'] ?? null,
                'tiempo_respuesta_ms' => (int) $tiempoMs,
                'score_relevancia' => $respuesta['score'] ?? null,
                'modelo_ia' => $config->modelo_activo,
                'metadata_consulta' => [
                    'temperatura' => $config->temperatura,
                    'max_tokens' => $config->max_tokens,
                    'top_k' => $config->top_k,
                ],
            ]);

            return response()->json([
                'success' => true,
                'respuesta' => $respuesta['respuesta'],
                'consulta_id' => $consulta->id_consulta,
                'tiempo_ms' => $tiempoMs,
                'score' => $respuesta['score'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Error en chatbot: ' . $e->getMessage(), [
                'usuario' => Auth::id(),
                'pregunta' => $request->pregunta,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la consulta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Valorar respuesta del asistente
     */
    public function valorar(Request $request, ConsultaAsistente $consulta)
    {
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5',
        ]);

        if ($consulta->id_usuario !== Auth::id()) {
            abort(403);
        }

        $consulta->update(['valoracion' => $request->valoracion]);

        return response()->json([
            'success' => true,
            'message' => 'Valoración registrada',
        ]);
    }

    /**
     * Buscar resoluciones con IA
     */
    public function buscarResoluciones(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $config = ConfiguracionIa::where('i_active', true)->first();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Asistente IA no configurado',
            ], 503);
        }

        try {
            // Obtener chunks relevantes usando similitud
            $chunks = $this->buscarChunksSimilares($request->query, $config);

            // Agrupar por resolución
            $resoluciones = collect();
            foreach ($chunks as $chunk) {
                $resolucion = $chunk->documentoTexto->resolucion;
                if (!$resoluciones->contains('id_resolucion', $resolucion->id_resolucion)) {
                    $resoluciones->push($resolucion);
                }
            }

            return response()->json([
                'success' => true,
                'resoluciones' => $resoluciones->take(10)->values(),
                'total' => $resoluciones->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en búsqueda: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ========================================
    // MÉTODOS PRIVADOS
    // ========================================

    /**
     * Obtener contexto según tipo de consulta
     */
    private function obtenerContexto(Request $request): ?string
    {
        if ($request->tipo_consulta === 'general') {
            return null;
        }

        if ($request->id_resolucion) {
            $resolucion = Resolucion::find($request->id_resolucion);
            
            if ($resolucion && $resolucion->documentoTexto) {
                return $resolucion->documentoTexto->texto_procesado;
            }
        }

        // Buscar contexto relevante en chunks
        $config = ConfiguracionIa::where('i_active', true)->first();
        $chunks = $this->buscarChunksSimilares($request->pregunta, $config);

        return $chunks->pluck('contenido_chunk')->take(3)->implode("\n\n");
    }

    /**
     * Buscar chunks similares (simulado - en producción usar embeddings)
     */
    private function buscarChunksSimilares(string $query, ConfiguracionIa $config)
    {
        // VERSIÓN SIMPLIFICADA: búsqueda por palabras clave
        // En producción: usar cosine similarity con embeddings

        $palabras = explode(' ', strtolower($query));
        
        return DocumentoChunk::whereNotNull('contenido_chunk')
            ->where(function($q) use ($palabras) {
                foreach ($palabras as $palabra) {
                    if (strlen($palabra) > 3) {
                        $q->orWhere('contenido_chunk', 'like', "%{$palabra}%");
                    }
                }
            })
            ->with('documentoTexto.resolucion')
            ->limit($config->top_k ?? 5)
            ->get();
    }

    /**
     * Generar respuesta con IA (usando Ollama)
     */
    private function generarRespuestaIA(string $pregunta, ?string $contexto, ConfiguracionIa $config): array
    {
        $prompt = $this->construirPrompt($pregunta, $contexto);

        try {
            $response = Http::timeout(30)->post($config->url_endpoint . '/api/generate', [
                'model' => $config->modelo_activo,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => $config->temperatura,
                    'num_predict' => $config->max_tokens,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'respuesta' => $data['response'] ?? 'Sin respuesta',
                    'score' => 0.85, // Simulado
                ];
            }

            throw new \Exception('Error en API de IA: ' . $response->status());

        } catch (\Exception $e) {
            Log::error('Error llamando a IA: ' . $e->getMessage());

            // Respuesta fallback
            return [
                'respuesta' => "Lo siento, no pude procesar tu consulta en este momento. Por favor, intenta de nuevo.",
                'score' => 0.0,
            ];
        }
    }

    /**
     * Construir prompt para IA
     */
    private function construirPrompt(string $pregunta, ?string $contexto): string
    {
        $systemPrompt = <<<PROMPT
Eres un asistente especializado en resoluciones administrativas del sector educación en Perú.
Debes responder de manera precisa, concisa y profesional.
Si no tienes información suficiente, indícalo claramente.
PROMPT;

        if ($contexto) {
            return <<<PROMPT
{$systemPrompt}

CONTEXTO:
{$contexto}

PREGUNTA DEL USUARIO:
{$pregunta}

RESPUESTA:
PROMPT;
        }

        return <<<PROMPT
{$systemPrompt}

PREGUNTA DEL USUARIO:
{$pregunta}

RESPUESTA:
PROMPT;
    }
}