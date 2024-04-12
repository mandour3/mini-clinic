<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;
    protected $table = 'sections';
    protected $primaryKey ='id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',

    ];

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }}
