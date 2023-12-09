<?php
namespace App\Repositories\Institute;

use App\Models\Permission;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionRepository implements BaseRepositoryInterface
{
    public function all(){        
    }


    public function permissionCreate($screen, $module, $name,$type)
    {
        DB::beginTransaction();
        try {
            if (!$screen) {
                DB::rollBack();
                return ["status" => false, "message" => "screen is mandatory"];
            }
            if (!$module) {
                DB::rollBack();
                return ["status" => false, "message" => "module is mandatory"];
            }
            if (!$name) {
                DB::rollBack();
                return ["status" => false, "message" => "name is mandatory"];
            }
          
            $createPermission = Permission::create([
                'screen' => $screen,
                'module' => $module,
                'name' => $name,
                'type' => "platform",
            ]);
            Log::warning($createPermission);
            DB::commit();
            return ["status" => true, "data" => $createPermission, "message" => "created sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    public function permissionUpdate($id, $screen, $module, $name)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $permission = Permission::find($id);
            if (!$permission) {
                return ["status" => false, "message" => "Id not found"];
            }
            if ($screen) {
                Permission::where('id', $id)
                    ->update(['screen' => $screen]);
            }
            if ($module) {
                Permission::where('id', $id)
                    ->update(['module' => $module]);
            }
            if ($name) {
                Permission::where('id', $id)
                    ->update(['name' => $name]);
            }

            Log::warning($permission);
            DB::commit();
            return ["status" => true, "data" => $permission, "message" => "role updated sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }



    public function permissionDelete($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $permissions = Permission::find($id);
            if (!$permissions) {
                return ["status" => false, "message" => "Permission Id not found"];
            }
            Permission::where('id', $id)->delete();
            Log::warning($permissions);
            DB::commit();
            return ["status" => true, "data" => $permissions, "message" => "deleted sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }



     //listAll permissions in permission table
     public function permissionGetAll($search)
     {
         DB::beginTransaction();
         try {
             $platform = Permission::when($search, function ($query) use ($search) {
                 $query->where('screen', 'like', '%' . $search . '%')
                     ->orWhere('module', 'like', '%' . $search . '%')
                     ->orWhere('name', 'like', '%' . $search . '%');
             })->paginate(10);
             Log::warning($platform);
             DB::commit();
             return ["status" => true, "data" => $platform, "message" => "listed sucessfully"];
         } catch (Exception $th) {
             Log::warning($th);
             DB::rollBack();
             return ["status" => false, "message" => $th->getMessage()];
         }
     }


     public function permissionListById($id)
     {
         DB::beginTransaction();
         try {
             if (!$id) {
                 DB::rollBack();
                 return ["status" => false, "message" => "id is mandatory"];
             }
             $permissions = Permission::find($id);
             if (!$permissions) {
                 return ["status" => false, "message" => "Permission Id not found"];
             }
             $listPermission = Permission::where('id', $id)->get();
             Log::warning($listPermission);
             DB::commit();
             return ["status" => true, "data" => $listPermission, "message" => "particular id fetched sucessfully"];
         } catch (Exception $th) {
             Log::warning($th);
             DB::rollBack();
             return ["status" => false, "message" => $th->getMessage()];
         }
     }
 
     
}    