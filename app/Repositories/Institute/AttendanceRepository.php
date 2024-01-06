<?php
namespace App\Repositories\Institute;

use App\Models\Attendance;
use App\Models\Staff;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class AttendanceRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    
    public function attendaneCreate($req){
        try {
            
    
            $get = Attendance::where('institute_id', $req['institute_id'])
                ->where('staff_id', $req['staff_id'])
                ->where('type', $req['type'])
                ->first();
    
            if($get){
                return ["status" => false, "message" => "Already Exist"];
            }
    
            $create = Attendance::create([
                'institute_id' => $req['institute_id'],
                'staff_id' => $req['staff_id'],
                'type' => $req['type'], 
                'is_present' => 'yes'
            ]);
    
            return ["status" => true, "data" => $create, "message" => "created successfully"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
    

    public function attendaneUpdate($req)
    {
        try {
            $arr =[];

            if(isset($req['institute_id'])){
                $arr['institute_id'] = $req['institute_id'];
            }
            if(isset($req['staff_id'])){
                $arr['staff_id'] = $req['staff_id'];
            }
            if(isset($req['type'])){
                $arr['type'] = $req['type'];
            }
            if(isset($req['is_present'])){
                $arr['is_present'] = $req['is_present'];
            }

            $update = Attendance::where('id',$req['id'])->update($arr);
            return ["status" => true ,"data" => $update , "message" => "updated successfully"] ;
                    
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    public function attendanceListById($req){
        try{
            $get = Attendance::where('id',$req['id'])->get();

            return ["status" => true ,"data" => $get , "message" => "listed successfully successfully"] ;


        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function getAll(){
        $get = Attendance::all();
        return ["status" => true ,"data" => $get , "message" => "listed successfully successfully"] ;

    }
}   