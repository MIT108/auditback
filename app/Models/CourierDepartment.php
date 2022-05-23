<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierDepartment extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'courier_id', 'departement_id', 'status'];
}
