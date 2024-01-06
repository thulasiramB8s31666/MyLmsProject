<?php
namespace App\Repositories\Student;

use App\Models\InstituteStudent;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\User;
use App\Repositories\BaseRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthenticationRepository implements BaseRepositoryInterface
{
    public function all(){       

    }



    public function studentRegister($request)
    {
        DB::beginTransaction();
        try {
            // Log::warning($request);
            $user = User::create([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'is_active' => 'yes',
                'user_type' => 'student',
            ]);
            DB::commit();
            
            
            $academicPrefix =  "STUDID";
            $newId = self::generateUniqueAcademicId($academicPrefix);
          
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $newId,
                'dob' => $request['dob'],
                'gender' => isset($request['gender']) ? $request['gender'] : null,
                'qualification' => isset($request['qualification']) ? $request['qualification'] : null,
                'address_line_1' => isset($request['address_line_1']) ? $request['address_line_1'] : null,
                'address_line_2' => isset($request['address_line_2']) ? $request['address_line_2'] : null,
                'city' => isset($request['city']) ? $request['city'] : null,
                'state' => isset($request['state']) ? $request['state'] : null,
                'pincode' => isset($request['pincode']) ? $request['pincode'] : null,
                'qualification' => $request['qualification']
            ]);


            return ["data" => true, "message" => "Student registered successfully"];
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }



     
    public function getUser($user){
        return User::where('is_deleted','no')->where('user_type','student')->where('is_active','yes')->where('email',$user)->orWhere('phone',$user)->first();
    }


    public function login($credentials){
        try {
            if(Auth::attempt($credentials)){
                $user = Auth::user();
                Log::warning($user);
                $usertype = $user->user_type;

                if($usertype === 'student'){               
               
                $two_step_enabled = $user->is_two_step_enabled;
                Log::warning($two_step_enabled);
                if($two_step_enabled === "yes"){
                    return $this->generateOtpWhileLogin($user->id);
                }
                $token = auth()->user()->createToken('AuthToken')->accessToken;
                $response = [
                    "user_id"=>$user->id,
                    "email"=>$user->email,
                    "phone"=>$user->phone,
                    "token"=>$token
                ];
                return ["status"=>true,"message"=>"Login successfull","data"=>$response];
                }
                return["status" => true, "message" => "check the user type"];         
            }
            else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
            return ["status"=>false,"message"=>$e->getMessage()];
        }

    }

    



    public function generateOtpWhileLogin($id){
        try{
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"user id is mandatory"];
            }    

                $user = DB::table('users')
                ->select('id', 'name','is_two_step_enabled','otp')
                ->where('id', $id)
                ->where('user_type','student')                
                ->first();

            
            $two_step_enabled = $user->is_two_step_enabled;
            Log::warning($two_step_enabled);

            if($two_step_enabled === 'yes'){   
                $otp = mt_rand(100001, 199999);
                Log::warning($otp);
                $update = "update users set otp =$otp where id =$id";
                DB::select($update);
                $responce['name'] = $user->name;
                $responce['is_two_step_enabled'] = $user->is_two_step_enabled;
                $responce['otp'] = $otp;
                return ["data" => $responce, "message" => "otp generated successfully"];

             }

            return ["data" => 'false', "message" => "given credential is wrong for 2 step verfication"];           
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }


    public function verifyotp($otp,$id){
        try{
            if(!$otp){
                DB::rollBack();
                return ["status"=>false,"message"=>"otp is manidatory"];
            }  
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"id is mandatory"];
            }
            $user = User::where('id', $id)->where('otp',$otp)->where('user_type','student')->first();

            if( $user){
                $responce['id'] = $user->id;
                $responce['name'] = $user->name;
                $responce['email'] = $user->email;
                $responce['phone'] = $user->phone;
                $token = $user->createToken('authToken')->accessToken;
                User::where('id',$id)->update(["otp"=>null]);
                return ["data" => $responce, "message" => "login successfully","Token"=>$token];
            }

            return ["data" => 'false', "message" => "otp is incorrect"];
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }



    public function generateOtp($name){
        try{
            if(!$name){
                DB::rollBack();
                return ["status"=>false,"message"=>"name is manidatory"];
            }  
            $user = User::where('name', $name)->first();
            if($user){
                $id = $user->id;
                $otp = mt_rand(100001, 199999);
                Log::warning($otp);
                $update = "update users set otp =$otp where id =$id ";
                DB::select($update);
                return ["status"=>true,"id"=>$id,"otp"=>$otp];
            }
            return ["status"=>false,"message"=>"user is not found"];
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }



    public function passwordResetVerifyotp($otp,$id){
        try{
            if(!$otp){
                DB::rollBack();
                return ["status"=>false,"message"=>"otp is manidatory"];
            }  
            if(!$id){
                DB::rollBack();
                return ["status"=>false,"message"=>"id is mandatory"];
            }
            $user = User::where('id', $id)->where('otp',$otp)->where('user_type','student')
                ->first();
            if( $user){               
                User::where('id',$id)->update(["otp"=>null]);
                return ["status"=>true, "message" => "OTP verified",];
            }
            return ["data" => 'false', "message" => "otp is incorrect"];
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }



    public function resetPassword($id,$pass,$c_pass){
        try{
        if(!$id){
            DB::rollBack();
            return ["status"=>false,"message"=>"id is manidatory"];
        }  
        if(!$pass){
            DB::rollBack();
            return ["status"=>false,"message"=>"password is manidatory"];
        }  
        if(!$c_pass){
            DB::rollBack();
            return ["status"=>false,"message"=>"cofirm password is manidatory"];
        }  
        if ($pass === $c_pass) {  
            $user = User::find($id);
                if (!$user) {
                    return ["status" => false, "message" => "User not found"];
                }
    
                $user->password = Hash::make($pass);//admin@123
                $user->save();
                $user->tokens()->delete();
                return ["status" => true, "message" => "Password updated successfully"];
            } 
                return ["status" => false, "message" => "Password and confirm password not matched"];
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }




    private static function generateUniqueAcademicId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = Student::where('student_id', 'like', $prefix . '%')->max('student_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }











    public function instituteStudentCreate($req){
        try{
            $existingRecord = InstituteStudent::where('student_id', $req['student_id'])
            ->where('institute_id', $req['institute_id'])
            ->first();

        if ($existingRecord) {
            return ["status" => false, "message" => "Record already exists for student_id and institute_id"];
        }
            $create = InstituteStudent::create([
                "student_id" => $req['student_id'],
                "institute_id" => $req['institute_id']
            ]);
            return ["status" => true, "data" => $create, "message" => "created successfully"];
              
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }

    public function instituteStudentUpdate($req){
        try{
            $existingRecord = InstituteStudent::where('student_id', $req['student_id'])
            ->where('institute_id', $req['institute_id'])
            ->first();

        if ($existingRecord) {
            return ["status" => false, "message" => "Record already exists for student_id and institute_id"];
        }
           $update =[];
           if(isset($req['student_id'])){
            $update['student_id'] = $req['student_id'];
           } 
           if(isset($req['institute_id'])){
            $update['institute_id'] = $req['institute_id'];
           } 
        
           $up = InstituteStudent::where('id',$req['id'])->update($update);
           return ["status" => true, "data" => $up, "message" => "updated successfully"];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }


    public function instituteStudentlistById($req){
        try{
            $get = InstituteStudent::where('id',$req['id'])->get();
            return ["status" => true, "data" => $get, "message" => "updated successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }

    public function instituteStudentGetAll(){
        try{
            $get = InstituteStudent::all();
            return ["status" => true, "data" => $get, "message" => "listed successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }






    public function studentCourseCreate($req){
        try{
            $teacherCourseCreate = StudentCourse::create([
                 'student_id' => $req['student_id'],
                 'institute_course_id' => $req['institute_course_id'],
                 'batch_id' => $req['batch_id'],
            ]);
            return ["status" => false, "message" => 'creatated successfully' ];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function studentCourseUpdate($req){
        try{
            $update =[];
            if(isset($req['student_id'])){
                $update['student_id'] = $req['student_id'];
            }
            if(isset($req['institute_course_id'])){
                $update['institute_course_id'] = $req['institute_course_id'];
            }

            if(isset($req['batch_id'])){
                $update['batch_id'] = $req['batch_id'];
            }
            $up = StudentCourse::where('id',$req['id'])->update($update);

            return ["status" => true, "data" => $up, "message" => "updated successfully"];

        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function studentCourseListById($req){
        try{
            $get = StudentCourse::where('id',$req['id'])->get();
            return ["status" => true, "data" => $get, "message" => "updated successfully"];
        }catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage() ];
        }
    }



    public function studentCourseGetAll(){
        $all = StudentCourse::all();
        return ["status" =>true , "data"=> $all , "message listed all successfully"];
    }
}    