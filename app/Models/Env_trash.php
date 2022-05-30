<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Env_trash extends Model
{
    protected $table = 'env_trash';
    protected $primaryKey = 'TRASH_ID';
    public $timestamps = false;
}