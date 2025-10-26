<?php

namespace App\Models;

class PacienteModel extends BaseModel
{
    public function getAll()
    {
        return $this->db->select("SELECT * FROM bio_pacientes");
    }

    public function getAllBasicData()
    {
        $sql = "SELECT
                    ID,
                    NOME,
                    GENERO,
                    TIMESTAMPDIFF(YEAR, DATA_NASCIMENTO, CURRENT_DATE) AS IDADE,
                    COALESCE(TELEFONE_PESSOAL, TELEFONE_RECADOS) AS TELEFONE,
                    GESTANTE
                FROM
                    BIO_PACIENTES
                ORDER BY
                    NOME";
        return $this->db->select($sql);
    }

    public function getDadosPacienteByID(int $idPaciente)
    {
        $sql = "SELECT
                    *
                FROM
                    BIO_PACIENTES
                WHERE
                    ID = :id_paciente
                ORDER BY
                    NOME";
        $result = $this->db->select($sql, ['id_paciente' => $idPaciente]);
        return $result[0] ?? [];
    }

    public function getAtendimentosPacienteByID(int $idPaciente)
    {
        $sql = "SELECT
                    *
                FROM
                    BIO_ATENDIMENTOS
                WHERE
                    ID = :id_paciente
                ORDER BY
                    CRIADO_EM DESC";
        $result = $this->db->select($sql, ['id_paciente' => $idPaciente]);
        return $result ?? [];
    }

    /* public function getById(string $table, string $column, int $id): array
    {
        $result = $this->db->select(
            "SELECT * FROM {$table} WHERE {$column} = :id_value",
            ['id_value' =>  $id]
        );
        return $result[0] ?? [];
    } */

    /* public function getAllById(string $table, string $column, int $id): array
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
    } */

    public function insertBioPacientes(array $request): int
    {
        return $this->db->insert(
            "bio_pacientes",
            [
                'nome' => $request['nome'],
                'cpf' => removerCaracteresNaoNumericos($request['cpf']),
                'rg' => !empty($request['rg']) ? $request['rg'] : NULL,
                'data_nascimento' => empty($request['data_nascimento']) ? NULL : $request['data_nascimento'],
                'endereco' => !empty($request['endereco']) ? $request['endereco'] : NULL,
                'naturalidade' => !empty($request['naturalidade']) ? $request['naturalidade'] : NULL,
                'telefone_pessoal' => !empty($request['telefone_pessoal']) ? removerCaracteresNaoNumericos($request['telefone_pessoal']) : NULL,
                'telefone_recados' => !empty($request['telefone_recados']) ? removerCaracteresNaoNumericos($request['telefone_recados']) : NULL,
                'email' => !empty($request['email']) ? $request['email'] : NULL,
                'profissao' => !empty($request['profissao']) ? $request['profissao'] : NULL,
                'religiao' => !empty($request['religiao']) ? $request['religiao'] : NULL,
                'genero' => !empty($request['genero']) ? $request['genero'] : NULL,
                'estado_civil' => !empty($request['estado_civil']) ? $request['estado_civil'] : NULL,
                'cor' => !empty($request['cor']) ? $request['cor'] : NULL,
                'escolaridade' => !empty($request['escolaridade']) ? $request['escolaridade'] : NULL,
                'filhos' => !empty($request['filhos']) ? $request['filhos'] : NULL,
                'gestante' => !empty($request['gestante']) ? ($request['gestante'] == 'on' ? 1 : 2) : NULL,
                'marcapasso' => !empty($request['marcapasso']) ? ($request['marcapasso'] == 'on' ? 1 : 2) : NULL,
                'medicamentos' => !empty($request['medicamentos']) ? $request['medicamentos'] : NULL,
                'exames' => !empty($request['exames']) ? $request['exames'] : NULL,
                'obs_terapeuta' => !empty($request['obs_terapeuta']) ? $request['obs_terapeuta'] : NULL,
                'risco_gestante' => !empty($request['risco_gestante']) ? ($request['risco_gestante'] == 'on' ? 1 : 2) : NULL,
                'contraceptivos' => !empty($request['contraceptivos']) ? ($request['contraceptivos'] == 'on' ? 1 : 2) : NULL,
                'usa_diu' => !empty($request['usa_diu']) ? ($request['usa_diu'] == 'on' ? 1 : 2) : NULL,
                'possui_protese' => !empty($request['possui_protese']) ? ($request['possui_protese'] == 'on' ? 1 : 2) : NULL,
            ]
        );
    }

