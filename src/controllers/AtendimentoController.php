<?php

namespace App\Controllers;

use App\Models\AtendimentoModel;

class AtendimentoController
{
    private $atendimentoModel;

    public function __construct()
    {
        $this->atendimentoModel = new AtendimentoModel();
    }

    public function getAllById(string $table, string $column, int $id): array
    {
        try {
            return $this->atendimentoModel->getAllById($table, $column, $id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getById(string $table, string $column, int $id): array
    {
        try {
            return $this->atendimentoModel->getById($table, $column, $id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getAllByTable(string $table): array
    {
        try {
            return $this->atendimentoModel->getAllByTable($table);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function excluirExameComplementar(int $idExame): int
    {
        try {
            return $this->atendimentoModel->excluirExameComplementar($idExame);
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function getAllDataAtendimento(): array
    {
        try {
            return $this->atendimentoModel->getAllDataAtendimento();
        } catch (\Throwable $th) {
            return [];
        }
    }
}
