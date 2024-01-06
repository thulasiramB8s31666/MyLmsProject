<?php

namespace App\Http\Controllers\Student\Authentication;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Student\AuthenticationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    protected $repo;
    protected $img;

    public function __construct(AuthenticationRepository $repo,ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }



    public function studentRegister(Request $request){

        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'phone' =>'required|unique:users,phone',
                'password' => 'required',   
                                  
            ]);

            $AcademicImgPath = "assets/academic/singleimages";
            $filePath = "";
            if ($request->hasFile('profile_image')) {
                $filePath = $this->img->uploadImage($request->file('profile_image'), $AcademicImgPath);
            }
            $validator->validate();

        


        
            return $this->repo->studentRegister($request->all());           
        }
        catch (ValidationException $e) {
            // DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        }
         catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }


    
    public function login(Request $request)
    {
        Log::warning($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            

            $user = $this->repo->getUser($request->input('username'));
            Log::warning($user);
            $credentials = [];
            $credentials['email'] = $user ? $user->email : null;
            $credentials['password'] = $request->input('password');
            return $this->repo->login($credentials);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function verifyOtp(Request $req){

        Log::warning($req->all());        
        $otp = $req->input('otp');        
        $id = $req->input('id');        
        Log::warning($id);

        return $this->repo->verifyotp($otp,$id);    
    }


    public function generateOtp(Request $req){
        Log::warning($req->all());
        $name = $req->input('name');
        return $this->repo->generateOtp($name);            
    }


    public function passwordResetVerifyotp(Request $req){
        Log::warning($req->all());
        $otp = $req->input('otp');
        $id = $req->input('id');
        return $this->repo->passwordResetVerifyotp($otp,$id);    
    }



    public function resetPassword(Request $req){
        Log::warning($req->all());
        $id = $req->input('id');
        $pass = $req->input('pass');
        $c_pass = $req->input('c_pass');
        return $this->repo->resetPassword($id,$pass,$c_pass);                    
    }





    public function instituteStudentCreate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'student_id' => 'required|exists:students',
                'institute_id' => 'required|exists:institutes'
            ]);
            $validate->validate();

            return $this->repo->instituteStudentCreate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function instituteStudentUpdate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:institute_students',
                'student_id' => 'required|exists:students',
                'institute_id' => 'nullable|exists:institutes',

            ]);
            $validate->validate();

            return $this->repo->instituteStudentUpdate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function instituteStudentlistById(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'id' => 'required|exists:institute_students'
            ]);
            $validate->validate();
            return $this->repo->instituteStudentlistById($req->all());
        }catch(ValidationException $e){
            return["status" => false , "message" => $e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }



    public function instituteStudentGetAll(){
        return $this->repo->instituteStudentGetAll();
    }







    public function studentCourseCreate(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'student_id' =>'required|exists:students',
                'institute_course_id' =>'required|exists:institute_courses',
                'batch_id' => 'required|exists:institute_course_batches'
            ]);
            $validate->validate();
            return $this->repo->studentCourseCreate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }



    public function studentCourseUpdate(Request $req){
        try{
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:student_courses',
                'student_id' =>'nullable|exists:students',
                'institute_course_id' => 'nullable|exists:institute_courses',
                'batch_id' => 'nullable|exists:institute_course_batches'

            ]);            

            $validate ->validate();
            return $this->repo->studentCourseUpdate($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }


    public function studentCourseListById(Request $req){
        try{
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:student_courses',
                'student_id' => 'nullable|exists:student',
            ]);            

            $validate ->validate();
            return $this->repo->studentCourseListById($req->all());

        }catch(ValidationException $e){
            return["status" => false , "message" =>$e->errors()];
        }catch(Exception $e){
            return["status" => false , "message" => $e->getMessage()];
        }
    }

    public function studentCourseGetAll(Request $req){
        return $this->repo->studentCourseGetAll();
    }
}
