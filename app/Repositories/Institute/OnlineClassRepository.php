<?php

namespace App\Repositories\Institute;

use App\Models\Online_class;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class OnlineClassRepository implements BaseRepositoryInterface{

    public function all()
    {
        
    }


    public function onlineClassCreate($req){
        try{
            $Prefix = "ON_CLA_ID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($Prefix);

            $create =Online_class::create([
                'institute_id' => $req['institute_id'],
                'institute_course_id' => $req['institute_course_id'],
                'online_class_id' => $newId,
                'batch_id' => $req['batch_id'],
                'Staff_id' => $req['Staff_id'],            
            ]);
            return ["status" => true , "data" => $create ,"message" => "created successfully"];

        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }




    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = Online_class::where('online_class_id', 'like', $prefix . '%')->max('online_class_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }





    public function onlineClassupdate($req){
        try{
            $update =[];

            if(isset($req['institute_id'])){
                $update['institute_id'] = $req['institute_id'];
            }

            if(isset($req['institute_course_id'])){
                $update['institute_course_id'] = $req['institute_course_id'];
            }

            if(isset($req['batch_id'])){
                $update['batch_id'] = $req['batch_id'];
            }

            if(isset($req['Staff_id'])){
                $update['Staff_id'] = $req['Staff_id'];
            }

            $up = Online_class::where('id',$req['id'])->update($update);

            return["status" => true , "data" => $up , "message" =>" updated successfully"];

        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function listById($req){
        try{
            $get= Online_class::where('id',$req['id'])->get();
            return ["status" =>true , "data" => $get , "message" => "listed successfully"];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function getAll(){
        try{
            $get =Online_class::all();
            return ["status" => true ,"data" => $get ,"message" => "listed successfully"];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }

}
