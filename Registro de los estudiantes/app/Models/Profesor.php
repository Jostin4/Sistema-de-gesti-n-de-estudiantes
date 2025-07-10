<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $fillable = ['user_id', 'nombre', 'apellido'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materias()
    {
        return $this->belongsToMany(materia::class, 'profesor_materia', 'profesor_id', 'materia_id');
    }
}
