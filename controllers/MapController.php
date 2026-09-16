<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BloodRequestModel.php';
require_once __DIR__ . '/../config/Config.php';

class MapController extends Controller {

    public function index(): void {
        $userModel = new UserModel();

        $donors = $userModel->getMapLocations();

        $requestModel = new BloodRequestModel();
        $requests     = $requestModel->getAllRequests();

        $this->render('map/index', [
            'donors'      => $donors,
            'requests'    => $requests,
            'gmapsApiKey' => Config::get('google_maps_api_key'),
        ]);
    }
}
