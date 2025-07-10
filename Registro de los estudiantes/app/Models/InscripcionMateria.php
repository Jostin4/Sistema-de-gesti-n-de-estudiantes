<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionMateria extends Model
{
    protected $table = 'inscripciones_materias';
    protected $fillable = [
        'estudiante_id', 
        'materia_id', 
        'semestre_id', 
        'seccion_id', 
        'estado', 
        'nota_final'
    ];

    protected $casts = [
        'nota_final' => 'decimal:2',
    ];

    // Relación con estudiante
    public function estudiante()
    {
        return $this->belongsTo(estudiante::class);
    }

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

    // Relación con sección
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    // Método para verificar si la materia está aprobada
    public function isAprobada()
    {
        return $this->estado === 'aprobado' && $this->nota_final >= 50.0;
    }

    // Método para verificar si la materia está reprobada
    public function isReprobada()
    {
        return $this->estado === 'reprobado' || ($this->nota_final !== null && $this->nota_final < 7.0);
    }

    // Método para obtener el estado en español
    public function getEstadoEnEspanol()
    {
        $estados = [
            'inscrito' => 'Inscrito',
            'aprobado' => 'Aprobado',
            'reprobado' => 'Reprobado',
            'retirado' => 'Retirado'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }
} 