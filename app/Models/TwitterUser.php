<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitterUser extends Model
{
    use HasFactory;
    protected $fillable = ['id','text','author_id','created_at'];
}
