<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntrepriseController extends Controller
{
    // Afficher toutes les entreprises
    public function index()
    {
        return response()->json(Entreprise::all());
    }

    // Afficher une entreprise
    public function show($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        // Statistiques
        $nb_interactions = \App\Models\Interaction::where('id_entreprise', $entreprise->id_entreprise)->count();
        $nb_avis = \App\Models\Interaction::where('id_entreprise', $entreprise->id_entreprise)
            ->whereNotNull('note_avis')->count();
        $moyenne_notes = \App\Models\Interaction::where('id_entreprise', $entreprise->id_entreprise)
            ->whereNotNull('note_avis')->avg('note_avis');
        $moyenne_notes = $moyenne_notes ? round($moyenne_notes, 1) : null;
        $duree_total_vue = $entreprise->duree_vue ?? 0;

        $data = $entreprise->toArray();
        $data['nombre_interactions'] = $nb_interactions;
        $data['nombre_avis'] = $nb_avis;
        $data['moyenne_notes'] = $moyenne_notes;
        $data['duree_total_vue'] = $duree_total_vue;

        return response()->json($data);
    }

    // Créer une entreprise avec upload du logo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'secteur' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $entreprise = new Entreprise($validated);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $entreprise->logo = $path;
        }

        $entreprise->save();
        return response()->json($entreprise, 201);
    }

    // Mettre à jour une entreprise et son logo
    public function update(Request $request, $id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $validated = $request->validate([
            'nom_entreprise' => 'sometimes|required|string|max:255',
            'secteur' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $entreprise->fill($validated);

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si présent
            if ($entreprise->logo) {
                Storage::disk('public')->delete($entreprise->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $entreprise->logo = $path;
        }

        $entreprise->save();
        return response()->json($entreprise);
    }

    // Supprimer une entreprise
    public function destroy($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        if ($entreprise->logo) {
            Storage::disk('public')->delete($entreprise->logo);
        }
        $entreprise->delete();
        return response()->json(['message' => 'Entreprise supprimée']);
    }
}
