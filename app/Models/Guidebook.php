<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guidebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'file',
    ];
}
