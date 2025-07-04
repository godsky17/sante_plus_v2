<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'rendezvous_id', 'patient_id', 'montant'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function rendezvous()
    {
        return $this->belongsTo(RendezVous::class);
    }
}
