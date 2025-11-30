<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'Status';
    
    protected $fillable = ['name'];
    
    public $timestamps = false;
}