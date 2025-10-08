<?php

namespace Modules\Contact\Entities;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $fillable = ['name', 'email', 'image'];
    
}
