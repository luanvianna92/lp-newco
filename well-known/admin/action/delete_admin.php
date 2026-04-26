<?php
    session_start();
    require_once '../../database.php';

    if (isset($_POST["idadmin"])) {
        //PREPARA O COMANDO PARA DELETAR O PRODUTO
        $sql = "UPDATE admin SET status = 1 WHERE idadmin = :idadmin";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idadmin', $_POST["idadmin"]);
        
        if ($stmt->execute()) {
            echo '1';  	//RETORNA 1 PARA CONFIRMAR O SUCESSO
        }
        else {
            echo $stmt->error_info(); 	//RETORNA O ERRO CASO TENHA FALHADO
        }
        
    //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
    } else {
        header('location: ../../index.php'); //PÁGINA INICIAL DO SITE
    }
 ?>