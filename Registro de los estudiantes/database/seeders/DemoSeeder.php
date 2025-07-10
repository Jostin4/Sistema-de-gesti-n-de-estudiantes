<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\estudiante;
use App\Models\carrera;
use App\Models\semestre;
use App\Models\materia;
use App\Models\Profesor;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🌱 Creando datos de demostración...');

        DB::beginTransaction();

        try {
            // Crear usuarios administradores
            $this->createAdminUsers();
            
            // Crear carreras
            $carreras = $this->createCarreras();
            
            // Crear semestres
            $semestres = $this->createSemestres();
            
            // Crear materias
            $materias = $this->createMaterias();
            
            // Crear profesores
            $profesores = $this->createProfesores();
            
            // Crear estudiantes
            $estudiantes = $this->createEstudiantes();
            
            // Asignar relaciones
            $this->assignRelations($carreras, $semestres, $materias, $profesores, $estudiantes);
            
            // Crear evaluaciones
            $this->createEvaluaciones($materias, $semestres);

            DB::commit();
            
            $this->command->info('✅ Datos de demostración creados exitosamente!');
            $this->command->info('📊 Resumen:');
            $this->command->info("   - Usuarios: " . User::count());
            $this->command->info("   - Carreras: " . carrera::count());
            $this->command->info("   - Semestres: " . semestre::count());
            $this->command->info("   - Materias: " . materia::count());
            $this->command->info("   - Profesores: " . Profesor::count());
            $this->command->info("   - Estudiantes: " . estudiante::count());
            $this->command->info("   - Evaluaciones: " . Evaluacion::count());
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('❌ Error al crear datos de demostración: ' . $e->getMessage());
        }
    }

    private function createAdminUsers()
    {
        $this->command->info('👤 Creando usuarios administradores...');
        
        // Admin principal
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('✅ Usuario admin creado: admin@test.com / password');
    }

    private function createCarreras()
    {
        $this->command->info('🎓 Creando carreras...');
        
        $carreras = [
            [
                'nombre' => 'Ingeniería en Sistemas Computacionales',
                'nivel_educacion' => 'Ingeniería',
                'numero_semestres' => 10,
                'descripcion' => 'Carrera enfocada en el desarrollo de software y sistemas informáticos',
                'codigo' => 'ISC',
                'estado' => true,
            ],
            [
                'nombre' => 'Licenciatura en Administración',
                'nivel_educacion' => 'Licenciatura',
                'numero_semestres' => 8,
                'descripcion' => 'Carrera enfocada en la gestión empresarial',
                'codigo' => 'LA',
                'estado' => true,
            ],
            [
                'nombre' => 'Técnico en Informática',
                'nivel_educacion' => 'Técnico',
                'numero_semestres' => 6,
                'descripcion' => 'Carrera técnica en informática y computación',
                'codigo' => 'TI',
                'estado' => true,
            ],
        ];

        $carrerasCreadas = [];
        foreach ($carreras as $carreraData) {
            $carrera = carrera::create($carreraData);
            $carrerasCreadas[] = $carrera;
        }

        return $carrerasCreadas;
    }

    private function createSemestres()
    {
        $this->command->info('📚 Creando semestres...');
        
        $semestres = [];
        for ($i = 1; $i <= 10; $i++) {
            $nombre = $this->getNombreSemestre($i);
            $semestre = semestre::create([
                'nombre' => $nombre,
                'momento_academico_activo' => $i <= 2, // Solo los primeros 2 semestres activos
                'fecha_inicio_inscripcion' => now(),
                'fecha_fin_inscripcion' => now()->addMonths(6),
            ]);
            $semestres[] = $semestre;
        }

        return $semestres;
    }

    private function createMaterias()
    {
        $this->command->info('📖 Creando materias...');
        
        $materias = [
            // Primer semestre
            ['nombre' => 'Matemáticas I', 'codigo' => 'MAT101', 'creditos' => 4],
            ['nombre' => 'Programación I', 'codigo' => 'PROG101', 'creditos' => 5],
            ['nombre' => 'Inglés I', 'codigo' => 'ING101', 'creditos' => 3],
            ['nombre' => 'Introducción a la Computación', 'codigo' => 'COMP101', 'creditos' => 4],
            
            // Segundo semestre
            ['nombre' => 'Matemáticas II', 'codigo' => 'MAT102', 'creditos' => 4],
            ['nombre' => 'Programación II', 'codigo' => 'PROG102', 'creditos' => 5],
            ['nombre' => 'Inglés II', 'codigo' => 'ING102', 'creditos' => 3],
            ['nombre' => 'Estructuras de Datos', 'codigo' => 'ED101', 'creditos' => 4],
            
            // Tercer semestre
            ['nombre' => 'Cálculo Diferencial', 'codigo' => 'CALC101', 'creditos' => 4],
            ['nombre' => 'Programación Orientada a Objetos', 'codigo' => 'POO101', 'creditos' => 5],
            ['nombre' => 'Bases de Datos I', 'codigo' => 'BD101', 'creditos' => 4],
            ['nombre' => 'Inglés III', 'codigo' => 'ING103', 'creditos' => 3],
        ];

        $materiasCreadas = [];
        foreach ($materias as $materiaData) {
            $materia = materia::create([
                'nombre' => $materiaData['nombre'],
                'codigo' => $materiaData['codigo'],
                'creditos' => $materiaData['creditos'],
                'estado' => 'Activo',
            ]);
            $materiasCreadas[] = $materia;
        }

        return $materiasCreadas;
    }

    private function createProfesores()
    {
        $this->command->info('👨‍🏫 Creando profesores...');
        
        $profesores = [
            ['nombre' => 'Dr. Juan', 'apellido' => 'Pérez', 'email' => 'juan.perez@test.com'],
            ['nombre' => 'Mtra. María', 'apellido' => 'García', 'email' => 'maria.garcia@test.com'],
            ['nombre' => 'Ing. Carlos', 'apellido' => 'López', 'email' => 'carlos.lopez@test.com'],
            ['nombre' => 'Lic. Ana', 'apellido' => 'Martínez', 'email' => 'ana.martinez@test.com'],
        ];

        $profesoresCreados = [];
        foreach ($profesores as $profesorData) {
            // Crear usuario
            $user = User::create([
                'name' => $profesorData['nombre'] . ' ' . $profesorData['apellido'],
                'email' => $profesorData['email'],
                'password' => Hash::make('password'),
                'role' => 'profesor',
            ]);

            // Crear profesor
            $profesor = Profesor::create([
                'user_id' => $user->id,
                'nombre' => $profesorData['nombre'],
                'apellido' => $profesorData['apellido'],
            ]);

            $profesoresCreados[] = $profesor;
        }

        return $profesoresCreados;
    }

    private function createEstudiantes()
    {
        $this->command->info('👨‍🎓 Creando estudiantes...');
        
        $estudiantes = [];
        for ($i = 1; $i <= 20; $i++) {
            // Crear usuario
            $user = User::create([
                'name' => "Estudiante {$i}",
                'email' => "estudiante{$i}@test.com",
                'password' => Hash::make('password'),
                'role' => 'estudiante',
            ]);

            // Crear estudiante
            $estudiante = estudiante::create([
                'nombre' => "Estudiante",
                'apellido_paterno' => "Apellido",
                'apellido_materno' => "{$i}",
                'fecha_nacimiento' => '2000-01-01',
                'correo' => "estudiante{$i}@test.com",
                'telefono' => "555-{$i}000",
                'genero' => $i % 2 == 0 ? 'Femenino' : 'Masculino',
                'estado' => 'activo',
                'matricula' => "2024E{$i}000",
                'user_id' => $user->id,
            ]);

            $estudiantes[] = $estudiante;
        }

        return $estudiantes;
    }

    private function assignRelations($carreras, $semestres, $materias, $profesores, $estudiantes)
    {
        $this->command->info('🔗 Asignando relaciones...');

        // Asignar semestres a carreras
        foreach ($carreras as $index => $carrera) {
            $semestresCarrera = array_slice($semestres, 0, $carrera->numero_semestres);
            $carrera->semestres()->attach(collect($semestresCarrera)->pluck('id'));
        }

        // Asignar materias a semestres
        $materiasPorSemestre = 4;
        foreach ($semestres as $index => $semestre) {
            $materiasSemestre = array_slice($materias, $index * $materiasPorSemestre, $materiasPorSemestre);
            if (!empty($materiasSemestre)) {
                $semestre->materias()->attach(collect($materiasSemestre)->pluck('id'));
            }
        }

        // Asignar profesores a materias
        foreach ($materias as $index => $materia) {
            $profesor = $profesores[$index % count($profesores)];
            $materia->profesores()->attach($profesor->id);
        }

        // Asignar estudiantes a carreras y semestres
        foreach ($estudiantes as $index => $estudiante) {
            $carrera = $carreras[$index % count($carreras)];
            $estudiante->carreras()->attach($carrera->id);
            
            // Asignar al primer semestre de la carrera
            $primerSemestre = $carrera->semestres()->first();
            if ($primerSemestre) {
                $estudiante->semestres()->attach($primerSemestre->id, [
                    'carreras_id' => $carrera->id,
                    'estado' => 'activo'
                ]);

                // Asignar materias del primer semestre
                $materiasPrimerSemestre = $primerSemestre->materias;
                foreach ($materiasPrimerSemestre as $materia) {
                    DB::table('materia_estudiante_semestres')->insert([
                        'materias_id' => $materia->id,
                        'estudiante_id' => $estudiante->id,
                        'semestres_id' => $primerSemestre->id,
                        'estado' => 'activo',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function createEvaluaciones($materias, $semestres)
    {
        $this->command->info('📝 Creando evaluaciones...');

        foreach ($materias as $materia) {
            foreach ($semestres as $semestre) {
                // Verificar si la materia está en este semestre
                if ($materia->semestres()->where('semestres.id', $semestre->id)->exists()) {
                    // Crear evaluaciones
                    $evaluaciones = [
                        ['nombre' => 'Primer Parcial', 'porcentaje' => 30],
                        ['nombre' => 'Segundo Parcial', 'porcentaje' => 30],
                        ['nombre' => 'Examen Final', 'porcentaje' => 40],
                    ];

                    foreach ($evaluaciones as $evalData) {
                        Evaluacion::create([
                            'materia_id' => $materia->id,
                            'semestre_id' => $semestre->id,
                            'nombre' => $evalData['nombre'],
                            'porcentaje' => $evalData['porcentaje'],
                        ]);
                    }
                }
            }
        }
    }

    private function getNombreSemestre($numero)
    {
        $nombres = [
            1 => 'Primer Semestre',
            2 => 'Segundo Semestre',
            3 => 'Tercer Semestre',
            4 => 'Cuarto Semestre',
            5 => 'Quinto Semestre',
            6 => 'Sexto Semestre',
            7 => 'Séptimo Semestre',
            8 => 'Octavo Semestre',
            9 => 'Noveno Semestre',
            10 => 'Décimo Semestre',
        ];

        return $nombres[$numero] ?? "Semestre {$numero}";
    }
} 