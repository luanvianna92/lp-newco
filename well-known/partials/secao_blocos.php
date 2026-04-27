<?php
/**
 * Helper para carregar os sub-blocos de uma seção pelo slug.
 * Usado pelas seções Tecnologia (A1), Localização (A3), Sustentabilidade (A4) etc.
 *
 * @param PDO    $conn
 * @param string $slug   slug da seção pai (ex: 'tecnologia')
 * @return array         lista de blocos ativos ordenados
 */
if (!function_exists('secao_blocos_por_slug')) {
    function secao_blocos_por_slug(PDO $conn, string $slug): array {
        $sql = "SELECT b.*
                FROM secao_bloco b
                INNER JOIN secao s ON s.idsecao = b.secao_id
                WHERE s.slug = :slug AND b.ativo = 1
                ORDER BY b.ordem ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
