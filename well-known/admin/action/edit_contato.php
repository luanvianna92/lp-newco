<?php 
session_start();
require '../../database.php';
//CONFERE SE O FORMULÁRIO DE EDIÇÃO FOI PREENCHIDO
if (isset($_POST['edit_contato'])) {

    //SQL PARA ALTERAR CONTATO
    $sql ="UPDATE contato SET endereco = :endereco, telefone1 = :telefone1, telefone2 = :telefone2, email = :email WHERE idcontato = :idcontato";

    //PREPARA E EXECUTA
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idcontato', $_POST["id_contato"]);
    $stmt->bindParam(':endereco', $_POST["cont_endereco"]);
    $stmt->bindParam(':telefone1', $_POST["cont_tel1"]);
    $stmt->bindParam(':telefone2', $_POST["cont_tel2"]);
    $stmt->bindParam(':email', $_POST["cont_email"]);


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