<?php

namespace App\Http\Controllers\Institue\UserManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(PermissionRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function createPermission(Request $request){
        Log::warning($request);
        $screen = $request->input('screen');
        $module = $request->input('module');
        $name = $request->input('name');
        $type = $request->input('type');
        $this->repo->permissionCreate($screen,$module,$name,$type);
     
    }

    public function updatePermission(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $screen = $request->input('screen');
        $module = $request->input('module');
        $name = $request->input('name');
        $this->repo->permissionUpdate($id,$screen,$module,$name);     
    }

    public function deletePermission(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        $this->repo->permissionDelete($id);
      
    }

    public function getAllPermission(Request $request){
        Log::warning($request);
        $search=$request->input('search','');
        return $this->repo->permissionGetAll($search);
    }


    public function listByIdPermission(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->permissionListById($id);
    }

}
