<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Services\DeeplinkService;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    public function handle(Request $request, DeeplinkService $deeplinkService)
    {
        $token = $request->query('token');

        if (! $token) {
            return response()->json([
                'status' => 'error',
                'code' => 'missing_token',
                'message' => 'Le paramètre token est requis.',
            ], 400);
        }

        $data = $deeplinkService->validateRegisterToken($token);

        if (! $data) {
            return response()->json([
                'status' => 'error',
                'code' => 'invalid_token',
                'message' => 'Token invalide, expiré ou signature incorrecte.',
            ], 401);
        }

        // Check if bat_id already exists
        $existingBatId = Subscriber::withTrashed()->where('bat_id', $data['bat_id'])->first();
        if ($existingBatId) {
            return response()->json([
                'status' => 'error',
                'code' => 'bat_id_exists',
                'message' => 'Un abonné avec ce bat-ID existe déjà.',
                'bat_id' => $data['bat_id'],
            ], 409);
        }

        // Check if phone already exists
        $existingPhone = Subscriber::withTrashed()->where('phone', $data['phone'])->first();
        if ($existingPhone) {
            return response()->json([
                'status' => 'error',
                'code' => 'phone_exists',
                'message' => 'Un abonné avec ce numéro de téléphone existe déjà.',
                'phone' => $data['phone'],
            ], 409);
        }

        // Create subscriber
        $subscriber = Subscriber::create([
            'bat_id' => $data['bat_id'],
            'phone' => $data['phone'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Abonné créé avec succès.',
            'subscriber' => [
                'id' => $subscriber->id,
                'bat_id' => $subscriber->bat_id,
                'phone' => $subscriber->phone,
                'created_at' => $subscriber->created_at->toIso8601String(),
            ],
        ], 201);
    }
}
