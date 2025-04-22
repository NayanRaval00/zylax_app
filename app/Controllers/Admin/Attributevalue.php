<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Attributes as AttributesModel;
use App\Models\AttributeSet as AttributeSetModel;
use App\Models\AttributeValues as AttributeValuesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Attributevalue extends Controller
{

    public $attributesTable, $attributeSetTable, $attributeValuesTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->attributesTable = new AttributesModel();
        $this->attributeSetTable = new AttributeSetModel();
        $this->attributeValuesTable = new AttributeValuesModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'attribute-value';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Attribute Set | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Attribute Set | ' . $settings['app_name'];

        $data['attributes'] = $this->attributesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_attribute_value()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'attributes_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Attributes is required!',
                    ]
                ],
                'value' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Attribute Value is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/attributevalue');
            }
            
            // Get uploaded file
            $swatche_color = $this->request->getPost('swatche_value');
            $swatche_image = $this->request->getFile('swatche_image');

            if($this->request->getPost('swatche_type') == 1 && $swatche_color == ""){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Attribute Color is required');
            }elseif($this->request->getPost('swatche_type') == 2 && $swatche_image == ""){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Attribute Image is required');
            }else{

                $exist_attribute_value = $this->attributeValuesTable
                ->where('attribute_id', $this->request->getPost('attribute_set'))
                ->where('value', $this->request->getPost('name'))
                ->first();

                if($exist_attribute_value){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Attribute Value Already exist you should use a different name');
                }else{

                    if($swatche_image != ""){
                        // Move the file to the public/uploads directory
                        $mainImage = $swatche_image->getRandomName();
                        $swatche_image->move('uploads/attributes/category', $mainImage);
                    }

                    $data = [
                        'attribute_id' =>  $this->request->getPost('attributes_id'),
                        'value' =>  $this->request->getPost('value'),
                        'swatche_type' =>  $this->request->getPost('swatche_type'),
                        'swatche_value' => $swatche_image != "" ? "uploads/attributes/category/".$mainImage : $this->request->getPost('swatche_value'),
                        'status' =>  1,
                    ];

                    $inserted = $this->attributeValuesTable->insert($data);

                    if ($inserted) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Attribute Value Added Successfully');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }

                }

            }
            
            // Validation passed, process the form data
            return redirect()->to('/admin/attributevalue');

        }else{
            return redirect()->to('/admin/attributevalue');
        }
    }

    public function update_attribute_value_status($edit_id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $updated = $this->attributeValuesTable->update($edit_id, $data);

        if ($updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Attribute Value status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/attributevalue/manage_attribute_value');
    }
    
    public function manage_attribute_value()
    {
        $data['main_page'] = TABLES . 'manage-attribute-value';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Attribute Value | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Attribute Value | ' . $settings['app_name'];

        $data['attribute_values_result'] = $this->attributeValuesTable->getAttributeValuesListing();

        return view('admin/template', $data);
    }
    
}