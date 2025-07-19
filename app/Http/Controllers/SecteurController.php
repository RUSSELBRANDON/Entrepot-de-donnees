<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecteurController extends Controller
{
    public function entreprisesParCategorie()
{
    $secteurs = Entreprise::select('secteur')
        ->whereNotNull('secteur')
        ->distinct()
        ->get()
        ->pluck('secteur');

    $resultats = [];
    foreach ($secteurs as $secteur) {
        $entreprises = Entreprise::where('secteur', $secteur)->get();
        
        $resultats[$secteur] = $entreprises->map(function($entreprise) {
            // Utilisation de requêtes directes pour une meilleure performance
            $nb_interactions = Interaction::where('id_entreprise', $entreprise->id_entreprise)
                                          ->count();
            
            $nb_avis = Interaction::where('id_entreprise', $entreprise->id_entreprise)
                                  ->whereNotNull('note_avis')
                                  ->count();
            
            $moyenne_notes = Interaction::where('id_entreprise', $entreprise->id_entreprise)
                                        ->whereNotNull('note_avis')
                                        ->avg('note_avis');
            
            $moyenne_notes = $moyenne_notes ? round($moyenne_notes, 1) : null;
            
            // Ajout des données supplémentaires directement dans l'objet entreprise
            $data = $entreprise->toArray();
            $data['moyenne_notes'] = $moyenne_notes;
            $data['nombre_interactions'] = $nb_interactions;
            $data['nombre_avis'] = $nb_avis;
            $data['nombre_vues'] = $entreprise->vues ?? 0; // Utilisation de la valeur existante
            
            return $data;
        })->sortByDesc(function($item) {
            // Tri par interactions, puis vues, puis avis (ordre décroissant)
            return [
                $item['nombre_interactions'] ?? 0,
                $item['nombre_vues'] ?? 0,
                $item['nombre_avis'] ?? 0
            ];
        })->values();
    }

    return response()->json($resultats);
}

    // Récupérer les secteurs les plus populaires
    public function secteursPopulaires()
    {
        $secteurs = Entreprise::select('secteur')
            ->whereNotNull('secteur')
            ->distinct()
            ->get()
            ->pluck('secteur');

        $resultats = [];
        foreach ($secteurs as $secteur) {
            $entreprises = Entreprise::where('secteur', $secteur)->pluck('id_entreprise');
            $nb_entreprises = $entreprises->count();
            $interactions = Interaction::whereIn('id_entreprise', $entreprises);
            $nb_interactions = $interactions->count();
            $moyenne_notes = $interactions->whereNotNull('note_avis')->avg('note_avis');
            $moyenne_notes = $moyenne_notes !== null ? round($moyenne_notes, 1) : null;
            $nb_avis = $interactions->whereNotNull('note_avis')->count();

            $resultats[] = [
                'secteur' => $secteur,
                'nombre_entreprises' => $nb_entreprises,
                'moyenne_notes' => $moyenne_notes,
                'nombre_interactions' => $nb_interactions,
                'nombre_avis' => $nb_avis,
            ];
        }

        // Tri par nombre d'interactions puis par moyenne des notes (ordre décroissant)
        usort($resultats, function($a, $b) {
            if ($b['nombre_interactions'] === $a['nombre_interactions']) {
                return ($b['moyenne_notes'] <=> $a['moyenne_notes']);
            }
            return ($b['nombre_interactions'] <=> $a['nombre_interactions']);
        });

        return response()->json($resultats);
    }
}