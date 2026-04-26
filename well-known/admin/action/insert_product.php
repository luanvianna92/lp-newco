<?php 
session_start();
require '../../database.php';
//CONFERE SE O FORMULÁRIO  FOI ENVIADO
if (isset($_POST['cadastrar'])) {

    //PEGA OS DADOS ENVIADAS PARA GRAVAR NO LOG
    $cod_atual = $_POST;
    //REMOVE VARIÁVEL QUE NÃO É NECESSÁRIA
    unset($cod_atual['cadastrar']);

    //CONFERE SE O CAMPO DA IMAGEM FOI PREENCHIDO
    if (!empty($_FILES['imagem'])) {
        //PEGA NOME E TMP DA IMAGEM DO PRODUTO
        $image = $_FILES['imagem']['tmp_name'];
        $image_name = $_FILES['imagem']['name'];

        //SALVA A IMAGEM NA PASTA PRODUCT_IMAGES
        move_uploaded_file($image, "../product_images/$image_name");

        //INSERE A IMAGEM NO LOG
        $cod_atual['imagem_edit'] = $image_name;
    }

    //CONFERE SE O CAMPO DO BANNER FOI PREENCHIDO
    if (!empty($_FILES['banner'])) {
        //PEGA NOME E TMP DO BANNER
        $banner = $_FILES['banner']['tmp_name'];
        $banner_name = $_FILES['banner']['name'];

        //SALVA A IMAGEM DO BANNER NA PASTA BANNERS
        move_uploaded_file($banner, "../banners/$banner_name");

        //INSERE O BANNER NO LOG
        $cod_atual['banner_edit'] = $banner_name;
    }

    //COMANDO PARA CADASTRAR PRODUTO NO BANCO DE DADOS
    $sql ="INSERT INTO produto (nome, short, descricao, funcionalidade, imagem, banner, categoria_idcategoria, nome_en, descricao_en, funcionalidade_en, status) VALUES (:nome, :short, :descricao, :funcionalidade, :imagem, :banner, :idCategoria, :nome_en, :descricao_en, :funcionalidade_en, :status)";

    //STATUS 0 = ATIVO  |   1 = DELETADO
    $status = 0;

    //ADICIONA HASH AO FINAL DO SHORT PRA NÃO DAR CONFLITO, PQ ELE TEM QUE SER ÚNICO
    $bytes = random_bytes(5);
    $short = $_POST["short"] . $bytes;

    //PREENCHE AS VARIÁVEIS COM AS INFORMAÇÕES DO FORMULÁRIO
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $_POST["nome"]);
    $stmt->bindParam(':nome_en', $_POST["nome_en"]);
    $stmt->bindParam(':short', $short);
    $stmt->bindParam(':descricao', $_POST["descricao"]);
    $stmt->bindParam(':descricao_en', $_POST["descricao_en"]);
    $stmt->bindParam(':funcionalidade', $_POST["funcionalidade"]);
    $stmt->bindParam(':funcionalidade_en', $_POST["funcionalidade_en"]);
    $stmt->bindParam(':imagem', $image_name);
    $stmt->bindParam(':banner', $banner_name);
    $stmt->bindParam(':idCategoria', $_POST["categoria"]);
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
    $sql_log = "INSERT INTO log_produto (acao, cod_anterior, cod_atual, hora, produto_idproduto, admin_idadmin) VALUES (:acao, :cod_anterior, :cod_atual, :hora, :produto, :idadmin)";

    //EXECUTA O COMANDO NO BANCO DE DADOS
    if ($stmt->execute()) {
        //ID DO PRODUTO RECÉM INSERIDO
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
        $stmt_log->bindParam(':produto', $id_atual);
        $stmt_log->bindParam(':idadmin', $id_admin);

        $_SESSION['retornoProduto'] = "Produto inserido com sucesso";   //MENSAGEM DE RETORNO DENTRO DA SESSÃO

        //GRAVA O LOG
        if (!$stmt_log->execute()) {
            $_SESSION['retornoProduto'] = "Problema para registrar o log! Por favor contate o desenvolvedor.";
        }

        //VOLTA PARA A PÁGINA PRINCIPAL
        header('location: ../produtos.php');
    } else {
        $_SESSION['retornoProduto'] = $stmt->error_info();
        header('location: ../produtos.php');
    }

//CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
 } else {
    header('location: ../../index.php');
 }
 ?> 