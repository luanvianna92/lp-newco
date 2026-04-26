<?php 
    session_start();
    require '../../database.php';
    //CONFERE SE O FORMULÁRIO FOI ENVIADO
    if (isset($_POST['editar'])) {
        //PEGA AS ALTERAÇÕES ENVIADAS PARA GRAVAR NO LOG
        $cod_atual = $_POST;
        //REMOVE VARIÁVEL QUE NÃO É NECESSÁRIA
        unset($cod_atual['editar']);
        unset($cod_atual['idcategoria']);

        //PEGA O QUE CONSTA NO BANCO ANTES DE SER EDITADO PARA GRAVAR NO LOG
        $sql = "SELECT nome_cat, nome_cat_en, capa FROM categoria WHERE idcategoria = " . $_POST['idcategoria'];
        $stmt_categoria = $conn->prepare($sql);
        $stmt_categoria->execute();
        $cod_anterior = $stmt_categoria->fetch(PDO::FETCH_ASSOC);

        //CASO A CAPA TENHA SIDO REENVIADA
        if (is_uploaded_file($_FILES['capa_edit']['tmp_name'])) {
            $image = $_FILES['capa_edit']['tmp_name'];
            $image_name = $_FILES['capa_edit']['name'];

            move_uploaded_file($image, "../../images/categorias/$image_name");

            //INSERE A CAPA NO LOG
            $cod_atual['capa_edit'] = $image_name;

            //SQL PARA ALTERAR CAPA
            $sql ="UPDATE categoria SET nome_cat = :nome_cat, nome_cat_en = :nome_cat_en, capa = :capa WHERE idcategoria = :idcategoria";

            //PREPARA
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome_cat', $_POST["nome_cat_edit"]);
            $stmt->bindParam(':nome_cat_en', $_POST["nome_cat_en_edit"]);
            $stmt->bindParam(':capa', $image_name);
            $stmt->bindParam(':idcategoria', $_POST["idcategoria"]);

        } else {
            //CASO CAPA NÃO TENHA SIDO ENVIADA
            //REMOVE AS VARIÁVEIS DO LOG QUE NÃO SÃO NECESSÁRIAS
            unset($cod_anterior['capa']);

            //SQL QUE NÃO ALTERA CAPA
            $sql ="UPDATE categoria SET nome_cat = :nome_cat, nome_cat_en = :nome_cat_en WHERE idcategoria = :idcategoria";

            //PREPARA
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome_cat', $_POST["nome_cat_edit"]);
            $stmt->bindParam(':nome_cat_en', $_POST["nome_cat_en_edit"]);
            $stmt->bindParam(':idcategoria', $_POST["idcategoria"]);
        }

        //PREPARA O LOG
        // 0 = INSERÇÃO | 1 = ALTERAÇÃO | 3 = EXCLUSÃO
        $acao = 1;

        //CONVERTE OS REGISTROS PARA JSON PARA ARMAZENAR NO BANCO
        $cod_anterior_json = json_encode($cod_anterior);
        $cod_atual_json = json_encode($cod_atual);

        //ID DO ADMIN LOGADO
        $id_admin = $_SESSION['id_admin'];

        //COMANDO DE INSERÇÃO DO LOG
        $sql_log = "INSERT INTO log_categoria (acao, cod_anterior, cod_atual, hora, categoria_idcategoria, admin_idadmin) VALUES (:acao, :cod_anterior, :cod_atual, :hora, :categoria, :idadmin)";

        //EXECUTA O COMANDO DA ALTERAÇÃO NO BANCO DE DADOS
        if ($stmt->execute()) {
            //MENSAGEM DE RETORNO DENTRO DA SESSÃO
            $_SESSION['retornoCategoria'] = "Categoria atualizada!";

            //HORÁRIO DO REGISTRO
            date_default_timezone_set('America/Sao_Paulo');
            $hora = date("Y-m-d H:i:s");

            //ATRIBUIÇÃO DAS VARIÁVEIS DO LOG
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bindParam(':acao', $acao);
            $stmt_log->bindParam(':cod_anterior', $cod_anterior_json);
            $stmt_log->bindParam(':cod_atual', $cod_atual_json);
            $stmt_log->bindParam(':hora', $hora);
            $stmt_log->bindParam(':categoria', $_POST['idcategoria']);
            $stmt_log->bindParam(':idadmin', $id_admin);
            
            //GRAVA O LOG
            if (!$stmt_log->execute()) {
                $_SESSION['retornoCategoria'] = "Problema para registrar o log! Por favor contate o desenvolvedor.";
            }

            //VOLTA PARA A PÁGINA QUE ESTAVA
            header('location: ../categorias.php');
        } 
        else {
            $_SESSION['retornoCategoria'] = '"Erro ao atualizar a categoria: "'. $stmt->error_info();
            header('location: ../categorias.php');
        }
        
    //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
    } else {
        header('location: ../../index.php');
    }
 ?>