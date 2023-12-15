<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTests extends Model
{
    use HasFactory;
    protected $table = 'lab_tests';
    protected $fillable = [
        'arabic_name',
        'englis_name',
    ];
}
