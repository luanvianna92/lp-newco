<?php 
    session_start();
    require '../../database.php';
    //CONFERE SE O FORMULÁRIO  FOI ENVIADO
    if (isset($_POST['cadastrar'])) {

        //ENCRIPTA A SENHA
        $hashPassword = password_hash($_POST['senha_adm'], PASSWORD_BCRYPT);

        //COMANDO PARA CADASTRAR A CONTA NO BANCO DE DADOS
        $sql ="INSERT INTO admin (login, senha, permissao, status) VALUES (:login, :senha, :permissao, :status)";

        //STATUS 0 = ATIVO  |   1 = DELETADO
        $status = 0;

        //PERMISSAO 0 = ACESSO TOTAL  |   1 = ACESSO LIMITADO
        $permissao = 1;

        //PREENCHE AS VARIÁVEIS COM AS INFORMAÇÕES ENVIADAS
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login', $_POST["login_adm"]);
        $stmt->bindParam(':senha', $hashPassword);
        $stmt->bindParam(':permissao', $permissao);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $_SESSION['retornoConta'] = "Conta inserido com sucesso!";   //MENSAGEM DE RETORNO DENTRO DA SESSÃO

            //VOLTA PARA A PÁGINA EM QUE ESTAVA
            header('location: ../contas.php');
        } else {
            $_SESSION['retornoConta'] = $stmt->error_info();
            header('location: ../contas.php');
        }

    //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
    } else {
        header('location: ../../index.php');
    }
 ?> 