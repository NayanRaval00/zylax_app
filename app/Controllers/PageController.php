<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;

class PageController extends BaseController
{

    public function index()
    {
        
        return view('frontend/about_us');
    }

}
