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
if (isset($_SESSION['retornoProduto'])) {
    echo '<script type="text/javascript">alert("'.$_SESSION['retornoProduto'].'");</script>';
    header('Refresh:0');
}
//LIMPA A SESSÃO DE MENSAGEM
unset($_SESSION['retornoProduto']);

//CARREGAR PRODUTOS E CATEGORIAS DO BANCO DE DADOS
$sql = "SELECT P.*, C.nome_cat FROM produto AS P INNER JOIN categoria AS C WHERE P.categoria_idcategoria = C.idcategoria AND P.status = 0";
$stmt_produtos = $conn->prepare($sql);
$stmt_produtos->execute();

//CARREGA TODAS CATEGORIAS NO ARRAY $CATEGORIAS
$sql = "SELECT * FROM categoria WHERE status = 0";
$stmt_categorias = $conn->prepare($sql);
if ($stmt_categorias->execute()) {
	$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
} else {
	echo $stmt_categorias->error_info();
	die();
}

$sql = "SELECT * FROM categoria";
$stmt_categorias_edit = $conn->prepare($sql);
$stmt_categorias_edit->execute();

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


        <!-- BOTÕES PARA ADICIONAR PRODUTOS -->
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h3><a href="home.php"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></h3>
                        <h4 class="page-head-line center">ADICIONAR PRODUTOS</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            Escolha a categoria a qual o produto pertencerá e preencha os campos com as informações necessárias.
                        </div>
                    </div>
                </div>

                <div class="row">

                <?php
                    if (!empty($categorias)) {
                        $i = 1;
                        foreach ($categorias as $categoria) {
                            //MONTA A CLASSE DA COR DA BOX
                            $color = "bk-clr-" . strval($i);
                            echo '
                            
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <a href="#modal_cadastrar" data-toggle="modal" data-categoria-id="' . $categoria["idcategoria"] . '">
                                    <div class="dashboard-div-wrapper ' . $color . ' botao_link">
                                        <i  class="fa fa-plus dashboard-div-icon" ></i>
                                        <h4>' . $categoria["nome_cat"] . '</h4>
                                    </div>
                                </a>
                            </div>
                            ';
                            //INCREMENTA O NUMERO DA CLASSE DA COR
                            $i++;
                        }
                    }
                ?>

            </div>
        </div>
    </div>
    <!-- /END BOTÕES PARA ADICIONAR PRODUTOS -->


    <!-- ÁREA PARA ADICIONAR PRODUTOS -->
    <div class="content-wrapper">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line center">EDITAR PRODUTOS</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        Selecione um produto existente para realizar alguma alteração em suas informações.
                    </div>
                </div>
            </div>

            <!-- tabela de produtos -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Tabela de Produtos</div>
                        <div class="panel-body">
                            <table id="marca_table" data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                                <thead>
                                    <tr>
                                        <th data-field="nome"  data-sortable="true">Nome do produto</th>
                                        <th data-field="nome_cat"  data-sortable="true">Categoria</th>
                                        <th data-field="editar" data-sortable="true">Editar ou visualizar informações</th>
                                        <th data-field="deletar" data-sortable="true">Deletar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($stmt_produtos->rowCount() > 0) { 
                                        while ($produto = $stmt_produtos->fetch(PDO::FETCH_ASSOC)){
                                            echo '
                                            <tr>
                                                <td>'.$produto["nome"].'</td>
                                                <td>'.$produto["nome_cat"].'</td>
                                                <td><input type="button" name="edit" value="Editar" class="btn btn-info product_edit" id="'.$produto["idproduto"].'"></td>
                                                <td><input type="button" name="'.$produto["nome"].'" value="Deletar" class="btn btn-danger product_delete" id="'.$produto["idproduto"].'"></td>
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
            <!-- /tabela de produtos -->  

        </div>
    </div>
    <!-- /END ÁREA PARA ADICIONAR PRODUTOS -->


    <!-- FOOTER-->
    <footer>
        <div class="container">
            <div class="row">
            </div>
        </div>
    </footer>
    <!-- FOOTER SECTION END-->

    <!-- ============ || FIM DO TEMPLATE || ============ -->


    <!-- MODAL PARA CADASTRAR PRODUTO -->
    <div class="modal fade" id="modal_cadastrar" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Cadastro de produto</h4>
          </div>
          <div class="modal-body">
              <form method="POST" action="action/insert_product.php" enctype="multipart/form-data" id="cadastro">
                <ul class="form-style-1">
                    <li>
                        <label>Nome (português) <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" class="field-long" required />
                    </li>
                    <li>
                        <label>Nome (inglês) <span class="required">*</span></label>
                        <input type="text" id="nome_en" name="nome_en" class="field-long" required />
                    </li>
                    <li>
                        <label>Categoria </label>
                        <select id="categoria" name="categoria" class="field-select">
                            <?php
                            if (!empty($categorias)) {
                                foreach ($categorias as $categoria) {
                                    echo '
                                    <option value="'.$categoria["idcategoria"].'">'.$categoria["nome_cat"].'</option>
                                    ';
                                }
                            } else {
                                echo '
                                <option value="">Nenhuma categoria encontrada</option>
                                ';
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <label>Abreviação <span class="required">*</span></label>
                        <p>Caso o nome original seja mais do que uma palavra, use aqui apenas a primeira. Senão, apenas repita o nome original.</p>
                        <input type="text" id="short" name="short" class="field-long" required />
                    </li>
                    <li>
                        <label>Descrição (português) </label>
                        <textarea id="descricao" name="descricao" class="field-long field-textarea fulltextarea"></textarea>
                    </li>
                    <li>
                        <label>Descrição (inglês) </label>
                        <textarea id="descricao_en" name="descricao_en" class="field-long field-textarea fulltextarea"></textarea>
                    </li>
                    <li>
                        <label>Funcionalidade (português) </label>
                        <textarea id="funcionalidade" name="funcionalidade" class="field-long field-textarea fulltextarea"></textarea>
                    </li>
                    <li>
                        <label>Funcionalidade (inglês) </label>
                        <textarea id="funcionalidade_en" name="funcionalidade_en" class="field-long field-textarea fulltextarea"></textarea>
                    </li>
                    <li>
                        <label>Imagem do produto </label>
                        <p>Tamanho recomendado de <b>440x276px</b>.</p>
                        <input type="file" id="imagem" name="imagem" accept="image/*" />
                    </li>
                    <br>
                    <li>
                        <label>Banner de informações </label>
                        <input type="file" id="banner" name="banner" accept="image/*" />
                    </li>
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
<!-- /MODAL PARA CADASTRAR PRODUTO -->


<!-- MODAL PARA EDITAR PRODUTO -->
<div class="modal fade" id="modal_editar" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edição de produto</h4>
      </div>
      <div class="modal-body">
          <form method="POST" action="action/edit_product.php" enctype="multipart/form-data" id="editar">
            <ul class="form-style-1">
                <li>
                    <input type="hidden" name="idproduto" id="idproduto" />
                </li>
                <li>
                    <label>Nome (português) <span class="required">*</span></label>
                    <input type="text" id="nome_edit" name="nome_edit" class="field-long" required />
                </li>
                <li>
                    <label>Nome (inglês) <span class="required">*</span></label>
                    <input type="text" id="nome_en_edit" name="nome_en_edit" class="field-long" required />
                </li>
                <li>
                    <label>Categoria </label>
                    <select id="categoria_edit" name="categoria_edit" class="field-select">
                        <?php
                        if ($stmt_categorias_edit->rowCount() > 0) { 
                            while ($categorias = $stmt_categorias_edit->fetch(PDO::FETCH_ASSOC)){
                                echo '
                                <option value="'.$categorias["idcategoria"].'">'.$categorias["nome_cat"].'</option>
                                ';
                            }
                        } else {
                            echo '
                            <option value="">Nenhuma categoria encontrada</option>
                            ';
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>Abreviação <span class="required">*</span></label>
                    <p>Caso o nome original seja mais do que uma palavra, use aqui apenas a primeira. Senão, apenas repita o nome original.</p>
                    <input type="text" id="short_edit" name="short_edit" class="field-long" required />
                </li>
                <li>
                    <label>Descrição (português) </label>
                    <textarea id="descricao_edit" name="descricao_edit" class="field-long field-textarea fulltextarea"></textarea>
                </li>
                <li>
                    <label>Descrição (inglês) </label>
                    <textarea id="descricao_en_edit" name="descricao_en_edit" class="field-long field-textarea fulltextarea"></textarea>
                </li>
                <li>
                    <label>Funcionalidade (português) </label>
                    <textarea id="funcionalidade_edit" name="funcionalidade_edit" class="field-long field-textarea fulltextarea"></textarea>
                </li>
                <li>
                    <label>Funcionalidade (inglês) </label>
                    <textarea id="funcionalidade_en_edit" name="funcionalidade_en_edit" class="field-long field-textarea fulltextarea"></textarea>
                </li>
                <br>
                <p>Se o produto já possuir imagem ou banner, escolha uma nova apenas caso dejese substituí-las.</p>
                <li>
                    <label>Imagem do produto </label>
                    <input type="file" id="imagem_edit" name="imagem_edit" accept="image/*" />
                </li>
                <li>
                    <label>Banner de informações </label>
                    <input type="file" id="banner_edit" name="banner_edit" accept="image/*" />
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
</div>
<!-- /MODAL PARA EDITAR PRODUTO -->


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
    $('#modal_cadastrar').modal({backdrop: 'static', show:false});

    //ABRE MODAL CADASTRAR PRODUTO
    $('#modal_cadastrar').on('show.bs.modal', function(e) {
        var categoriaId = $(e.relatedTarget).data('categoria-id');  //PEGA O VALOR DO ID DA CATEGORIA REPRESENTADA PELO BOTÃO
        $('#categoria').val(categoriaId);                           //SETA A SELECT BOX COM O VALOR DA ID
    });

    //ABRE ABA EDITAR PRODUTO
    $(document).on('click', '.product_edit', function(){
        var idProduto = $(this).attr("id");
        $.ajax({
            url:"action/fetch_product.php",
            method:"POST",
            data:{idProduto:idProduto},
            dataType:"json",
            success:function(data){
                $("#nome_edit").val(data.nome);
                $("#nome_en_edit").val(data.nome_en);
                $("#short_edit").val(data.short);
                $("#categoria_edit").val(data.categoria_idcategoria);
                tinymce.get('descricao_edit').setContent(data.descricao);
                tinymce.get('descricao_en_edit').setContent(data.descricao_en);
                tinymce.get('funcionalidade_edit').setContent(data.funcionalidade);
                tinymce.get('funcionalidade_en_edit').setContent(data.funcionalidade_en);
                $('#idproduto').val(data.idproduto);
                $('#modal_editar').modal({
                    backdrop: 'static',
                    show: true
                }); 
            }
        });
    });

    //BOTÃO DE DELETAR PRODUTO
    $(document).on('click', '.product_delete', function(){
        var idproduto=$(this).attr('id');
        var text = $(this).attr('name');
        if (confirm('Deseja deletar o item ' + text + '?')) {
            $.ajax({
                url:"action/delete_product.php",
                method:"POST",
                data:{idproduto:idproduto},
                dataType:"text",
                success:function(data){
                //se o sql foi executado com sucesso
                if($.trim(data) == '1'){
                    alert("Produto deletado!");
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
