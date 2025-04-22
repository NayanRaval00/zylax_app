<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Tickets as TicketsModel;
use App\Models\TicketTypes as TicketTypesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Tickets extends Controller
{

    public $ticketsTable, $ticketTypesTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->ticketsTable = new TicketsModel();
        $this->ticketTypesTable = new TicketTypesModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'tickets';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Ticket System | ' . $settings['app_name'];
        $data['meta_description'] = 'Ticket System | ' . $settings['app_name'];

        $data['ticket_result'] = $this->ticketsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }
    
    public function ticket_types()
    {
        $data['main_page'] = TABLES . 'manage-ticket-types';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Ticket Types | ' . $settings['app_name'];
        $data['meta_description'] = 'Ticket Types | ' . $settings['app_name'];

        $data['ticket_types_result'] = $this->ticketTypesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function manage_ticket_types()
    {
        $data['main_page'] = FORMS . 'ticket-type';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Ticket Type | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Ticket Type , Create Brand | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_ticket_type()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The title is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/tickets/manage_ticket_types');
            }

            $data = [
                'title' =>  $this->request->getPost('title'),
            ];

            $added = $this->ticketTypesTable->insert($data);

            if ($added) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Ticket Type Added Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/tickets/manage_ticket_types');

        }else{
            return redirect()->to('/admin/tickets/manage_ticket_types');
        }
    }

    public function delete_ticket_type()
    {
        $ticket_type_id = $this->request->getGet('id');
        $deleted = $this->ticketTypesTable->delete($ticket_type_id);

        if ($deleted) {
           $response['error'] = false;
           $response['message'] = 'Ticket Types Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Ticket Types not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function edit_ticket_type()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-ticket-type';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Ticket Types | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Ticket Types | ' . $settings['app_name'];

            $data['fetched_data'] = $this->ticketTypesTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/brand');
        }
    }

    public function update_ticket_type()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Title is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/tickets/edit_ticket_type?edit_id='.$this->request->getPost('edit_ticket_type'));
            }

            $edit_id = $this->request->getPost('edit_ticket_type');

            $data = [
                'title' =>  $this->request->getPost('title'),
            ];

            // dd($data);
            $updated = $this->ticketTypesTable->update($edit_id, $data);

            if ($updated) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Ticket Type updated Successful!');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/tickets/edit_ticket_type?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/tickets');
        }
    }
    
}