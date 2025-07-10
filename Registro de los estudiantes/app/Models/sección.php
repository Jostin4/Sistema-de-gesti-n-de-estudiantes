<?php

namespace App\Models;


use App\Models\recorrido_academico;
use App\Models\materia;
use Illuminate\Database\Eloquent\Model;

class sección extends Model
{
    protected $table = 'secciones';
    protected $fillable = ['nombre'];
    protected $guarded = [];

    public function materias()
    {
        return $this->hasMany(materia::class);
    }
    public function recorrido_academico()
    {
        return $this->hasMany(recorrido_academico::class);
    }
}
