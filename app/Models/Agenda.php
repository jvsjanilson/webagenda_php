<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = ($value != "") ? (float) str_replace(',', '.', str_replace('.', '', $value)) : 0;
    }

    public function setValorFreteAttribute($value)
    {
        $this->attributes['valor_frete'] = ($value != "") ? (float) str_replace(',', '.', str_replace('.', '', $value)) : 0;
    }

    public function getValorAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }


    public function getValorFreteAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    //  return ($valor != "") ? (float) str_replace(',', '.', str_replace('.', '', $valor)) : 0

    // public function getDtAgendaAttribute($value)
    // {
    //     return Carbon::parse($value)->format("d/m/Y");
    // }
}
