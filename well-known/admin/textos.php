<?php 
header ('Content-type: text/html; charset=UTF-8');
//INICIA A SESSÃO
session_start();
//CONECTA COM O BANCO DE DADOS
require_once '../database.php';
//VERIFICA SE O ADMIN ESTÁ LOGADO
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
}
//VERIFICA SE HÁ MENSAGEM PARA EXIBIR
if (isset($_SESSION['retornoEdit'])) {
    echo '<script type="text/javascript">alert("'.$_SESSION['retornoEdit'].'");</script>';
    echo '<script>window.location.reload();</script>';
}
//LIMPA A SESSÃO DE MENSAGEM
unset($_SESSION['retornoEdit']);

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


        <!-- ÁREA PARA ALTERAR TEXTOS -->
        <div class="content-wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h3><a href="home.php"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></h3>
                        <h4 class="page-head-line center">ALTERAR TEXTOS</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            Escolha qual seção do site deseja alterar.
                        </div>
                    </div>
                </div>

                <div class="row">

                 <div class="col-sm-12 col-md-6 mbottom-minus">
                     <a href="#modal_texto" class="texto_edit" id="1">
                        <div class="dashboard-div-wrapper bk-dark-blue botao_link">
                            <h4>Início</h4>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-6 mbottom-minus">
                    <a href="#modal_texto" class="texto_edit" id="2">
                        <div class="dashboard-div-wrapper bk-dark-blue botao_link">
                            <h4>Quem somos</h4>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-6 mbottom-minus">
                    <a href="#modal_texto" class="texto_edit" id="3">
                        <div class="dashboard-div-wrapper bk-dark-blue botao_link">
                            <h4>O que fazemos</h4>
                        </div>
                    </a>
                </div>

                <div class="col-sm-12 col-md-6 mbottom-minus">
                    <a href="#modal_texto" class="texto_edit" id="4">
                        <div class="dashboard-div-wrapper bk-dark-blue botao_link">
                            <h4>Produtos</h4>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- /END ÁREA PARA ALTERAR TEXTOS -->

    <!-- VISUALIZAR ÁREA PARA ALTERAR CONTATO APENAS ADMS COM PERMISSÃO -->
    <?php
        if ($_SESSION['permissao'] === 0) {
            echo '
            <div class="content-wrapper">
                <div class="container">

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-head-line center">EDITAR INFORMAÇÕES DE CONTATO</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mbottom-minus">
                            <a href="#modal_contato" class="contato_edit" >
                                <div class="dashboard-div-wrapper bk-green botao_link">
                                    <h4>Newco</h4>
                                </div>
                            </a>
                        </div>
                    </div>

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
            ';
        }
    ?>

    <!-- ============ || FIM DO TEMPLATE || ============ -->


<!-- MODAL PARA EDITAR TEXTO -->
<div class="modal fade" id="modal_texto" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edição de produto</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="action/edit_texto.php" enctype="multipart/form-data" id="editar_texto">
                    <ul class="form-style-1">
                        <li>
                            <label>Título (português) <span class="required">*</span></label>
                            <input type="text" id="txt_titulo" name="txt_titulo" class="field-long" required />
                        </li>
                        <li>
                            <label>Título (inglês) <span class="required">*</span></label>
                            <input type="text" id="txt_titulo_en" name="txt_titulo_en" class="field-long" required />
                        </li>
                        <br>
                        <li>
                            <label>Texto Principal (português) <span class="required">*</span></label>
                            <textarea id="txt_principal" name="txt_principal" class="field-long field-textarea fulltextarea"></textarea>
                        </li>
                        <li>
                            <label>Texto Principal (inglês) <span class="required">*</span></label>
                            <textarea id="txt_principal_en" name="txt_principal_en" class="field-long field-textarea fulltextarea"></textarea>
                        </li>
                        <li>
                            <label>Saiba Mais (português) </label>
                            <textarea id="txt_modal" name="txt_modal" class="field-long field-textarea fulltextarea"></textarea>
                        </li>
                        <li>
                            <label>Saiba Mais (inglês) </label>
                            <textarea id="txt_modal_en" name="txt_modal_en" class="field-long field-textarea fulltextarea"></textarea>
                        </li>
                        <li>
                            <input type="hidden" name="id_texto" id="id_texto" />
                        </li>
                        <li>
                            <input type="submit" name="edit_texto" value="Atualizar" />
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
<!-- /MODAL PARA EDITAR TEXTO -->


