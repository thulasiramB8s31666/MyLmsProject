<?php

namespace App\Repositories\Institute;

use App\Models\TermsAndCondition;
use App\Repositories\BaseRepositoryInterface;
use Exception;

class TermsAndConditionRepository implements BaseRepositoryInterface
{

    public function all()
    {
    }

    public function termsCreation($req)
    {
        try {
            $create = TermsAndCondition::create([
                'institute_id' => $req['institute_id'],
                'title' => $req['title'],
                'description' => $req['description']
            ]);
            return ["status" => true, "message" => "created successfully"];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }




    public function termsUpdate($req)
    {
        try {
            $array = [];

            if (isset($req['institute_id'])) {
                $array['institute_id'] = $req['institute_id'];
            }

            if (isset($req['title'])) {
                $array['title'] = $req['title'];
            }
            if (isset($req['description'])) {
                $array['description'] = $req['description'];
            }

            $update = TermsAndCondition::where('id', $req['id'])->update($array);

            return ["status" => true, "message" => "updated Successfully"];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }



    public function listById($req)
    {
        try {
            $get = TermsAndCondition::where('id', $req['id'])->first();
            return ["status" => true, "data" => $get, "message" => "listed successfully"];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }



    public function getAll()
    {
        $get = TermsAndCondition::all();
        return ["status" => true, "data" => $get, "message" => "listed successfully"];
    }


    public function termsAndCreation($req)
    {
        try {

            $get = TermsAndCondition::where('institute_id', $req['institute_id'])->where('title', $req['title'])->where('description', $req['description'])->get();
            if ($get) {
                return ["status" => false, "message" => "alread exist"];
            }
            $create = TermsAndCondition::create([
                'institute_id' => $req['institute_id'],
                'title' => $req['title'],
                'description' => $req['description']
            ]);

            return ["status" => true, "message" => "created successfully"];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }



    public function tearmAndUpdate($req)
    {
        try {
            $array = [];

            if (isset($req['institute_id'])) {
                $array['institute_id'] = $req['institute_id'];
            }
            if (isset($req['title'])) {
                $array['title'] = $req['title'];
            }
            if (isset($req['description'])) {
                $array['description'] = $req['description'];
            }

            $update = TermsAndCondition::where('id', $req['id'])->update($array);

            return ["status" => true, "message" => "updated successfully"];
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }





    public function isDelete($req)
    {
        try {            
        } catch (Exception $e) {
            return ["status" => true, "message" => $e->getMessage()];
        }
    }



}
