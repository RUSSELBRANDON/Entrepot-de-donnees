<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Changer le mot de passe de l'utilisateur connecté
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'old_password' => 'required|string|min:4',
            'new_password' => 'required|string|min:4',
        ]);

        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect'], 422);
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }
    // Afficher le profil de l'utilisateur connecté
    public function show()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    // Mettre à jour le profil de l'utilisateur connecté
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->only([
            'ville', 'age', 'genre', 'nom', 'email'
        ]);
        $user->update($data);
        return response()->json(['message' => 'Profil mis à jour avec succès', 'user' => $user]);
    }

    // Supprimer le compte de l'utilisateur connecté
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'Compte supprimé avec succès']);
    }
}
