<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credenciales extends Model
{
    protected $table = 'credenciales';
    protected $fillable = ['brand', 'client_id', 'secret_id'];

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }
}
