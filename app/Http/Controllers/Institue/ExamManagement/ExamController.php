<?php

namespace App\Http\Controllers\Institue\ExamManagement;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Repositories\Institute\ExamRepository;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $repo;
    protected $img;
    public function __construct(ExamRepository $repo, ImageRepository $img){
        $this->repo=$repo;
        $this->img=$img;
    }

}
