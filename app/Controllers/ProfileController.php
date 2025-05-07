<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AddressModel;
use App\Controllers\BaseController;
use App\Models\CityModel;
use App\Models\Countries;
use App\Models\StateModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;
use App\Models\orders;
use App\Models\Products;
use App\Models\ProductsVariants;
use App\Models\WishlistModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        // Load UserModel
        $userModel = new UserModel();

        // Fetch user data from the database
        $userData = $userModel->find($userId);

        $countryModel = new Countries();
        $countries = $countryModel->select("name, id")->whereIn('iso3', ['USA', 'AUS'])->orderBy('name', 'asc')->findAll();

        // echo "<pre>"; print_r($userData); exit;
        $states = [];
        if ($userData['country_code'] > 0) {
            $stateModel = new StateModel();
            $states = $stateModel->where('status', 1)->where('country', $userData['country_code'])->orderBy('state', 'asc')->findAll();
        }

        $cities = [];
        if ($userData['state'] > 0) {
            $cityModel = new CityModel();
            $cities = $cityModel->where('status', 1)->where('state', $userData['state'])->orderBy('name', 'asc')->findAll();
        }

        // Pass data to the view
        return view('frontend/profile', ['user' => $userData, 'countries' => $countries, 'states' => $states, 'cities' => $cities]);
    }

    public function orders()
    {
        $orderModel = new Orders();
        $perPage = 50;
        $user_id = session()->get('user_id'); // Get user ID from session

        $orderDetails = $orderModel->getOrderUserDetails($user_id, $perPage);
        $pager = $orderModel->pager; // Get pagination object

        return view('frontend/orders', [
            'data' => $orderDetails,
            'pagination' => $pager // Send pagination to view
        ]);
    }

    public function showDetails($tracking_id)
    {
        $orderModel = new Orders();
        $data['orders_details'] = $orderModel->getOrderDetails($tracking_id);
        return view('frontend/order_details', $data);
    }
    public function wishlists()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('login')->with('error', 'Please login to access your wishlist.');
        }

        $userId = session()->get('user_id');
        $wishlistModel = new WishlistModel();
        $productModel = new Products();
        $productVariants = new ProductsVariants();

        // Get wishlist product_ids
        $wishlistItems = $wishlistModel->where('user_id', $userId)->findAll();
        $productIds = array_column($wishlistItems, 'product_id');

        $products = [];

        if (!empty($productIds)) {
            $db = \Config\Database::connect();

            $builder = $db->table('products');
            $builder->select('products.*, product_variants.price');
            $builder->join('product_variants', 'product_variants.product_id = products.id', 'left');
            $builder->whereIn('products.id', $productIds);
            $builder->groupBy('products.id'); 
            $builder->orderBy('products.id','DESC'); 
            $products = $builder->get()->getResultArray();
        }

        return view('frontend/wishlists', ['products' => $products]);
    }


    public function changepassword()
    {
        return view('frontend/changepassword');
    }

    public function update()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');
        $userModel = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'fname'    => 'required|min_length[2]|max_length[50]',
            'lname'    => 'required|min_length[2]|max_length[50]',
            'email'    => 'required|valid_email',
            /*'mobile'  => 'required|min_length[10]|max_length[15]|numeric',
            'country'  => 'required',
            'state'    => 'required',
            'city'     => 'required',
            'pincode'  => 'required|min_length[4]|max_length[10]|numeric',
            */
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel->update($userId, [
            'fname'    => $this->request->getPost('fname'),
            'lname'    => $this->request->getPost('lname'),
            'email'    => $this->request->getPost('email'),
            'mobile'    => $this->request->getPost('mobile'),
            'country_code'  => $this->request->getPost('country_code'),
            'state'    => $this->request->getPost('state'),
            'city'     => $this->request->getPost('city'),
            'pincode'  => $this->request->getPost('pincode'),
            'address'  => $this->request->getPost('address'),
        ]);

        return redirect()->to('auth/my-account')->with('success', 'Profile updated successfully!');
    }

    public function uploadProfileImage()
    {
        $session = session();
        $userId = $session->get('user_id'); // Ensure the user is logged in

        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not authenticated']);
        }

        $userModel = new UserModel();
        $file = $this->request->getFile('profileimg');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $extension = $file->getExtension();

            if (!in_array($extension, $allowedExtensions)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file format.']);
            }

            // Generate unique file name
            $newFileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $filePath = 'uploads/profile/' . $newFileName; // File storage path

            // Move the file to the uploads directory
            if ($file->move('uploads/profile/', $newFileName)) {
                // Update user's profile image in the database
                \Config\Services::image()
                    ->withFile($filePath)
                    ->resize(75, 75, true, 'auto')
                    ->save($filePath);

                $userModel->update($userId, ['image' => $filePath]);
                $session->set('user_image', base_url($filePath));

                return $this->response->setJSON([
                    'status' => 'success',
                    'image_url' => base_url($filePath)
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File upload failed.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file.']);
        }
    }

    public function changePasswordSubmit()
    {
        $session = session();
        $userModel = new UserModel();
        $emailService = \Config\Services::email();

        $userId = $session->get('user_id');
        $user = $userModel->find($userId);

        // Define validation rules
        $validationRules = [
            'oldpassword' => 'required',
            'newpassword' => 'required|min_length[6]|max_length[20]|regex_match[/^(?=.*[A-Z])(?=.*\d).{8,}$/]',
            'confirmpassword' => 'required|matches[newpassword]'
        ];

        $validationMessages = [
            'oldpassword' => [
                'required' => 'Current password is required.'
            ],
            'newpassword' => [
                'required' => 'New password is required.',
                'min_length' => 'New password must be at least 6 characters long.',
                'max_length' => 'New password cannot exceed 20 characters.',
                'regex_match' => 'New password must include at least one uppercase letter and one number.'
            ],
            'confirmpassword' => [
                'required' => 'Confirm password is required.',
                'matches' => 'Confirm password does not match the new password.'
            ]
        ];

        // Run validation
        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldPassword = $this->request->getPost('oldpassword');
        $newPassword = $this->request->getPost('newpassword');

        // Check if old password is correct
        if (!password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->withInput()->with('errors', ['oldpassword' => 'Current password is incorrect.']);
        }

        // Hash and update new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel->update($userId, ['password' => $hashedPassword]);

        $emailHelper = new \App\Libraries\EmailHelper();


        if (!$emailHelper->sendNewPassword($user["email"], $user, $newPassword)) {
            return redirect()->back()->with('errors', ['email' => 'Failed to send email notification.']);
        }

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function manageaddress()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');

        // Load UserModel
        // $userModel = new UserModel();
        $addressModel = new AddressModel();

        // Fetch user data from the database
        // $userData = $userModel->find($userId);

        // $countryModel = new Countries();
        // $countries = $countryModel->select("name, id")->whereIn('iso3', ['USA', 'AUS'])->orderBy('name','asc')->findAll();

        $userAddress = $addressModel->select("
                        address.*
                    ")
            ->where("address.user_id", $userId)
            ->findAll();

        // $states = [];
        // $stateModel = new StateModel();
        // $states = $stateModel->where('status',1)->orderBy('state','asc')->findAll();

        // $cities = [];
        // $cityModel = new CityModel();
        // $cities = $cityModel->where('status',1)->orderBy('name','asc')->findAll();

        return view('frontend/manage-address', ['address' => $userAddress]);
    }

    public function insertAddress()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');
        $addressModel = new AddressModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'     => 'required|valid_email',
            'phoneno'   => 'required|numeric|min_length[10]|max_length[15]',
            'company'   => 'required',
            'state'     => 'required',
            'city'    => 'required',
            'pincode'   => 'required',
            'address_1'   => 'required'

        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $insstatus = $this->request->getPost('status_addr');

        $addressModel->insert([
            'user_id'      => $userId,
            'email'        => $this->request->getPost('email'),
            'phoneno'      => $this->request->getPost('phoneno'),
            'company'      => $this->request->getPost('company'),
            'address_1'      => $this->request->getPost('address_1'),
            'address_2'      => $this->request->getPost('address_2'),
            'state'        => $this->request->getPost('state'),
            'city'         => $this->request->getPost('city'),
            'pincode'      => $this->request->getPost('pincode'),
            'status_addr'  => $insstatus,
        ]);

        $lastInsertedID = $addressModel->insertID();
        if ($insstatus == '1') {
            $upstatus = 0;
            $addressModel->whereNotIn('address_id', [$lastInsertedID])
                ->set(['status_addr' => $upstatus])
                ->update();
        }

        return redirect()->to('auth/manage-address')->with('success', 'Address Insert successfully!');
    }

    public function deleteAddress($id)
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');
        $addressModel = new AddressModel();

        if ($addressModel->delete($id)) {
            return redirect()->to('auth/manage-address')->with('success', 'Address deleted successfully.');
        } else {
            return redirect()->to('auth/manage-address')->with('errors', 'Something went wrong.');
        }
    }

    public function updateAddress()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userId = $session->get('user_id');
        $addr_id = $this->request->getPost('address_id');
        $addressModel = new AddressModel();

        $insstatus = $this->request->getPost('status_addr_update');


        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'     => 'required|valid_email',
            'phoneno'   => 'required|numeric|min_length[10]|max_length[15]',
            'company'   => 'required',
            'state'     => 'required',
            'city'    => 'required',
            'pincode'   => 'required',
            'address_1'   => 'required'

        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $addressModel->update($addr_id, [
            'email'        => $this->request->getPost('email'),
            'phoneno'      => $this->request->getPost('phoneno'),
            'company'      => $this->request->getPost('company'),
            'address_1'      => $this->request->getPost('address_1'),
            'address_2'      => $this->request->getPost('address_2'),
            'state'        => $this->request->getPost('state'),
            'city'         => $this->request->getPost('city'),
            'pincode'      => $this->request->getPost('pincode'),
            'status_addr'  => $insstatus,
        ]);

        if ($insstatus == '1') {
            $upstatus = 0;
            $addressModel->whereNotIn('address_id', [$addr_id])
                ->set(['status_addr' => $upstatus])
                ->update();
        }


        return redirect()->to('auth/manage-address')->with('success', 'Address updated successfully!');
    }
}
