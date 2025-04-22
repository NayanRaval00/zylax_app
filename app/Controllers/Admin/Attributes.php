<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Attributes as AttributesModel;
use App\Models\AttributeSet as AttributeSetModel;
use App\Models\AttributeValues as AttributeValuesModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Attributes extends Controller
{

    public $attributesTable, $attributeSetTable, $attributeValuesTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->attributesTable = new AttributesModel();
        $this->attributeSetTable = new AttributeSetModel();
        $this->attributeValuesTable = new AttributeValuesModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'attribute';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Attribute Set | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Attribute Set | ' . $settings['app_name'];

        // $data['attribute_set'] = $this->attributeSetTable
        // ->orderBy('id', 'DESC')
        // ->findAll();
        $data['attribute_set'] = $this->attributeSetTable->getAttributeSetWithCategory();
        // getAttributeSetWithCategory
        // dd($data['attribute_set']);

        return view('admin/template', $data);
    }

    public function add_attributes()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'attribute_set' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Attribute Set is required!',
                    ]
                ],
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Attribute Name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Attribute slug is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                $session->setFlashdata('old_input', $this->request->getPost()); // Store old input
                return redirect()->to('/admin/attributes')->withInput(); // Redirect with input
            }

            $slug_attribute = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

            if($slug_attribute){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $exist_attribute = $this->attributesTable
                ->where('attribute_set_id', $this->request->getPost('attribute_set'))
                ->where('name', $this->request->getPost('name'))
                ->first();

                if($exist_attribute){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Attribute Already exist you should use a different name');
                }else{

                    $data = [
                        'attribute_set_id' =>  $this->request->getPost('attribute_set'),
                        'name' =>  $this->request->getPost('name'),
                        'slug' =>  $this->request->getPost('slug'),
                        'status' =>  1,
                    ];

                    $inserted_attribute_id = $this->attributesTable->insert($data);

                    if ($inserted_attribute_id) {

                        $slug_data = [
                            'type' =>  'attribute_name',
                            'ref_id' =>  $inserted_attribute_id,
                            'slug' =>  $this->request->getPost('slug'),
                        ];
    
                        $slug_added = $this->slugTable->insert($slug_data);

                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Attribute Added Successfully');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }

                }

            }        

            // Validation passed, process the form data
            return redirect()->to('/admin/attributes');

        }else{
            return redirect()->to('/admin/attributes');
        }
    }
    
    public function manage_attribute()
    {
        $data['main_page'] = TABLES . 'manage-attribute';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Attribute | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Attribute | ' . $settings['app_name'];

        $data['attribute_result'] = $this->attributesTable->getAttributesListing();

        return view('admin/template', $data);
    }

    
}