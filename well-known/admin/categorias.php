<?php
    header ('Content-type: text/html; charset=UTF-8');
    //INICIA A SESSAO
    session_start();
    //CONECTA COM O BANCO DE DADOS
    require_once '../database.php';
    //VERIFICA SE O ADMIN ESTÁ LOGADO
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php");
    }
    //VERIFICA SE HÁ MENSAGEM PARA EXIBIR
    if (isset($_SESSION['retornoCategoria'])) {
        echo '<script type="text/javascript">alert("'.$_SESSION['retornoCategoria'].'");</script>';
        header('Refresh:0');
    }
    //LIMPA A SESSÃO DE MENSAGEM
    unset($_SESSION['retornoCategoria']);

    //CARREGAR CATEGORIAS
    $sql = "SELECT * FROM categoria WHERE status = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
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


        <!-- ADICIONAR CATEGORIA -->
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h3><a href="home.php"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></h3>
                        <h4 class="page-head-line center">ADICIONAR CATEGORIA</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <a href="#modal_cadastrar" data-toggle="modal" data-categoria-id="3">
                            <div class="dashboard-div-wrapper bk-clr-3 botao_link">
                                <i  class="fa fa-plus dashboard-div-icon" ></i>
                                <h4>Nova Categoria</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /END ADICIONAR CATEGORIA -->


        <!-- ÁREA PARA EDITAR CATEGORIA -->
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="page-head-line center">EDITAR CATEGORIAS</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            Selecione uma categoria existente para realizar alguma alteração em suas informações.
                        </div>
                    </div>
                </div>

                <!-- tabela de categorias -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Tabela de Categorias</div>
                            <div class="panel-body">
                                <table id="categoria_table" data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                                    <thead>
                                        <tr>
                                            <th data-field="nome"  data-sortable="true">Nome da categoria</th>
                                            <th data-field="editar" data-sortable="true">Editar ou visualizar informações</th>
                                            <th data-field="deletar" data-sortable="true">Deletar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($stmt->rowCount() > 0) { 
                                            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '
                                                <tr>
                                                    <td>'.$categoria["nome_cat"].'</td>
                                                    <td><input type="button" name="edit" value="Editar" class="btn btn-info categoria_edit" id="'.$categoria["idcategoria"].'"></td>
                                                    <td><input type="button" name="'.$categoria["nome_cat"].'" value="Deletar" class="btn btn-danger categoria_delete" id="'.$categoria["idcategoria"].'"></td>
                                                </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /tabela de categorias -->  

            </div>
        </div>
        <!-- /END EDITAR CATEGORIA -->


        <!-- FOOTER-->
        <footer>
            <div class="container">
                <div class="row">
                </div>
            </div>
        </footer>
        <!-- FOOTER SECTION END-->

        <!-- ============ || FIM DO TEMPLATE || ============ -->


        <!-- MODAL PARA CADASTRAR CATEGORIA -->
        <div class="modal fade" id="modal_cadastrar" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cadastro de categoria</h4>
                    </div>

                    <div class="modal-body">
                        <form method="POST" action="action/insert_categoria.php" enctype="multipart/form-data" id="cadastro">
                            <ul class="form-style-1">
                                <li>
                                    <label>Nome (português) <span class="required">*</span></label>
                                    <input type="text" id="nome_cat" name="nome_cat" class="field-long" required />
                                </li>
                                <li>
                                    <label>Nome (inglês) <span class="required">*</span></label>
                                    <input type="text" id="nome_cat_en" name="nome_cat_en" class="field-long" required />
                                </li>
                                <li>
                                    <label>Capa da Categoria (aparece na página inicial).</label>
                                    <p>Tamanho recomendado de <b>440x276px</b>.</p>
                                    <input type="file" id="capa" name="capa" accept="image/*" required />
                                </li>
                                <br>
                                <li>
                                    <input type="submit" name="cadastrar" value="Cadastrar" />
                                </li>
                            </ul>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /MODAL PARA CADASTRAR CATEGORIA -->


        <!-- MODAL PARA EDITAR CATEGORIA -->
        <div class="modal fade" id="modal_editar" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edição de categoria</h4>
                </div>

                <div class="modal-body">
                    <form method="POST" action="action/edit_categoria.php" enctype="multipart/form-data" id="editar">
                        <ul class="form-style-1">
                            <li>
                                <input type="hidden" name="idcategoria" id="idcategoria" />
                            </li>
                            <li>
                                <label>Nome (português) <span class="required">*</span></label>
                                <input type="text" id="nome_cat_edit" name="nome_cat_edit" class="field-long" required />
                            </li>
                            <li>
                                <label>Nome (inglês) <span class="required">*</span></label>
                                <input type="text" id="nome_cat_en_edit" name="nome_cat_en_edit" class="field-long" required />
                            </li>
                            <br>
                            <p>Se a categoria já possuir capa, escolha uma nova apenas caso dejese substituí-la.</p>
                            <li>
                                <label>Capa da categoria</label>
                                <input type="file" id="capa_edit" name="capa_edit" accept="image/*" />
                            </li>
                            <li>
                                <input type="submit" name="editar" value="Atualizar" />
                            </li>
                        </ul>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <!-- /MODAL PARA EDITAR CATEGORIA -->


        <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- BOOTSTRAP TABLE SCRIPTS -->
        <script src="assets/js/bootstrap-table.js"></script>
        <!-- TINY MCE TEXTAREA -->
        <script src="https://cdn.tiny.cloud/1/n1wl5xkudrxddlw5ntia4tozijdt6lq8sgej3abzadkmetw0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({ selector:'.fulltextarea' });</script>

        <script>
            //CONFIGURA A MODAL CADASTRAR
            $('#modal_cadastrar').modal({backdrop: 'static', show:false});

            //ABRE ABA EDITAR CATEGORIA
            $(document).on('click', '.categoria_edit', function(){
                var idcategoria = $(this).attr("id");
                $.ajax({
                    url:"action/fetch_categoria.php",
                    method:"POST",
                    data:{idcategoria:idcategoria},
                    dataType:"json",
                    success:function(data){
                        $("#nome_cat_edit").val(data.nome_cat);
                        $("#nome_cat_en_edit").val(data.nome_cat_en);
                        $('#idcategoria').val(data.idcategoria);
                        $('#modal_editar').modal({
                            backdrop: 'static',
                            show: true
                        });
                    }
                });
            });

            //BOTÃO DE DELETAR CATEGORIA
            $(document).on('click', '.categoria_delete', function(){
                var idcategoria=$(this).attr('id');
                var text = $(this).attr('name');
                if (confirm('Deseja deletar o item ' + text + '?')) {
                    $.ajax({
                        url:"action/delete_categoria.php",
                        method:"POST",
                        data:{idcategoria:idcategoria},
                        dataType:"text",
                        success:function(data){
                            //se o sql foi executado com sucesso
                            if($.trim(data) == '1'){
                                alert("Categoria deletada!");
                                location.reload();
                            }
                            //se houve um erro
                            else {
                                alert ('Não foi possível deletar o registro! Erro:\r\n' + data);
                            }
                        }
                    });
                }
            });
        </script>
    </body>
</html>
