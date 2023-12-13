<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drugs extends Model
{
    use HasFactory;
    protected $table = 'drugs'; // Update with your actual table name
    protected $primaryKey = 'drug_id';
    protected $fillable = [
        'drug_id',
        'name',
    ];
}
