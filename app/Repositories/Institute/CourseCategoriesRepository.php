<?php

namespace App\Repositories\Institute;

use App\Models\CourseCategory;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CourseCategoriesRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function create($req, $filePath)
    {
        try {
            $checkName = CourseCategory::where('name', $req['name'])->first();

            if ($checkName) {
                return ["status" => false, "message" => 'Name already exist'];
            }
            // $academic_username ="COUCATID";
            $academicPrefix = "COUCATID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($academicPrefix);

            $insert = CourseCategory::create([
                'category_id' => $newId,
                'name' => $req['name'],
                'logo' => $filePath
            ]);

            return ["status" => true, "message" => "course categories created"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = CourseCategory::where('category_id', 'like', $prefix . '%')->max('category_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }


    public function update($id,$name,$filePath)
    {
        try {            
            if (!$id) {              
                return ["status" => false, "message" => "id is mandatory"];
            }
            if($name){
                CourseCategory::where('id',$id)->update(['name'=>$name]);
 
                if($filePath){
                   CourseCategory::where('id',$id)->update(['logo'=>$filePath]);
                    return ["status" => true, "message" => "name and logo updated successfully"];
                }
                
                return ["status" => true, "message" => "name updated successfully"];


            }

        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }
}
