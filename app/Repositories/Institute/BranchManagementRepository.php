<?php

namespace App\Repositories\Institute;

use App\Models\branche;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class BranchManagementRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function create($req)
    {
        try {
            $Prefix = "BRAN_ID"; // Assuming $username is a property of the model
            $newId = self::generateUniqueAcademicFaqModuleId($Prefix);
            $insert = branche::create([
                'institute_id' => $req['institute_id'],
                'branch_id' => $newId,
                'address' => $req['address'],
                'pincode' => $req['pincode']

            ]);
            return ["status" => true, "message" => "Branch Created "];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    private static function generateUniqueAcademicFaqModuleId($prefix)
    {
        // Find the maximum faq_module_id with the given prefix
        $maxId = branche::where('branch_id', 'like', $prefix . '%')->max('branch_id');

        // Extract the numeric part, increment, and pad with zeros
        $numericPart = $maxId ? (int) substr($maxId, strlen($prefix)) + 1 : 1;
        $newId = $prefix . str_pad($numericPart, 4, '0', STR_PAD_LEFT);

        return $newId;
    }


    public function update($req){
        try{
            $update =[];

            if(isset($req['address'])){
                $update['address'] = $req['address'];
            }
            if(isset($req['pincode'])){
                $update['pincode'] = $req['pincode'];
            }

            $updating = branche::where('branch_id',$req['branch_id'])->update($update);
            return ["status" => true , "message" =>"updated successfully"];

        }
        catch(Exception $e){
            return ["status" => false , "message" => $e->getMessage()];
        }
    }

   
    public function listById($id){
        try{
            if(!$id){
                return ["status" => false , "message" => "ID is mandatory"];
            }
            $select = branche::where('branch_id',$id)->first();
            return ["status" => true, "data" =>$select ,"message" =>"listed successfully"];

            if(!$select){
                return ["status" => false , "message" => "ID is invalid"];
            }


        }catch(Exception $e){
            return ["status" => false,  "message" =>$e->getMessage()];
        }
    }


    public function showAll(){
        try{
            $select = branche::get();
            return ["status" => true, "data" =>$select ,"message" =>"listed successfully"];
        }catch(Exception $e){
            return ["status" => false,  "message" =>$e->getMessage()];
        }
    }


  
}
