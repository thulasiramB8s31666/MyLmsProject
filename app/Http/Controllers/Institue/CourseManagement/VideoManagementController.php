<?php

namespace App\Http\Controllers\Institue\CourseManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\VideoManagementRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VideoManagementController extends Controller
{

    protected $repo;
    protected $img;
    public function __construct(VideoManagementRepository $repo, ImageRepository $img){
        $this->repo=$repo;
        $this->img=$img;
    }


    public function create(Request $req){
        try{
            $validation = Validator::make($req->all(),[
                'institute_course_id' => 'required',
                'video' => 'required|mimes:mp4,avi,wmv,mov|max:10240',
            ]);
            $validation->validate();
            $videoPath = "assets/institute/modulvideo/videos";
            $videoFilePath = "";
            if($req->hasFile('video')){
                $videoFilePath = $this->img->uploadVideo($req->file('video'), $videoPath);
            }
           
            return $this->repo->create($req->all(),$videoFilePath);
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }




    public function update(Request $req){
        $id = $req->input('id');
        $institute_course_id = $req->input('institute_course_id');
        $title = $req->input('title');
        $description = $req->input('description');
        $staff_id = $req->input('staff_id');
        $videoPath = "assets/institute/modulvideo/videos";
            $videoFilePath = "";
            if($req->hasFile('video')){
                $videoFilePath = $this->img->uploadVideo($req->file('video'), $videoPath);
            }
        return $this->repo->update($id,$institute_course_id,$title,$description,$staff_id,$videoFilePath);    
    }


    public function listById(Request $req){
        $id = $req->input('id');
        return $this->repo->listById($id);
    }


    public function showAll(Request $req){
        return $this->repo->showAll();
    }
}
