<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;

use App\Models\Pages;
use App\Models\Faqs;

class PageController extends BaseController
{

    public function show($slug = "")
    {
        $uri = service('uri');

        if($slug != ""){
            
            $pageModel = new Pages();

            $currentPage = ltrim($uri->getPath(),'/');
            $staticPage = $pageModel->where('slug',$slug)->first();
            $sidebar = $pageModel->where('page_type','Repair')->where('active',1)->orderBy('sort', 'asc')->findAll();

            $seoTags = [
                'title' => $staticPage['meta_title'],
                'meta_description' => $staticPage['meta_description'],
                'meta_keywords' => $staticPage['meta_keyword'],
            ];

            return view('frontend/static/page', ['seo' => $seoTags, 'activePage' => $currentPage, 'page' => $staticPage, 'sidebar' => $sidebar ]);
        }

    }
    
    public function submit_page(){
        helper(['form']);
        $request = service('request');
        $validation = \Config\Services::validation();

        if ($request->is('post')) {
        
            $rules = [
                'os_type'     => 'required',
                'os_brand'     => 'required',
                'os_model_no'     => 'required',
                'os_serial_no'     => 'required',
                'os_problem'     => 'required',
                'os_fname'     => 'required',
                'os_lname'     => 'required',
                'os_suburb_postcode'     => 'required',
                'os_email'      => 'required|valid_email',
                'os_contact_no'     => 'required',
                // 'file'     => 'required',
                'os_msg'     => 'required',
            ];
                // Validate input data
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            $image = $this->request->getFile('file');

            // Move the file to the public/uploads directory
            if ($image != "" && $image->isValid() && !$image->hasMoved()) {
                $imageFile = $image->getRandomName();
                $image->move('uploads/static-pages/', $imageFile);

                $imagePath = 'uploads/static-pages/'. $imageFile;

            }else{
                $imagePath = "";
            }

            $emailData = [
                "os_type" => $this->request->getPost("os_type"),
                "os_brand" => $this->request->getPost("os_brand"),
                "os_model_no" => $this->request->getPost("os_model_no"),
                "os_serial_no" => $this->request->getPost("os_serial_no"),
                "os_year_purchased" => $this->request->getPost("os_year_purchased"),
                "os_problem" => $this->request->getPost("os_problem"),
                "os_fname" => $this->request->getPost("os_fname"),
                "os_lname" => $this->request->getPost("os_lname"),
                "os_suburb_postcode" => $this->request->getPost("os_suburb_postcode"),
                "os_email" => $this->request->getPost("os_email"),
                "os_contact_no" => $this->request->getPost("os_contact_no"),
                "os_msg" => $this->request->getPost("os_msg"),
                "slug_url" => $this->request->getPost("slug_url"),
            ];

            // dd($emailData);

            $emailHelper = new \App\Libraries\EmailHelper();
            $adminEmail = env('ADMIN_EMAIL');
            $emailHelper->pageFormSubmit($adminEmail, $emailData, $imagePath);


            // Do something with valid data (e.g., save, email)
            session()->setFlashdata('success', 'Form submitted successfully!');
            return redirect()->to($this->request->getPost('slug_url'));
        }
    }

    public function about_us(){
        return view('frontend/about_us');
    }
    
    public function contact_us(){
        helper(['form']);
        $request = service('request');
        $validation = \Config\Services::validation();

        if ($request->is('post')) {
           
            $rules = [
                'firstName'     => 'required|min_length[3]|max_length[50]',
                'lastName'      => 'required|min_length[3]|max_length[50]',
                'email'      => 'required|valid_email',
                'country'      => 'required',
            ];
                // Validate input data
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            $emailHelper = new \App\Libraries\EmailHelper();
            $adminEmail = env('ADMIN_EMAIL');
            $emailHelper->contactUsFormSubmit($adminEmail, $this->request->getPost('firstName'), $this->request->getPost('lastName'), $this->request->getPost('email'), $this->request->getPost('phone'), $this->request->getPost('country'), $this->request->getPost('subject'), $this->request->getPost('message'));

            // Do something with valid data (e.g., save, email)
            session()->setFlashdata('success', 'Form submitted successfully!');
            return redirect()->to('/contact-us');
        }

        return view('frontend/contact_us');
    }

    public function faq(){


        $faqsModel = new Faqs();

        $faqs = $faqsModel->where('active',1)->orderBy('id', 'asc')->findAll();

        $seoTags = [
            'title' => "FAQ",
            'meta_description' => "FAQ",
            'meta_keywords' => "FAQ",
        ];

        return view('frontend/faq', ['seo' => $seoTags, 'faqs' => $faqs ]);
    }

}
