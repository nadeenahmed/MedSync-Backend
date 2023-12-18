<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatments extends Model
{
    use HasFactory;
    protected $table = 'treatments';
    protected $fillable = [
        'name',
    ];
}
