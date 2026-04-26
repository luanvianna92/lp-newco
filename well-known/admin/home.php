<?php session_start();
header ('Content-type: text/html; charset=UTF-8');

require_once '../database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
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
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
 
    <!-- LOGO HEADER-->
    <div id="navbar"></div>
    <!-- /END LOGO HEADER -->


    <!-- BOTÕES PARA DIRECIONAR -->
    <div class="content-wrapper">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">ESCOLHA A SEÇÃO</h4>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="categorias.php" >
                    <div class="dashboard-div-wrapper bk-grey botao_link">
                        <h4>Categorias</h4>
                    </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="produtos.php" >
                    <div class="dashboard-div-wrapper bk-blue botao_link">
                        <h4>Produtos</h4>
                    </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="textos.php">
                    <div class="dashboard-div-wrapper bk-grey botao_link">
                        <h4>Institucional</h4>
                    </div>
                    </a>
                </div>

                <!-- VISUALIZAR LOGS APENAS PARA ADMS COM PERMISSÃO -->
                <?php 
                    if ($_SESSION['permissao'] === 0) {
                        echo 
                        '<div class="col-md-3 col-sm-3 col-xs-6">
                            <a href="logs.php">
                            <div class="dashboard-div-wrapper bk-blue botao_link">
                                <h4>Registros</h4>
                            </div>
                            </a>
                        </div>';
                    }
                ?>

                <!-- VISUALIZAR CONTAS APENAS PARA ADMS COM PERMISSÃO -->
                <?php
                    if ($_SESSION['permissao'] === 0) {
                        echo '
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <a href="contas.php">
                            <div class="dashboard-div-wrapper bk-grey botao_link">
                                <h4>Admnistradores</h4>
                            </div>
                            </a>
                        </div>';
                    }
                ?>

            </div>
        </div>
    </div>
    <!-- /END BOTÕES -->


    <!-- ============ || FIM DO TEMPLATE || ============ -->


<!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

<!-- CORE JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.11.1.js"></script>
<script> $(function(){$("#navbar").load("navbar.html");});</script>
<!-- BOOTSTRAP SCRIPTS  -->
<script src="assets/js/bootstrap.js"></script>

</body>
</html>
