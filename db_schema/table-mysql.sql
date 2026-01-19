CREATE TABLE bio_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cor_primaria VARCHAR(7) NOT NULL DEFAULT '#3498db',
    cor_secundaria VARCHAR(7) NOT NULL DEFAULT '#2ecc71',
    cor_fonte VARCHAR(7) NOT NULL DEFAULT '#333333'
);

-------------------

CREATE TABLE bio_pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único para cada paciente
    nome VARCHAR(150) NOT NULL,         -- Nome do paciente
    cpf CHAR(11) UNIQUE NOT NULL,       -- CPF, campo único
    rg VARCHAR(20),                     -- RG do paciente
    data_nascimento DATE,               -- Data de nascimento
    endereco TEXT,                      -- Endereço completo
    naturalidade VARCHAR(100),          -- Cidade/estado de origem
    telefone_pessoal VARCHAR(15),       -- Telefone principal do paciente
    telefone_recados VARCHAR(15),       -- Telefone para recados
    email VARCHAR(150),                 -- E-mail do paciente
    profissao VARCHAR(100),             -- Profissão do paciente
    religiao VARCHAR(100),              -- Religião do paciente
    genero TINYINT DEFAULT 2,           -- Gênero (1 = masculino, 2 = feminino, outro)
    estado_civil VARCHAR(20),           -- Estado civil (solteiro, casado, etc.)
    cor VARCHAR(20),                    -- Cor ou raça
    escolaridade VARCHAR(50),           -- Nível de escolaridade
    filhos INT DEFAULT 0,               -- Quantidade de filhos
    gestante TINYINT DEFAULT 2,         -- Se é gestante
    marcapasso TINYINT DEFAULT 2,       -- Se utiliza marcapasso
    medicamentos VARCHAR(255),          -- Medicamentos atuais
    risco_gestante TINYINT DEFAULT 2,   -- Se tem risco de estar grávida
	contraceptivos TINYINT DEFAULT 2,   -- Se usa métodos contraceptivos
	usa_diu TINYINT DEFAULT 2,          -- Se usa DIU
	possui_protese TINYINT DEFAULT 2,   -- Se possui prótese de silicone
    obs_terapeuta TEXT,                 -- Anotações da terapeuta
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data de atualização automática
);

-- Criar TRIGGER para garantir atualização do campo 'atualizado_em'
DELIMITER $$

CREATE TRIGGER set_atualizado_em
BEFORE UPDATE ON bio_pacientes
FOR EACH ROW
BEGIN
    SET NEW.atualizado_em = CURRENT_TIMESTAMP;
END $$

DELIMITER ;


--------------------

CREATE TABLE bio_historico_saude (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único
    id_paciente INT NOT NULL,          	-- Chave estrangeira para bio_pacientes(id)
    doencas_infancia TEXT,     			-- Doenças de infância
    vacinacao TEXT,             		-- Vacinação
	transfusao TEXT,            		-- Transfusão sanguínea
	doacao_sangue TEXT,         		-- Doação de sangue
	alergias TEXT,              		-- Alergias
	fraturas TEXT,              		-- Fraturas
	cirurgias TEXT,             		-- Cirurgias
	tatuagens TEXT,             		-- Tatuagens
	piercings TEXT,             		-- Piercings
	doencas_sexuais TEXT,       		-- Doenças sexualmente transmissíveis
	fenomenos_tumorais TEXT,    		-- Fenômenos tumorais
	problemas_memoria TEXT,     		-- Problemas de memória
	problemas_dormir TEXT,      		-- Problemas para dormir
	problemas_visao TEXT,       		-- Problemas com a visão
	problemas_audicao TEXT,     		-- Problemas com audição
	problemas_digestivos TEXT,  		-- Problemas digestivos
	problemas_renais TEXT,      		-- Problemas renais e urinários
	problemas_respiratorios TEXT, 		-- Problemas respiratórios
	problemas_cardiacos TEXT,   		-- Problemas cardíacos
	problemas_metabolicos TEXT, 		-- Problemas metabólicos
	problemas_psicoemocionais TEXT, 	-- Problemas psicoemocionais
	problemas_hepaticos TEXT,   		-- Problemas hepáticos
	problemas_reprodutor TEXT,  		-- Problemas no sistema reprodutor
	problemas_musculares TEXT,  		-- Problemas musculoesqueléticos
	problemas_pele TEXT,        		-- Problemas de pele
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data de atualização do registro
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);


