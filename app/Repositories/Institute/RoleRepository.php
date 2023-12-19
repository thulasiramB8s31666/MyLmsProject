<?php

namespace App\Repositories\Institute;

use App\Models\Role;
use App\Models\RoleGroup;
use App\Models\RolePermission;
use App\Models\User;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function createRole($name, $institute_id, $permission_id)
    {
        // Log::warning($name);
        DB::beginTransaction();
        try {
            if (!$name) {
                DB::rollBack();
                return ["status" => false, "message" => "name is mandatory"];
            }
            if (!$institute_id) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "institute Id is mandatory"], 400);
            }

            // Check if the data already exists
            $existingRole = Role::where('name', $name)->where('institute_id', $institute_id)->first();
            if ($existingRole) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "Data already exists"], 400);
            }

            $createQuery = Role::create([
                'name' => $name,
                'institute_id' => $institute_id,
                // 'is_academic' => 'yes',
                'is_active' => 'yes'
            ]);

            DB::commit();
            Log::warning($createQuery);

            $this->generateRolePermission($createQuery->id, $permission_id);

            return ["status" => true, "data" => $createQuery, "message" => "Role created successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return response()->json(["status" => false, "message" => $th->getMessage()], 500);
        }
    }



    public function generateRolePermission($roleId, $permission_id)
    {
        try {
            $roleArray = [];
            foreach ($permission_id as $key => $Id) {
                $permissionExists = RolePermission::where('role_id', $roleId)
                    ->where('permission_id', $Id)->exists();
                if (!$permissionExists) { //con === 0
                    Log::warning($Id);
                    $role = [
                        "role_id" => $roleId,
                        "permission_id" => $Id,
                    ];
                    array_push($roleArray, $role);
                }
            }
            RolePermission::insert($roleArray);
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return response()->json(["status" => false, "message" => $th->getMessage()], 500);
        }
    }



    public function updateRole($id, $name, $permissionId)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $roleExists = Role::where('id', $id)
                ->where('is_deleted', 'no')
                ->exists();
            if (!$roleExists) {
                DB::rollBack();
                return ["status" => false, "message" => "invalid data"];
            }
            if ($name) {
                $update = Role::where('id', $id)
                    ->update(['name' => $name]);
                DB::commit();

                if ($permissionId) {
                    // Delete role permissions that are not in the provided list
                    RolePermission::where('role_id', $id)->whereNotIn('permission_id', $permissionId)->delete();

                    // Generate role permissions based on the provided list
                    $this->generateRolePermission($id, $permissionId);
                    return ["status" => true, "data" => "done", "message" => "Role updated successfully"];

                }

                return ["status" => false, "message" => "Role Name Updated Successfully"];
            }



          

            // $data = Role::where('is_deleted', 'no')->with('rolePermission')->get();
            // Log::warning($data);
            // DB::commit();

        } catch (Exception $th) {
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }



    public function status($id, $status)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "Id is mandatory"];
            }
            if (!$status) {
                DB::rollBack();
                return ["status" => false, "message" => "Status is mandatory"];
            }

            $roles = Role::find($id);

            if (!$roles) {
                DB::rollBack();
                return ["status" => false, "message" => "Data not found"];
            }

            $roles->update(['is_active' => $status]);
            $roles->refresh();

            DB::commit();

            return ["status" => true, "data" => [], "message" => "{$roles->name} status updated successfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }




    public function createRoleGroup($role_id, $user_id)
    {
        DB::beginTransaction();
        try {
            if (!$role_id) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "Role Id is mandatory"], 400);
            }
            if (!$user_id) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "User Id is mandatory"], 400);
            }

            $role = Role::where('id', $role_id)->where('is_deleted', 'no')->first();
            $user = User::where('id', $user_id)->where('is_deleted', 'no')->first();

            // Use && instead of || to check if both role and user exist
            if (!$role || !$user) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "Role or User not available"], 400);
            }

            $rolegroup = RoleGroup::create([
                'role_id' => $role_id,
                'user_id' => $user_id,
            ]);

            DB::commit();
            return response()->json(["status" => true, "data" => $rolegroup, "message" => "Role group created successfully"], 201);
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return response()->json(["status" => false, "message" => $th->getMessage()], 500);
        }
    }


    //update role id using user id function
    public function roleUpdate($roleId, $userId)
    {
        DB::beginTransaction();
        try {
            if (!$userId) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "User Id is mandatory"], 400);
            }

            if (!$roleId) {
                DB::rollBack();
                return response()->json(["status" => false, "message" => "Role Id is mandatory"], 400);
            }

            $updateQuery = RoleGroup::where('user_id', $userId)->update(['role_id' => $roleId]);

            if ($updateQuery === 0) {
                // If no records were updated, it means the specified user_id does not exist in the RoleGroup table
                DB::rollBack();
                return response()->json(["status" => false, "message" => "User not found in RoleGroup"], 404);
            }

            DB::commit();
            return response()->json(["status" => true, "data" => $updateQuery, "message" => "Role updated successfully"], 200);
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return response()->json(["status" => false, "message" => $th->getMessage()], 500);
        }
    }


    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();

            if (!$id) {
                DB::rollBack();
                return ['status' => false, 'message' => 'ID is mandatory'];
            }

            // Change variable name from $user to $role
            $role = RoleGroup::find($id)->where('is_deleted', 'no')->first();

            if (!$role) {
                DB::rollBack();
                return ['status' => false, 'message' => 'Role not found or already deleted'];
            }

            // Instead of deleting, update the is_deleted field
            $role->update(['is_deleted' => 'yes']);

            DB::commit();

            return ['status' => true, 'message' => "Role '$id' marked as deleted successfully"];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'Error marking role as deleted', 'error' => $e->getMessage()];
        }
    }



    public function getUserRoleById($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "id is mandatory"];
            }
            $role = Role::find($id);
            if (!$role) {
                return ["status" => false, "message" => "Role Id not found"];
            }
            $listQuerry = Role::where('is_deleted', 'no')->where('id', $id)->with('rolePermission')->get();
            Log::warning($listQuerry);
            DB::commit();
            return ["status" => true, "data" => $listQuerry, "message" => "particular id fetched sucessfully"];
        } catch (Exception $th) {
            Log::warning($th);
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }




    


}
