<?php

namespace App\Controllers;

use App\Models\StateModel;
use App\Models\CityModel;
use CodeIgniter\Controller;

class LocationController extends Controller
{
    public function getStates()
    {
        $stateModel = new StateModel();
        $country_id = $this->request->getPost('country_id');

        $states = $stateModel->where('country', $country_id)->findAll();

        $options = '<option value="">Select...</option>';
        foreach ($states as $state) {
            $options .= '<option value="' . $state['id'] . '">' . $state['state'] . '</option>';
        }

        return $this->response->setStatusCode(200)->setBody($options);
    }

    public function getCities()
    {
        $cityModel = new CityModel();
        $state_id = $this->request->getPost('state_id');

        $cities = $cityModel->where('state', $state_id)->findAll();

        $options = '<option value="">Select...</option>';
        foreach ($cities as $city) {
            $options .= '<option value="' . $city['id'] . '">' . $city['name'] . '</option>';
        }

        return $this->response->setStatusCode(200)->setBody($options);
    }
}
