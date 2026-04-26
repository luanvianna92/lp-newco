-- Schema reconstruído por engenharia reversa das queries de well-known/**.
-- Tipos e tamanhos são heurísticos — ajustaremos quando tivermos o dump real de produção.
SET NAMES utf8mb4;
SET time_zone = 'America/Sao_Paulo';

CREATE TABLE texto (
  idtexto INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  titulo VARCHAR(255) NOT NULL DEFAULT '',
  titulo_en VARCHAR(255) NOT NULL DEFAULT '',
  texto TEXT,
  texto_en TEXT,
  texto_modal TEXT,
  texto_modal_en TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contato (
  idcontato INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  endereco VARCHAR(500) NOT NULL DEFAULT '',
  telefone1 VARCHAR(50) NOT NULL DEFAULT '',
  telefone2 VARCHAR(50) DEFAULT NULL,
  email VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categoria (
  idcategoria INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nome_cat VARCHAR(255) NOT NULL DEFAULT '',
  nome_cat_en VARCHAR(255) NOT NULL DEFAULT '',
  capa VARCHAR(500) DEFAULT NULL,
  status TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produto (
  idproduto INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL DEFAULT '',
  nome_en VARCHAR(255) NOT NULL DEFAULT '',
  short VARCHAR(255) NOT NULL,
  descricao TEXT,
  descricao_en TEXT,
  funcionalidade TEXT,
  funcionalidade_en TEXT,
  imagem VARCHAR(500) DEFAULT NULL,
  banner VARCHAR(500) DEFAULT NULL,
  categoria_idcategoria INT UNSIGNED NOT NULL,
  status TINYINT UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE KEY uniq_produto_short (short),
  KEY idx_produto_categoria (categoria_idcategoria),
  CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_idcategoria)
    REFERENCES categoria (idcategoria) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admin (
  idadmin INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  login VARCHAR(100) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  permissao TINYINT UNSIGNED NOT NULL DEFAULT 1,
  status TINYINT UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE KEY uniq_admin_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE log_categoria (
  idlog_cat INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  acao TINYINT UNSIGNED NOT NULL,
  cod_anterior LONGTEXT,
  cod_atual LONGTEXT,
  hora DATETIME NOT NULL,
  categoria_idcategoria INT UNSIGNED NOT NULL,
  admin_idadmin INT UNSIGNED NOT NULL,
  KEY idx_logcat_categoria (categoria_idcategoria),
  KEY idx_logcat_admin (admin_idadmin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE log_produto (
  idlog_prod INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  acao TINYINT UNSIGNED NOT NULL,
  cod_anterior LONGTEXT,
  cod_atual LONGTEXT,
  hora DATETIME NOT NULL,
  produto_idproduto INT UNSIGNED NOT NULL,
  admin_idadmin INT UNSIGNED NOT NULL,
  KEY idx_logprod_produto (produto_idproduto),
  KEY idx_logprod_admin (admin_idadmin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============ SEEDS ============
-- Os 4 textos da home (index.php usa $texto[0..3] em ordem natural).
-- Conteúdo é placeholder; será substituído na fase "atualização de conteúdo".
INSERT INTO texto (idtexto, titulo, titulo_en, texto, texto_en, texto_modal, texto_modal_en) VALUES
(1, 'Início', 'Home',
 '<p>Bem-vindo à Newco Brazil. Indústria de produtos funcionais.</p>',
 '<p>Welcome to Newco Brazil. Functional products industry.</p>',
 '', ''),
(2, 'Quem Somos', 'About Us',
 '<p>A Newco Brazil é referência em soluções industriais de produtos funcionais para o mercado nacional e internacional.</p>',
 '<p>Newco Brazil is a reference in industrial solutions of functional products for the national and international market.</p>',
 '<p>Texto adicional sobre nossa história, missão, visão e valores.</p>',
 '<p>Additional text about our history, mission, vision and values.</p>'),
(3, 'O que fazemos', 'What we do',
 '<p>Desenvolvemos e produzimos adoçantes, frutas processadas, óleos especiais e ingredientes funcionais para a indústria alimentícia.</p>',
 '<p>We develop and produce sweeteners, processed fruits, special oils and functional ingredients for the food industry.</p>',
 '', ''),
(4, 'Produtos', 'Products',
 '<p>Conheça nossas categorias de produtos clicando nas imagens abaixo.</p>',
 '<p>Discover our product categories by clicking on the images below.</p>',
 '', '');

-- Único registro de contato (a tabela é singleton no app).
INSERT INTO contato (idcontato, endereco, telefone1, telefone2, email) VALUES
(1, 'Rua Exemplo, 100 — Centro, Cidade/UF, CEP 00000-000',
 '+55 (35) 9989-6978', '+55 (35) 9126-9835', 'contato@newcobrazil.com');

-- Categorias de produtos.
-- IDs alinhados com o código legado em well-known/en/{adocantes,frutas,funcionais,oleos}.php:
-- 1 = Frutas e Vegetais, 2 = Adoçantes, 3 = Outros Funcionais, 4 = Óleos.
INSERT INTO categoria (idcategoria, nome_cat, nome_cat_en, capa, status) VALUES
(1, 'Frutas e Vegetais', 'Fruits and Vegetables', '01.jpg', 0),
(2, 'Adoçantes', 'Sweeteners', '02.jpg', 0),
(3, 'Outros Funcionais', 'Other Functional Products', '03.jpg', 0),
(4, 'Óleos', 'Oils', '04.jpg', 0);

-- Produtos por categoria. O campo `short` precisa ser único e válido como ID HTML
-- (produto.php usa o valor em document.getElementById e na URL do modal).
INSERT INTO produto
  (nome, nome_en, short, descricao, descricao_en, funcionalidade, funcionalidade_en,
   imagem, banner, categoria_idcategoria, status) VALUES
-- Categoria 2: Adoçantes
('Monk Fruit', 'Monk Fruit', 'prod-monk',
 '<p>Adoçante natural extraído do monk fruit.</p>',
 '<p>Natural sweetener extracted from monk fruit.</p>',
 '<p>Zero calorias, alto poder adoçante.</p>',
 '<p>Zero calories, high sweetening power.</p>',
 'monge.jpg', 'adocante_banner.jpg', 2, 0),
('Cana de Açúcar', 'Sugar Cane', 'prod-cana',
 '<p>Adoçante derivado da cana de açúcar.</p>',
 '<p>Sweetener derived from sugar cane.</p>',
 '<p>Fonte natural de energia.</p>',
 '<p>Natural source of energy.</p>',
 'cana.jpg', 'adocante_banner.jpg', 2, 0),
-- Categoria 1: Frutas e Vegetais
('Acerola', 'Acerola', 'prod-acerola',
 '<p>Acerola in natura, rica em vitamina C.</p>',
 '<p>Acerola in natura, rich in vitamin C.</p>',
 '<p>Antioxidante e fortalecedor do sistema imunológico.</p>',
 '<p>Antioxidant and immune system booster.</p>',
 'Acerola-basket.jpg', 'acerola-info.jpg', 1, 0),
('Maracujá', 'Passion Fruit', 'prod-maracuja',
 '<p>Maracujá tropical brasileiro.</p>',
 '<p>Brazilian tropical passion fruit.</p>',
 '<p>Calmante natural e fonte de fibras.</p>',
 '<p>Natural calmer and fiber source.</p>',
 'passionfruit.jpg', NULL, 1, 0);

-- Admin master para acesso local.
-- Login: admin  |  Senha: admin123  (somente desenvolvimento — trocar antes de qualquer ambiente compartilhado).
-- Hash bcrypt de "admin123" gerado via password_hash(..., PASSWORD_BCRYPT).
INSERT INTO admin (idadmin, login, senha, permissao, status) VALUES
(1, 'admin', '$2y$10$5E9xn7VS97KIunTuPCQ81OVSDDfI8P3Z0jqecmA7Vev0IXimyKLES', 0, 0);
