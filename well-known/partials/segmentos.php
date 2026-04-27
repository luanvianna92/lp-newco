<?php
/**
 * Helpers para a frente C1 — segmentação Nutracêutico/Alimentício.
 */

if (!function_exists('segmentos_ativos')) {
    /**
     * Lista todos os segmentos ativos ordenados.
     */
    function segmentos_ativos(PDO $conn): array {
        $stmt = $conn->prepare(
            "SELECT * FROM segmento WHERE ativo = 1 ORDER BY ordem ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('segmentos_por_produto')) {
    /**
     * Retorna mapa [idproduto => [seg1, seg2, ...]] para uma lista de produtos.
     * Cada segmento é um array com idsegmento, slug, nome, nome_en, cor_hex.
     */
    function segmentos_por_produto(PDO $conn, array $produto_ids): array {
        if (empty($produto_ids)) return [];
        $placeholders = implode(',', array_fill(0, count($produto_ids), '?'));
        $sql = "SELECT ps.produto_id, s.idsegmento, s.slug, s.nome, s.nome_en, s.cor_hex
                FROM produto_segmento ps
                INNER JOIN segmento s ON s.idsegmento = ps.segmento_id
                WHERE s.ativo = 1 AND ps.produto_id IN ($placeholders)
                ORDER BY s.ordem ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute($produto_ids);
        $mapa = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $pid = (int) $row['produto_id'];
            unset($row['produto_id']);
            $mapa[$pid][] = $row;
        }
        return $mapa;
    }
}
