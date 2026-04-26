<?php 
    session_start();
    require '../../database.php';
    //CONFERE SE O FORMULÁRIO FOI ENVIADO
    if (isset($_POST['editar'])) {

        //VERIFICA SE SENHA FOI ALTERADA OU NÃO
        if (!empty($_POST['senha_adm_edit'])) {
            //ENCRIPTA A SENHA
            $hashPassword = password_hash($_POST['senha_adm_edit'], PASSWORD_BCRYPT);
            //SQL ALTERA SENHA
            $sql ="UPDATE admin SET login = :login, senha = :senha WHERE idadmin = :idadmin";
            //PREPARA
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':login', $_POST["login_adm_edit"]);
            $stmt->bindParam(':senha', $hashPassword);
            $stmt->bindParam(':idadmin', $_POST["idadmin"]);

        } else {
            $sql ="UPDATE admin SET login = :login WHERE idadmin = :idadmin";
            //PREPARA
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':login', $_POST["login_adm_edit"]);
            $stmt->bindParam(':idadmin', $_POST["idadmin"]);
        }
        
        //EXECUTA O COMANDO DA ALTERAÇÃO NO BANCO DE DADOS
        if ($stmt->execute()) {
            //MENSAGEM DE RETORNO DENTRO DA SESSÃO
            $_SESSION['retornoConta'] = "Conta atualizada!";

            //VOLTA PARA A PÁGINA QUE ESTAVA
            header('location: ../contas.php');
        } 
        else {
            $_SESSION['retornoConta'] = '"Erro ao atualizar a conta: "'. $stmt->error_info();
            header('location: ../contas.php');
        }
        
    //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
    } else {
        header('location: ../../index.php');
    }
 ?>