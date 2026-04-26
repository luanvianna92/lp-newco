<?php
    header ('Content-type: text/html; charset=UTF-8');
    //INICIA A SESSAO
    session_start();
    //CONECTA COM O BANCO DE DADOS
    require_once '../database.php';
    //VERIFICA SE O ADMIN ESTÁ LOGADO E POSSUI PERMISSÃO
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php");
        exit;
    }
    if ($_SESSION['permissao'] !== 0) {
        header("Location: home.php");
    }
    //VERIFICA SE HÁ MENSAGEM PARA EXIBIR
    if (isset($_SESSION['retornoLog'])) {
        echo '<script type="text/javascript">alert("'.$_SESSION['retornoLog'].'");</script>';
        header('Refresh:0');
    }
    //LIMPA A SESSÃO DE MENSAGEM
    unset($_SESSION['retornoProduto']);

    //CARREGAR LOGS DE PRODUTOS NO ARRAY $LOG_PRODS
    $sql_prod = "SELECT L.*, P.nome, A.login FROM log_produto AS L INNER JOIN produto AS P ON L.produto_idproduto = P.idproduto INNER JOIN admin AS A ON L.admin_idadmin = A.idadmin";
    $stmt_prod = $conn->prepare($sql_prod);
    if ($stmt_prod->execute()) {
        $log_prods = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo $stmt_prod->error_info();
        die();
    }

    //CARREGAR LOGS DE CATEGORIAS NO ARRAY $LOG_CATS
    $sql_cat = "SELECT L.*, C.nome_cat, A.login FROM log_categoria AS L INNER JOIN categoria AS C ON L.categoria_idcategoria = C.idcategoria INNER JOIN admin AS A ON L.admin_idadmin = A.idadmin";
    $stmt_cat = $conn->prepare($sql_cat);
    if ($stmt_cat->execute()) {
        $log_cats = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo $stmt_cat->error_info();
        die();
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <title>Newco - Painel Administrativo</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME ICONS  -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <!-- BOOTSTRAP TABLE -->
        <link href="assets/css/bootstrap-table.css" rel="stylesheet">
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- CORE JQUERY SCRIPTS -->
        <script src="assets/js/jquery-1.11.1.js"></script>
        <!-- ADICIONA A NAVBAR -->
        <script> $(function(){$("#navbar").load("navbar.html");});</script>
    </head>
    <body>
        <!-- LOGO HEADER-->
        <div id="navbar"></div>
        <!-- /END LOGO HEADER -->

        <!-- TABELA DE LOGS -->
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h3><a href="home.php"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></h3>
                        <h4 class="page-head-line center">LOGS DE REGISTRO</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            A tabela pode ser ordenada clicando nos cabeçalhos e filtrada através do campo "Search".
                        </div>
                    </div>
                </div>

                <!-- tabela de produtos -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Tabela de Registros</div>
                            <div class="panel-body">
                                <table id="log_table" data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                                    <thead>
                                        <tr>
                                            <th data-field="acao"  data-sortable="true">Ação</th>
                                            <th data-field="tabela" data-sortable="true">Tabela</th>
                                            <th data-field="hora" data-sortable="true">Hora</th>
                                            <th data-field="detalhes" data-sortable="true">Detalhes</th>
                                            <th data-field="item" data-sortable="true">Item</th>
                                            <th data-field="autor" data-sortable="true">Autor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- LOGS DE PRODUTOS -->
                                        <?php
                                            $i = 0; //CONTADOR
                                            if (!empty($log_prods)) {
                                                foreach ($log_prods as $log_prod) {

                                                    //TRADUZIR REGISTRO EM TEXTO
                                                    switch ($log_prod['acao']) {
                                                        case 0:
                                                            $acao = "INSERIR";
                                                            break;
                                                        case 1:
                                                            $acao = "ATUALIZAR";
                                                            break;
                                                        case 3:
                                                            $acao = "DELETAR";
                                                            break;
                                                    }

                                                    //FORMATAR DATA
                                                    $hora = date("d/m/Y H:i:s", strtotime($log_prod['hora']));

                                                    echo '
                                                    <tr>
                                                        <td>' . $acao . '</td>
                                                        <td>PRODUTO</td>
                                                        <td>' . $hora . '</td>
                                                        <td><input type="button" name="detalhes" value="Ver Detalhes" class="btn btn-info btn-log-detalhes" data-table="1" id=" ' . $log_prod["idlog_prod"] . ' "></td>
                                                        <td>' . $log_prod['nome'] . '</td>
                                                        <td>' . $log_prod['login'] . '</td>
                                                    </tr>';
                                                }
                                            } else {
                                                $i++;
                                            }

                                            //LOGS DE CATEGORIAS
                                            if (!empty($log_cats)) {
                                                foreach ($log_cats as $log_cat) {

                                                    //TRADUZIR REGISTRO EM TEXTO
                                                    switch ($log_cat['acao']) {
                                                        case 0:
                                                            $acao = "INSERIR";
                                                            break;
                                                        case 1:
                                                            $acao = "ATUALIZAR";
                                                            break;
                                                        case 3:
                                                            $acao = "DELETAR";
                                                            break;
                                                    }

                                                    //FORMATAR DATA
                                                    $hora = date("d/m/Y H:i:s", strtotime($log_cat['hora']));

                                                    echo '
                                                    <tr>
                                                        <td>' . $acao . '</td>
                                                        <td>CATEGORIA</td>
                                                        <td>' . $hora . '</td>
                                                        <td><input type="button" name="detalhes" value="Ver Detalhes" class="btn btn-info btn-log-detalhes" data-table="2" id=" ' . $log_cat["idlog_cat"] . ' "></td>
                                                        <td>' . $log_cat['nome_cat'] . '</td>
                                                        <td>' . $log_cat['login'] . '</td>
                                                    </tr>';
                                                }
                                            } else {
                                                $i++;
                                            }

                                            //SE TODAS TABELAS ESTAVAM VAZIAS
                                            if ($i==2) {
                                                echo '<tr>
                                                        A tabela de Registros está vazia.
                                                    </tr>
                                                ';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /tabela de produtos -->  

            </div>
        </div>

        <!-- FOOTER-->
        <footer>
            <div class="container">
                <div class="row">
                </div>
            </div>
        </footer>
        <!-- FOOTER SECTION END-->

        <!-- ============ || FIM DO TEMPLATE || ============ -->


        <!-- MODAL PARA DETALHOS DO LOG -->
        <div class="modal fade" id="modal_detalhes" role="dialog">
                <!-- Modal content-->
                <div class="modal-detalhes">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detalhes do Log</h4>
                </div>

                <div class="modal-body">
                    <!-- <div class="tag btn-info">Colocar dinamicamente valor de ação</div> -->
                    <div class="detalhes-container">
                        <div class="modal-left">
                            <h4>CÓDIGO ANTERIOR</h4>
                            <p id="cod_anterior" name="cod_anterior"></p>
                        </div>
                        <div class="modal-right">
                            <h4>CÓDIGO ATUAL</h4>
                            <p id="cod_atual" name="cod_atual"></p>
                        </div>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
        </div>
        <!-- FIM MODAL DETALHES LOG -->
        
        <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- BOOTSTRAP TABLE SCRIPTS -->
        <script src="assets/js/bootstrap-table.js"></script>
        <!-- TINY MCE TEXTAREA -->
        <script src="https://cdn.tiny.cloud/1/n1wl5xkudrxddlw5ntia4tozijdt6lq8sgej3abzadkmetw0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({ selector:'.fulltextarea' });</script>

        <script>
            //CONFIGURA A MODAL
            //$('#modal_detalhes').modal({backdrop: 'static', show:false});

            //ABRE ABA EDITAR CATEGORIA
            $(document).on('click', '.btn-log-detalhes', function(){
                var idlog = $(this).attr("id");
                var table = $(this).attr("data-table");
                $.ajax({
                    url:"action/fetch_log.php",
                    method:"POST",
                    data:{idlog:idlog, table:table},
                    dataType:"json",
                    success:function(data){
                        //MONTA OBJETO JSON E FORMATA
                        var codAtual = JSON.parse(data.cod_atual);
                        codAtual = JSON.stringify(codAtual, null, 4);
                        //MONTA OBJETO JSON E FORMATA
                        var codAnterior = JSON.parse(data.cod_anterior);
                        codAnterior = JSON.stringify(codAnterior, null, 4);
                        //INJETA OS DADOS NO HTML
                        $("#cod_anterior").html('<pre>' + codAnterior + '</pre>');
                        $("#cod_atual").html('<pre>' + codAtual + '</pre>');
                        $('#modal_detalhes').modal({
                            backdrop: 'static',
                            show: true
                        });
                    }
                });
            });
        </script>
    </body>
</html>
