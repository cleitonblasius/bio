function calcularIdade(elm, targetID) {
    if (targetID == undefined || targetID == '') {
        return;
    }

    const hoje = moment();
    const nascimento = moment($(elm).val(), "YYYY-MM-DD");
    let idade = hoje.diff(nascimento, 'years'); // Calcula a idade em anos

    $(`#${targetID}`).val(idade);
}

/**
 * Remove todos os caracteres que não sejam números
 */
function getNumericValue(value) {
    return value.replace(/\D/g, '');
}

/**
 * Formata o CPF no formato ###.###.###-## enquanto o usuário digita.
 *
 * @param {HTMLInputElement} input O campo de entrada do CPF
 */
function formatarCPF(input) {
    // Remove todos os caracteres que não sejam números
    let cpf = input.value.replace(/\D/g, '');

    // Formata o CPF adicionando pontos e traço
    cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    cpf = cpf.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');

    input.value = cpf;
}

/**
 * Formata o número de telefone no formato (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
 * enquanto o usuário digita.
 *
 * @param {HTMLInputElement} input O campo de entrada do telefone
 */
function formatarTelefone(input) {
    // Remove todos os caracteres que não sejam números
    let telefone = input.value.replace(/\D/g, '');

    // Adiciona o parêntese ao DDD
    telefone = telefone.replace(/^(\d{2})(\d)/, '($1) $2');

    // Formata com 9 dígitos no formato (XX) XXXXX-XXXX
    telefone = telefone.replace(/(\d{5})(\d{4})$/, '$1-$2');

    // Formata com 8 dígitos no formato (XX) XXXX-XXXX
    telefone = telefone.replace(/(\d{4})(\d{4})$/, '$1-$2');

    // Atualiza o valor do campo com o telefone formatado
    input.value = telefone;
}

/**
 * Retorna o icone do FontAwesome de acordo com a extensão do arquivo.
 * @param {string} extension        - A extensão do arquivo 
 * @param {boolean} onlyClassName   - Se true, retorna apenas o nome da classe. Se false, retorna a tag <i> completa
 * @param {boolean} isSolid         - Se true, retorna o icone sólido. Se false, retorna o icone regular
 */
function getFileIconClass(extension, onlyClassName = true, isSolid = false) {
    let solid, regular;

    switch (extension.toLowerCase()) {
        case 'pdf':
            solid = 'fa-file-pdf';
            regular = 'fa-file-pdf';
            break;
        case 'doc':
        case 'docx':
            solid = 'fa-file-word';
            regular = 'fa-file-word';
            break;
        case 'xls':
        case 'xlsx':
            solid = 'fa-file-excel';
            regular = 'fa-file-excel';
            break;
        case 'csv':
            solid = 'fa-file-csv';
            regular = 'fa-file-csv';
            break;
        case 'rar':
        case 'zip':
            solid = 'fa-file-zipper';
            regular = 'fa-file-zipper';
            break;
        default:
            solid = 'fa-file-image';
            regular = 'fa-file-image';
            break;
    }

    const fontType = isSolid ? 'fa-solid' : 'fa-regular';
    const fontName = isSolid ? solid : regular;

    return onlyClassName ? fontName : `<i class="${fontType} ${fontName}"></i>`;
}
