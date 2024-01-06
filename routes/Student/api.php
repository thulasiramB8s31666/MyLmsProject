<?php

use App\Http\Controllers\Student\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('studentRegister',[AuthenticationController::class,'studentRegister']);
Route::post('login',[AuthenticationController::class,'login']);
Route::post('verifyOtp',[AuthenticationController::class,'verifyOtp']);
Route::post('generateOtp',[AuthenticationController::class,'generateOtp']);
Route::post('passwordResetVerifyotp',[AuthenticationController::class,'passwordResetVerifyotp']);
Route::post('resetPassword',[AuthenticationController::class,'resetPassword']);



Route::prefix('institute-student')->group(function(){
    Route::post('institute-student-create',[AuthenticationController::class,'instituteStudentCreate']);
    Route::post('institute-student-update',[AuthenticationController::class,'instituteStudentUpdate']);
    Route::post('institute-student-listById',[AuthenticationController::class,'instituteStudentlistById']);
    Route::post('institute-student-getall',[AuthenticationController::class,'instituteStudentGetAll']);
});



Route::prefix('student-course')->group(function(){
    Route::post('student-course-create',[AuthenticationController::class,'studentCourseCreate']);
    Route::post('student-course-update',[AuthenticationController::class,'studentCourseUpdate']);
    Route::post('student-course-list-by-id',[AuthenticationController::class,'studentCourseListById']);
    Route::post('student-course-get-all',[AuthenticationController::class,'studentCourseGetAll']);
});
