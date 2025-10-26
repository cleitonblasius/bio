<?php

namespace App\Controllers;

use App\Models\TerapeutaModel;

class TerapeutaController extends BaseController
{
    private $terapeutaModel;

    public function __construct()
    {
        $this->terapeutaModel = new TerapeutaModel();
    }

    public function index()
    {
        return $this->terapeutaModel->getAll();
    }

    public function getById(string $table, string $column, int $id): array
    {
        try {
            return $this->terapeutaModel->getById($table, $column, $id);
        } catch (\Throwable $th) {
            return [];
        }
    }
}
