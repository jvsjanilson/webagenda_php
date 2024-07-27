<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Limite extends Model
{
    use HasFactory;
    public $guarded = ['id'];


    public function getDtLimiteAttribute($value)
    {
        return Carbon::parse($value)->format("d/m/Y");
    }

    public function getTipoAgendaAttribute($value)
    {
        return $value == 'M' ? 'Montagem' : 'Entrega';
    }

}
