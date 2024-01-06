<?php

namespace App\Http\Controllers\Institue\BatchManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\InstituteCourseBatchRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InstituteCourseBatchController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(InstituteCourseBatchRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function create(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'institute_course_id' => 'required|exists:institute_courses',
                'institute_id' => 'required|exists:institutes',
                'batch_type' => 'required',
                'batch_timing' => 'required'
            ]);
            $validate->validate();

            return $this->repo->create($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    public function update(Request $req){
        try{

            $validate = Validator::make($req->all(),[
                'batch_id' => 'required|exists:institute_course_batches'
            ]);
            $validate->validate();

            return $this->repo->update($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function listById(Request $req){
        $id = $req->input('id');
        return $this->repo->listById($id);
    }


    public function getAll(Request $req){
        return $this->repo->getAll();
    }
}
