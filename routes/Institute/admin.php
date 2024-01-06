<?php

use App\Http\Controllers\Institue\AttendanceManagement\AttendanceController;
use App\Http\Controllers\Institue\Authentication\AuthenticationController;
use App\Http\Controllers\Institue\BatchManagement\CourseBatchScheduleController;
use App\Http\Controllers\Institue\BatchManagement\InstituteCourseBatchController;
use App\Http\Controllers\Institue\BranchManagement\BranchController;
use App\Http\Controllers\Institue\ClassManagement\OnlineClassController;
use App\Http\Controllers\Institue\CourseManagement\CourseCategoriesController;
use App\Http\Controllers\Institue\CourseManagement\CourseControler;
use App\Http\Controllers\Institue\CourseManagement\InstituteCourseController;
use App\Http\Controllers\Institue\CourseManagement\VideoManagementController;
use App\Http\Controllers\Institue\TermAndCondition\TermsAndConditionController;
use App\Http\Controllers\Institue\UserManagement\PermissionController;
use App\Http\Controllers\Institue\UserManagement\RoleController;
use App\Http\Controllers\Staff\StaffManagement\StaffController;
use App\Models\TermsAndCondition;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthenticationController::class,'register']);
Route::post('login',[AuthenticationController::class,'login']);
Route::post('verifyOtp',[AuthenticationController::class,'verifyOtp']);
Route::post('generateOtp',[AuthenticationController::class,'generateOtp']);
Route::post('passwordResetVerifyotp',[AuthenticationController::class,'passwordResetVerifyotp']);
Route::post('resetPassword',[AuthenticationController::class,'resetPassword']);



Route::prefix('permission')->group(function () {
    Route::post('permission-create',[PermissionController::class,'createPermission']);
    Route::post('update-permission',[PermissionController::class,'updatePermission']);
    Route::delete('delete-Permission',[PermissionController::class,'deletePermission']);
    Route::post('get-all-permission',[PermissionController::class,'getAllPermission']);
    Route::post('list-By-Id',[PermissionController::class,'listByIdPermission']);
});



Route::prefix('role')->group(function () {

    Route::post('create-role',[RoleController::class,'create']);
    Route::post('update-role',[RoleController::class,'update']);
    Route::post('status',[RoleController::class,'status']);
    Route::post('create-role-group',[RoleController::class,'createRoleGroup']);
    Route::post('update-role-group',[RoleController::class,'updateRoleGroup']);
    Route::post('delete-role-group',[RoleController::class,'deleteRoleGroup']);
    Route::post('getUserRoleById',[RoleController::class,'getUserRoleById']);


});


Route::prefix('course-categories')->group(function () {

    Route::post('create',[CourseCategoriesController::class,'create']);
    Route::post('update',[CourseCategoriesController::class,'update']);
    Route::post('showAll',[CourseCategoriesController::class,'showAll']);
    Route::post('statusUpdate',[CourseCategoriesController::class,'statusUpdate']);
   

});    

Route::prefix('courses')->group(function(){

    Route::post('create',[CourseControler::class,'create']);
    Route::post('update',[CourseControler::class,'update']);
    Route::post('list-by-id',[CourseControler::class,'listById']);
    Route::post('show',[CourseControler::class,'show']);

});


Route::prefix('institute-courses')->group(function(){
    Route::post('create',[InstituteCourseController::class,'create']);
    Route::post('update',[InstituteCourseController::class,'update']);
    Route::post('listById',[InstituteCourseController::class,'listById']);
    Route::post('showAll',[InstituteCourseController::class,'showAll']);
});


Route::prefix('video-module')->group(function(){
    Route::post('create',[VideoManagementController::class,'create']);
    Route::post('update',[VideoManagementController::class,'update']);
    Route::post('listById',[VideoManagementController::class,'listById']);
    Route::post('showAll',[VideoManagementController::class,'showAll']);
    Route::post('statusUpdate',[VideoManagementController::class,'statusUpdate']);
});


