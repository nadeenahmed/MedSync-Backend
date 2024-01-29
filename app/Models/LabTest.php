<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;
    protected $table = 'lab_tests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'arabic_name',
        'english_name',
        'reference_range',
    ];
}
