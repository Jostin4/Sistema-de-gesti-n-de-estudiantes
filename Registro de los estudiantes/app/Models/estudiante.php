<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $fillable = [
        'nombre',
        'segundo_nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'correo',
        'telefono',
        'genero',
        'estado',
        'matricula',
        'user_id',
        'direccion',
        'ciudad',
        'estado_direccion',
        'codigo_postal'
    ];
    protected $guarded = [];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con semestres
    public function semestres()
    {
        return $this->belongsToMany(semestre::class,'semestres_estudiantes','estudiantes_id','semestres_id');
    }

    // Relación con carreras
    public function carreras()
    {
        return $this->belongsToMany(carrera::class,'carrera_estudiantes','estudiantes_id','carreras_id');
    }

    // Relación con materias a través de materia_estudiante_semestres
    public function materias()
    {
        return $this->belongsToMany(materia::class, 'materia_estudiante_semestres', 'estudiante_id', 'materia_id');
    }

    // Relación con notas
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    // Relación con evaluaciones a través de notas
    public function evaluaciones()
    {
        return $this->hasManyThrough(Evaluacion::class, Nota::class, 'estudiante_id', 'id', 'id', 'evaluacion_id');
    }

    // Relación con inscripciones de materias
    public function inscripcionesMaterias()
    {
        return $this->hasMany(InscripcionMateria::class);
    }

    // Método para obtener materias aprobadas
    public function getMateriasAprobadas()
    {
        return $this->inscripcionesMaterias()
            ->where('estado', 'aprobado')
            ->where('nota_final', '>=', 50.0)
            ->with('materia')
            ->get();
    }

    // Método para calcular créditos aprobados
    public function getCreditosAprobados()
    {
        return $this->inscripcionesMaterias()
            ->where('estado', 'aprobado')
            ->where('nota_final', '>=', 50.0)
            ->join('materias', 'inscripciones_materias.materia_id', '=', 'materias.id')
            ->sum('materias.creditos');
    }

    // Método para obtener materias reprobadas
    public function getMateriasReprobadas()
    {
        return $this->inscripcionesMaterias()
            ->where(function($query) {
                $query->where('estado', 'reprobado')
                      ->orWhere('nota_final', '<', 50.0);
            })
            ->with('materia')
            ->get();
    }

    // Método para verificar si puede inscribir materias del siguiente semestre
    public function puedeInscribirSiguienteSemestre($semestreId)
    {
        // Obtener materias del semestre anterior
        $semestreAnterior = semestre::where('id', '<', $semestreId)->orderBy('id', 'desc')->first();
        
        if (!$semestreAnterior) {
            return true; // Es el primer semestre
        }

        // Obtener materias aprobadas del semestre anterior
        $materiasAprobadas = $this->inscripcionesMaterias()
            ->where('semestre_id', $semestreAnterior->id)
            ->where('estado', 'aprobado')
            ->where('nota_final', '>=', 50.0)
            ->count();

        // Obtener total de materias del semestre anterior
        $totalMaterias = $this->inscripcionesMaterias()
            ->where('semestre_id', $semestreAnterior->id)
            ->count();

        // Debe aprobar al menos el 70% de las materias
        return $totalMaterias === 0 || ($materiasAprobadas / $totalMaterias) >= 0.7;
    }

    // Método para obtener materias disponibles para reinscripción
    public function getMateriasDisponiblesReinscripcion($semestreId)
    {
        // Obtener todas las materias del semestre
        $materiasSemestre = materia::whereHas('semestres', function($query) use ($semestreId) {
            $query->where('semestres.id', $semestreId);
        })->get();

        // Filtrar materias ya inscritas
        $materiasInscritas = $this->inscripcionesMaterias()
            ->where('semestre_id', $semestreId)
            ->pluck('materia_id');

        return $materiasSemestre->whereNotIn('id', $materiasInscritas);
    }

    // Método para obtener el nombre completo
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre;
        if ($this->segundo_nombre) {
            $nombre .= ' ' . $this->segundo_nombre;
        }
        return $nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }

    // Método para calcular el promedio general
    public function getPromedioGeneralAttribute()
    {
        $notas = $this->notas;
        return $notas->count() > 0 ? round($notas->avg('nota'), 2) : 0;
    }

    // Método para obtener semestres aprobados
    public function getSemestresAprobadosAttribute()
    {
        $semestresAprobados = collect();
        
        foreach ($this->semestres as $semestre) {
            $materiasSemestre = $this->materias->where('pivot.semestre_id', $semestre->id);
            $todasAprobadas = true;
            $tieneNotas = false;
            
            foreach ($materiasSemestre as $materia) {
                $notasMateria = $this->notas->where('evaluacion.materia_id', $materia->id);
                if ($notasMateria->count() > 0) {
                    $tieneNotas = true;
                    $promedioMateria = $notasMateria->avg('nota');
                    if ($promedioMateria < 50) {
                        $todasAprobadas = false;
                    }
                }
            }
            
            if ($tieneNotas && $todasAprobadas) {
                $semestresAprobados->push($semestre);
            }
        }
        
        return $semestresAprobados;
    }

    // Método para obtener la edad
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? Carbon::parse($this->fecha_nacimiento)->age : null;
    }

    // Método para obtener la dirección completa
    public function getDireccionCompletaAttribute()
    {
        $direccion = [];
        
        if ($this->direccion) {
            $direccion[] = $this->direccion;
        }
        if ($this->ciudad) {
            $direccion[] = $this->ciudad;
        }
        if ($this->estado_direccion) {
            $direccion[] = $this->estado_direccion;
        }
        if ($this->codigo_postal) {
            $direccion[] = $this->codigo_postal;
        }
        
        return implode(', ', $direccion);
    }

    // Método para verificar si tiene cuenta de usuario
    public function getTieneCuentaAttribute()
    {
        return !is_null($this->user_id);
    }

    /**
     * Obtiene el semestre actual del estudiante en una carrera específica
     */
    public function getSemestreActual($carreraId)
    {
        return $this->semestres()
            ->whereHas('carreras', function($query) use ($carreraId) {
                $query->where('carreras.id', $carreraId);
            })
            ->orderBy('nombre')
            ->first();
    }

    /**
     * Verifica si el estudiante está inscrito en una carrera específica
     */
    public function estaInscritoEnCarrera($carreraId)
    {
        return $this->carreras()->where('carreras.id', $carreraId)->exists();
    }

    // Método para obtener el estado formateado
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
            'graduado' => 'Graduado',
            'retirado' => 'Retirado'
        ];
        
        return $estados[$this->estado] ?? $this->estado;
    }

    // Método para obtener el género formateado
    public function getGeneroFormateadoAttribute()
    {
        $generos = [
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro'
        ];
        
        return $generos[$this->genero] ?? $this->genero;
    }

    // Scope para filtrar por estado
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Scope para filtrar por género
    public function scopePorGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }

    // Scope para buscar por nombre, apellido, matrícula o correo
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido_paterno', 'like', "%{$termino}%")
              ->orWhere('apellido_materno', 'like', "%{$termino}%")
              ->orWhere('matricula', 'like', "%{$termino}%")
              ->orWhere('correo', 'like', "%{$termino}%");
        });
    }

    // Método para validar que la fecha de nacimiento sea válida
    public function setFechaNacimientoAttribute($value)
    {
        $this->attributes['fecha_nacimiento'] = $value;
    }

    // Método para validar que la matrícula sea única
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($estudiante) {
            if (empty($estudiante->matricula)) {
                $estudiante->matricula = $estudiante->generarMatricula();
            }
        });
    }

    // Método para generar matrícula automática
    public function generarMatricula()
    {
        $anio = date('Y');
        $apellidoInicial = strtoupper(substr($this->apellido_paterno, 0, 1));
        $nombreInicial = strtoupper(substr($this->nombre, 0, 1));
        
        // Buscar el último número de matrícula del año actual
        $ultimaMatricula = static::where('matricula', 'like', $anio . $apellidoInicial . $nombreInicial . '%')
            ->orderBy('matricula', 'desc')
            ->first();
        
        if ($ultimaMatricula) {
            $ultimoNumero = intval(substr($ultimaMatricula->matricula, -4));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        return $anio . $apellidoInicial . $nombreInicial . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
    }
}
