<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient_details extends Model
{
    use HasFactory;
    protected $table = 'patient_details';
    protected $primaryKey ='id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'patient_id',
        'Heart_trouble',
        'pregnancy',
        'Hepatitis',
        'TB',
        'Anemia',
        'Rad_Therapy',
        'Aspirin_intake',
        'Asthma',
        'Hypertension',
        'Diabetes',
        'AIDS',
        'Allergies',
        'Rheu_Arthritis',
        'Hemophilia',
        'Kidney_Trouble',
        'Hey_fever',
        'MedicalHistory',
        'ChiefComplain',
        'Diagnosis',



    ];
    public function patient(){
        return $this->belongsTo('App\Models\patient','patient_id','id');
    }


}
