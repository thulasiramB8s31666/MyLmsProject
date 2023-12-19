<?php

namespace App\Repositories\Institute;

use App\Models\Course;
use App\Models\InstituteCourse;
use App\Models\Module_video;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class VideoManagementRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }


    public function create($req,$videoFilePath){
        try{
           $create =  Module_video::create([
             'institute_course_id' => $req['institute_course_id'],
             'title' => $req['title'],
             'description' => $req['description'],
             'video_path' =>  $videoFilePath,
             'staff_id' => $req['staff_id'],             
           ]);
           return ["status" => true, "message" => $create ,"data" => "created successfully"];
           
        }
        catch(Exception $e){
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function update($id,$institute_course_id,$title,$description,$staff_id,$videoFilePath){
        try{
            if(!$id){
            return ["status" => false ,"message" => "ID is manditary"];
            }
            if($institute_course_id){
                Module_video::where('id',$id)->update(['institute_course_id' =>$institute_course_id]);
            }
            if($title){
                Module_video::where('id',$id)->update(['title' =>$title]);
            }
            if($description){
                Module_video::where('id',$id)->update(['description' =>$description]);
            }
            if($staff_id){
                Module_video::where('id',$id)->update(['staff_id' =>$staff_id]);
            }
            if($videoFilePath){
                Module_video::where('id',$id)->update(['video_path' =>$videoFilePath]);
            }

            return ["status" => false ,"message" => "updated successfully"];


        }
        catch(Exception $e){
            return ["status" => false ,"message" =>$e->getMessage()];
        }
    }


    public function listById($id){
        try{
           $find = Module_video::where('id',$id)->get();
           return ['status' => true , 'data' => $find , 'message' =>  "Listed successfully"];
        }
        catch(Exception $e){
            return ["status" => false ,"message" =>$e->getMessage()];
        }
    }


    public function showAll(){
        try{
            
            $getAll = Module_video::get();
            return ["status" => false ,"message" => "Listed Successfully", "Data" => $getAll];
        }
        catch(Exception $e){
            return ["status" => false ,"message" =>$e->getMessage()];
        }
    }
}