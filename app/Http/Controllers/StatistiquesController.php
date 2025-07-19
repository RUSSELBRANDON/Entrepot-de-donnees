<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiquesController extends Controller
{
    // Nombre de villes distinctes
    public function nombreVilles()
    {
        $count = User::distinct('ville')->whereNotNull('ville')->count('ville');
        return response()->json(['nombre_villes' => $count]);
    }
    // Nombre total d'utilisateurs
    public function nombreUtilisateurs()
    {
        $count = User::count();
        return response()->json(['nombre_utilisateurs' => $count]);
    }

    // Nombre total d'entreprises
    public function nombreEntreprises()
    {
        $count = Entreprise::count();
        return response()->json(['nombre_entreprises' => $count]);
    }

    // Nombre de secteurs distincts
    public function nombreSecteurs()
    {
        $count = Entreprise::distinct('secteur')->count('secteur');
        return response()->json(['nombre_secteurs' => $count]);
    }

    // Moyenne des notes
    public function moyenneNotes()
    {
        $moyenne = Interaction::whereNotNull('note_avis')->avg('note_avis');
        $moyenne = $moyenne !== null ? round($moyenne, 1) : null;
        return response()->json(['moyenne_notes' => $moyenne]);
    }
}
