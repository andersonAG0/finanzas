<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Chats;
use App\Models\Entrie;
use App\Models\Expense;
use App\Models\History;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $chats = Chats::where('id_user', $userId)->orderBy('created_at', 'asc')->get();

        $userName = $request->user()->name;

        return view('chat.index', compact('chats', 'userName'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
    
        $userId = $request->user()->id;
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ]);
        }
    
        $age = $user->age;
        $name = $user->name;

        $monthlyIncome = Entrie::where('id_user', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $monthlyExpenses = Expense::where('id_user', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $financialHistory = History::where('id_user', $userId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get(['total_entries', 'total_expenses', 'month', 'year']);
    
        Chats::create([
            'id_user' => $userId,
            'sender' => 'usuario',
            'message' => $request->message,
        ]);
    
        $previousMessages = Chats::where('id_user', $userId)->orderBy('created_at', 'asc')->get();
        $context = '';
        foreach ($previousMessages as $msg) {
            $context .= ($msg->sender == 'usuario' ? 'Usuario: ' : 'ChatBot: ') . $msg->message . "\n";
        }

        $historyText = '';
        foreach ($financialHistory as $history) {
            $historyText .= "Mes: " . $history->month . "/" . $history->year . ", Ingresos: $" . number_format($history->total_entries, 2, ',', '.') . " COP, Gastos: $" . number_format($history->total_expenses, 2, ',', '.') . " COP\n";
        }
    
        

        $prompt = "Eres un asistente financiero experto en ayudar a las personas a administrar su dinero. 
        No debes repetir la información del usuario en tus respuestas.

        A continuación, encontrarás información sobre el usuario y el historial financiero. Usa esta información para responder la pregunta sin repetirla en la respuesta.

        INFORMACIÓN DEL USUARIO:
        Nombre: $name  
        Edad: $age años  
        País: Colombia  
        Moneda: COP (Peso Colombiano)  
        Ingresos mensuales: " . number_format($monthlyIncome, 2, ',', '.') . " COP  
        Gastos mensuales: " . number_format($monthlyExpenses, 2, ',', '.') . " COP  .  
        \nPregunta: " . $request->message . "\nRespuesta:";

    
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
            ])->timeout(120)->post('https://api-inference.huggingface.co/models/tiiuae/falcon-7b-instruct', [
                'inputs' => $prompt,
                'parameters' => [
                    'max_length' => 100,
                    'temperature' => 0.7,
                    'top_p' => 0.9
                ]
            ]);
    
            if ($response->failed()) {
                return response()->json([
                    'message' => 'Hubo un error al procesar tu solicitud. Intenta nuevamente más tarde.',
                ]);
            }
    
            $data = $response->json();
    
            if (isset($data[0]['generated_text'])) {
                $generated_text = $data[0]['generated_text'];
                $response_start = strpos($generated_text, "Respuesta:");
                if ($response_start !== false) {
                    $generated_text = substr($generated_text, $response_start + 10);
                }
                $response_end = strpos($generated_text, "Pregunta:");
                if ($response_end !== false) {
                    $generated_text = substr($generated_text, 0, $response_end);
                }
    
                // Guardar el mensaje del asistente en la base de datos
                Chats::create([
                    'id_user' => $userId,
                    'sender' => 'asistente',
                    'message' => trim($generated_text) ?: 'Lo siento, no tengo una respuesta en este momento.',
                ]);
    
                return response()->json([
                    'message' => trim($generated_text) ?: 'Lo siento, no tengo una respuesta en este momento.',
                ]);
            }
    
            return response()->json([
                'message' => 'Lo siento, no pude generar una respuesta adecuada.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al procesar la solicitud: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al procesar tu solicitud. Intenta nuevamente más tarde.',
            ]);
        }
    }
}