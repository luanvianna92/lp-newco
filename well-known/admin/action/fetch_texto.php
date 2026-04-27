<?php
require_once '../../database.php';

if (isset($_POST["idTexto"])) {
    // Frente A5: a tabela `texto` foi substituída por `secao`. Mantemos o
    // nome do parâmetro POST (`idTexto`) por compatibilidade com o admin atual.
    $sql = "SELECT
                idsecao         AS idtexto,
                titulo,
                titulo_en,
                conteudo        AS texto,
                conteudo_en     AS texto_en,
                conteudo_modal  AS texto_modal,
                conteudo_modal_en AS texto_modal_en
            FROM secao WHERE idsecao = :idTexto";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idTexto', $_POST["idTexto"]);
    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'fetch failed']);
    }
} else {
    header('location: ../../index.php');
}
