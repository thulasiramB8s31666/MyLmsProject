<?php

namespace App\Repositories\Institute;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CourseRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = Course::where('course_id', 'like', $prefix . '%')->max('course_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }



    public function create($req, $filePath)
    {
        try {
            $CourseCategory = CourseCategory::where('category_id', $req['category_id'])->first();

            if (!$CourseCategory) {
                return ["status" => false, "message" => 'category_id is not found'];
            }
            // $academic_username ="COUCATID";
            $academicPrefix = "COUID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($academicPrefix);

            $insert = course::create([
                'course_id' => $newId,
                'category_id' => $req['category_id'],
                'name' => $req['name'],
                'logo' => $filePath
            ]);

            return ["status" => true, "message" => "course categories created"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }





    public function update($name, $filePath, $category_id, $course_id)
    {
        try {
            if (!$course_id) {
                return ["status" => false, "message" => 'course id is mandatory'];
            }

            $updateData = [];
            if ($course_id) {
                $course =  Course::where('course_id', $course_id)->first();
                if (!$course) {
                    return ["status" => false, "message" => 'course not found'];
                }
            }

            if ($filePath) {
                $updateData['logo'] = $filePath;
            }

            if ($category_id) {
                $CourseCategory = CourseCategory::where('category_id', $category_id)->first();
                if ($CourseCategory) {
                    $updateData['category_id'] = $category_id;
                } else {
                    return ["status" => false, "message" => 'category_id is not in the table'];
                }
            }

            if ($name) {
                $updateData['name'] = $name;
            }

            if (!empty($updateData)) {
                Course::where('course_id', $course_id)->update($updateData);
                return ["status" => true, "message" => "updated successfully"];
            } else {
                return ["status" => false, "message" => "No data provided for update"];
            }
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    public function listById($id)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                DB::rollBack();
                return ["status" => false, "message" => "Id is mandatory"];
            }
            $course = Course::where('id', $id)->where('is_deleted', 'no')->get();

            if ($course->isEmpty()) {
                DB::rollBack();
                return ["status" => false, "message" => "Id is not found from Courses table"];
            }
            return ["status" => true, "data" => [$course], "message" => "Course Data displayed by id"];
        } catch (Exception $th) {
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }

    public function show()
    {
        DB::beginTransaction();
        try {
            $course = Course::get();
            return ["status" => true, "data" => [$course], "message" => "Course datas displayed successfully "];
        } catch (Exception $th) {
            DB::rollBack();
            return ["status" => false, "message" => $th->getMessage()];
        }
    }
}
