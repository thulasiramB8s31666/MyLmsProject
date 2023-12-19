<?php

namespace App\Repositories\Institute;

use App\Models\Course;
use App\Models\InstituteCourse;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class InstituteCourseRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }


    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = InstituteCourse::where('institute_course_id', 'like', $prefix . '%')->max('institute_course_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }


    public function create($req,$filepath){
        try{
            $Course = Course::where('course_id', $req['course_id'])->first();

            if (!$Course) {
                return ["status" => false, "message" => 'course_id is not found'];
            }
            // $academic_username ="COUCATID";
            $Prefix = "INS_COU_ID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($Prefix);
            $insert = InstituteCourse::create([
              'institute_id'=> $req['institute_id'],
              'course_id' => $req['course_id'],
              'institute_course_id' => $newId,
              'title' => $req['title'],
              'overview' =>$req['overview'],
              'course_duration' => $req['course_duration'],
              'difficulty_level' => $req['difficulty_level'],
              'logo' => $filepath,
              'delivery_mode' => $req['delivery_mode'],
              'description' => $req['description'],
              'price' => $req['price'],
              'discount' => $req['discount'],
              'discount_rate' => $req['discount_rate'],
              'is_active' => 'yes'
            ]);

            return ["status" => true, "message" => "institute course created"];

        } 
        catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }

    }


    public function update($req){
        try {
            $course = InstituteCourse::where('institute_course_id',$req['institute_course_id'])->first();

            if (!$course) {
                return ["status" => false, "message" => "institute_course_id is not valid"];
            }

            $updateData = [];

           
            if (isset($req['course_id'])) {
                $updateData['course_id'] = $req['course_id'];
            }

            if (isset($req['title'])) {
                $updateData['title'] = $req['title'];
            }

            if (isset($req['course_duration'])) {
                $updateData['course_duration'] = $req['course_duration'];
            }

            if (isset($req['difficulty_level'])) {
                $updateData['difficulty_level'] = $req['difficulty_level'];
            }

            if (isset($req['type'])) {
                $updateData['type'] = $req['type'];
            }

            if (isset($req['description'])) {
                $updateData['description'] = $req['description'];
            }

            if (isset($req['price'])) {
                $updateData['price'] = $req['price'];
            }

            if (isset($req['discount'])) {
                $updateData['discount'] = $req['discount'];
            }

            if (isset($req['discount_rate'])) {
                $updateData['discount_rate'] = $req['discount_rate'];
            }

           
            if (empty($updateData)) {
                return ["status" => true, "message" => "No updates provided"];
            }

            
            $course->update($updateData);

            return ["status" => true, "message" => "Updated successfully"];
        }
        catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function listById($id){
        try{
            if (!$id) {
                return ["status" => false, "message" => "id is mandatory"];
            }
            $instituteCourse = InstituteCourse::where('institute_course_id',$id)->get();
            if (!$instituteCourse) {
                return ["status" => false, "message" => "instituteCourse not found"];
            }
            return  ["status" => false, "data" => $instituteCourse,"message" => "listed successfully"];
        }
        catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function showAll(){
        try{
            $all = InstituteCourse::get();
            return  ["status" => false, "data" => $all ,"message" => "listed successfully"];

        }
        catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

}