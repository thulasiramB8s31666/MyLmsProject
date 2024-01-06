<?php

namespace App\Repositories\Staff;

use App\Models\InstituteTeacher;
use App\Models\Staff;
use App\Models\TeacherCourse;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class StaffManagemenTRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function update($req)
    {
        try {
            $update = [];
            if (isset($req['dob'])) {
                $update['dob'] = $req['dob'];
            }
            if (isset($req['position'])) {
                $update['position'] = $req['position'];
            }
            if (isset($req['type'])) {
                $update['type'] = $req['type'];
            }
            if (isset($req['gender'])) {
                $update['gender'] = $req['gender'];
            }
            if (isset($req['address_line_1'])) {
                $update['address_line_1'] = $req['address_line_1'];
            }
            if (isset($req['address_line_2'])) {
                $update['address_line_2'] = $req['address_line_2'];
            }
            if (isset($req['city'])) {
                $update['city'] = $req['city'];
            }
            if (isset($req['state'])) {
                $update['state'] = $req['state'];
            }
            if (isset($req['pincode'])) {
                $update['pincode'] = $req['pincode'];
            }
            if (isset($req['qualification'])) {
                $update['qualification'] = $req['qualification'];
            }
            if (isset($req['work_experience'])) {
                $update['work_experience'] = $req['work_experience'];
            }
            if (isset($req['specialization'])) {
                $update['specialization'] = $req['specialization'];
            }

            $up = Staff::where('staff_id', $req['staff_id'])->update($update);

            return ["status" => false, "data" => $up, "message" => "updated successfully"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    public function listByid($req)
    {
        try {
            $list = Staff::where('staff_id', $req['staff_id'])->first();
            return ["status" => true, "data" => $list, "message" => "listed successfully"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }


    public function getAll()
    {
        try {
            $getAll = Staff::get();
            return ["status" => true, "data" => $getAll, "message" => "listed successfully"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function isDeleted($req){
        try{
           $isDeleted = Staff::where('staff_id', $req['staff_id'])->update(['is_deleted' => $req['is_delete']]);
           return ["status" => true, "data" => $isDeleted, "message" => "is_deleted updated successfully"];  
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }
 
    

    public function instituteTeacherCreate($req){
        try{
            $existingRecord = InstituteTeacher::where('staff_id', $req['staff_id'])
            ->where('institute_id', $req['institute_id'])
            ->first();

        if ($existingRecord) {
            return ["status" => false, "message" => "Record already exists for staff_id and institute_id"];
        }
            $create = InstituteTeacher::create([
                "staff_id" => $req['staff_id'],
                "institute_id" => $req['institute_id']
            ]);
            return ["status" => true, "data" => $create, "message" => "created successfully"];
              
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }

    public function instituteTeacherupdate($req){
        try{
            $existingRecord = InstituteTeacher::where('staff_id', $req['staff_id'])
            ->where('institute_id', $req['institute_id'])
            ->first();

        if ($existingRecord) {
            return ["status" => false, "message" => "Record already exists for staff_id and institute_id"];
        }
           $update =[];
           if(isset($req['staff_id'])){
            $update['staff_id'] = $req['staff_id'];
           } 
           if(isset($req['institute_id'])){
            $update['institute_id'] = $req['institute_id'];
           } 
        
           $up = InstituteTeacher::where('id',$req['id'])->update($update);
           return ["status" => true, "data" => $up, "message" => "updated successfully"];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }


    public function instituteTeacherlistById($req){
        try{
            $get = InstituteTeacher::where('id',$req['id'])->get();
            return ["status" => true, "data" => $get, "message" => "updated successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }

    public function instituteTeacherGetAll(){
        try{
            $get = InstituteTeacher::all();
            return ["status" => true, "data" => $get, "message" => "listed successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }


    public function teacherCourseCreate($req){
        try{
            $teacherCourseCreate = TeacherCourse::create([
                 'staff_id' => $req['staff_id'],
                 'institute_course_id' => $req['institute_course_id']
            ]);
            return ["status" => false, "message" => 'creatated successfully' ];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function teacherCourseUpdate($req){
        try{
            $update =[];
            if(isset($req['staff_id'])){
                $update['staff_id'] = $req['staff_id'];
            }
            if(isset($req['institute_course_id'])){
                $update['institute_course_id'] = $req['institute_course_id'];
            }
            $up = TeacherCourse::where('id',$req['id'])->update($update);

            return ["status" => true, "data" => $up, "message" => "updated successfully"];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function teacherCourseListById($req){
        try{
            $get = TeacherCourse::where('id',$req['id'])->get();
            return ["status" => true, "data" => $get, "message" => "updated successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function teacherCourseGetAll(){
        $all = TeacherCourse::all();
        return ["status" =>true , "data"=> $all , "message listed all successfully"];
    }
}
