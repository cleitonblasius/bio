<?php

namespace App\Models;

class AtendimentoModel extends BaseModel
{
    public function getById(string $table, string $column, int $id): array
    {
        $result = $this->db->select(
            "SELECT * FROM {$table} WHERE {$column} = :id_value",
            ['id_value' =>  $id]
        );
        return $result[0] ?? [];
    }

    public function insertBioAtendimentos(array $request): int
    {
        return $this->db->insert(
            "bio_atendimentos",
            [
                'status' => 1,
                'id_paciente' => $request['id_paciente'],
                'queixa_principal' => !empty($request['queixa_principal']) ? $request['queixa_principal'] : NULL,
                'problema' => !empty($request['problema']) ? $request['problema'] : NULL,
                'estado_emocional' => !empty($request['estado_emocional']) ? $request['estado_emocional'] : NULL,
            ]
        );
    }

    public function updateBioPacientesAtend(array $request)
    {
        $this->db->update(
            "bio_pacientes",
            [
                'marcapasso' => !empty($request['marcapasso']) ? ($request['marcapasso'] == 'on' ? 1 : 2) : NULL,
                'gestante' => !empty($request['gestante']) ? ($request['gestante'] == 'on' ? 1 : 2) : NULL,
                'risco_gestante' => !empty($request['risco_gestante']) ? ($request['risco_gestante'] == 'on' ? 1 : 2) : NULL,
                'usa_diu' => !empty($request['usa_diu']) ? ($request['usa_diu'] == 'on' ? 1 : 2) : NULL,
                'contraceptivos' => !empty($request['contraceptivos']) ? ($request['contraceptivos'] == 'on' ? 1 : 2) : NULL,
                'possui_protese' => !empty($request['possui_protese']) ? ($request['possui_protese'] == 'on' ? 1 : 2) : NULL,
            ],
            [
                'id' => $request['id_paciente']
            ]
        );
    }

    public function getAllDataAtendimento(): array
    {
        $result = $this->db->select(
            "SELECT
                BA.ID,
                BA.ID_PACIENTE,
                BA.ESTADO_EMOCIONAL,
                BA.QUEIXA_PRINCIPAL,
                BA.PROBLEMA,
                BP.NOME,
                DATE_FORMAT(BA.CRIADO_EM, '%d/%m/%Y') AS CRIADO_EM_DMY
            FROM
                BIO_ATENDIMENTOS BA
            INNER JOIN BIO_PACIENTES BP ON
                (BA.ID_PACIENTE = BP.ID)
            ORDER BY
                BA.CRIADO_EM DESC",
        );
        return $result ?? [];
    }

    public function getRatreiosAtendimento(): array
    {
        $result = $this->db->select("SELECT ID_ATENDIMENTO FROM BIO_ATENDIMENTO_RASTREIO");
        return $result ?? [];
    }

    public function insertBioAtendimentoRastreio(int $idAtendimento, string $codigoPar, string $sintomas)
    {
        return $this->db->insert(
            "bio_atendimento_rastreio",
            [
                'id_atendimento' => $idAtendimento,
                'codigo_par' => $codigoPar,
                'sintomas' => !empty($sintomas) ? $sintomas : NULL,
            ]
        );
    }

    public function updateBioAtendimentoRastreio(int $idAtendimento, string $codigoPar, string $sintomas)
    {
        return $this->db->update(
            "bio_atendimento_rastreio",
            [
                'sintomas' => $sintomas ?? NULL
            ],
            [
                'id_atendimento' => $idAtendimento,
                'codigo_par' => $codigoPar
            ]
        );
    }
}
