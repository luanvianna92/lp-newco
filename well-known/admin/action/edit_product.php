<?php 
session_start();
require '../../database.php';
//CONFERE SE O FORMULÁRIO FOI ENVIADO
if (isset($_POST['editar'])) {

    //PEGA AS ALTERAÇÕES ENVIADAS PARA GRAVAR NO LOG
    $cod_atual = $_POST;
    //REMOVE VARIÁVEL QUE NÃO É NECESSÁRIA
    unset($cod_atual['editar']);
    unset($cod_atual['idproduto']);

    //PEGA O QUE CONSTA ATUALMENTE NO BANCO PARA GRAVAR NO LOG
    $sql_produto = "SELECT nome, short, banner, imagem, nome_en, categoria_idcategoria AS categoria, descricao, descricao_en, funcionalidade, funcionalidade_en FROM produto WHERE idproduto = " . $_POST['idproduto'];
    $stmt_produto = $conn->prepare($sql_produto);
    $stmt_produto->execute();
    $cod_anterior = $stmt_produto->fetch(PDO::FETCH_ASSOC);

    //CASO TANTO IMAGEM COMO BANNER TENHAM SIDO REENVIADOS
    if (is_uploaded_file($_FILES['imagem_edit']['tmp_name']) && is_uploaded_file($_FILES['banner_edit']['tmp_name'])) {
        $image = $_FILES['imagem_edit']['tmp_name'];
        $image_name = $_FILES['imagem_edit']['name'];

        move_uploaded_file($image, "../product_images/$image_name");
        
        $banner = $_FILES['banner_edit']['tmp_name'];
        $banner_name = $_FILES['banner_edit']['name'];

        move_uploaded_file($banner, "banners/$banner_name");

        //INSERE IMAGEM E BANNER NO LOG
        $cod_atual['imagem_edit'] = $image_name;
        $cod_atual['banner_edit'] = $banner_name;

        //SQL PARA ALTERAR BANNER E IMAGEM
        $sql ="UPDATE produto SET nome = :nome, short = :short, descricao = :descricao, funcionalidade = :funcionalidade, imagem = :imagem, banner = :banner, categoria_idcategoria = :idCategoria, nome_en = :nome_en, descricao_en = :descricao_en, funcionalidade_en = :funcionalidade_en WHERE idproduto = :idproduto";

        //PREPARA OS PARAMETROS
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $_POST["nome_edit"]);
        $stmt->bindParam(':nome_en', $_POST["nome_en_edit"]);
        $stmt->bindParam(':short', $_POST["short_edit"]);
        $stmt->bindParam(':descricao', $_POST["descricao_edit"]);
        $stmt->bindParam(':descricao_en', $_POST["descricao_en_edit"]);
        $stmt->bindParam(':funcionalidade', $_POST["funcionalidade_edit"]);
        $stmt->bindParam(':funcionalidade_en', $_POST["funcionalidade_en_edit"]);
        $stmt->bindParam(':imagem', $image_name);
        $stmt->bindParam(':banner', $banner_name);
        $stmt->bindParam(':idCategoria', $_POST["categoria_edit"]);
        $stmt->bindParam(':idproduto', $_POST["idproduto"]);

    } else 

    //CASO APENAS A IMAGEM TENHA SIDO REENVIADA
    if (is_uploaded_file($_FILES['imagem_edit']['tmp_name'])) {
        $image = $_FILES['imagem_edit']['tmp_name'];
        $image_name = $_FILES['imagem_edit']['name'];

        move_uploaded_file($image, "../product_images/$image_name");

        //INSERE A IMAGEM NO LOG
        $cod_atual['imagem_edit'] = $image_name;

        //REMOVE AS VARIÁVEIS DO LOG QUE NÃO SÃO NECESSÁRIAS
        unset($cod_anterior['banner']);

        //SQL PARA ALTERAR IMAGEM
        $sql ="UPDATE produto SET nome = :nome, short = :short, descricao = :descricao, funcionalidade = :funcionalidade, imagem = :imagem, categoria_idcategoria = :idCategoria, nome_en = :nome_en, descricao_en = :descricao_en, funcionalidade_en = :funcionalidade_en WHERE idproduto = :idproduto";

        //PREPARA
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $_POST["nome_edit"]);
        $stmt->bindParam(':nome_en', $_POST["nome_en_edit"]);
        $stmt->bindParam(':short', $_POST["short_edit"]);
        $stmt->bindParam(':descricao', $_POST["descricao_edit"]);
        $stmt->bindParam(':descricao_en', $_POST["descricao_en_edit"]);
        $stmt->bindParam(':funcionalidade', $_POST["funcionalidade_edit"]);
        $stmt->bindParam(':funcionalidade_en', $_POST["funcionalidade_en_edit"]);
        $stmt->bindParam(':imagem', $image_name);
        $stmt->bindParam(':idCategoria', $_POST["categoria_edit"]);
        $stmt->bindParam(':idproduto', $_POST["idproduto"]);

    } else

    //CASO APENAS O BANNER TENHA SIDO REENVIADO
    if (is_uploaded_file($_FILES['banner_edit']['tmp_name'])) {
        $banner = $_FILES['banner_edit']['tmp_name'];
        $banner_name = $_FILES['banner_edit']['name'];

        move_uploaded_file($banner, "../banners/$banner_name");

        //INSERE O BANNER NO LOG
        $cod_atual['banner_edit'] = $banner_name;

        //REMOVE AS VARIÁVEIS DO LOG QUE NÃO SÃO NECESSÁRIAS
        unset($cod_anterior['imagem']);


        //SQL PARA ALTERAR BANNER
        $sql ="UPDATE produto SET nome = :nome, short = :short, descricao = :descricao, funcionalidade = :funcionalidade, banner = :banner, categoria_idcategoria = :idCategoria, nome_en = :nome_en, descricao_en = :descricao_en, funcionalidade_en = :funcionalidade_en WHERE idproduto = :idproduto";

        //PREPARA
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $_POST["nome_edit"]);
        $stmt->bindParam(':nome_en', $_POST["nome_en_edit"]);
        $stmt->bindParam(':short', $_POST["short_edit"]);
        $stmt->bindParam(':descricao', $_POST["descricao_edit"]);
        $stmt->bindParam(':descricao_en', $_POST["descricao_en_edit"]);
        $stmt->bindParam(':funcionalidade', $_POST["funcionalidade_edit"]);
        $stmt->bindParam(':funcionalidade_en', $_POST["funcionalidade_en_edit"]);
        $stmt->bindParam(':banner', $banner_name);
        $stmt->bindParam(':idCategoria', $_POST["categoria_edit"]);
        $stmt->bindParam(':idproduto', $_POST["idproduto"]);

    }
    //CASO NEM A IMAGEM OU O BANNER TENHAM SIDO REENVIADOS
    else {

        //REMOVE AS VARIÁVEIS DO LOG QUE NÃO SÃO NECESSÁRIAS
        unset($cod_anterior['banner']);
        unset($cod_anterior['imagem']);


        //SQL QUE NÃO ALTERA BANNER NEM IMAGEM
        $sql ="UPDATE produto SET nome = :nome, short = :short, descricao = :descricao, funcionalidade = :funcionalidade, categoria_idcategoria = :idCategoria, nome_en = :nome_en, descricao_en = :descricao_en, funcionalidade_en = :funcionalidade_en WHERE idproduto = :idproduto";

        //PREPARA E EXECUTA
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $_POST["nome_edit"]);
        $stmt->bindParam(':nome_en', $_POST["nome_en_edit"]);
        $stmt->bindParam(':short', $_POST["short_edit"]);
        $stmt->bindParam(':descricao', $_POST["descricao_edit"]);
        $stmt->bindParam(':descricao_en', $_POST["descricao_en_edit"]);
        $stmt->bindParam(':funcionalidade', $_POST["funcionalidade_edit"]);
        $stmt->bindParam(':funcionalidade_en', $_POST["funcionalidade_en_edit"]);
        $stmt->bindParam(':idCategoria', $_POST["categoria_edit"]);
        $stmt->bindParam(':idproduto', $_POST["idproduto"]);

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
    $sql_log = "INSERT INTO log_produto (acao, cod_anterior, cod_atual, hora, produto_idproduto, admin_idadmin) VALUES (:acao, :cod_anterior, :cod_atual, :hora, :produto, :idadmin)";

     //EXECUTA O COMANDO NO BANCO DE DADOS
    if ($stmt->execute()) {
        //MENSAGEM DE RETORNO DENTRO DA SESSÃO
        $_SESSION['retornoProduto'] = "Produto atualizado!";

        //HORÁRIO DO REGISTRO
        date_default_timezone_set('America/Sao_Paulo');
        $hora = date("Y-m-d H:i:s");

        //ATRIBUIÇÃO DAS VARIÁVEIS DO LOG
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bindParam(':acao', $acao);
        $stmt_log->bindParam(':cod_anterior', $cod_anterior_json);
        $stmt_log->bindParam(':cod_atual', $cod_atual_json);
        $stmt_log->bindParam(':hora', $hora);
        $stmt_log->bindParam(':produto', $_POST['idproduto']);
        $stmt_log->bindParam(':idadmin', $id_admin);
        
        //GRAVA O LOG
        if (!$stmt_log->execute()) {
            $_SESSION['retornoProduto'] = "Problema para registrar o log! Por favor contate o desenvolvedor.";
        }

        //VOLTA PARA A PÁGINA PRINCIPAL
        header('location: ../produtos.php');
    } 
    else {
        $_SESSION['retornoProduto'] = '"Erro ao atualizar o produto: "'. $stmt->error_info();
        header('location: ../produtos.php');
    }
    
//CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
 } else {
    header('location: ../../index.php');
 }
 ?>