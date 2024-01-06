<?php

namespace App\Http\Controllers\Institue\TermAndCondition;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\TermsAndConditionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TermsAndConditionController extends Controller
{
    protected $img;
    protected $repo;

    public function __construct(TermsAndConditionRepository $repo, ImageRepository $img)
    {
        $this->repo = $repo;
        $this->img = $img;
    }


    public function termsCreation(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'institute_id' => 'required|exist:institutes',
                'title' => 'required',
                'description' => 'required'
            ]);
            return $this->repo->termsCreation($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }


    public function termsUpdate(Request $req)
    {
        try {
            $validate = Validator::make([
                'id' => 'required|privacy_policies'
            ]);

            $validate->validate();

            return $this->repo->termsUpdate($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }



    public function termsListById(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:privacy_policies'
            ]);
            $validate->validate();
            return $this->repo->listById($req->all());
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }




    public function termsGetAll()
    {
        return $this->repo->getAll();
    }



    public function termsAndCreation(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'institute_id' => 'required|institutes',
                'title' => 'required',
                'description' => 'required'
            ]);
            $validate->validate();
            return $this->repo->termsAndCreation($req);
        } catch (ValidationException $e) {
            return ["status" => false, "message" => $e->errors()];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }



    public function tearmAndUpdate(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'id' => 'required|exists:terms_and_conditions',
            ]);

            $validate->validate();

            return $this->repo->tearmAndUpdate($req);
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }





    public function isDelete(Request $req)
    {
        try {
            $validate = Validator::make($req->all(), [
                'isdelete' => 'required'
            ]);
            $validate->validate();

            return $this->repo->isDelete($req->all());
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }




    public function listById($req)
    {
        try {
            $get = "fdfjjf";

            // Send push notification
            $notificationData = [
                'status' => false,
                'data' => $get,
                'message' => 'Listed Successfully',
            ];

            // Replace 'fcm' with your channel name and $req['device_token'] with the recipient's device token
            Notification::route('fcm', $req['device_token'])
                ->notify(new PushNotification($notificationData));

            return ["status" => false, "data" => $get, "message" => "Listed Successfully"];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }




    
}
