<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carrera extends Model
{
    protected $table = 'carreras';
    protected $fillable = ['nombre', 'nivel_educacion', 'numero_semestres', 'descripcion', 'codigo', 'estado'];
    protected $guarded = [];
    
    protected $casts = [
        'numero_semestres' => 'integer',
        'estado' => 'boolean',
    ];

    // Método para obtener el número de semestres según el nivel educativo
    public function getNumeroSemestresPorNivelAttribute()
    {
        $semestresPorNivel = [
            'Técnico' => 6,
            'Ingeniería' => 10,
            'Licenciatura' => 8,
            'Maestría' => 4,
            'Doctorado' => 6
        ];

        return $semestresPorNivel[$this->nivel_educacion] ?? 10;
    }

    // Método para asignar semestres automáticamente según el nivel educativo
    public function asignarSemestresAutomaticamente()
    {
        $numeroSemestres = $this->getNumeroSemestresPorNivelAttribute();
        
        // Obtener o crear los semestres necesarios
        $semestres = [];
        for ($i = 1; $i <= $numeroSemestres; $i++) {
            $nombreSemestre = $this->getNombreSemestre($i);
            $semestre = semestre::firstOrCreate(
                ['nombre' => $nombreSemestre],
                [
                    'momento_academico_activo' => false,
                    'fecha_inicio_inscripcion' => now(),
                    'fecha_fin_inscripcion' => now()->addMonths(6)
                ]
            );
            $semestres[] = $semestre->id;
        }

        // Asociar los semestres a la carrera
        $this->semestres()->sync($semestres);
        
        // Actualizar el número de semestres en la carrera
        $this->update(['numero_semestres' => $numeroSemestres]);

        return $this->semestres;
    }

    // Método para obtener el nombre del semestre según el nivel
    private function getNombreSemestre($numero)
    {
        $nombres = [
            'Técnico' => ['Primer Semestre', 'Segundo Semestre', 'Tercer Semestre', 'Cuarto Semestre', 'Quinto Semestre', 'Sexto Semestre'],
            'Ingeniería' => ['Primer Semestre', 'Segundo Semestre', 'Tercer Semestre', 'Cuarto Semestre', 'Quinto Semestre', 'Sexto Semestre', 'Séptimo Semestre', 'Octavo Semestre', 'Noveno Semestre', 'Décimo Semestre'],
            'Licenciatura' => ['Primer Semestre', 'Segundo Semestre', 'Tercer Semestre', 'Cuarto Semestre', 'Quinto Semestre', 'Sexto Semestre', 'Séptimo Semestre', 'Octavo Semestre'],
            'Maestría' => ['Primer Semestre', 'Segundo Semestre', 'Tercer Semestre', 'Cuarto Semestre'],
            'Doctorado' => ['Primer Semestre', 'Segundo Semestre', 'Tercer Semestre', 'Cuarto Semestre', 'Quinto Semestre', 'Sexto Semestre']
        ];

        return $nombres[$this->nivel_educacion][$numero - 1] ?? "Semestre {$numero}";
    }
    
    public function recorrido_academico()
    {
        return $this->hasMany(recorrido_academico::class);
    }
    
    public function semestres()
    {
        return $this->belongsToMany(semestre::class,'carrera_semestres','carreras_id','semestres_id');
    }
    
    public function estudiantes()
    {
        return $this->belongsToMany(estudiante::class,'carrera_estudiantes','carreras_id','estudiantes_id');
    }

    // Método para obtener materias a través de semestres
    public function materias()
    {
        return $this->hasManyThrough(
            materia::class,
            semestre::class,
            'id', // Clave foránea en semestres
            'id', // Clave foránea en materias
            'id', // Clave local en carreras
            'id'  // Clave local en semestres
        )->whereHas('semestres', function($query) {
            $query->whereHas('carreras', function($subQuery) {
                $subQuery->where('carreras.id', $this->id);
            });
        });
    }

    // Método para obtener el progreso de semestres
    public function getProgresoSemestresAttribute()
    {
        $totalSemestres = $this->numero_semestres;
        $semestresAsociados = $this->semestres->count();
        
        return $totalSemestres > 0 ? round(($semestresAsociados / $totalSemestres) * 100, 1) : 0;
    }

    // Método para obtener semestres faltantes
    public function getSemestresFaltantesAttribute()
    {
        return $this->numero_semestres - $this->semestres->count();
    }

    // Método para verificar si la carrera está completa
    public function getEstaCompletaAttribute()
    {
        return $this->semestres->count() >= $this->numero_semestres;
    }

    // Scope para carreras activas
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    // Scope para carreras completas
    public function scopeCompletas($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM carrera_semestres WHERE carreras_id = carreras.id) >= numero_semestres');
    }

    // Método para obtener estadísticas de la carrera
    public function getEstadisticasAttribute()
    {
        return [
            'total_estudiantes' => $this->estudiantes->count(),
            'total_materias' => $this->materias->count(),
            'semestres_asociados' => $this->semestres->count(),
            'semestres_faltantes' => $this->semestres_faltantes,
            'progreso' => $this->progreso_semestres,
            'esta_completa' => $this->esta_completa,
        ];
    }
}
