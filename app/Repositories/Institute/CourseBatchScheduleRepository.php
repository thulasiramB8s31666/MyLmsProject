<?php

namespace App\Repositories\Institute;

use App\Models\CourseBatchSchedule;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class CourseBatchScheduleRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function create($req){
        try{
            $courseBatchSchedule = CourseBatchSchedule::create([
                'batch_id' => $req['batch_id'],
                'class_date' => $req['class_date'],
                'start_time' => $req['start_time'],
                'end_time' => $req['end_time']
            ]);

            return ["status" => true , "message" => "created successfully"];

        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }

 

    public function update($req){
        try{
            $update =[];
            if(isset($req['class_date'])){
                $update['class_date']= $req['class_date'];
            }
            if(isset($req['start_time'])){
                $update['start_time']= $req['start_time'];
            }
            if(isset($req['end_time'])){
                $update['end_time']= $req['end_time'];
            }

            $update = CourseBatchSchedule::where('batch_id',$req['batch_id'])->update($update);
            return["status" => false , "message"=>"updated successfully"];

        }catch(Exception $e){
            return["status" => false , "message"=> $e->getMessage()];
        }
    }



    public function updateStatus($status,$id){
        try{
        if(!$status){
            return["status" => true, "message" => "status is mandatory"];
        }
        if(!$id){
            return["status" => true, "message" => "id is mandatory"];
        }

        $check = CourseBatchSchedule::where('batch_id',$id)->get();
        if(!$check){
            return["status" => true, "message" => "invalid id "];
        }

        $update = CourseBatchSchedule::where('batch_id', $id)->update(['is_deleted' =>$status]);
        return ["status" => true , "message" => "updated successfully"];
    }catch(Exception $e){
        return["status" => false , "message"=> $e->getMessage()];
    }
    }



    
    public function listById($req){
        try{
           $get = CourseBatchSchedule::where('batch_id',$req['batch_id'])->get();
           return["status" => false , "data" => $get ,"message"=>"Listed Successfully"];
        }catch(Exception $e ){
            return["status" => false , "message" => $e->getMessage() ];
        }
    }


    public function showAll(){
        $show = CourseBatchSchedule::get();
        return ["status" =>true , "data" => $show ,"message" => "Listed Successfully"];
        
    }
}    