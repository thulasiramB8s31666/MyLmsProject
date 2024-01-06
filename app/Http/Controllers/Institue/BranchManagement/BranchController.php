<?php

namespace App\Http\Controllers\Institue\BranchManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\BranchManagementRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(BranchManagementRepository $repo, ImageRepository $img){
        $this->repo=$repo;
        $this->img=$img;
    }

    public function create(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'institute_id' => 'required|exists:institutes',
                'address' => 'required|unique:branches',
                'pincode' => 'required|unique:branches',
            ]);
            $validate->validate();
            return $this->repo->create($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "data" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function update(Request $req){
        try{
            $validate = Validator::make($req->all(),[
                'branch_id' => 'required|exists:branches',
            ]);
            $validate->validate();
            return $this->repo->update($req->all());
        }
        catch(ValidationException $e){
            return ["status" => false , "Message" => $e->errors()];
        }
        catch(Exception $e){
            return ["status" => false , "Message" => $e->getMessage()];
        }
    }


    public function listById(Request $req){
        $id = $req->input('id');
        return $this->repo->listById($id);
    }

    public function showAll(){
        return $this->repo->showAll();
    }

   
}
