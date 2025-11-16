<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory; // ← important pour pouvoir utiliser les factories si besoin

    protected $fillable = [
        'name',
        'domain',
        'database_name',
        'db_username',
        'db_password',
    ];
}