    public function insertBioHistoricoSaude(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_historico_saude",
            [
                'id_paciente' => $idPaciente,
                'doencas_infancia' => !empty($request['doencas_infancia']) ? $request['doencas_infancia'] : NULL,
                'vacinacao' => !empty($request['vacinacao']) ? $request['vacinacao'] : NULL,
                'transfusao' => !empty($request['transfusao']) ? $request['transfusao'] : NULL,
                'doacao_sangue' => !empty($request['doacao_sangue']) ? $request['doacao_sangue'] : NULL,
                'alergias' => !empty($request['alergias']) ? $request['alergias'] : NULL,
                'fraturas' => !empty($request['fraturas']) ? $request['fraturas'] : NULL,
                'cirurgias' => !empty($request['cirurgias']) ? $request['cirurgias'] : NULL,
                'tatuagens' => !empty($request['tatuagens']) ? $request['tatuagens'] : NULL,
                'piercings' => !empty($request['piercings']) ? $request['piercings'] : NULL,
                'doencas_sexuais' => !empty($request['doencas_sexuais']) ? $request['doencas_sexuais'] : NULL,
                'fenomenos_tumorais' => !empty($request['fenomenos_tumorais']) ? $request['fenomenos_tumorais'] : NULL,
                'problemas_memoria' => !empty($request['problemas_memoria']) ? $request['problemas_memoria'] : NULL,
                'problemas_dormir' => !empty($request['problemas_dormir']) ? $request['problemas_dormir'] : NULL,
                'problemas_visao' => !empty($request['problemas_visao']) ? $request['problemas_visao'] : NULL,
                'problemas_audicao' => !empty($request['problemas_audicao']) ? $request['problemas_audicao'] : NULL,
                'problemas_digestivos' => !empty($request['problemas_digestivos']) ? $request['problemas_digestivos'] : NULL,
                'problemas_renais' => !empty($request['problemas_renais']) ? $request['problemas_renais'] : NULL,
                'problemas_respiratorios' => !empty($request['problemas_respiratorios']) ? $request['problemas_respiratorios'] : NULL,
                'problemas_cardiacos' => !empty($request['problemas_cardiacos']) ? $request['problemas_cardiacos'] : NULL,
                'problemas_metabolicos' => !empty($request['problemas_metabolicos']) ? $request['problemas_metabolicos'] : NULL,
                'problemas_psicoemocionais' => !empty($request['problemas_psicoemocionais']) ? $request['problemas_psicoemocionais'] : NULL,
                'problemas_hepaticos' => !empty($request['problemas_hepaticos']) ? $request['problemas_hepaticos'] : NULL,
                'problemas_reprodutor' => !empty($request['problemas_reprodutor']) ? $request['problemas_reprodutor'] : NULL,
                'problemas_musculares' => !empty($request['problemas_musculares']) ? $request['problemas_musculares'] : NULL,
                'problemas_pele' => !empty($request['problemas_pele']) ? $request['problemas_pele'] : NULL,
                'outros_exames' => !empty($request['outros_exames']) ? $request['outros_exames'] : NULL,
            ]
        );
    }

    public function insertBioHistoriaSocial(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_historia_social",
            [
                'id_paciente' => $idPaciente,
                'bebidas_alcoolicas' => !empty($request['bebidas_alcoolicas']) ? $request['bebidas_alcoolicas'] : NULL,
                'tabagismo_drogas' => !empty($request['tabagismo_drogas']) ? $request['tabagismo_drogas'] : NULL,
                'ingestao_agua' => !empty($request['ingestao_agua']) ? $request['ingestao_agua'] : NULL,
                'habitos_sono' => !empty($request['habitos_sono']) ? $request['habitos_sono'] : NULL,
                'habitos_lazer' => !empty($request['habitos_lazer']) ? $request['habitos_lazer'] : NULL,
                'viagens' => !empty($request['viagens']) ? $request['viagens'] : NULL,
                'ambiente_trabalho' => !empty($request['ambiente_trabalho']) ? $request['ambiente_trabalho'] : NULL,
                'atividade_fisica' => !empty($request['atividade_fisica']) ? $request['atividade_fisica'] : NULL,
                'cafe_manha' => !empty($request['cafe_manha']) ? $request['cafe_manha'] : NULL,
                'lanche_manha' => !empty($request['lanche_manha']) ? $request['lanche_manha'] : NULL,
                'almoco' => !empty($request['almoco']) ? $request['almoco'] : NULL,
                'lanche_tarde' => !empty($request['lanche_tarde']) ? $request['lanche_tarde'] : NULL,
                'janta' => !empty($request['janta']) ? $request['janta'] : NULL,
            ]
        );
    }

    public function insertBioHistoriaFisiologica(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_historia_fisiologica",
            [
                'id_paciente' => $idPaciente,
                'nascimento' => !empty($request['nascimento']) ? $request['nascimento'] : NULL,
                'desenvolvimento' => !empty($request['desenvolvimento']) ? $request['desenvolvimento'] : NULL,
                'menstruacao' => !empty($request['menstruacao']) ? $request['menstruacao'] : NULL,
                'primeira_relacao' => !empty($request['primeira_relacao']) ? $request['primeira_relacao'] : NULL,
                'menopausa' => !empty($request['menopausa']) ? $request['menopausa'] : NULL,
                'gestacoes' => !empty($request['gestacoes']) ? $request['gestacoes'] : NULL,
                'qtd_filhos' => !empty($request['qtd_filhos']) ? $request['qtd_filhos'] : NULL,
                'qtd_abortos' => !empty($request['qtd_abortos']) ? $request['qtd_abortos'] : NULL,
            ]
        );
    }

    public function insertBioHistoriaDoenca(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_historia_doenca",
            [
                'id_paciente' => $idPaciente,
                'problema' => !empty($request['problema']) ? $request['problema'] : NULL,
                'tipo_dor' => !empty($request['tipo_dor']) ? $request['tipo_dor'] : NULL,
                'intensidade_dor' => !empty($request['intensidade_dor']) ? $request['intensidade_dor'] : NULL,
                'melhorar' => !empty($request['melhorar']) ? $request['melhorar'] : NULL,
                'desencadeia' => !empty($request['desencadeia']) ? $request['desencadeia'] : NULL,
                'acompanha_sintoma' => !empty($request['acompanha_sintoma']) ? $request['acompanha_sintoma'] : NULL,
                'inicio_doenca' => !empty($request['inicio_doenca']) ? $request['inicio_doenca'] : NULL,
                'situacao_atual' => !empty($request['situacao_atual']) ? $request['situacao_atual'] : NULL,
            ]
        );
    }

    public function insertBioEstruturaFamiliar(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_estrutura_familiar",
            [
                'id_paciente' => $idPaciente,
                'tipo_moradia' => !empty($request['tipo_moradia']) ? $request['tipo_moradia'] : NULL,
                'habitantes' => !empty($request['habitantes']) ? $request['habitantes'] : NULL,
                'animais' => !empty($request['animais']) ? $request['animais'] : NULL,
                'parentes' => !empty($request['parentes']) ? $request['parentes'] : NULL,
                'comunidade' => !empty($request['comunidade']) ? $request['comunidade'] : NULL,
            ]
        );
    }

    public function insertBioDoencasFamilia(array $request, int $idPaciente): int
    {
        return $this->db->insert(
            "bio_doencas_familia",
            [
                'id_paciente' => $idPaciente,
                'mae' => !empty($request['mae']) ? $request['mae'] : NULL,
                'pai' => !empty($request['pai']) ? $request['pai'] : NULL,
                'irmaos' => !empty($request['irmaos']) ? $request['irmaos'] : NULL,
                'avos_maternos' => !empty($request['avos_maternos']) ? $request['avos_maternos'] : NULL,
                'avos_paternos' => !empty($request['avos_paternos']) ? $request['avos_paternos'] : NULL,
            ]
        );
    }

    public function updateBioPacientes(array $request)
    {
        $this->db->update(
            "bio_pacientes",
            [
                'nome' => $request['nome'],
                'rg' => !empty($request['rg']) ? $request['rg'] : NULL,
                'data_nascimento' => empty($request['data_nascimento']) ? NULL : $request['data_nascimento'],
                'endereco' => !empty($request['endereco']) ? $request['endereco'] : NULL,
                'naturalidade' => !empty($request['naturalidade']) ? $request['naturalidade'] : NULL,
                'email' => !empty($request['email']) ? $request['email'] : NULL,
                'telefone_pessoal' => !empty($request['telefone_pessoal']) ? removerCaracteresNaoNumericos($request['telefone_pessoal']) : NULL,
                'telefone_recados' => !empty($request['telefone_recados']) ? removerCaracteresNaoNumericos($request['telefone_recados']) : NULL,
                'profissao' => !empty($request['profissao']) ? $request['profissao'] : NULL,
                'religiao' => !empty($request['religiao']) ? $request['religiao'] : NULL,
                'genero' => !empty($request['genero']) ? $request['genero'] : NULL,
                'cor' => !empty($request['cor']) ? $request['cor'] : NULL,
                'estado_civil' => !empty($request['estado_civil']) ? $request['estado_civil'] : NULL,
                'filhos' => !empty($request['filhos']) ? $request['filhos'] : NULL,
                'escolaridade' => !empty($request['escolaridade']) ? $request['escolaridade'] : NULL,
                'gestante' => !empty($request['gestante']) ? ($request['gestante'] == 'on' ? 1 : 2) : NULL,
                'marcapasso' => !empty($request['marcapasso']) ? ($request['marcapasso'] == 'on' ? 1 : 2) : NULL,
                'medicamentos' => !empty($request['medicamentos']) ? $request['medicamentos'] : NULL,
                'exames' => !empty($request['exames']) ? $request['exames'] : NULL,
                'obs_terapeuta' => !empty($request['obs_terapeuta']) ? $request['obs_terapeuta'] : NULL,
                'risco_gestante' => !empty($request['risco_gestante']) ? ($request['risco_gestante'] == 'on' ? 1 : 2) : NULL,
                'contraceptivos' => !empty($request['contraceptivos']) ? ($request['contraceptivos'] == 'on' ? 1 : 2) : NULL,
                'usa_diu' => !empty($request['usa_diu']) ? ($request['usa_diu'] == 'on' ? 1 : 2) : NULL,
                'possui_protese' => !empty($request['possui_protese']) ? ($request['possui_protese'] == 'on' ? 1 : 2) : NULL,
            ],
            [
                'id' => $request['id_paciente']
            ]
        );
    }

    public function updateBioHistoricoSaude(array $request)
    {
        return $this->db->update(
            'bio_historico_saude',
            [
                'doencas_infancia' => !empty($request['doencas_infancia']) ? $request['doencas_infancia'] : NULL,
                'vacinacao' => !empty($request['vacinacao']) ? $request['vacinacao'] : NULL,
                'transfusao' => !empty($request['transfusao']) ? $request['transfusao'] : NULL,
                'doacao_sangue' => !empty($request['doacao_sangue']) ? $request['doacao_sangue'] : NULL,
                'alergias' => !empty($request['alergias']) ? $request['alergias'] : NULL,
                'fraturas' => !empty($request['fraturas']) ? $request['fraturas'] : NULL,
                'cirurgias' => !empty($request['cirurgias']) ? $request['cirurgias'] : NULL,
                'tatuagens' => !empty($request['tatuagens']) ? $request['tatuagens'] : NULL,
                'piercings' => !empty($request['piercings']) ? $request['piercings'] : NULL,
                'doencas_sexuais' => !empty($request['doencas_sexuais']) ? $request['doencas_sexuais'] : NULL,
                'fenomenos_tumorais' => !empty($request['fenomenos_tumorais']) ? $request['fenomenos_tumorais'] : NULL,
                'problemas_memoria' => !empty($request['problemas_memoria']) ? $request['problemas_memoria'] : NULL,
                'problemas_dormir' => !empty($request['problemas_dormir']) ? $request['problemas_dormir'] : NULL,
                'problemas_visao' => !empty($request['problemas_visao']) ? $request['problemas_visao'] : NULL,
                'problemas_audicao' => !empty($request['problemas_audicao']) ? $request['problemas_audicao'] : NULL,
                'problemas_digestivos' => !empty($request['problemas_digestivos']) ? $request['problemas_digestivos'] : NULL,
                'problemas_renais' => !empty($request['problemas_renais']) ? $request['problemas_renais'] : NULL,
                'problemas_respiratorios' => !empty($request['problemas_respiratorios']) ? $request['problemas_respiratorios'] : NULL,
                'problemas_cardiacos' => !empty($request['problemas_cardiacos']) ? $request['problemas_cardiacos'] : NULL,
                'problemas_metabolicos' => !empty($request['problemas_metabolicos']) ? $request['problemas_metabolicos'] : NULL,
                'problemas_psicoemocionais' => !empty($request['problemas_psicoemocionais']) ? $request['problemas_psicoemocionais'] : NULL,
                'problemas_hepaticos' => !empty($request['problemas_hepaticos']) ? $request['problemas_hepaticos'] : NULL,
                'problemas_reprodutor' => !empty($request['problemas_reprodutor']) ? $request['problemas_reprodutor'] : NULL,
                'problemas_musculares' => !empty($request['problemas_musculares']) ? $request['problemas_musculares'] : NULL,
                'problemas_pele' => !empty($request['problemas_pele']) ? $request['problemas_pele'] : NULL,
                'outros_exames' => !empty($request['outros_exames']) ? $request['outros_exames'] : NULL,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function updateBioHistoriaSocial(array $request)
    {
        return $this->db->update(
            'bio_historia_social',
            [
                'bebidas_alcoolicas' => !empty($request['bebidas_alcoolicas']) ? $request['bebidas_alcoolicas'] : NULL,
                'tabagismo_drogas' => !empty($request['tabagismo_drogas']) ? $request['tabagismo_drogas'] : NULL,
                'ingestao_agua' => !empty($request['ingestao_agua']) ? $request['ingestao_agua'] : NULL,
                'habitos_sono' => !empty($request['habitos_sono']) ? $request['habitos_sono'] : NULL,
                'habitos_lazer' => !empty($request['habitos_lazer']) ? $request['habitos_lazer'] : NULL,
                'viagens' => !empty($request['viagens']) ? $request['viagens'] : NULL,
                'ambiente_trabalho' => !empty($request['ambiente_trabalho']) ? $request['ambiente_trabalho'] : NULL,
                'atividade_fisica' => !empty($request['atividade_fisica']) ? $request['atividade_fisica'] : NULL,
                'cafe_manha' => !empty($request['cafe_manha']) ? $request['cafe_manha'] : NULL,
                'lanche_manha' => !empty($request['lanche_manha']) ? $request['lanche_manha'] : NULL,
                'almoco' => !empty($request['almoco']) ? $request['almoco'] : NULL,
                'lanche_tarde' => !empty($request['lanche_tarde']) ? $request['lanche_tarde'] : NULL,
                'janta' => !empty($request['janta']) ? $request['janta'] : NULL,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function updateBioHistoriaFisiologica(array $request)
    {
        return $this->db->update(
            'bio_historia_fisiologica',
            [
                'nascimento' => !empty($request['nascimento']) ? $request['nascimento'] : NULL,
                'desenvolvimento' => !empty($request['desenvolvimento']) ? $request['desenvolvimento'] : NULL,
                'menstruacao' => !empty($request['menstruacao']) ? $request['menstruacao'] : NULL,
                'primeira_relacao' => !empty($request['primeira_relacao']) ? $request['primeira_relacao'] : NULL,
                'menopausa' => !empty($request['menopausa']) ? $request['menopausa'] : NULL,
                'gestacoes' => !empty($request['gestacoes']) ? $request['gestacoes'] : 0,
                'qtd_filhos' => !empty($request['qtd_filhos']) ? $request['qtd_filhos'] : 0,
                'qtd_abortos' => !empty($request['qtd_abortos']) ? $request['qtd_abortos'] : 0,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function updateBioHistoriaDoenca(array $request)
    {
        return $this->db->update(
            'bio_historia_doenca',
            [
                'problema' => !empty($request['problema']) ? $request['problema'] : NULL,
                'tipo_dor' => !empty($request['tipo_dor']) ? $request['tipo_dor'] : NULL,
                'intensidade_dor' => !empty($request['intensidade_dor']) ? $request['intensidade_dor'] : NULL,
                'melhorar' => !empty($request['melhorar']) ? $request['melhorar'] : NULL,
                'desencadeia' => !empty($request['desencadeia']) ? $request['desencadeia'] : NULL,
                'acompanha_sintoma' => !empty($request['acompanha_sintoma']) ? $request['acompanha_sintoma'] : NULL,
                'inicio_doenca' => !empty($request['inicio_doenca']) ? $request['inicio_doenca'] : NULL,
                'situacao_atual' => !empty($request['situacao_atual']) ? $request['situacao_atual'] : NULL,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function updateBioEstruturaFamiliar(array $request)
    {
        return $this->db->update(
            'bio_estrutura_familiar',
            [
                'tipo_moradia' => !empty($request['tipo_moradia']) ? $request['tipo_moradia'] : NULL,
                'habitantes' => !empty($request['habitantes']) ? $request['habitantes'] : NULL,
                'animais' => !empty($request['animais']) ? $request['animais'] : NULL,
                'parentes' => !empty($request['parentes']) ? $request['parentes'] : NULL,
                'comunidade' => !empty($request['comunidade']) ? $request['comunidade'] : NULL,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function updateBioDoencasFamilia(array $request)
    {
        return $this->db->update(
            'bio_doencas_familia',
            [
                'id_paciente' => !empty($request['id_paciente']) ? $request['id_paciente'] : NULL,
                'mae' => !empty($request['mae']) ? $request['mae'] : NULL,
                'pai' => !empty($request['pai']) ? $request['pai'] : NULL,
                'irmaos' => !empty($request['irmaos']) ? $request['irmaos'] : NULL,
                'avos_maternos' => !empty($request['avos_maternos']) ? $request['avos_maternos'] : NULL,
                'avos_paternos' => !empty($request['avos_paternos']) ? $request['avos_paternos'] : NULL,
            ],
            [
                'id_paciente' => $request['id_paciente']
            ]
        );
    }

    public function deleteBioPacientes(int $idPaciente)
    {
        return $this->db->delete(
            "bio_pacientes",
            ['id' => $idPaciente]
        );
    }

    public function deleteBioHistoricoSaude(int $idPaciente)
    {
        return $this->db->delete(
            "bio_historico_saude",
            ['id_paciente' => $idPaciente]
        );
    }

    public function deleteBioHistoriaSocial(int $idPaciente)
    {
        return $this->db->delete(
            "bio_historia_social",
            ['id_paciente' => $idPaciente]
        );
    }

    public function deleteBioHistoriaFisiologica(int $idPaciente)
    {
        return $this->db->delete(
            "bio_historia_fisiologica",
            ['id_paciente' => $idPaciente]
        );
    }

    /* public function deleteBioHistoriaDoenca(int $idPaciente)
    {
        return $this->db->delete(
            "bio_historia_doenca",
            ['id_paciente' => $idPaciente]
        );
    } */

    public function deleteBioEstruturaFamiliar(int $idPaciente)
    {
        return $this->db->delete(
            "bio_estrutura_familiar",
            ['id_paciente' => $idPaciente]
        );
    }

    public function deleteBioDoencasFamilia(int $idPaciente)
    {
        return $this->db->delete(
            "bio_doencas_familia",
            ['id_paciente' => $idPaciente]
        );
    }

    public function getHistoricoSaude(int $idPaciente)
    {
        $sql = "SELECT *
                FROM BIO_HISTORIA_DOENCA
                WHERE ID_PACIENTE = :id_paciente
                ORDER BY DATA_CRIACAO DESC
                LIMIT 1";
        return $this->db->select($sql, ['id_paciente' => $idPaciente]);
    }

    /*public function getExamesComplementares(int $idPaciente)
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
    }*/
}