Route::prefix('branch')->group(function(){
    Route::post('create',[BranchController::class,'create']);
    Route::post('update',[BranchController::class,'update']);
    Route::post('listById',[BranchController::class,'listById']);
    Route::post('showAll',[BranchController::class,'showAll']);
    Route::post('statusUpdate',[BranchController::class,'statusUpdate']);
});


Route::prefix('institute-course-batch')->group(function(){
    Route::post('create',[InstituteCourseBatchController::class,'create']);
    Route::post('update',[InstituteCourseBatchController::class,'update']);
    Route::post('listById',[InstituteCourseBatchController::class,'listById']);
    Route::post('getAll',[InstituteCourseBatchController::class,'getAll']);
});


Route::prefix('course-batch-schedule')->group(function(){
    Route::post('create',[CourseBatchScheduleController::class,'create']);
    Route::post('update',[CourseBatchScheduleController::class,'update']);
    Route::post('updateStatue',[CourseBatchScheduleController::class,'updateStatue']);
    Route::post('listById',[CourseBatchScheduleController::class,'listById']);
    Route::post('showAll',[CourseBatchScheduleController::class,'showAll']);
});


Route::prefix('staff-management')->group(function(){
    Route::post('update',[StaffController::class,'update']);
    Route::post('listById',[StaffController::class,'listById']);
    Route::post('getAll',[StaffController::class,'getAll']);
    Route::post('isDeleted',[StaffController::class,'isDeleted']);
});


Route::prefix('institute-Teacher')->group(function(){
    Route::post('institute-teacher-create',[StaffController::class,'instituteTeacherCreate']);
    Route::post('institute-teacher-update',[StaffController::class,'instituteTeacherUpdate']);
    Route::post('institute-teacher-listById',[StaffController::class,'instituteTeacherlistById']);
    Route::post('institute-teacher-getall',[StaffController::class,'instituteTeacherGetAll']);
});



Route::prefix('teacher-course')->group(function(){
    Route::post('teacher-course-create',[StaffController::class,'teacherCourseCreate']);
    Route::post('teacher-course-update',[StaffController::class,'teacherCourseUpdate']);
    Route::post('teacher-course-list-by-id',[StaffController::class,'teacherCourseListById']);
    Route::post('teacher-course-get-all',[StaffController::class,'teacherCourseGetAll']);
});


Route::prefix('online-class')->group(function(){
    Route::post('online-class-create',[OnlineClassController::class,'onlineClassCreate']);
    Route::post('online-class-update',[OnlineClassController::class,'onlineClassUpdate']);
    Route::post('online-class-list-by-id',[OnlineClassController::class,'onlineClasslistById']);
    Route::post('online-class-get-all',[OnlineClassController::class,'onlineClassGetAll']);    
});



Route::prefix('atendance')->group(function(){

    Route::post('attendance-create',[AttendanceController::class,'attendaneCreate']);
    Route::post('attendance-update',[AttendanceController::class,'attendaneUpdate']);
    Route::post('attendance-list-by-id',[AttendanceController::class,'attendanceListById']);
    Route::post('attendance-get-all',[AttendanceController::class,'attendanceGetAll']);

});


Route::prefix('terms')->group(function(){
    Route::post('terms-creation',[TermsAndConditionController::class,'termsCreation']);
    Route::post('terms-update',[TermsAndConditionController::class,'termsUpdate']);
    Route::post('terms-list-by-id',[TermsAndConditionController::class,'termsListById']);
    Route::post('terms-get-all',[TermsAndConditionController::class,'termsGetAll']);
});



Route::prefix('terms-and-condition')->group(function(){
    Route::post('terms-and-conition-create',[TermsAndConditionController::class,'termsAndCreation']);
    Route::post('terms-and-conition-update',[TermsAndConditionController::class,'termsAndUpdate']);
});


Route::post('notify',[TermsAndConditionController::class,'notify']);


