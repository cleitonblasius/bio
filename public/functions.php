<?php
/* --------------------------------------------------------------------- */
/* Arquivo de funções úteis para serem reaproveitadas em diversos locais */
/* --------------------------------------------------------------------- */

/**
 * Formata o CPF no formato ###.###.###-##
 *
 * @param $cpf O valor do CPF
 */
function formatarCPF(string $cpf): string
{
    // Verifica se o CPF tem 11 dígitos
    if (strlen($cpf) !== 11) {
        return ''; // Retorna vazio para CPFs inválidos
    }

    // Remove qualquer caractere que não seja número
    $cpf = preg_replace('/\D/', '', $cpf);

    // Formata o CPF
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

/**
 * Formata um número de telefone no formato brasileiro.
 * (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
 *
 * @param string $telefone O número de telefone a ser formatado (com ou sem máscara).
 * @return string O telefone formatado ou vazio se inválido.
 */
function formatarTelefone(string $telefone): string
{
    // Remove qualquer caractere que não seja número
    $telefone = preg_replace('/\D/', '', $telefone);

    // Verifica se o número tem um tamanho válido (10 ou 11 dígitos)
    if (strlen($telefone) === 11) {
        // Formato com 9 dígitos: (XX) XXXXX-XXXX
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
    } elseif (strlen($telefone) === 10) {
        // Formato com 8 dígitos: (XX) XXXX-XXXX
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
    }

    // Retorna vazio para números inválidos
    return '';
}

function removerCaracteresNaoNumericos($string)
{
    return preg_replace('/\D/', '', $string);
}

/**
 * Abrevia nomes de pessoas. Se tiver apenas dois nomes, não altera, senão, abrevia os nomes do meio.
 * @param string $nome 
 * @returns 
 */
function abreviarNome(string $nome)
{
    $partes = explode(' ', trim($nome)); // Divide o nome em partes
    $totalPartes = count($partes);

    if ($totalPartes <= 2) {
        return $nome; // Se houver apenas um sobrenome, retorna como está
    }

    $abreviado = $partes[0] . ' '; // Mantém o primeiro nome completo

    $ignore = ['de', 'da', 'do', 'das', 'dos', 'e'];

    for ($i = 1; $i < $totalPartes - 1; $i++) {
        if (in_array($partes[$i], $ignore)) {
            continue;
        }

        if (!empty($partes[$i])) {
            $abreviado .= strtoupper(substr($partes[$i], 0, 1)) . '. '; // Adiciona a inicial e um ponto
        }
    }

    $abreviado .= $partes[$totalPartes - 1]; // Mantém o último nome completo

    return trim($abreviado);
}

function showResponseAndDie(string $status, string $message, array $data = [], string $details = '')
{
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data,
        'details' => $details
    ]);
    http_response_code($status == 'error' ? 500 : 200);
    die();
}

function getFileIconClass(string $extension, bool $onlyClassName = true, bool $isSolid = false)
{
    switch ($extension) {
        case 'pdf':
            // Arquivos PDF
            $solid = 'fa-file-pdf';
            $regular = 'fa-file-pdf';
            break;
        case 'doc':
        case 'docx':
            // Arquivos Word
            $solid = 'fa-file-word';
            $regular = 'fa-file-word';
            break;
        case 'xls':
        case 'xlsx':
            // Arquivos Excel
            $solid = 'fa-file-excel';
            $regular = 'fa-file-excel';
            break;
        case 'csv':
            // Arquivos CSV
            $solid = 'fa-file-csv';
            $regular = 'fa-file-csv';
            break;
        case 'rar':
        case 'zip':
            // Arquivos compactados
            $solid = 'fa-file-zipper';
            $regular = 'fa-file-zipper';
            break;
        default:
            // Imagens
            $solid = 'fa-file-image';
            $regular = 'fa-file-image';
            break;
    }

    $fontType = $isSolid ? 'fa-solid' : 'fa-regular';
    $fontname = $isSolid ? $solid : $regular;

    return $onlyClassName ? $fontType . ' ' . $fontname : "<i class='{$fontType} {$fontname}'></i>";
}

function printNoDataFoundMessage(string $title = 'Nenhum registro encontrado.', string $subtitle): string
{
    $html = '<div class="m-2 text-center">
                <div class="w-100">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 45px; color: orange;"></i>
                    <div class="my-3">
                        <div>
                            ' . $title . '
                        </div>';
    if (!empty($subtitle)) {
        $html .=        '<div>
                            ' . $subtitle . '
                        </div>';
    }

    $html .= '       </div>
                </div>
            </div>';
    return $html;
}
