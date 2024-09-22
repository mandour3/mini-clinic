<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    use HasFactory;
    protected $table = 'patients';
    protected $primaryKey ='id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'Patient_Name',
        'Date_of_Birth',
        'Address',
        'Phone_Number',
        'Job',
        'createdAt',
        'age',
        'session_date'

    ];

    public function sections()
    {
        return $this->belongsToMany(sections::class, 'patient_section', 'patient_id', 'section_id');
    }
    public function section(){
        return $this->belongsTo('App\Models\sections','section_id','id');
    }
    public function details(){
        return $this->hasOne('App\Models\patient_details');
    }
}
