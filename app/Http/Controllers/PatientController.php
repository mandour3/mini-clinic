<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectionResource;
use App\Models\image;
use App\Models\patient;
use App\Models\patient_details;
use App\Models\Photo;
use App\Models\sections;
use App\Trait\AttachmentTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    use AttachmentTrait;

    public function index()
    {

        $patients = patient::with('details', 'sections')->get();

        return $this->apiResponse(['numberOfPatients'=>$patients->count(),'numberOfSections'=>sections::count(),'patients'=>$patients], "Get patients Successfully", 200);


    }
    public function getSections()
    {

        $sections = sections::get();

        return $this->apiResponse($sections, "Get sections Successfully", 200);


    }
public function getPatient(Request $request,$Patient_id){


    $patient = Patient::whereHas('sections', function ($query) use ($Patient_id) {
        $query->where('patient_id', $Patient_id);
    })->with('sections')->get();

    $patientData = $patient->map(function ($patient) {
        return [
            'Patient_Name' => $patient->Patient_Name,
            'Date_of_Birth' => $patient->Date_of_Birth,
            'Address' => $patient->Address,
            'Phone_Number' => $patient->Phone_Number,
            'session_date'=>$patient->session_date,
            'Job' => $patient->Job,
            'createdAt' => $patient->created_at,
            'age' => Carbon::parse($patient->Date_of_Birth)->age,
            'sections' => SectionResource::collection($patient->sections),
        ];
    });

    $details = patient_details::where('patient_id', $Patient_id)->first();
   // $patient = patient::with('details', 'sections')->find($Patient_id)->first();
    $profileImage = image::where('path','profileimage/'.$Patient_id)->first();
    $patientPhotos = image::where('path','patientPhotos/'.$Patient_id)->get();

    if($patient->isNotEmpty()){

        return $this->apiResponse(['profileImage'=>$profileImage,'patientPhotos'=>$patientPhotos,'patient'=>$patientData,'details'=>$details], "Get patient Successfully", 200);

    }
    return $this->apiResponse(null, "there is no patient", 404);

}
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Patient_Name' => 'required|string|max:255',
            'Date_of_Birth' => 'required|date',
          //  'Date_of_Birth' => 'required',
            'Address' => 'required|string|max:255',
            'Phone_Number' => 'required|string|max:20',
            'Job' => 'nullable|string|max:255',
       //     'section_id' => 'required|integer', // Assuming section_id is an integer
            'Heart_trouble' => 'nullable|string|max:255',
            'pregnancy' => 'nullable|string|max:255',
            'Hepatitis' => 'nullable|string|max:255',
            'TB' => 'nullable|string|max:255',
            'Anemia' => 'nullable|string|max:255',
            'Rad_Therapy' => 'nullable|string|max:255',
            'Aspirin_intake' => 'nullable|string|max:255',
            'Asthma' => 'nullable|string|max:255',
            'Hypertension' => 'nullable|string|max:255',
            'Diabetes' => 'nullable|string|max:255',
            'AIDS' => 'nullable|string|max:255',
            'Allergies' => 'nullable|string|max:255',
            'Rheu_Arthritis' => 'nullable|string|max:255',
            'Hemophilia' => 'nullable|string|max:255',
            'Kidney_Trouble' => 'nullable|string|max:255',
            'Hey_fever' => 'nullable|string|max:255',
            'MedicalHistory' => 'nullable|string|max:255',
            'ChiefComplain' => 'nullable|string|max:255',
            'Diagnosis' => 'nullable|string|max:255',
       //     'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photos' => 'array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'section_ids' => 'required|array', // Ensure section_ids is an array
            'section_ids.*' => 'integer|exists:sections,id', // Ensure each section_id exists in sections table
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        try {


            DB::beginTransaction();

        $patient = Patient::create([
            'Patient_Name' => $request->Patient_Name,
            'Date_of_Birth' => $request->Date_of_Birth,
            'Address' => $request->Address,
            'Phone_Number' => $request->Phone_Number,
            'Job' => $request->Job,
         //   'section_id' => $request->section_id,
            'createdAt' => Carbon::now(),



        ]);
            $patient->sections()->attach($request->input('section_ids'));

            if ($request->hasFile('images')) {
                $image = $request->file('images');
                $imageName = $this->saveAttach($image, 'profileimage/' .$patient->id );
                $newImage = new image([
                    'name' => $imageName,
                    'path' => 'profileimage/' .$patient->id,


                ]);
                $newImage->save();
            }
//            $image = $request->file('images');
//            $imageName = $this->saveAttach($image, 'profileimage/' .$patient->id );
//            $newImage = new image([
//                'name' => $imageName,
//                'path' => 'profileimage/' .$patient->id,
//
//
//            ]);
//            $newImage->save();




// Create details for the patient
           $patient_details = patient_details::create([
            'patient_id' => $patient->id,
            'Heart_trouble' => $request->input('Heart_trouble', false),
               'pregnancy' => $request->input('pregnancy', false),
               'Hepatitis' => $request->input('Hepatitis', false),
               'TB' => $request->input('TB', false),
               'Anemia' => $request->input('Anemia', false),
               'Rad_Therapy' => $request->input('Rad_Therapy', false),
               'Aspirin_intake' => $request->input('Aspirin_intake', false),
               'Asthma' => $request->input('Asthma', false),
               'Hypertension' => $request->input('Hypertension', false),
               'Diabetes' => $request->input('Diabetes', false),
               'AIDS' => $request->input('AIDS', false),
               'Allergies' => $request->input('Allergies', false),
               'Rheu_Arthritis' => $request->input('Rheu_Arthritis', false),
               'Hemophilia' => $request->input('Hemophilia', false),
               'Kidney_Trouble' => $request->input('Kidney_Trouble', false),
               'Hey_fever' => $request->input('Hey_fever', false),
               'MedicalHistory' => $request->input('MedicalHistory'),
               'ChiefComplain' => $request->input('ChiefComplain'),
               'Diagnosis' => $request->input('Diagnosis'),


           ]);
            if ($request->hasFile('photos')) {

                foreach ($request->file('photos') as $photo) {

                    $photoName = $this->saveAttach($photo, 'patientPhotos/' . $patient->id);
                    $photoName = new image([
                        'name' => $photoName,
                        'path' => 'patientPhotos/' . $patient->id,


                    ]);
                    $photoName->save();
                }
            }
            DB::commit();

            return $this->apiResponse(['patient'=>Patient::with('section')->find($patient->id),'profileImage'=>$newImage,'patient_details'=>$patient_details], "add patient Successfully", 200);


    } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' =>  $e->getMessage(),], 500);
}
    }


    public function getSection(Request $request,$Section_id){

//        $patients = patient::with('details', 'section')->where('section_id',$Section_id)->get();

        $patients = Patient::whereHas('sections', function ($query) use ($Section_id) {
            $query->where('section_id', $Section_id);
        })->get();
        if($patients->isNotEmpty()){

            return $this->apiResponse($patients, "Get patients Successfully", 200);

        }
        return $this->apiResponse(null, "there is no patients", 404);

    }

    public function AddSection(Request $request){
        $validator = Validator::make($request->all(), [
            'NameOfSection' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

       $newSection = sections::create([
            'name'=>$request->NameOfSection,
        ]);
        return $this->apiResponse($newSection, "Add Section Successfully", 200);

    }

    public function search(Request $request){
        $validator = Validator::make($request->all(), [
            'Patient_Name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
               $patient =   patient::with('details', 'sections')->where('Patient_Name', 'like', '%' . $request->Patient_Name . '%')->get();
        if($patient->isNotEmpty()){

            return $this->apiResponse($patient, "Get patients Successfully", 200);

        }
        return $this->apiResponse(null, "there is no patients", 404);

    }

}

