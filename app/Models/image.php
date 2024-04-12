<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $primaryKey ='id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'path',


    ];}
