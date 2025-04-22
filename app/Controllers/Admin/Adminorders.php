<?php

namespace App\Controllers\Admin;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\orders as live_order;
use App\Models\Transaction;
use App\Models\Tracking_logs;
use Dompdf\Dompdf;
use Dompdf\Options;

use CodeIgniter\Controller;

class Adminorders extends Controller
{
    public $settingTable; public $live_order;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->settingTable = new SettingsModel();
        $this->live_order = new live_order();

    }

    public function index()
    {
        $data['main_page'] = TABLES . 'orders';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Orders | ' . $settings['app_name'];
        $data['meta_description'] = 'Orders | ' . $settings['app_name'];

        $data['orders'] = get_settings('orders');

        return view('admin/template', $data);
    }

    public function fetch_order()
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
            'order_status'     => $request->getPost('order_status') ?? 'all',
            'payment_status'   => $request->getPost('payment_status') ?? 'all',
        ];

        // print_r($filters);
        // die;
        
        // Fetch order details with optional filters
        $orders = $this->live_order->getOrderDetails(null, $filters);
        
        // Apply search filter if applicable
        if (!empty($searchValue)) {
            $orders = array_filter($orders, function ($order) use ($searchValue) {
                return stripos($order['shipping_name'], $searchValue) !== false ||
                       stripos($order['billing_name'], $searchValue) !== false ||
                       stripos($order['product_name'], $searchValue) !== false;
            });
        }
        
        // Get total and filtered records
        $totalRecords = count($this->live_order->getOrderDetails(null, $filters));
        $filteredRecords = count($orders);
        
        // Always apply pagination
        $orders = array_slice(array_values($orders), $start, $length);
        
        // Format data for DataTables
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                '#' . $order['tracking_order_id'],
                '#' . $order['tran_id'],
                '$' . number_format($order['tran_total_amt'], 2),
                $order['billing_email'],
                $order['payment_source'],
                $order['order_status'],
                date("d-M-Y : h:iA", strtotime($order['created_at'])),
                '<a href="' . base_url('admin/adminorders/showDetails/' . $order['tracking_order_id']) . '" target="_blank" class="btn btn-primary btn-sm">View</a>',
                '<a href="' . base_url('admin/adminorders/downloadInvoice/' . $order['tracking_order_id']) . '" target="_blank" class="btn btn-primary btn-sm">Print</a>',
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
    
    

    public function showDetails($tracking_id)
    {
        $data['main_page'] = TABLES . 'orders_details';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Orders | ' . $settings['app_name'];
        $data['meta_description'] = 'Orders | ' . $settings['app_name'];

        $data['orders'] = get_settings('orders');

        $data['orders_details'] = $this->live_order->getOrderDetails($tracking_id);

        return view('admin/template', $data);
    }

    public function downloadInvoice($tracking_id)
    {
        // Fetch order details
        $data['orders_details'] = $this->live_order->getOrderDetails($tracking_id);

        // Create a new instance of DOMPDF
        $dompdf = new Dompdf();

        // Set options for DOMPDF (optional)
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        // Load the view and get HTML content
        $htmlContent = view('admin/pages/view/download_Invoice', $data);

        // Load the HTML content into DOMPDF
        $dompdf->loadHtml($htmlContent);

        // (Optional) Set paper size
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('defaultFont', 'DejaVu Sans');

        $dompdf->set_option('margin_top', 0);
        $dompdf->set_option('margin_right', 0);
        $dompdf->set_option('margin_bottom', 0);
        $dompdf->set_option('margin_left', 0);

        // Render the PDF
        $dompdf->render();

        // Stream the PDF to the browser for download
        $dompdf->stream("invoice_{$tracking_id}.pdf", array("Attachment" => 1));
    }

    public function status_update(){
        $data = [
            'tracking_id' => $_POST['tracking_order_id'],
            'status' => $_POST['order_status']
        ];

        $transactionModel = new Transaction();
        $tracking_logs = new Tracking_logs();


        // Update the transaction where tracking_order_id matches
        $inserted  = $tracking_logs->insert($data);

        if ($inserted ) {
            $transactionModel->where('tracking_order_id', $_POST['tracking_order_id'])
                         ->set(['order_status' => $_POST['order_status']])
                         ->update();
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update status'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    // public function fetchOrderFilter()
    // {
    //     $request = $this->request->getPost();

    //     $filters = [
    //         'order_daterange'  => $request['order_daterange'] ?? '',
    //         'orderEmail'       => $request['orderEmail'] ?? '',
    //         'order_status'     => $request['order_status'] ?? 'all',
    //         'payment_status'   => $request['payment_status'] ?? 'all',
    //     ];

    //     $orders = $this->live_order->getOrderDetails(null, $filters);

    //     $data = [];
    //     foreach ($orders as $order) {
    //         $data[] = [
    //             '#'.$order['tracking_order_id'],
    //             '#'.$order['tran_id'],
    //             '$'.number_format($order['tran_total_amt'], 2),
    //             $order['billing_email'],
    //             $order['payment_source'],
    //             $order['order_status'],
    //             date("d-M-Y : h:iA", strtotime($order['created_at'])),
    //             '<a href="'.base_url('admin/adminorders/showDetails/'.$order['tracking_order_id']).'" class="btn btn-primary btn-sm">View</a>',
    //         ];
    //     }

        
    
    //     // Return JSON response
    //     return $this->response->setJSON([
    //         "recordsTotal" => $totalRecords,
    //         "recordsFiltered" => $filteredRecords,
    //         "data" => $data
    //     ]);
    // }

}