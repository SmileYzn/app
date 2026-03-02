-- UTF-8
ALTER DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Desativar Foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Configuração
DROP TABLE IF EXISTS configuracao;

CREATE TABLE configuracao
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    chave VARCHAR(255) UNIQUE COMMENT 'Chave',
    valor VARCHAR(255) COMMENT 'Valor',
    descricao VARCHAR(255) COMMENT 'Descrição',

    PRIMARY KEY(id)
) COMMENT = 'Tabela Configuração';

-- País
DROP TABLE IF EXISTS pais;

CREATE TABLE pais
(
    id VARCHAR(6) COMMENT 'Id',
    sigla VARCHAR(6) DEFAULT NULL COMMENT 'Sigla',
    nome VARCHAR(255) DEFAULT NULL COMMENT 'Nome',

    PRIMARY KEY (id)
) COMMENT = 'País';

-- Estado
DROP TABLE IF EXISTS estado;

CREATE TABLE estado
(
    id INT COMMENT 'Id',
    uf VARCHAR(2) COMMENT 'UF',
    nome VARCHAR(255) COMMENT 'Nome',

    PRIMARY KEY (id)
) COMMENT = 'Estado';

-- Cidade
DROP TABLE IF EXISTS cidade;

CREATE TABLE cidade
(
    id INT COMMENT 'Id',
    nome  VARCHAR(255) COMMENT 'Nome',
    latitude FLOAT(8) COMMENT 'Latitude',
    longitude FLOAT(8) COMMENT 'Longitude',
    capital BOOLEAN COMMENT 'Capital',
    estadoFK INT COMMENT 'Estado',

    PRIMARY KEY (id),
    FOREIGN KEY (estadoFK) REFERENCES estado(id)
) COMMENT = 'Cidade';

DROP TABLE IF EXISTS regimeTributario;

CREATE TABLE regimeTributario
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    nome VARCHAR(255) COMMENT 'Nome',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT 1 COMMENT 'Ativo',

    PRIMARY KEY (id)
) COMMENT = 'Regime Tributário';

-- Unidade
DROP TABLE IF EXISTS unidade;

CREATE TABLE unidade
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    estadoFK INT COMMENT 'Estado',
    cidadeFK INT COMMENT 'Cidade',
    regimeTributarioFK INT COMMENT 'Regime Tributário',
    cnpj VARCHAR(255) COMMENT 'CNPJ',
    nome VARCHAR(255) COMMENT 'Nome',
    razaoSocial VARCHAR(255) COMMENT 'Razão Social',
    nomeFantasia VARCHAR(255) COMMENT 'Nome Fantasia',
    qsa VARCHAR(255) COMMENT 'Quadro Sócio / Administrativo',
    inscricaoEstadual VARCHAR(255) COMMENT 'Inscrição Estadual',
    inscricaoMunicipal VARCHAR(255) COMMENT 'Inscrição Municipal',
    isento TINYINT(1) DEFAULT '0' COMMENT 'Isento',
    abertura DATE NULL COMMENT 'Abertura',
    ultimaAtualizacao DATETIME NULL COMMENT 'Última atualização com a Receita Federal',
    cnae VARCHAR(255) COMMENT 'CNAE', 
    tipo VARCHAR(255) COMMENT 'Tipo da Empresa',
    porte VARCHAR(255) COMMENT 'Porte da Empresa',
    naturezaJuridica VARCHAR(255) COMMENT 'Natureza Jurídica',
    efr VARCHAR(255) COMMENT 'Ente Federativo Responsável',
    capitalSocial VARCHAR(255) COMMENT 'Capital Social',
    situacao VARCHAR(255) COMMENT 'Situação',
    situacaoData DATE NULL COMMENT 'Data da Situação',
    situacaoMotivo VARCHAR(255) COMMENT 'Motivo da Situação',
    situacaoEspecial VARCHAR(255) COMMENT 'Situação Especial',
    situacaoEspecialData DATE NULL COMMENT 'Data da Situação Especial',
    cep VARCHAR(255) COMMENT 'CEP',
    bairro VARCHAR(255) COMMENT 'Bairro',
    logradouro VARCHAR(255) COMMENT 'Logradouro',
    numero VARCHAR(255) COMMENT 'Número',
    complemento VARCHAR(255) COMMENT 'Complemento',
    telefone VARCHAR(255) COMMENT 'Telefone',
    celular VARCHAR(255) COMMENT 'Celular',
    email VARCHAR(255) COMMENT 'Email',
    website VARCHAR(255) COMMENT 'WebSite',
    observacoes TEXT COMMENT 'Observações', 
    imagem VARCHAR(255) DEFAULT 'assets/img/default.svg' COMMENT 'Imagem',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',  
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',        

    PRIMARY KEY (id),
    FOREIGN KEY (estadoFK) REFERENCES estado(id),
    FOREIGN KEY (cidadeFK) REFERENCES cidade(id),
    FOREIGN KEY (regimeTributarioFK) REFERENCES regimeTributario(id)
) COMMENT = 'Unidade';

