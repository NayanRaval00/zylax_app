<?php 

namespace App\Controllers;

class ErrorController extends BaseController
{
    public function index()
    {
        return view('frontend/error_404');
    }
}
?>