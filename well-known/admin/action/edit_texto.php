<?php
session_start();
require '../../database.php';

if (isset($_POST['edit_texto'])) {
    // Frente A5: tabela `secao` substituiu `texto`. POST keys mantidas para
    // compatibilidade com o admin atual (txt_titulo, txt_principal, etc.).
    $sql = "UPDATE secao SET
              titulo            = :titulo,
              titulo_en         = :titulo_en,
              conteudo          = :conteudo,
              conteudo_en       = :conteudo_en,
              conteudo_modal    = :conteudo_modal,
              conteudo_modal_en = :conteudo_modal_en
            WHERE idsecao = :idsecao";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idsecao',           $_POST["id_texto"]);
    $stmt->bindParam(':titulo',            $_POST["txt_titulo"]);
    $stmt->bindParam(':titulo_en',         $_POST["txt_titulo_en"]);
    $stmt->bindParam(':conteudo',          $_POST["txt_principal"]);
    $stmt->bindParam(':conteudo_en',       $_POST["txt_principal_en"]);
    $stmt->bindParam(':conteudo_modal',    $_POST["txt_modal"]);
    $stmt->bindParam(':conteudo_modal_en', $_POST["txt_modal_en"]);

    if ($stmt->execute()) {
        $_SESSION['retornoEdit'] = "Atualizado com sucesso!";
        header('location: ../textos.php');
    } else {
        $_SESSION['retornoEdit'] = "Erro ao atualizar a seção.";
        header('location: ../textos.php');
    }
} else {
    header('location: ../../index.php');
}
