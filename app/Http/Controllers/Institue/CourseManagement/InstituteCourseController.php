<?php

namespace App\Http\Controllers\Institue\CourseManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\InstituteCourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InstituteCourseController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(InstituteCourseRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }





    public function create(Request $req)
    {

        try {
            $validate = Validator::make($req->all(), [
                'institute_id' => 'required',
                'course_id' => 'required',
                'title' => 'required|unique:institute_courses,title',
                'overview' => 'required|unique:institute_courses,overview',
                'description' => 'required|unique:institute_courses,description',
            ]);

            $validate->validate();

            $logoPath = "assets/institute/institutecourses/images";
            $filePath = "";

            if ($req->hasFile('logo')) {
                $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
            }
            return $this->repo->create($req->all(), $filePath);
        } catch (ValidationException $e) {
            // DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }




    public function update(Request $req)
    {
        try {
            $validate = validator::make($req->all(), [
                'institute_course_id' => 'required'
            ]);
            $validate->validate();
            return $this->repo->update($req->all());
        } catch (ValidationException $e) {
            // DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        }
    }


    public function listById(Request $req){
        $id = $req->input('id');
        return $this->repo->listById($id);
    }


    public function showAll(Request $req){
        return $this->repo->showAll();
    }

}
