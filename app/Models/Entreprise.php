<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $table = 'dimension_entreprises';
    protected $primaryKey = 'id_entreprise';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_entreprise',
        'nom_entreprise',
        'secteur',
        'ville',
        'type_abonnement',
        'statut_paiement',
        'logo',
        'description',
    ];
}
