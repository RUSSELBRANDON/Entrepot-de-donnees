<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $table = 'fait_interactions';
    protected $primaryKey = 'id_interaction';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_utilisateur',
        'id_entreprise',
        'date_interaction',
        'type_interaction',
        'duree_vue',
        'note_avis',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise', 'id_entreprise');
    }
    public function temps()
    {
        return $this->belongsTo(Temps::class, 'date_interaction', 'date_id');
    }
}