-- Área
DROP TABLE IF EXISTS area;

CREATE TABLE area
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    nome VARCHAR(255) COMMENT 'Nome',
    descricao VARCHAR(255) COMMENT 'Descrição',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id)
) COMMENT = 'Área';

-- Situação
DROP TABLE IF EXISTS situacao;

CREATE TABLE situacao
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    nome VARCHAR(255) COMMENT 'Nome',
    descricao VARCHAR(255) COMMENT 'Descrição',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id)
) COMMENT = 'Situação';

-- Usuário
DROP TABLE IF EXISTS usuario;

CREATE TABLE usuario
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    unidadeFK INT COMMENT 'Unidade',
    areaFK INT COMMENT 'Área',
    situacaoFK INT COMMENT 'Situação',
    estadoFK INT COMMENT 'Estado', 
    cidadeFK INT COMMENT 'Cidade', 
    nome VARCHAR(255) COMMENT 'Nome',  
    nascimento DATE NULL COMMENT 'Nascimento',
    login VARCHAR(255) COMMENT 'Login',   
    senha VARCHAR(255) COMMENT 'Senha',   
    email VARCHAR(255) COMMENT 'Email',
    cep VARCHAR(255) COMMENT 'CEP',
    bairro VARCHAR(255) COMMENT 'Bairro',
    logradouro VARCHAR(255) COMMENT 'Logradouro',
    numero VARCHAR(255) COMMENT 'Número',
    complemento VARCHAR(255) COMMENT 'Complemento',
    telefone VARCHAR(255) COMMENT 'Telefone',
    celular VARCHAR(255) COMMENT 'Celular',
    website VARCHAR(255) COMMENT 'WebSite',
    observacoes TEXT COMMENT 'Observações',  
    imagem VARCHAR(255) DEFAULT 'assets/img/default.svg' COMMENT 'Imagem', 
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',   

    PRIMARY KEY (id),
    FOREIGN KEY (unidadeFK) REFERENCES unidade(id),
    FOREIGN KEY (areaFK) REFERENCES area(id),
    FOREIGN KEY (situacaoFK) REFERENCES situacao(id),
    FOREIGN KEY (estadoFK) REFERENCES estado(id),
    FOREIGN KEY (cidadeFK) REFERENCES cidade(id),
    UNIQUE INDEX email (email),
    UNIQUE INDEX login (login)
) COMMENT = 'Usuário';

-- Login
DROP TABLE IF EXISTS login;

CREATE TABLE login
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    login VARCHAR(255) COMMENT 'Login',
    ip VARCHAR(255) COMMENT 'IP',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id)
) COMMENT = 'Login';

-- Recuperar
DROP TABLE IF EXISTS recuperar;

CREATE TABLE recuperar
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    usuarioFK INT COMMENT 'Área',
    ip VARCHAR(255) COMMENT 'IP',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id),
    FOREIGN KEY (usuarioFK) REFERENCES usuario(id)
) COMMENT = 'Recuperar';

-- Módulo 
DROP TABLE IF EXISTS modulo;

CREATE TABLE modulo
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    moduloFK INT DEFAULT 0 COMMENT 'Módulo',
    ordem INT DEFAULT 0 COMMENT 'Ordem',
    nome VARCHAR(255) COMMENT 'Nome',
    descricao VARCHAR(255) COMMENT 'Descrição',
    url VARCHAR(255) COMMENT 'Link',
    ocultar TINYINT(1) DEFAULT 0 COMMENT 'Ocultar',
    icone VARCHAR(255) COMMENT 'Ícone',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id),
    INDEX (moduloFK)
) COMMENT = 'Módulo';

-- Acesso 
DROP TABLE IF EXISTS acesso;

CREATE TABLE acesso
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    nome VARCHAR(255) COMMENT 'Nome',
    acesso VARCHAR(255) COMMENT 'Acesso',
    cor VARCHAR(255) COMMENT 'Cor',
    icone VARCHAR(255) COMMENT 'Ícone',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id)
) COMMENT = 'Acesso';

-- Permissao 
DROP TABLE IF EXISTS permissao;

CREATE TABLE permissao
(
    id INT AUTO_INCREMENT COMMENT 'Id',
    areaFK INT COMMENT 'Área',
    moduloFK INT COMMENT 'Módulo',
    acesso SET('index', 'adicionar', 'editar', 'excluir') COMMENT 'Acesso',
    data DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data',
    ativo TINYINT(1) DEFAULT '1' COMMENT 'Ativo',

    PRIMARY KEY (id),
    FOREIGN KEY (areaFK) REFERENCES area(id),
    FOREIGN KEY (moduloFK) REFERENCES modulo(id)
) COMMENT = 'Permissão';

-- Ativar Foreign key checks
SET FOREIGN_KEY_CHECKS = 1;