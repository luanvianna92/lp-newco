<?php 
    session_start();
    require '../../database.php';
    //CONFERE SE O FORMULÁRIO  FOI ENVIADO
    if (isset($_POST['cadastrar'])) {

        //PEGA OS DADOS ENVIADAS PARA GRAVAR NO LOG
        $cod_atual = $_POST;
        //REMOVE VARIÁVEL QUE NÃO É NECESSÁRIA
        unset($cod_atual['cadastrar']);

        //CONFERE SE O CAMPO DA CAPA FOI PREENCHIDO
        if (!empty($_FILES['capa'])) {
            //PEGA NOME E TMP DA CAPA DA CATEGORIA
            $image = $_FILES['capa']['tmp_name'];
            $image_name = $_FILES['capa']['name'];

            //SALVA A CAPA NA PASTA IMAGES/CATEGORIAS
            move_uploaded_file($image, "../../images/categorias/$image_name");

            //INSERE A CAPA NO LOG
            $cod_atual['capa_edit'] = $image_name;
        }

        //COMANDO PARA CADASTRAR CATEGORIA NO BANCO DE DADOS
        $sql ="INSERT INTO categoria (nome_cat, nome_cat_en, capa, status) VALUES (:nome_cat, :nome_cat_en, :capa, :status)";

        //STATUS 0 = ATIVO  |   1 = DELETADO
        $status = 0;

        //PREENCHE AS VARIÁVEIS COM AS INFORMAÇÕES DO FORMULÁRIO
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome_cat', $_POST["nome_cat"]);
        $stmt->bindParam(':nome_cat_en', $_POST["nome_cat_en"]);
        $stmt->bindParam(':capa', $image_name);
        $stmt->bindParam(':status', $status);

        //PREPARA O LOG
        // 0 = INSERÇÃO | 1 = ALTERAÇÃO | 3 = EXCLUSÃO
        $acao = 0;

        //CONVERTE O REGISTRO PARA JSON PARA ARMAZENAR NO BANCO
        $cod_atual_json = json_encode($cod_atual);
        //NÃO HÁ CÓDIGO ANTERIOR
        $cod_anterior = null;

        //ID DO ADMIN LOGADO
        $id_admin = $_SESSION['id_admin'];

        //COMANDO DE INSERÇÃO DO LOG
        $sql_log = "INSERT INTO log_categoria (acao, cod_anterior, cod_atual, hora, categoria_idcategoria, admin_idadmin) VALUES (:acao, :cod_anterior, :cod_atual, :hora, :categoria, :idadmin)";

        echo $cod_atual_json;
        //EXECUTA O COMANDO NO BANCO DE DADOS
        if ($stmt->execute()) {
            //ID DA CATEGORIA RECÉM INSERIDO
            $id_atual = $conn->lastInsertId();

            //HORÁRIO DO REGISTRO
            date_default_timezone_set('America/Sao_Paulo');
            $hora = date("Y-m-d H:i:s");

            //ATRIBUIÇÃO DAS VARIÁVEIS DO LOG
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bindParam(':acao', $acao);
            $stmt_log->bindParam(':cod_anterior', $cod_anterior);
            $stmt_log->bindParam(':cod_atual', $cod_atual_json);
            $stmt_log->bindParam(':hora', $hora);
            $stmt_log->bindParam(':categoria', $id_atual);
            $stmt_log->bindParam(':idadmin', $id_admin);

            $_SESSION['retornoCategoria'] = "Categoria inserido com sucesso";   //MENSAGEM DE RETORNO DENTRO DA SESSÃO

            //GRAVA O LOG
            if (!$stmt_log->execute()) {
                $_SESSION['retornoCategoria'] = "Problema para registrar o log! Por favor contate o desenvolvedor.";
            }

            //VOLTA PARA A PÁGINA EM QUE ESTAVA
            header('location: ../categorias.php');
        } else {
            $_SESSION['retornoCategoria'] = $stmt->error_info();
            header('location: ../categorias.php');
        }

    //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
    } else {
        header('location: ../../index.php');
    }
 ?> 