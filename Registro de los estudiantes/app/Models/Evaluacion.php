<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $fillable = ['materia_id', 'semestre_id', 'nombre', 'porcentaje'];

    public function materia()
    {
        return $this->belongsTo(materia::class);
    }
    public function semestre()
    {
        return $this->belongsTo(semestre::class);
    }
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
