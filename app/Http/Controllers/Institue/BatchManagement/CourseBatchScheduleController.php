<?php

namespace App\Http\Controllers\Institue\BatchManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\CourseBatchScheduleRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CourseBatchScheduleController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(CourseBatchScheduleRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    public function create(Request $req){
        try{
        $validate = Validator::make($req->all(),[
            'batch_id' => 'required|exists:institute_course_batches'
        ]);
        $validate->validate();
        return $this->repo->create($req->all());
        }catch(ValidationException $e){
            return ["status" => false, "message" => $e->errors()];
        }catch(Exception $e){
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

 
    public function update(Request $req){
        try{
        $validate = Validator::make($req->all(),[
            'batch_id' => "required|exists:course_batch_schedules",
        ]);
        
        $validate->validate();

        return $this->repo->update($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()] ;
        }
        catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }

    }


    public function updateStatue(Request $req){
        $id = $req->input('id');
        $status = $req->input('status');
       
        return $this->repo->updateStatus($status,$id);
    }


    public function listById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'batch_id' => 'required|exists:course_batch_schedules',
            ]);
            $validate->validate();
            return $this->repo->listById($req->all());
        }
        catch(Exception $e){
            return["status" =>false, "message" =>$e->getMessage()];
        }
    }


    public function showAll(){
        return $this->repo->showAll();
    }
}
