<?php

namespace App\Models;

use PDOException;
use App\Config\Database;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }

    public function getById(string $table, string $column, int $id): array
    {
        $result = $this->db->select(
            "SELECT * FROM {$table} WHERE {$column} = :id_value",
            ['id_value' =>  $id]
        );
        return $result[0] ?? [];
    }

    public function getAllByTable(string $table): array
    {
        $result = $this->db->select("SELECT *, DATE_FORMAT(CRIADO_EM, '%d/%m/%Y') AS CRIADO_EM FROM {$table}");
        return $result ?? [];
    }

    public function getAllById(string $table, string $column, int $id): array
    {
        $result = $this->db->select(
            "SELECT
                *,
                DATE_FORMAT(CRIADO_EM, '%d/%m/%Y') AS criado_em_formatado
            FROM
                {$table}
            WHERE
                {$column} = :id_value
            ORDER BY
                CRIADO_EM",
            ['id_value' =>  $id]
        );
        return $result ?? [];
    }

    public function getExamesComplementares(int $idPaciente)
    {
        $sql = "SELECT
                    ID,
                    ID_PACIENTE,
                    DESCRICAO,
                    ARQUIVO,
                    DATE_FORMAT(CRIADO_EM, '%d/%m/%Y') AS CRIADO_EM
                FROM
                    BIO_EXAMES
                WHERE
                    ID_PACIENTE = :id_paciente
                ORDER BY
                    CRIADO_EM DESC";
        return $this->db->select($sql, ['id_paciente' => $idPaciente]);
    }

    public function inserirNovoAnexoExame(int $idPaciente, string $descricao, string $caminho_arquivo)
    {
        return $this->db->insert(
            "bio_exames",
            [
                'id_paciente' => $idPaciente,
                'descricao' => !empty($descricao) ? $descricao : NULL,
                'arquivo' => !empty($caminho_arquivo) ? $caminho_arquivo : NULL,
            ]
        );
    }

    public function updateAnexoExame(int $idPaciente, string $descricao)
    {
        return $this->db->update(
            'bio_exames',
            [
                'descricao' => !empty($descricao) ? $descricao : NULL,
            ],
            [
                'id_paciente' => $idPaciente
            ]
        );
    }

    public function excluirExameComplementar(int $idExame)
    {
        $fields = $this->db->select(
            "SELECT ARQUIVO FROM BIO_EXAMES WHERE ID = :id",
            ['id' => $idExame]
        );

        $arquivo = $fields[0]['ARQUIVO'] ?? null;

        $result = $this->db->delete(
            "bio_exames",
            ['id' => $idExame]
        );

        if ($result && !empty($arquivo)) {
            $caminhoCompleto = __DIR__ . '/../../public/' . $arquivo;
            if (file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            }
        }

        return $result;
    }
}
