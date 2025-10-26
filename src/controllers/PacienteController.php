<?php

namespace App\Controllers;

use App\Models\PacienteModel;

class PacienteController extends BaseController
{
    private $pacienteModel;

    public function __construct()
    {
        $this->pacienteModel = new PacienteModel();
    }

    public function index()
    {
        return $this->pacienteModel->getAll();
    }

    public function getDadosPacienteByID(int $idPaciente)
    {
        return $this->pacienteModel->getDadosPacienteByID($idPaciente);
    }

    public function getAtendimentosPacienteByID(int $idPaciente)
    {
        return $this->pacienteModel->getAtendimentosPacienteByID($idPaciente);
    }

    //Retorna todos os dados 'Básicos' listados no Datatable de pacientes
    public function getAllBasicData()
    {
        return $this->pacienteModel->getAllBasicData();
    }

    public function getAllById(string $table, string $column, int $id): array
    {
        try {
            return $this->pacienteModel->getAllById($table, $column, $id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getById(string $table, string $column, int $id): array
    {
        try {
            return $this->pacienteModel->getById($table, $column, $id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getExamesComplementares(int $idPaciente): array
    {
        try {
            return $this->pacienteModel->getExamesComplementares($idPaciente);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getHistoricoSaude(int $idPaciente): array
    {
        try {
            return $this->pacienteModel->getHistoricoSaude($idPaciente);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function excluirExameComplementar(int $idExame): int
    {
        try {
            return $this->pacienteModel->excluirExameComplementar($idExame);
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
