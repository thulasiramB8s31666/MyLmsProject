<?php

namespace App\Http\Controllers\Institue\AttendanceManagement;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\AttendanceRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(AttendanceRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function attendaneCreate(Request $req)
    {
        try {
            $validate = Validator::make($req->all(),[
                'institute_id' =>  'required|exists:institutes',
                'staff_id' => 'required|exists:staff',   
                

            ]);

            $validate->validate();
            return $this->repo->attendaneCreate($req->all());

        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function attendaneUpdate(Request $req)
    {
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:attendances',
                'institute_id' =>  'nullable|exists:institutes',
                'staff_id' => 'nullable|exists:staff',  
            ]);

            $validate->validate();
            return $this->repo->attendaneUpdate($req->all());
            
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }

    }


    public function attendanceListById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:attendances',
            ]);

            $validate->validate();
            return $this->repo->attendanceListById($req->all());
            
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }

    }

    public function attendanceGetAll(){
        return $this->repo->getAll();
    }



    public function showIs($search) //SUBCATEGORY DATA BY LIST
    {
        DB::beginTransaction();
        try {
            $subcategories = Attendance::where('is_deleted', 'no')->where('is_active','yes')->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->with('department')->paginate(10);
            DB::commit();
            return ["status" => true, "data" => $subcategories, "message" => "Department data list  successfully"];
        } catch (Exception $e) {
            Log::warning($e);
            DB::rollBack();
            return ["status" => false, "message" => $e->getMessage()];
        }
    }




      







}