---------------------


CREATE TABLE bio_historia_social (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único
    id_paciente INT NOT NULL,           -- Chave estrangeira para bio_pacientes(id)
    bebidas_alcoolicas VARCHAR(255),    -- Bebidas alcoólicas
    tabagismo_drogas VARCHAR(255),      -- Tabagismo/ drogas
    ingestao_agua VARCHAR(255),         -- Ingestão de água (quantidade)
    habitos_sono VARCHAR(255),          -- Hábitos de Sono
    habitos_lazer VARCHAR(255),         -- Hábitos de Lazer
    viagens VARCHAR(255),               -- Viagens
    ambiente_trabalho VARCHAR(255),     -- Ambiente de trabalho
    atividade_fisica VARCHAR(255),      -- Atividade física
    cafe_manha VARCHAR(255),            -- Hábitos alimentares => Café da manhã
    lanche_manha VARCHAR(255),          -- Hábitos alimentares => Lanche
    almoco VARCHAR(255),                -- Hábitos alimentares => Almoço
    lanche_tarde VARCHAR(255),          -- Hábitos alimentares => Lanche da tarde
    janta VARCHAR(255),                 -- Hábitos alimentares => Janta
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data de atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);


-------------------------

 CREATE TABLE bio_historia_fisiologica (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identificador único
    id_paciente INT NOT NULL,           -- Chave estrangeira para bio_pacientes(id)
    nascimento TEXT,                    -- Tipo de parto/ onde nasceu
    desenvolvimento TEXT,               -- Atraso no desenvolvimento
    menstruacao TEXT,                   -- Primeira menstruação/ como é
    primeira_relacao TEXT,              -- Primeira relação sexual
    menopausa TEXT,                     -- Menopausa
    gestacoes INT DEFAULT 0,            -- Número de Gestações
    qtd_filhos INT DEFAULT 0,           -- Quantos filhos
    qtd_abortos INT DEFAULT 0,          -- Teve aborto?
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data de atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);


-------------------------

/*CREATE TABLE bio_historia_doenca (
    id INT AUTO_INCREMENT PRIMARY KEY, 	-- Identificador único
    id_paciente INT NOT NULL,           -- Chave estrangeira para bio_pacientes(id)
    problema TEXT,                   	-- Qual o problema / Tipo da dor (irradiada ou local) / Intensidade da dor / O que faz a dor melhorar / O que desencadeia / O que acompanha o sintoma / Período da doença: (Quando começou) / Neste instante como se encontra
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);*/

CREATE TABLE bio_historia_doenca (
    id INT AUTO_INCREMENT PRIMARY KEY, 	-- Identificador único
    id_paciente INT NOT NULL,           -- Chave estrangeira para bio_pacientes(id)
    problema TEXT,                   	-- Qual o problema
    tipo_dor VARCHAR(255),              -- Tipo da dor (irradiada ou local)
    intensidade_dor VARCHAR(255),       -- Intensidade da dor
    melhorar TEXT,                      -- O que faz a dor melhorar
    desencadeia TEXT,                   -- O que desencadeia
    acompanha_sintoma TEXT,             -- O que acompanha o sintoma
    inicio_doenca TEXT,                 -- Período da doença: (Quando começou)
    situacao_atual TEXT,                -- Neste instante como se encontra
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);


--------------------------

CREATE TABLE bio_estrutura_familiar (
    id INT AUTO_INCREMENT PRIMARY KEY,    -- Identificador único
    id_paciente INT NOT NULL,             -- Chave estrangeira para bio_pacientes(id)
    tipo_moradia VARCHAR(255),            -- Tipo de moradia
    habitantes VARCHAR(255),              -- Descrição de quem mora na casa
    animais VARCHAR(255),                 -- Indica se há animais de estimação (sim/não)
    parentes VARCHAR(255),                -- Parentes que moram perto
    comunidade VARCHAR(255),              -- Comunidade (rede de apoio)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);


--------------------------