<!-- MODAL PARA EDITAR CONTATO -->
<div class="modal fade" id="modal_contato" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edição de informações de contato</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="action/edit_contato.php" enctype="multipart/form-data" id="editar_contato">
                    <ul class="form-style-1">
                        <li>
                            <label>Endereço <span class="required">*</span></label>
                            <input type="text" id="cont_endereco" name="cont_endereco" class="field-long" required />
                        </li>
                        <li>
                            <label>Telefone 1 <span class="required">*</span></label>
                            <input type="text" id="cont_tel1" name="cont_tel1" class="field-long" required />
                        </li>
                        <li>
                            <label>Telefone 2 </label>
                            <input type="text" id="cont_tel2" name="cont_tel2" class="field-long" />
                        </li>
                        <li>
                            <label>E-mail <span class="required">*</span> </label>
                            <input type="text" id="cont_email" name="cont_email" class="field-long" required />
                        </li>
                        <li>
                            <input type="hidden" name="id_contato" id="id_contato" />
                        </li>
                        <li>
                            <input type="submit" name="edit_contato" value="Atualizar" />
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
<!-- /MODAL PARA EDITAR CONTATO -->



<!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->

<!-- BOOTSTRAP SCRIPTS  -->
<script src="assets/js/bootstrap.js"></script>
<!-- BOOTSTRAP TABLE SCRIPTS -->
<script src="assets/js/bootstrap-table.js"></script>
<!-- TINY MCE TEXTAREA -->
<script src="https://cdn.tiny.cloud/1/n1wl5xkudrxddlw5ntia4tozijdt6lq8sgej3abzadkmetw0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({ selector:'.fulltextarea' });</script>

<script>
    //ABRE ABA EDITAR TEXTO
    $(document).on('click', '.texto_edit', function(){
        var idTexto = $(this).attr("id");
        $.ajax({
            url:"action/fetch_texto.php",
            method:"POST",
            data:{idTexto:idTexto},
            dataType:"json",
            success:function(data){
                $("#txt_titulo").val(data.titulo);
                $("#txt_titulo_en").val(data.titulo_en);
                tinymce.get('txt_principal').setContent(data.texto);
                tinymce.get('txt_principal_en').setContent(data.texto_en);
                tinymce.get('txt_modal').setContent(data.texto_modal);
                tinymce.get('txt_modal_en').setContent(data.texto_modal_en);
                $('#id_texto').val(data.idtexto);
                $('#modal_texto').modal({
                    backdrop: 'static',
                    show: true
                }); 
            }
        });
    });

    //ABRE ABA EDITAR CONTATO
    $(document).on('click', '.contato_edit', function(){
        var idContato = 1;      // VALOR ESTÁTICO POIS POR ENQUANTO SÓ EXISTE UM REGISTRO NESTA TABELA
        $.ajax({
            url:"action/fetch_contato.php",
            method:"POST",
            data:{idContato:idContato},
            dataType:"json",
            success:function(data){
                $("#cont_endereco").val(data.endereco);
                $("#cont_tel1").val(data.telefone1);
                $("#cont_tel2").val(data.telefone2);
                $("#cont_email").val(data.email);
                $('#id_contato').val(data.idcontato);
                $('#modal_contato').modal({
                    backdrop: 'static',
                    show: true
                }); 
            }
        });
    });
</script>
</body>
</html>