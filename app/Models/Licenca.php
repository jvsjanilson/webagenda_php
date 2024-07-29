<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;
    public $guarded = ['id'];

    // protected $appends = ['ativo'];

    // public function getAtivoAttribute($value)
    // {
    //     return $this->validade >= Carbon::now()->format('Y-m-d');
    // }

}

