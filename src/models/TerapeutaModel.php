<?php

namespace App\Models;

class TerapeutaModel extends BaseModel
{
    public function getAll()
    {
        return $this->db->select("SELECT * FROM bio_terapeutas");
    }

    public function getById(string $table, string $column, int $id): array
    {
        $result = $this->db->select(
            "SELECT * FROM {$table} WHERE {$column} = :id_value",
            ['id_value' =>  $id]
        );
        //print_r("SELECT * FROM {$table} WHERE id = {$id}");
        return $result[0] ?? [];
    }

    public function insertBioTerapeuta(array $request): int
    {
        return $this->db->insert(
            "bio_terapeutas",
            [
                'nome' => $request['nome'],
                'email' => !empty($request['email']) ? $request['email'] : NULL,
                'cpf' => !empty($request['cpf']) ? removerCaracteresNaoNumericos($request['cpf']) : NULL,
                'data_nascimento' => empty($request['data_nascimento']) ? NULL : $request['data_nascimento'],
                'endereco' => !empty($request['endereco']) ? $request['endereco'] : NULL,
                'telefone_principal' => !empty($request['telefone_principal']) ? removerCaracteresNaoNumericos($request['telefone_principal']) : NULL,
                'telefone_secundario' => !empty($request['telefone_secundario']) ? removerCaracteresNaoNumericos($request['telefone_secundario']) : NULL,
                'google_agenda' => !empty($request['google_agenda']) ? $request['google_agenda'] : NULL,
            ]
        );
    }

    public function updateBioTerapeuta(array $request)
    {
        $this->db->update(
            "bio_terapeutas",
            [
                'nome' => $request['nome'],
                'email' => !empty($request['email']) ? $request['email'] : NULL,
                'cpf' => !empty($request['cpf']) ? removerCaracteresNaoNumericos($request['cpf']) : NULL,
                'data_nascimento' => empty($request['data_nascimento']) ? NULL : $request['data_nascimento'],
                'endereco' => !empty($request['endereco']) ? $request['endereco'] : NULL,
                'telefone_principal' => !empty($request['telefone_principal']) ? removerCaracteresNaoNumericos($request['telefone_principal']) : NULL,
                'telefone_secundario' => !empty($request['telefone_secundario']) ? removerCaracteresNaoNumericos($request['telefone_secundario']) : NULL,
                'google_agenda' => !empty($request['google_agenda']) ? $request['google_agenda'] : NULL,
            ],
            [
                'id' => $request['id_terapeuta']
            ]
        );
    }
}
