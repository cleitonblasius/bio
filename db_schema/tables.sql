CREATE SEQUENCE bio_pacientes_id_seq;

CREATE TABLE bio_pacientes (
    --id SERIAL PRIMARY KEY,               -- Identificador único para cada paciente
    id INT DEFAULT nextval('bio_pacientes_id_seq') PRIMARY KEY, -- Identificador único para cada paciente
    nome VARCHAR(150) NOT NULL,          -- Nome do paciente
    cpf CHAR(11) UNIQUE NOT NULL,        -- CPF, campo único
    rg VARCHAR(20),                      -- RG do paciente
    data_nascimento DATE,                -- Data de nascimento
    endereco TEXT,                       -- Endereço completo
    naturalidade VARCHAR(100),           -- Cidade/estado de origem
    telefone_pessoal VARCHAR(15),        -- Telefone principal do paciente
    telefone_recados VARCHAR(15),        -- Telefone para recados
    email VARCHAR(150),                  -- E-mail do paciente
    profissao VARCHAR(100),              -- Profissão do paciente
    religiao VARCHAR(100),               -- Religião do paciente
    genero INTEGER DEFAULT 2,            -- Gênero (1 = masculino, 2 = feminino, outro)
    estado_civil VARCHAR(20),            -- Estado civil (solteiro, casado, etc.)
    cor VARCHAR(20),                     -- Cor ou raça
    escolaridade VARCHAR(50),            -- Nível de escolaridade
    filhos INTEGER DEFAULT 0,            -- Quantidade de filhos
    gestante INTEGER DEFAULT 2,          -- Se é gestante
    marcapasso INTEGER DEFAULT 2,        -- Se utiliza marcapasso
    medicamentos VARCHAR(255), 			 -- Medicamentos atuais
    exames VARCHAR(255),				 -- Exames complementares
    obs_terapeuta TEXT,                  -- Anotações da terapeuta
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

--Função para atualizar os campos "atualizado_em"
CREATE OR REPLACE FUNCTION update_atualizado_em_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.atualizado_em = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_pacientes
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_historico_saude (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    doencas_infancia VARCHAR(255), --Doenças de infância
    vacinacao VARCHAR(255), --Vacinação
    transfusao VARCHAR(255), --Transfusão sanguínea
    doacao_sangue VARCHAR(255), --Doação de sangue
    alergias VARCHAR(255), --Alergias
    fraturas VARCHAR(255), --Fraturas
    cirurgias VARCHAR(255), --Cirurgias
    tatuagens VARCHAR(255), --Tatuagens
    piercings VARCHAR(255), --Piercings
    doencas_sexuais VARCHAR(255), --Doenças sexualmente transmissíveis
    fenomenos_tumorais VARCHAR(255), --Fenômenos tumorais
    problemas_memoria VARCHAR(255), --Problemas de memória
    problemas_dormir VARCHAR(255), --Problemas para dormir
    problemas_visao VARCHAR(255), --Problemas com a visão
    problemas_audicao VARCHAR(255), --Problemas com audição
    problemas_digestivos VARCHAR(255), --Problemas Digestivos
    problemas_renais VARCHAR(255), --Problemas renais e urinários
    problemas_respiratorios VARCHAR(255), --Problemas respiratórios
    problemas_cardiacos VARCHAR(255), --Problemas Cardíacos
    problemas_metabolicos VARCHAR(255), --Problemas metabólicos
    problemas_psicoemocionais VARCHAR(255), --Problemas psicoemocionais
    problemas_hepaticos VARCHAR(255), --Problemas hepáticos
    problemas_reprodutor VARCHAR(255), --Problemas no sistema reprodutor
    problemas_musculares VARCHAR(255), --Problemas muscoloesqueléticos
    problemas_pele VARCHAR(255), --Problemas de pele
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_historico_saude
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_historico_saude
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_historia_social (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    bebidas_alcoolicas VARCHAR(255), -- Bebidas alcoólicas
    tabagismo_drogas VARCHAR(255), -- Tabagismo/ drogas
    ingestao_agua VARCHAR(255), -- Ingestão de água (quantidade)
    habitos_sono VARCHAR(255), -- Hábitos de Sono
    habitos_lazer VARCHAR(255), -- Hábitos de Lazer
    viagens VARCHAR(255), -- Viagens
    ambiente_trabalho VARCHAR(255), -- Ambiente de trabalho
    atividade_fisica VARCHAR(255), -- Atividade física
    cafe_manha VARCHAR(255), -- Hábitos alimentares => Café da manhã
    lanche_manha VARCHAR(255), -- Hábitos alimentares => Lanche
    almoco VARCHAR(255), -- Hábitos alimentares => Almoço
    lanche_tarde VARCHAR(255), -- Hábitos alimentares => Lanche da tarde
    janta VARCHAR(255), -- Hábitos alimentares => Janta
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_historia_social
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_historia_social
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_historia_fisiologica (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    nascimento TEXT, -- Tipo de parto/ onde nasceu
    desenvolvimento TEXT, --Atraso no desenvolvimento
    menstruacao TEXT, --Primeira menstruação/ como é
    primeira_relacao TEXT, --Primeira relação sexual
    menopausa TEXT, --Menopausa
    gestacoes INTEGER DEFAULT 0, --Número de Gestações
    qtd_filhos INTEGER DEFAULT 0, --Quantos filhos
    qtd_abortos INTEGER DEFAULT 0, --Teve aborto?
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_historia_fisiologica
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_historia_fisiologica
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_historia_doenca (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    problema TEXT, -- Qual o problema
    tipo_dor VARCHAR(255), -- Tipo da dor (irradiada ou local)
    intensidade_dor VARCHAR(255), -- Intensidade da dor
    melhorar TEXT, -- O que faz a dor melhorar
    desencadeia TEXT, -- O que desencadeia
    acompanha_sintoma TEXT, -- O que acompanha o sintoma
    inicio_doenca TEXT, -- Período da doença: (Quando começou)
    situacao_atual TEXT, -- Neste instante como se encontra
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_historia_doenca
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_historia_doenca
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_estrutura_familiar (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    tipo_moradia VARCHAR(255), -- Tipo de moradia
    habitantes VARCHAR(255), -- Descrição de quem mora na casa
    animais VARCHAR(255), -- Indica se há animais de estimação (sim/não)
    parentes VARCHAR(255), -- Parentes que moram perto
    comunidade VARCHAR(255), -- Comunidade (rede de apoio)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_estrutura_familiar
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_estrutura_familiar
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();

CREATE TABLE bio_doencas_familia (
    id SERIAL PRIMARY KEY, -- Identificador único
    id_paciente INT NOT NULL, -- Chave estrangeira para bio_pacientes(id)
    mae VARCHAR(255), -- Doenças da Mãe
    pai VARCHAR(255), -- Doenças do Pai
    irmaos VARCHAR(255), -- Doenças dos Irmãos
    avos_maternos VARCHAR(255), -- Doenças dos Avós maternos
    avos_paternos VARCHAR(255), -- Doenças dos Avós paternos
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, --Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP --Data de atualização do registro
);

ALTER TABLE bio_doencas_familia
ADD CONSTRAINT fk_id_paciente
FOREIGN KEY (id_paciente)
REFERENCES bio_pacientes (id)
ON DELETE CASCADE;

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_doencas_familia
FOR EACH ROW
EXECUTE FUNCTION update_atualizado_em_column();