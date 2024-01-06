<?php

namespace App\Http\Controllers\Institue\ClassManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\OnlineClassRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OnlineClassController extends Controller
{

    protected $repo;
    protected $img;
    public function __construct(OnlineClassRepository $repo, ImageRepository $img){
        $this->repo=$repo;
        $this->img=$img;
    }


    public function onlineClassCreate(Request $req){
        try{
           $validate =  Validator::make($req->all(),[
               'institute_id' =>'required|exists:institutes',
               'institute_course_id' => 'required|exists:institute_courses',
               'batch_id' =>'required|exists:institute_course_batches',
               'Staff_id' => 'required|exists:staff',        
           ]);

           $validate->validate();
           return $this->repo->onlineClassCreate($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }





    public function onlineClassUpdate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
               'id' => 'required|exists:online_classes',
               'institute_id' =>'nullable|exists:institutes',
               'institute_course_id' => 'nullable|exists:institute_courses',
               'batch_id' =>'nullable|exists:institute_course_batches',
               'Staff_id' => 'nullable|exists:staff',  
            ]);

           $validate->validate();
           return $this->repo->onlineClassupdate($req->all());
           
        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function onlineClasslistById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:online_classes',
            ]);
            $validate->validate();
            return $this->repo->listById($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }
    

    public function onlineClassGetAll(){
        return $this->repo->getAll();
    }


    
}
