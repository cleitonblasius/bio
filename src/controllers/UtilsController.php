<?php

namespace App\Controllers;

use App\Models\UtilsModel;

class UtilsController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new UtilsModel();
    }

    public function getMonthBirthdays()
    {
        return $this->model->getMonthBirthdays();
    }
    
    public function getBioMagneticPairsArray()
    {
        return $this->model->getBioMagneticPairsArray();
    }
}
