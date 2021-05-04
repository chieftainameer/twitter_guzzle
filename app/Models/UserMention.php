<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMention extends Model
{
    use HasFactory;

    protected $fillable = ['post_id','text','users_oldest_id','users_newest_id','username_appearance_count','created_at'];
}
