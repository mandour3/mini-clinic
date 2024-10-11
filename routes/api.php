<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

});

Route::group(['middleware'=>'jwt.verify'],function() {

        Route::get('/index',[PatientController::class,'index']);

    Route::post('/updatePassword',[UserController::class,'updatePassword']);
    Route::post('/addPatient',[PatientController::class,'add']);
    Route::get('/getSection/{Section_id}',[PatientController::class,'getSection']);
    Route::get('/getPatient/{Patient_id}',[PatientController::class,'getPatient']);
    // Add API to gel All Patients
    Route::get('/getPatients',[PatientController::class,'getPatients']);
    Route::get('/getSections',[PatientController::class,'getSections']);
    Route::post('/search',[PatientController::class,'search']);
    Route::post('/AddSection',[PatientController::class,'AddSection']);







});
