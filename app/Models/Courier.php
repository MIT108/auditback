<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Courier extends Model
{
    use HasFactory;

    public function getImageAttribute($value){
        return Storage::url("images/".$value);
    }

    protected $fillable = [
        'name', 'description', 'image', 'serial_number', 'file_name', 'status', 'user_id'
    ];

}

