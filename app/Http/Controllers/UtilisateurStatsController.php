<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class UtilisateurStatsController extends Controller
{
    // Nombre de consultations de l'utilisateur
    public function nombreConsultations($id_utilisateur)
    {
        $nb_consultations = Interaction::where('id_utilisateur', $id_utilisateur)
            ->where('type_interaction', 'consultation')
            ->count();
        return response()->json(['nombre_consultations' => $nb_consultations]);
    }

    // Nombre d'avis de l'utilisateur
    public function nombreAvis($id_utilisateur)
    {
        $nb_avis = Interaction::where('id_utilisateur', $id_utilisateur)
            ->whereNotNull('note_avis')
            ->count();
        return response()->json(['nombre_avis' => $nb_avis]);
    }

    // Historique des consultations des entreprises de l'utilisateur
    public function historiqueConsultations($id_utilisateur)
    {
        $interactions = Interaction::where('id_utilisateur', $id_utilisateur)
            ->orderByDesc('date_interaction')
            ->get();

        $historique = $interactions->map(function($interaction) {
            $entreprise = Entreprise::find($interaction->id_entreprise);
            return [
                'nom_entreprise' => $entreprise ? $entreprise->nom_entreprise : null,
                'type_interaction' => $interaction->type_interaction,
                'date_interaction' => $interaction->date_interaction,
            ];
        });

        return response()->json($historique);
    }
}
