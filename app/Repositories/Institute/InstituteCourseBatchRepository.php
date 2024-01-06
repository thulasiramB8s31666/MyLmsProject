<?php

namespace App\Repositories\Institute;

use App\Models\CourseBatchSchedule;
use App\Models\InstituteCourseBatch;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class InstituteCourseBatchRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }


    public function create($req){
        try{
            $Prefix = "BTH_ID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($Prefix);
            $instituteCoursebatch = InstituteCourseBatch::create([
                'institute_course_id' => $req['institute_course_id'],
                'institute_id' => $req['institute_id'],
                'batch_name' =>$req['batch_name'],
                'batch_id' => $newId,
                'batch_type' => $req['batch_type'],
                'batch_timing' => $req['batch_timing'],
                'max_capacity' => $req['max_capacity'],
                'start_date' => $req['start_date'],
                'end_date' => $req['end_date'],
                'is_active' => 'yes'
            ]);

            return ["status" => true, "message" => "batch created successfully"];

        }catch(Exception $e){
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = CourseBatchSchedule::where('batch_id', 'like', $prefix . '%')->max('batch_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }


    public function update($req){
        try{
            $update =[];
            if(isset($req['institute_course_id'])){
                $update['institute_course_id']= $req['institute_course_id'];
            }
            if(isset($req['batch_name'])){
                $update['batch_name']= $req['batch_name'];
            }
            if(isset($req['batch_type'])){
                $update['batch_type']= $req['batch_type'];
            }
            if(isset($req['batch_timing'])){
                $update['batch_timing']= $req['batch_timing'];
            }
            if(isset($req['max_capacity'])){
                $update['max_capacity']= $req['max_capacity'];
            }
            if(isset($req['start_date'])){
                $update['start_date']= $req['start_date'];
            }
            if(isset($req['end_date'])){
                $update['end_date']= $req['end_date'];
            }

            $check = InstituteCourseBatch::where('batch_id',$req['batch_id'])->update($update);

            if($check){
                return ["status" => true, "message" =>"updated successfully"];                
            }
            return ["status" => false, "message" => "check"];


        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    public function listById($id){
        try{
            if(!$id){
                return["status" => false,"message" => "ID is mandatory"];
            }
            $check = InstituteCourseBatch::where('batch_id',$id)->get();
            if($check){
                return["status" => false, "data" =>$check,"message" => "Listed successfully"];                
            }
            return["status" => false,"message" => "ID is invalid"];             



        }catch(Exception $e){
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function getAll(){
        try{
            $get = InstituteCourseBatch::all();
            if(!$get){
                return["status" => false,"message" => "no data"];                    
            }
            return ["status" => true,"Data" => $get ,"message" =>"listed successfully"];
        }catch(Exception $e){
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}    