<?php

namespace App\Http\Controllers\Institue\UserManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\InstituteUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InstituteUserController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(InstituteUserRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    public function InstituteUser(Request $req)
    {

        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'phone' => 'required',
                'password' => 'required',
                'logo' => 'required',
                'description' => 'required',
                'registered_date' => 'required',
                'role_id' => 'required',
            ]);
            $validator->validate();

            $imagePath = [];
            $imageDirectory = "assets/institute/images"; //image storing folder
            if ($req->hasFile('image')) { //col name
                $productFiles = $req->file('image'); //col name
                foreach ($productFiles as $key => $value) {
                    $imagePath[] = $this->img->uploadImage($value, $imageDirectory);
                }
            }



            //gallery
            $galleryPath = [];
            $imageDirectory = "assets/institute/gallery"; //image storing folder
            if ($req->hasFile('gallery')) {
                $instuteFile = $req->file('gallery');
                foreach ($instuteFile as $key => $value) {
                    $galleryPath[] = $this->img->uploadImage($value, $imageDirectory);
                }
            }

            $InstituteImgPath = "assets/institute/register";
            $logoPath = "";
            if ($req->hasFile('logo')) {
                $logoPath = $this->img->uploadImage($req->file('logo'), $InstituteImgPath);
            }

            return  $this->repo->createInstituteUser($req->all(), $imagePath, $galleryPath, $logoPath);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