CREATE TABLE bio_doencas_familia (
    id INT AUTO_INCREMENT PRIMARY KEY,    -- Identificador único
    id_paciente INT NOT NULL,             -- Chave estrangeira para bio_pacientes(id)
    mae VARCHAR(255),                     -- Doenças da Mãe
    pai VARCHAR(255),                     -- Doenças do Pai
    irmaos VARCHAR(255),                  -- Doenças dos Irmãos
    avos_maternos VARCHAR(255),           -- Doenças dos Avós maternos
    avos_paternos VARCHAR(255),           -- Doenças dos Avós paternos
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Atualização automática
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);

--------------------------

CREATE TABLE bio_terapeutas (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único
    nome VARCHAR(255) NOT NULL,             -- Nome do terapeuta
    email VARCHAR(255) UNIQUE NOT NULL,     -- E-mail do terapeuta
    cpf CHAR(11) UNIQUE,           			-- CPF do terapeuta
    data_nascimento DATE,                   -- Data de nascimento do terapeuta
    telefone_principal VARCHAR(15),         -- Telefone principal
    telefone_secundario VARCHAR(15),        -- Telefone secundário
    endereco TEXT,                          -- Endereço do terapeuta
    google_agenda VARCHAR(255),             -- Link do Google Agenda
    /*senha VARCHAR(255) NOT NULL,            -- Senha do terapeuta (armazenar de forma segura)*/
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Data de atualização
);

---------------------------

CREATE TABLE bio_exames(
    id INT PRIMARY KEY AUTO_INCREMENT,      -- Identificador único
    id_paciente INT NOT NULL,             	-- Chave estrangeira para bio_pacientes(id)
    descricao TEXT NOT NULL,             	-- Descrição do exame
    arquivo VARCHAR(255),  					-- Caminho do arquivo da imagem no servidor
    id_atendimento INT DEFAULT NULL,     	-- Indica o atendimento no qual o exame foi anexado
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data de atualização
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);

---------------------------

CREATE TABLE bio_atendimentos(
    id INT PRIMARY KEY AUTO_INCREMENT,      -- Identificador único
    id_paciente INT NOT NULL,             	-- Chave estrangeira para bio_pacientes(id)
    queixa_principal TEXT NOT NULL,         -- Motivo da consulta (Queixa reportada pelo paciente)
    problema TEXT,                   		-- Descrição do problema (Detalhes do problema informados pela terapeuta)
    estado_emocional TEXT NOT NULL,         -- Estado emocional do paciente ao iniciar o atendimento
    status TINYINT,         				-- Situação do atendimento (1 - Criado mas não iniciado, 2 - Em andamento, 3 - Encerrado, 4 - Cancelado)
    id_rastreio INT NULL,             	    -- Chave estrangeira para bio_atendimento_rastreio(id)
    id_npn INT NULL,             	    	-- Chave estrangeira para bio_atendimento_npn(id)
    id_dem INT NULL,             	    	-- Chave estrangeira para bio_atendimento_dem(id)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data de criação do registro
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data de atualização
    FOREIGN KEY (id_paciente) REFERENCES bio_pacientes(id) ON DELETE CASCADE
);

---------------------------

CREATE TABLE bio_pares_rastreio (
    id INT PRIMARY KEY AUTO_INCREMENT,              -- Identificador único do registro
    codigo_par VARCHAR(10) NOT NULL UNIQUE,         -- Código do par biomagnético (ex: R1, P23)
    classificacao VARCHAR(255),                     -- Classificação do par (ex: Reservatório universal)
    descricao_par VARCHAR(255),                     -- Descrição detalhada do par
    patogeno TEXT,                                  -- Nome(s) ou tipo(s) de patógeno associado(s)
    diagnostico TEXT,                               -- Diagnóstico relacionado ao par
--    sintomas TEXT                                   -- Sintomas associados ao par -- nao necessario, sintomas sao informados no rastreio
);

---------------------------

CREATE TABLE bio_atendimento_rastreio (
    id INT PRIMARY KEY AUTO_INCREMENT,                 -- Identificador único do registro
    id_atendimento INT NOT NULL,                       -- Chave estrangeira para bio_atendimentos(id)
    codigo_par VARCHAR(5) NOT NULL,                    -- Chave estrangeira para bio_pares_rastreio(codigo_par)
    sintomas TEXT,                                     -- Sintomas informados no rastreio
    FOREIGN KEY (id_atendimento) REFERENCES bio_atendimentos(id) ON DELETE CASCADE,
    FOREIGN KEY (codigo_par) REFERENCES bio_pares_rastreio(codigo_par) ON DELETE CASCADE
);
