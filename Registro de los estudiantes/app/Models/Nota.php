<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = [
        'nota',
        'comentario',
        'evaluacions_id',
        'materia_estudiante_semestre_id',
        'fecha_registro',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    /**
     * Relación con evaluación
     */
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacions_id');
    }

    /**
     * Relación con materia_estudiante_semestre
     */
    public function materiaEstudianteSemestre()
    {
        return $this->belongsTo(MateriaEstudianteSemestre::class, 'materia_estudiante_semestre_id');
    }

    /**
     * Relación con estudiante a través de materia_estudiante_semestre
     */
    public function estudiante()
    {
        return $this->hasOneThrough(
            estudiante::class,
            MateriaEstudianteSemestre::class,
            'id',
            'id',
            'materia_estudiante_semestre_id',
            'estudiante_id'
        );
    }

    /**
     * Verificar si la nota es aprobatoria
     */
    public function isAprobatoria()
    {
        return $this->nota >= 6.0;
    }

    /**
     * Obtener el estado de la nota
     */
    public function getEstadoAttribute()
    {
        if ($this->nota >= 9.0) return 'Excelente';
        if ($this->nota >= 8.0) return 'Muy Bueno';
        if ($this->nota >= 50.0) return 'Bueno';
        if ($this->nota >= 6.0) return 'Aprobado';
        return 'Reprobado';
    }
}
