-- Migration A5: substituir tabela `texto` por `secao` (+ `secao_bloco`).
-- Aplicar em produção (cPanel/phpMyAdmin) APÓS deploy do código da frente A5.
-- Em DEV o init.sql já cria o schema novo direto; este arquivo é só para prod.

START TRANSACTION;

-- 1. Cria as novas tabelas
CREATE TABLE IF NOT EXISTS secao (
  idsecao INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(60) NOT NULL,
  ordem SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  tipo VARCHAR(40) NOT NULL DEFAULT 'texto',
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

CREATE TABLE IF NOT EXISTS secao_bloco (
  idbloco INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  secao_id INT UNSIGNED NOT NULL,
  ordem SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  icone VARCHAR(60) DEFAULT NULL,
  valor_destaque VARCHAR(120) DEFAULT NULL,
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

-- 2. Migra dados de `texto` para `secao` mapeando posição → slug.
INSERT INTO secao (idsecao, slug, ordem, tipo, titulo, titulo_en, conteudo, conteudo_en, conteudo_modal, conteudo_modal_en)
SELECT
  idtexto,
  CASE idtexto
    WHEN 1 THEN 'inicio'
    WHEN 2 THEN 'quem-somos'
    WHEN 3 THEN 'oque-fazemos'
    WHEN 4 THEN 'produtos'
    ELSE CONCAT('legacy-', idtexto)
  END AS slug,
  idtexto * 10 AS ordem,
  CASE idtexto
    WHEN 1 THEN 'hero'
    WHEN 4 THEN 'galeria'
    ELSE 'texto'
  END AS tipo,
  titulo, titulo_en, texto, texto_en, texto_modal, texto_modal_en
FROM texto;

-- 3. Drop da tabela antiga.
DROP TABLE texto;

COMMIT;
