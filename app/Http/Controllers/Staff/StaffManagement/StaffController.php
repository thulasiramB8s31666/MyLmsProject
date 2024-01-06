<?php

namespace App\Http\Controllers\Staff\StaffManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Staff\StaffManagementRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Return_;

class StaffController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(StaffManagementRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function update(Request $req){        
        try{
            $validate = Validator::make($req->all(),[
                "staff_id" => "required|exists:staff",
            ]);
            $validate->validate();
            return $this->repo->update($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function listById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'staff_id' => 'required|exists:staff',                
            ]);
            $validate->validate();
            return $this->repo->listById($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function getAll(Request $req){
        try{
            return $this->repo->getAll();
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }

    
    
    public function isDeleted(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'staff_id' => 'required|exists:staff',   
                "is_delete" => "required",
            ]);
            $validate->validate();
            return $this->repo->isDeleted($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }



    public function instituteTeacherCreate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'staff_id' => 'required|exists:staff',
                'institute_id' => 'required|exists:institutes'
            ]);
            $validate->validate();

            return $this->repo->instituteTeacherCreate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function instituteTeacherUpdate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:institute_teachers',
                'staff_id' => 'nullable|exists:staff',
                'institute_id' => 'nullable|exists:institutes',

            ]);
            $validate->validate();

            return $this->repo->instituteTeacherupdate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function instituteTeacherlistById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:institute_teachers'
            ]);
            $validate->validate();
            return $this->repo->instituteTeacherlistById($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }



    public function instituteTeacherGetAll(){
        return $this->repo->instituteTeacherGetAll();
    }


    

    public function teacherCourseCreate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'staff_id' =>'required|exists:staff',
                'institute_course_id' =>'required|exists:institute_courses',
            ]);
            $validate->validate();
            return $this->repo->teacherCourseCreate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }



    public function teacherCourseUpdate(Request $req){
        try{
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:teacher_courses',
                'staff_id' => 'nullable|exists:staff',
                'institute_course_id' => 'nullable|exists:institute_courses',
            ]);            

            $validate ->validate();
            return $this->repo->teacherCourseUpdate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function teacherCourseListById(Request $req){
        try{
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:teacher_courses',
            ]);            

            $validate ->validate();
            return $this->repo->teacherCourseListById($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }

    public function teacherCourseGetAll(Request $req){
        return $this->repo->teacherCourseGetAll();
    }
}
