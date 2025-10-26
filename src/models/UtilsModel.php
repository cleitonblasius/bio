<?php

namespace App\Models;

class UtilsModel extends BaseModel
{
    public function getMonthBirthdays()
    {
        $sql = "SELECT 
                    ID,
                    NOME,
                    DATA_NASCIMENTO,
                    DATE_FORMAT(DATA_NASCIMENTO, '%d/%m') AS ANIVERSARIO,
                    CASE 
                        WHEN MONTH(DATA_NASCIMENTO) < MONTH(CURDATE())
                            OR (MONTH(DATA_NASCIMENTO) = MONTH(CURDATE()) 
                                AND DAY(DATA_NASCIMENTO) <= DAY(CURDATE()))
                        THEN TIMESTAMPDIFF(YEAR, DATA_NASCIMENTO, CURDATE())  -- Mantém a idade atual
                        ELSE TIMESTAMPDIFF(YEAR, DATA_NASCIMENTO, CURDATE()) + 1  -- Soma 1 se ainda não fez aniversário este ano
                    END AS IDADE_A_FAZER,
                    CASE 
                        WHEN DAY(DATA_NASCIMENTO) <= DAY(CURDATE()) 
                        THEN 1  -- Já fez aniversário este ano
                        ELSE 2  -- Ainda não fez aniversário este ano
                    END AS STATUS_ANIVERSARIO,
                    CASE 
                        WHEN DAY(DATA_NASCIMENTO) = DAY(CURDATE()) - 1 THEN 1  -- Fez aniversário ontem
                        WHEN DAY(DATA_NASCIMENTO) = DAY(CURDATE()) THEN 2  -- Faz aniversário hoje
                        WHEN DAY(DATA_NASCIMENTO) = DAY(CURDATE()) + 1 THEN 3  -- Faz aniversário amanhã
                        ELSE 0  -- Fora desses casos
                    END AS DIA_ANIVERSARIO
                FROM 
                    BIO_PACIENTES
                WHERE
                    DATA_NASCIMENTO IS NOT NULL
                    AND MONTH(DATA_NASCIMENTO) = MONTH(CURDATE())
                ORDER BY 
                    DAY(DATA_NASCIMENTO);";

        return $this->db->select($sql);
    }

    public function getColorConfig()
    {
        $fields = $this->db->select("SELECT * FROM BIO_CONFIG LIMIT 1");
        return $fields[0] ?? [];
    }
    
    public function getBioMagneticPairsArray()
    {
        $fields = $this->db->select("SELECT * FROM BIO_CONFIG LIMIT 1");
        return $fields[0] ?? [];
    }
}
