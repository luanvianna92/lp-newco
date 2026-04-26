<?php 
session_start();
require '../../database.php';
//CONFERE SE O FORMULÁRIO DE EDIÇÃO FOI PREENCHIDO
if (isset($_POST['edit_texto'])) {

    //SQL PARA ALTERAR BANNER E IMAGEM
    $sql ="UPDATE texto SET titulo = :titulo, titulo_en = :titulo_en, texto = :texto, texto_en = :texto_en, texto_modal = :texto_modal, texto_modal_en = :texto_modal_en WHERE idtexto = :idtexto";

    //PREPARA E EXECUTA
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idtexto', $_POST["id_texto"]);
    $stmt->bindParam(':titulo', $_POST["txt_titulo"]);
    $stmt->bindParam(':titulo_en', $_POST["txt_titulo_en"]);
    $stmt->bindParam(':texto', $_POST["txt_principal"]);
    $stmt->bindParam(':texto_en', $_POST["txt_principal_en"]);
    $stmt->bindParam(':texto_modal', $_POST["txt_modal"]);
    $stmt->bindParam(':texto_modal_en', $_POST["txt_modal_en"]);


    if ($stmt->execute()) {
        $_SESSION['retornoEdit'] = "Atualizado com sucesso!";   //MENSAGEM DE RETORNO DENTRO DA SESSÃO
        header('location: ../textos.php'); //VOLTA PARA A PÁGINA PRINCIPAL
    } else {
        $_SESSION['retornoEdit'] = $stmt->error_info();
        header('location: ../textos.php');
    }
//CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
 } else {
    header('location: ../../index.php');
 }
 ?>

?>