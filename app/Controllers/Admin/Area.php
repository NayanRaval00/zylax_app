<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Countries as CountriesModel;
use App\Models\Cities as CitiesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Area extends Controller
{

    public $countriesTable, $citiesTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->countriesTable = new CountriesModel();
        $this->citiesTable = new CitiesModel();
    }

    // public function index()
    // {
    //     $data['main_page'] = TABLES . 'sales-report';

    //     $settings = get_settings('system_settings', true);
    //     $data['title'] = 'Sales Report | ' . $settings['app_name'];
    //     $data['meta_description'] = 'Sales Report | ' . $settings['app_name'];

    //     return view('admin/template', $data);
    // }

    public function manage_countries()
    {
        $data['main_page'] = TABLES . 'manage-countries';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Countries Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Countries Management | ' . $settings['app_name'];

        $data['countries_result'] = $this->countriesTable
        ->orderBy('id', 'ASC')
        ->findAll();

        return view('admin/template', $data);
    }
    
    public function manage_cities()
    {
        $data['main_page'] = TABLES . 'manage-city';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Countries Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Countries Management | ' . $settings['app_name'];

        $data['cities_result'] = $this->citiesTable
        ->orderBy('id', 'ASC')
        ->findAll();

        return view('admin/template', $data);
    }
  
    public function manage_zipcodes()
    {
        $data['main_page'] = TABLES . 'manage-zipcodes';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Countries Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Countries Management | ' . $settings['app_name'];

        $data['city'] = $this->citiesTable
        ->orderBy('id', 'ASC')
        ->findAll();

        return view('admin/template', $data);
    }
  
    
}