<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaFoto extends Model
{
    use HasFactory;
    protected $fillable = ['agenda_id', 'foto_path'];
}
