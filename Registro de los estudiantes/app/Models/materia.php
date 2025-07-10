<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class materia extends Model
{
    protected $table = 'materias';
    protected $fillable = ['nombre', 'estado', 'creditos', 'codigo'];

    public function semestres()
    {
        return $this->belongsToMany(semestre::class, 'materia_semestre', 'materia_id', 'semestre_id');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(estudiante::class, 'materia_estudiante_semestres', 'materia_id', 'estudiante_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }

    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'profesor_materia', 'materia_id', 'profesor_id');
    }

    // Nueva relación con secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }

    // Nueva relación con inscripciones
    public function inscripciones()
    {
        return $this->hasMany(InscripcionMateria::class);
    }
}
