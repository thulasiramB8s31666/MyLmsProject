<?php

namespace App\Http\Controllers\Institue\UserManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(RoleRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }

    public function create(Request $request){
        // Log::warning($request);
        $name = $request->input('name');
        $institute_id = $request->input('institute_id');
        $permission_id = $request->input('permission_id');
        return $this->repo->createRole($name, $institute_id,$permission_id);
     
    }

    public function update(Request $request){
        // Log::warning($request);
        $id = $request->input('id');
        $name = $request->input('name');
        $permission_id = $request->input('permission_id');
        return $this->repo->updateRole($id,$name,$permission_id);
   
    }

    public function status(Request $req){
        Log::warning($req);
        $id=$req->input('id');
        $status=$req->input('status');
        return $this->repo->status($id,$status);
    }


    public function createRoleGroup(Request $req){
        Log::warning($req);
        $role_id=$req->input('role_id');
        $user_id=$req->input('user_id');
        return $this->repo->createRoleGroup($role_id,$user_id);
    }

    public function updateRoleGroup(Request $request){
        Log::warning($request);
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');
        return $this->repo->roleUpdate($roleId,$userId);
    }


    public function deleteRoleGroup(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->deleteRole($id);   
    }

    public function getUserRoleById(Request $request){
        Log::warning($request);
        $id = $request->input('id');
        return $this->repo->getUserRoleById($id);
    }
}
