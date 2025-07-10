<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriaEstudianteSemestre extends Model
{
    protected $fillable = ['estudiante_id', 'materia_id', 'semestre_id', 'estado'];

    public function estudiante()
    {
        return $this->belongsTo(estudiante::class);
    }
    
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

    /**
     * Scope para obtener inscripciones activas
     */
    public function scopeInscrito($query)
    {
        return $query->where('estado', 'inscrito');
    }

    /**
     * Scope para obtener materias aprobadas
     */
    public function scopeAprobado($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope para obtener materias reprobadas
     */
    public function scopeReprobado($query)
    {
        return $query->where('estado', 'reprobado');
    }

    /**
     * Scope para obtener materias cursando
     */
    public function scopeCursando($query)
    {
        return $query->where('estado', 'cursando');
    }
}
