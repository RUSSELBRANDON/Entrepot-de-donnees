<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest\RegisterRequest;
use App\Http\Requests\AuthRequest\LoginRequest;
use App\Models\Session as ModelsSession;
use Illuminate\Support\Facades\Cache;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $validatedData = $request->validated();
        // S'assurer que le mot de passe est hashé
        $validatedData['password'] = Hash::make($validatedData['password']);
        // Générer un id_utilisateur sous la forme Uxxx
        $lastUser = User::where('id_utilisateur', 'LIKE', 'U%')
            ->orderByDesc('id_utilisateur')
            ->first();
        if ($lastUser) {
            $lastNumber = intval(substr($lastUser->id_utilisateur, 1));
            $nextId = $lastNumber + 1;
        } else {
            $nextId = 1;
        }
        $validatedData['id_utilisateur'] = 'U' . $nextId;
        $validatedData['date_inscription'] = now()->toDateString();
        $user = User::create($validatedData);
        $verificationCode = Str::random(6);
        Cache::put('verification_code_'.$user->email, $verificationCode, now()->addMinutes(60));
        $user->notify(new EmailVerificationNotification($verificationCode));
        return response()->json(['message' => 'Code de vérification envoyé par email']);
    }

    public function verify($data)
    {
        try {
            $decrypted = Crypt::decrypt(urldecode($data));
            $email = $decrypted['email'];
            $code = $decrypted['code'];

            if (Cache::get('verification_code_' . $email) === $code) {
                $user = User::where('email', $email)->firstOrFail();
                // Ajout d'un champ email_verified à la table si besoin
                $user->email_verified = true;
                $user->save();
                Cache::forget('verification_code_' . $email);
                return response()->json(['message' => 'Email vérifié avec succès !'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lien invalide ou expiré'], 400);
        }
        return response()->json(['error' => 'Échec de la vérification'], 400);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
        }
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'message' => 'Accès autorisé',
            'token' => $token,
            'type_token' => 'Bearer',
            'id_utilisateur' => $user->id_utilisateur,
            'nom' => $user->nom,
            'email' => $user->email
        ], 200);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
