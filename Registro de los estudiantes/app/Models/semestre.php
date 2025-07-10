<?php

namespace App\Models;

use App\Models\recorrido_academico;
use Illuminate\Database\Eloquent\Model;

class semestre extends Model
{
    protected $table = 'semestres';
    protected $fillable = ['nombre', 'momento_academico_activo', 'fecha_inicio_inscripcion', 'fecha_fin_inscripcion'];
    protected $guarded = [];

    protected $casts = [
        'momento_academico_activo' => 'boolean',
        'fecha_inicio_inscripcion' => 'date',
        'fecha_fin_inscripcion' => 'date',
    ];

    public function materias()
    {
        return $this->belongsToMany(materia::class, 'materia_semestre', 'semestre_id', 'materia_id');
    }

    public function carreras()
    {
        return $this->belongsToMany(carrera::class,'carrera_semestres','semestres_id','carreras_id');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(estudiante::class,'semestres_estudiantes','semestres_id','estudiantes_id');
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

    // Método para verificar si el momento académico está activo
    public function isMomentoAcademicoActivo()
    {
        if (!$this->momento_academico_activo) {
            return false;
        }

        $now = now();
        return $now->between($this->fecha_inicio_inscripcion, $this->fecha_fin_inscripcion);
    }
}
