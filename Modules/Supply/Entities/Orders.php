<?php

namespace Modules\Supply\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['ID','CODE','NOTE','DEPARTMENT_ID','PERSON_ID','STATUS_ID'];
    
    protected static function newFactory()
    {
        return \Modules\Supply\Database\factories\OrdersFactory::new();
    }
}
