<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Websetting extends Controller
{
    public $settingTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->settingTable = new SettingsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'web-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Web Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Web Settings | ' . $settings['app_name'];

        $data['web_settings'] = get_settings('web_settings', true);

        // dd($data);

        return view('admin/template', $data);
    }

    public function update_web_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'site_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Site Title is required!',
                    ]
                ],
                'support_number' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Support Number is required!',
                    ]
                ],
                'support_email' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Support Email is required!',
                    ]
                ],
                'copyright_details' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Copyright Details is required!',
                    ]
                ],
                'address' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Address is required!',
                    ]
                ],
                'app_short_description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Short Description is required!',
                    ]
                ],
                'map_iframe' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Map Iframe is required!',
                    ]
                ],
                'meta_keywords' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Meta Keywords is required!',
                    ]
                ],
                'meta_description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Meta Description is required!',
                    ]
                ],
                'app_download_section_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The App Title is required!',
                    ]
                ],
                'app_download_section_tagline' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Tagline is required!',
                    ]
                ],
                'app_download_section_short_description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Short Description is required!',
                    ]
                ],
                'app_download_section_playstore_url' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Playstore URL is required!',
                    ]
                ],
                'app_download_section_appstore_url' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Tagline URL is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/websetting');
            }

            // $site_title = $this->request->getPost('site_title');

            // Get uploaded file
            $logo = $this->request->getFile('logo');
            $footer_logo = $this->request->getFile('footer_logo');
            $favicon = $this->request->getFile('favicon');

            if($logo != ""){
                if ($logo->isValid() && !$logo->hasMoved()) {
                    // Move the file to the public/uploads directory
                    $logoImage = $logo->getRandomName();
                    $logo->move('uploads/logo', $logoImage);
                }
            }

            if($footer_logo != ""){
                if ($footer_logo->isValid() && !$footer_logo->hasMoved()) {
                    // Move the file to the public/uploads directory
                    $footer_logoImage = $footer_logo->getRandomName();
                    $footer_logo->move('uploads/footer', $footer_logoImage);
                }
            }

            if($favicon != ""){
                if ($favicon->isValid() && !$favicon->hasMoved()) {
                    // Move the file to the public/uploads directory
                    $faviconImage = $favicon->getRandomName();
                    $favicon->move('uploads/favicon', $faviconImage);
                }
            }

            // $check = isset($logoImage) ? $logoImage : $this->request->getPost('old_logo');
            
            $data = [
                'site_title' =>  $this->request->getPost('site_title'),
                'support_number' =>  $this->request->getPost('support_number'),
                'support_email' =>  $this->request->getPost('support_email'),
                'copyright_details' =>  $this->request->getPost('copyright_details'),
                'address' =>  $this->request->getPost('address'),
                'app_short_description' =>  $this->request->getPost('app_short_description'),
                'map_iframe' =>  $this->request->getPost('map_iframe'),
                'meta_keywords' =>  $this->request->getPost('meta_keywords'),
                'meta_description' =>  $this->request->getPost('meta_description'),
                'app_download_section' =>  $this->request->getPost('app_download_section') ? true : false,
                'app_download_section_title' =>  $this->request->getPost('app_download_section_title'),
                'app_download_section_tagline' =>  $this->request->getPost('app_download_section_tagline'),
                'app_download_section_short_description' =>  $this->request->getPost('app_download_section_short_description'),
                'app_download_section_playstore_url' =>  $this->request->getPost('app_download_section_playstore_url'),
                'app_download_section_appstore_url' =>  $this->request->getPost('app_download_section_appstore_url'),
                'twitter_link' =>  $this->request->getPost('twitter_link'),
                'facebook_link' =>  $this->request->getPost('facebook_link'),
                'instagram_link' =>  $this->request->getPost('instagram_link'),
                'youtube_link' =>  $this->request->getPost('youtube_link'),
                'shipping_mode' =>  $this->request->getPost('shipping_mode') ? true : false,
                'shipping_title' =>  $this->request->getPost('shipping_title'),
                'shipping_description' =>  $this->request->getPost('shipping_description'),
                'return_mode' =>  $this->request->getPost('return_mode') ? true : false,
                'return_title' =>  $this->request->getPost('return_title'),
                'return_description' =>  $this->request->getPost('return_description'),
                'support_mode' =>  $this->request->getPost('support_mode') ? true : false,
                'support_title' =>  $this->request->getPost('support_title'),
                'support_description' =>  $this->request->getPost('support_description'),
                'safety_security_mode' =>  $this->request->getPost('safety_security_mode') ? true : false,
                'safety_security_title' =>  $this->request->getPost('safety_security_title'),
                'safety_security_description' =>  $this->request->getPost('safety_security_description'),
                'primary_color' =>  $this->request->getPost('primary_color'),
                'secondary_color' =>  $this->request->getPost('secondary_color'),
                'font_color' =>  $this->request->getPost('font_color'),
                'modern_theme_color' =>  $this->request->getPost('modern_theme_color'),
                'logo' =>  isset($logoImage) ? "uploads/logo/".$logoImage : $this->request->getPost('old_logo'),
                'footer_logo' =>  isset($footer_logoImage) ? "uploads/footer/".$footer_logoImage : $this->request->getPost('old_footer_logo'),
                'favicon' =>  isset($faviconImage) ? "uploads/favicon/".$faviconImage : $this->request->getPost('old_favicon'),
                'shipping_gst' => $this->request->getPost('shipping_gst'),
                'order_gst' => $this->request->getPost('order_gst'),
                'product_gst' => $this->request->getPost('product_gst'),
                'checkout_disbaled' => $this->request->getPost('checkout_disbaled') ? true : false
            ];

            $json_data = json_encode($data);
            $json_data_value = ['value' =>  $json_data];
            $updated = $this->settingTable->where('variable', 'web_settings')->set($json_data_value)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Setting updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/websetting');

        }else{
            return redirect()->to('/admin/websetting');
        }
    }
    
    public function firebase()
    {
        $data['main_page'] = FORMS . 'firebase-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Firebase Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Firebase Settings | ' . $settings['app_name'];

        $data['firebase_settings'] = get_settings('firebase_settings', true);

        // dd($data);

        return view('admin/template', $data);
    }

    public function store_firebase()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'apiKey' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The apiKey is required!',
                    ]
                ],
                'authDomain' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The authDomain is required!',
                    ]
                ],
                'databaseURL' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The databaseURL is required!',
                    ]
                ],
                'projectId' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The projectId is required!',
                    ]
                ],
                'storageBucket' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The storageBucket is required!',
                    ]
                ],
                'messagingSenderId' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The messagingSenderId is required!',
                    ]
                ],
                'appId' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The appId is required!',
                    ]
                ],
                'measurementId' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The measurementId is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/websetting/firebase');
            }
            
            $data = [
                'apiKey' =>  $this->request->getPost('apiKey'),
                'authDomain' =>  $this->request->getPost('authDomain'),
                'databaseURL' =>  $this->request->getPost('databaseURL'),
                'projectId' =>  $this->request->getPost('projectId'),
                'storageBucket' =>  $this->request->getPost('storageBucket'),
                'messagingSenderId' =>  $this->request->getPost('messagingSenderId'),
                'appId' =>  $this->request->getPost('appId'),
                'measurementId' =>  $this->request->getPost('measurementId'),
            ];

            $json_data = json_encode($data);
            $json_data_value = ['value' =>  $json_data];
            $updated = $this->settingTable->where('variable', 'firebase_settings')->set($json_data_value)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Setting updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/websetting/firebase');

        }else{
            return redirect()->to('/admin/websetting/firebase');
        }
    }

}