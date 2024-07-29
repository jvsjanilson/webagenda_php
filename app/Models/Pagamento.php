<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pagamento extends Model
{
    use HasFactory;
    public $guarded = ['id'];

    public function getValorAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function getDataPagamentoAttribute($value)
    {
        return is_null($value) ? '' : Carbon::parse($value)->format("d/m/Y");
    }
}
