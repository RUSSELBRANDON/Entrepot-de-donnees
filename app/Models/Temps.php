<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temps extends Model
{
    use HasFactory;

    protected $table = 'dimension_temps';
    protected $primaryKey = 'date_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'date_id',
        'jour',
        'mois',
        'annee',
        'jour_semaine',
    ];
}
