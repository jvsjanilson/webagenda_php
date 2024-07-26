<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getDtAgendaAttribute($value)
    // {
    //     return Carbon::parse($value)->format("d/m/Y");
    // }
}
