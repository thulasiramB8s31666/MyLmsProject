<?php

namespace App\Http\Controllers\Institue\CourseManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\CourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseControler extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(CourseRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'logo' => 'required',
            'category_id' => 'required',
        ]);
        $logoPath = "assets/institute/courses/images";
        $filePath = "";

        if ($req->hasFile('logo')) {
            $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
        }
        return $this->repo->create($req->all(), $filePath);
    }



    public function update(Request $req){
        
        $name = $req->input('name');
        $category_id = $req->input('category_id');
        $course_id = $req->input('course_id');

        $logoPath = "assets/institute/courses/images";
        $filePath = "";

        if ($req->hasFile('logo')) {
            $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
        }

        return $this->repo->update($name,$filePath,$category_id,$course_id);

    }

    public function listById(Request $req){
        $id=$req->input('id');
        return $this->repo->listById($id);
    }

    public function show(){
        return $this->repo->show();
    }
}
