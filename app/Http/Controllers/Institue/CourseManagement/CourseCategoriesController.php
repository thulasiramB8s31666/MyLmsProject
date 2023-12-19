<?php

namespace App\Http\Controllers\Institue\CourseManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\CourseCategoriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseCategoriesController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(CourseCategoriesRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    public function create(Request $req)
    {

        $validator = Validator::make($req->all(),[
            'name' => 'required|string',
            'logo'=>'required',
        ]);
        $logoPath = "assets/institute/categories/images";
        $filePath = "";


        if ($req->hasFile('logo')) {
            $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
        }
        return $this->repo->create($req->all(),$filePath);
    }


    public function update(Request $req){
      
        $id = $req->input('id');
        $name = $req->input('name');
        $logoPath = "assets/institute/categories/images";
        $filePath = "";  
        if ($req->hasFile('logo')) {
          $filePath = $this->img->uploadImage($req->file('logo'), $logoPath);
        }
        return $this->repo->update($id, $name, $filePath);
    }


    public function showAll(Request $req){
        return $this->repo->showAll();
    }

}
