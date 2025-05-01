<?php

namespace App\Controllers\Admin;

use App\Models\AdminModel;
use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\CityModel;
use App\Models\Countries;
use App\Models\StateModel;
use App\Models\AddressModel;

class Customer extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES . 'manage-customer';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Customer | ' . $settings['app_name'];
        $data['meta_description'] = 'View Customer | ' . $settings['app_name'];
        return view('admin/template', $data);
    }

    public function addresses()
    {
        $data['main_page'] = TABLES . 'manage-address';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Address | ' . $settings['app_name'];
        $data['meta_description'] = 'View Address | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function fetch_user()
    {
        $request = service('request');
        $draw = (int) $request->getPost('draw') ?? 1;
        $start = (int) $request->getPost('start') ?? 0;
        $length = (int) $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Filters from request
        $filters = [
            'order_daterange'  => $request->getPost('order_daterange') ?? '',
            'orderID'       => $request->getPost('orderEmail') ?? '',
            'userstatus' => $request->getPost('userstatus') ?? '',
            'usertype' => $request->getPost('usertype') ?? '',
        ];
        // Fetch order details with optional filters
        $UserModel = new UserModel();
        $orders = $UserModel->getUserDetails($filters);
        
        // Apply search filter if applicable
        // if (!empty($searchValue)) {
        //     $orders = array_filter($orders, function ($order) use ($searchValue) {
        //         return stripos($order['shipping_name'], $searchValue) !== false ||
        //                stripos($order['billing_name'], $searchValue) !== false ||
        //                stripos($order['product_name'], $searchValue) !== false;
        //     });
        // }
        
        // Get total and filtered records
        $totalRecords = count($UserModel->getUserDetails($filters));
        $filteredRecords = count($orders);
        
        // Always apply pagination
        $orders = array_slice(array_values($orders), $start, $length);
        
        // Format data for DataTables
        $data = [];
        $i = 1;
        foreach ($orders as $order) {
            $data[] = [
                $i++,
                $order['username'],
                $order['email'],
                $order['company'],
                $order['mobile'],
                $order['user_type'] === 'guest' ? 'guest' : 'regular',
                date("d-M-Y : h:iA", strtotime($order['created_at'])),
                $order['active'] == '1' ? 'Active' : 'Inactive',
                '<a href="' . base_url('admin/customer/edituser/' . $order['id']) . '" target="_blank" class="btn btn-primary btn-sm">Edit</a>
                <a href="' . base_url('admin/customer/userAddress/' . $order['id']) . '" target="_blank" class="btn btn-warning btn-sm">Address</a>
                <a href="' . base_url('admin/customer/deleteuser/' . $order['id']) . '" target="_blank" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>',
            ];            
        }
        
        // Return JSON response
        return $this->response->setJSON([
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }

    public function adminusers(){
        $data['main_page'] = TABLES . 'manage-adminusers';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Admin | ' . $settings['app_name'];
        $data['meta_description'] = 'View Admin | ' . $settings['app_name'];
        return view('admin/template', $data);
    }

    public function fetch_admin()
    {
        $request = service('request');
        $draw = (int) $request->getPost('draw') ?? 1;
        $start = (int) $request->getPost('start') ?? 0;
        $length = (int) $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Filters from request
        $filters = [
            'order_daterange'  => $request->getPost('order_daterange') ?? '',
            'orderID'       => $request->getPost('orderEmail') ?? '',
        ];
        
        // Fetch order details with optional filters
        $UserModel = new AdminModel();
        $orders = $UserModel->getAdminDetails($filters);
        
        // Apply search filter if applicable
        // if (!empty($searchValue)) {
        //     $orders = array_filter($orders, function ($order) use ($searchValue) {
        //         return stripos($order['shipping_name'], $searchValue) !== false ||
        //                stripos($order['billing_name'], $searchValue) !== false ||
        //                stripos($order['product_name'], $searchValue) !== false;
        //     });
        // }
        
        // Get total and filtered records
        $totalRecords = count($UserModel->getAdminDetails($filters));
        $filteredRecords = count($orders);
        
        // Always apply pagination
        $orders = array_slice(array_values($orders), $start, $length);
        
        // Format data for DataTables
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                $order['id'],
                $order['username'],
                $order['email'],
                $order['mobile'],
            ];
        }
        
        // Return JSON response
        return $this->response->setJSON([
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }
    
    public function deleteuser($id){
        $session = session();
        $userModel = new UserModel();

        if ($userModel->find($id)) {
            $userModel->delete($id);
            session()->setFlashdata('success', 'User Delete successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to update user.');
        }

        return redirect()->to('/admin/customer/');
    }

    public function edituser($id){
        $data['main_page'] = FORMS . 'edit-user';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'editusers | ' . $settings['app_name'];
        $data['meta_description'] = 'editusers | ' . $settings['app_name'];

        // $UserModel = new UserModel();
        // $data['user'] = $UserModel->getUserByUserid($id);


        $userModel = new UserModel();

        // Fetch user data from the database
        $userData = $userModel->find($id);

        $countryModel = new Countries();
        $data['countries'] = $countryModel->select("name, id")->whereIn('iso3', ['USA', 'AUS'])->orderBy('name','asc')->findAll();

        // echo "<pre>"; print_r($userData); exit;
        $states = [];
        if($userData['country_code'] > 0)
        {
            $stateModel = new StateModel();
            $states = $stateModel->where('status',1)->where('country',$userData['country_code'])->orderBy('state','asc')->findAll();
        }

        $cities = [];
        if($userData['state'] > 0)
        {
            $cityModel = new CityModel();
            $cities = $cityModel->where('status',1)->where('state',$userData['state'])->orderBy('name','asc')->findAll();
        }

        $data['user'] = $userData;
        $data['states'] = $states;
        $data['cities'] = $cities;
        return view('admin/template', $data);
    }

    public function updateusers()
    {
        $request = \Config\Services::request();
        $session = session();
    
        $userID = $request->getPost('user_id');
    
        // Only hash password if it's set
        $password = $request->getPost('password');
        $passwordHash = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
    
        $data = [
            'fname'        => $request->getPost('fname'),
            'lname'        => $request->getPost('lname'),
            'email'        => $request->getPost('email'),
            'mobile'       => $request->getPost('mobile'),
            'country_code' => $request->getPost('country_code'),
            'state'        => $request->getPost('state'),
            'city'         => $request->getPost('city'),
            'pincode'      => $request->getPost('pincode'),
            'address'      => $request->getPost('address'),
            'active'       => $request->getPost('status'),
            'company'       => $request->getPost('company'),
        ];

        // Only include password if it's not empty
        if ($passwordHash) {
            $data['password'] = $passwordHash;
        }
    
        $userModel = new UserModel();
    
        if ($userModel->update($userID, $data)) {
            $session->setFlashdata('success', 'User updated successfully.');
        } else {
            $session->setFlashdata('error', 'Failed to update user.');
        }
    
        return redirect()->to('/admin/customer/edituser/' . $userID);
    }
    
    public function userAddress($id){
        $data['main_page'] = VIEW . 'user_address';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'user_address | ' . $settings['app_name'];
        $data['meta_description'] = 'user_address | ' . $settings['app_name'];

        $AddressModel = new AddressModel();

        $data['address'] = $AddressModel->where('user_id', $id)->findAll();


        // print_r($data['address']);
        // die;

        return view('admin/template',$data);
    }
    
}