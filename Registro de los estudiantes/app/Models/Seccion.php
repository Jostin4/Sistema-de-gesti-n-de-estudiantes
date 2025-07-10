<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'secciones';
    protected $fillable = [
        'nombre', 
        'materia_id', 
        'semestre_id', 
        'horario', 
        'aula', 
        'cupo_maximo', 
        'cupo_actual', 
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    // Relación con materia
    public function materia()
    {
        return $this->belongsTo(materia::class);
    }

    // Relación con semestre
    public function semestre()
    {
        return $this->belongsTo(semestre::class);
    }

    // Relación con inscripciones
    public function inscripciones()
    {
        return $this->hasMany(InscripcionMateria::class);
    }

    // Relación con estudiantes a través de inscripciones
    public function estudiantes()
    {
        return $this->belongsToMany(estudiante::class, 'inscripciones_materias', 'seccion_id', 'estudiante_id');
    }

    // Método para verificar si hay cupo disponible
    public function tieneCupoDisponible()
    {
        return $this->cupo_actual < $this->cupo_maximo;
    }

    // Método para obtener cupos disponibles
    public function getCuposDisponibles()
    {
        return $this->cupo_maximo - $this->cupo_actual;
    }

    // Método para incrementar cupo actual
    public function incrementarCupo()
    {
        if ($this->tieneCupoDisponible()) {
            $this->increment('cupo_actual');
            return true;
        }
        return false;
    }

    // Método para decrementar cupo actual
    public function decrementarCupo()
    {
        if ($this->cupo_actual > 0) {
            $this->decrement('cupo_actual');
            return true;
        }
        return false;
    }
} 