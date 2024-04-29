<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $fillable = ['nombre', 'apellido', 'edad', 'telefono'];

    public function credenciales()
    {
        return $this->belongsTo(Credenciales::class);
    }
}
