<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function store(Request $request)
    {

        $participant = auth()->user();

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        $userAgent = $request->header('User-Agent');
        if (!$userAgent || preg_match('/bot|crawl|spider/i', $userAgent)) {
            return response()->json(['message' => 'Bot detectado.'], 403);
        }

        $origin = $request->header('Origin');
        $referer = $request->header('Referer');
        if (!str_contains($origin, 'localhost:5173') && !str_contains($referer, 'localhost:5173')) {
            return response()->json(['message' => 'Origem inválida'], 403);
        }

        // evitar múltiplas contagens da mesma pessoa no mesmo dia
        $recentVisit = Visit::where('ip_address', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($recentVisit) {
            return response()->json(['message' => 'Visita já registrada hoje.'], 200);
        }

        if ($participant) {
                Visit::create([
                    'participant_id' => $participant->id,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                ]);

                $participant->increment('points', 1); // visita de indicado

                $participant->save();
        }

        return response()->json(['message' => 'Visita registrada.', 'user' => $participant]);
    }

    public function share(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|exists:participants,referral_code'
        ]);

        $participant = Participant::where('referral_code', $request->referral_code)->first();

        if ($participant) {
            $participant->increment('points', 10); // pontos por compartilhar
            return response()->json(['message' => 'Compartilhamento registrado.']);
        }

        return response()->json(['message' => 'Código inválido.'], 400);
    }
}
