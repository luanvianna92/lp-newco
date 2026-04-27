-- Schema reconstruído por engenharia reversa das queries de well-known/**.
-- Frente A5: tabela `texto` substituída por `secao` (modelo flexível).
SET NAMES utf8mb4;
SET time_zone = 'America/Sao_Paulo';

-- ============ SEÇÕES INSTITUCIONAIS ============
-- Substitui a antiga `texto` (4 linhas fixas, acessadas por posição).
-- `secao` permite ordenar, ativar/desativar, criar novas seções sem mexer em código.
CREATE TABLE secao (
  idsecao INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(60) NOT NULL,
  ordem SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  tipo VARCHAR(40) NOT NULL DEFAULT 'texto',
  -- 'hero' | 'texto' | 'galeria' | 'tecnologia' | 'kpis' | 'sustentabilidade' | etc.
  titulo VARCHAR(255) NOT NULL DEFAULT '',
  titulo_en VARCHAR(255) NOT NULL DEFAULT '',
  subtitulo VARCHAR(500) DEFAULT NULL,
  subtitulo_en VARCHAR(500) DEFAULT NULL,
  conteudo LONGTEXT,
  conteudo_en LONGTEXT,
  conteudo_modal LONGTEXT,
  conteudo_modal_en LONGTEXT,
  imagem_capa VARCHAR(500) DEFAULT NULL,
  cta_texto VARCHAR(120) DEFAULT NULL,
  cta_texto_en VARCHAR(120) DEFAULT NULL,
  cta_url VARCHAR(500) DEFAULT NULL,
  ativo TINYINT UNSIGNED NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_secao_slug (slug),
  KEY idx_secao_ordem (ativo, ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sub-blocos repetidos dentro de uma seção (3 benefícios do spray-drying,
-- 4 KPIs do Porto Seco, etc.). Permite renderização tipo "grid de cards".
CREATE TABLE secao_bloco (
  idbloco INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  secao_id INT UNSIGNED NOT NULL,
  ordem SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  icone VARCHAR(60) DEFAULT NULL,         -- ex: "fa-thermometer-half"
  valor_destaque VARCHAR(120) DEFAULT NULL,    -- ex: "R$ 25 bi", "65%"
  valor_destaque_en VARCHAR(120) DEFAULT NULL,
  titulo VARCHAR(255) NOT NULL DEFAULT '',
  titulo_en VARCHAR(255) NOT NULL DEFAULT '',
  conteudo TEXT,
  conteudo_en TEXT,
  ativo TINYINT UNSIGNED NOT NULL DEFAULT 1,
  KEY idx_bloco_secao (secao_id, ordem),
  CONSTRAINT fk_bloco_secao FOREIGN KEY (secao_id)
    REFERENCES secao (idsecao) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============ DEMAIS TABELAS (intactas em relação ao schema anterior) ============

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

-- Seções da home (slugs estáveis usados pelo template index.php).
INSERT INTO secao
  (idsecao, slug, ordem, tipo, titulo, titulo_en, conteudo, conteudo_en, conteudo_modal, conteudo_modal_en) VALUES
(1, 'inicio', 10, 'hero',
 'Início', 'Home',
 '<p>Bem-vindo à Newco Brazil. Indústria de produtos funcionais.</p>',
 '<p>Welcome to Newco Brazil. Functional products industry.</p>',
 '', ''),
(2, 'quem-somos', 20, 'texto',
 'Quem Somos', 'About Us',
 '<p>A Newco Brazil é referência em soluções industriais de produtos funcionais para o mercado nacional e internacional.</p>',
 '<p>Newco Brazil is a reference in industrial solutions of functional products for the national and international market.</p>',
 '<p>Texto adicional sobre nossa história, missão, visão e valores.</p>',
 '<p>Additional text about our history, mission, vision and values.</p>'),
(3, 'oque-fazemos', 30, 'texto',
 'O que fazemos', 'What we do',
 '<p>Desenvolvemos e produzimos adoçantes, frutas processadas, óleos especiais e ingredientes funcionais para a indústria alimentícia.</p>',
 '<p>We develop and produce sweeteners, processed fruits, special oils and functional ingredients for the food industry.</p>',
 '', ''),
(4, 'produtos', 40, 'galeria',
 'Produtos', 'Products',
 '<p>Conheça nossas categorias de produtos clicando nas imagens abaixo.</p>',
 '<p>Discover our product categories by clicking on the images below.</p>',
 '', '');

-- Frente A1: seção Tecnologia (spray-drying) com 3 benefícios em sub-blocos.
INSERT INTO secao
  (idsecao, slug, ordem, tipo, titulo, titulo_en, subtitulo, subtitulo_en, conteudo, conteudo_en) VALUES
(5, 'tecnologia', 35, 'tecnologia',
 'Tecnologia', 'Technology',
 'Spray-drying: secagem por atomização',
 'Spray-drying: atomization drying',
 '<p>A técnica de Spray-drying (secagem por atomização) é um dos métodos mais eficientes e utilizados na indústria alimentícia e farmacêutica para transformar líquidos (soluções, emulsões ou suspensões) em pó fino e seco. Essa tecnologia é reconhecida por preservar a alta qualidade dos ingredientes, sendo ideal para produtos sensíveis ao calor: a secagem ocorre de forma quase instantânea, minimizando a degradação térmica e garantindo a manutenção das propriedades nutricionais e organolépticas (sabor, aroma e cor) do produto in natura.</p>',
 '<p>Spray-drying (atomization drying) is one of the most efficient and widely used methods in the food and pharmaceutical industries for transforming liquids (solutions, emulsions or suspensions) into fine, dry powder. This technology is recognized for preserving the high quality of ingredients and is ideal for heat-sensitive products: drying occurs almost instantly, minimizing thermal degradation and ensuring the maintenance of nutritional and organoleptic properties (flavor, aroma and color) of the in natura product.</p>');

INSERT INTO secao_bloco (secao_id, ordem, icone, titulo, titulo_en, conteudo, conteudo_en) VALUES
(5, 1, 'fa-thermometer-half',
 'Baixo dano térmico', 'Low thermal damage',
 '<p>Embora o ar de entrada seja quente, o tempo de exposição é tão curto e a evaporação da água tão rápida que a temperatura da partícula seca não atinge níveis que causem degradação do produto.</p>',
 '<p>Although the inlet air is hot, exposure time is so short and water evaporation so rapid that the dried particle temperature never reaches levels that cause product degradation.</p>'),
(5, 2, 'fa-shield',
 'Microencapsulação', 'Microencapsulation',
 '<p>A técnica permite envolver compostos bioativos em uma matriz protetora (agentes encapsulantes), protegendo nutrientes (vitaminas, antioxidantes) e aromas contra oxidação e volatilização — preservando sabor e cor por prazos de validade mais longos.</p>',
 '<p>The technique allows bioactive compounds to be wrapped in a protective matrix (encapsulating agents), protecting nutrients (vitamins, antioxidants) and aromas against oxidation and volatilization — preserving flavor and color for longer shelf lives.</p>'),
(5, 3, 'fa-tint',
 'Alta solubilidade', 'High solubility',
 '<p>Produz um pó esférico e uniforme que se reidrata rapidamente, mantendo a qualidade original do alimento líquido.</p>',
 '<p>Produces a uniform spherical powder that rehydrates quickly, maintaining the original quality of the liquid food.</p>');

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

INSERT INTO produto
  (nome, nome_en, short, descricao, descricao_en, funcionalidade, funcionalidade_en,
   imagem, banner, categoria_idcategoria, status) VALUES
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
-- Login: admin  |  Senha: admin123  (apenas dev — trocar antes de qualquer ambiente compartilhado).
INSERT INTO admin (idadmin, login, senha, permissao, status) VALUES
(1, 'admin', '$2y$10$5E9xn7VS97KIunTuPCQ81OVSDDfI8P3Z0jqecmA7Vev0IXimyKLES', 0, 0);
