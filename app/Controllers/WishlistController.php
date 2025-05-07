<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use CodeIgniter\Controller;

class WishlistController extends Controller
{
    public function toggle()
    {
        $request = service('request');
        $user_id = $request->getPost('user_id');
        $product_id = $request->getPost('product_id');

        if (!$user_id || !$product_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing data']);
        }

        $wishlistModel = new WishlistModel();

        $exists = $wishlistModel
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($exists) {
            $wishlistModel
                ->where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->delete();

            return $this->response->setJSON(['status' => 'removed']);
        } else {
            $wishlistModel->insert([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->response->setJSON(['status' => 'added']);
        }
    }
}
