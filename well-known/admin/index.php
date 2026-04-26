<?php session_start();
    header ('Content-type: text/html; charset=UTF-8');

    require_once '../database.php';
    require_once 'assets/passwordLib.php';

    if (isset($_SESSION['admin'])) {
        header("Location: home.php");
    }

    //se todas variaveis foram recebidas
    if (!empty($_POST['login']) && !empty($_POST['senha'])){

        $records = $conn->prepare('SELECT * FROM admin WHERE status = 0 AND login = :login');
        $records->bindParam(':login', $_POST['login']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        $senha = $_POST['senha'];
        $hash = $results['senha'];

        //$hashPassword = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        $message = '';

        if (password_verify($senha, $hash)) {
            $_SESSION['admin'] = $results['login'];
            $_SESSION['id_admin'] = $results['idadmin'];
            $_SESSION['permissao'] = $results['permissao'];
            header("Location: home.php");
        } else {
            $message = 'Dados incorretos. Tente novamente.';
        }
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <title>Login Newco - Painel Administrativo</title>
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
    <script src="assets/js/jquery-1.11.1.js"></script>
    <script> $(function(){$("#navbar").load("navbar.html");});</script>
</head>

<body>
    <!-- LOGO HEADER-->
    <div id="navbar"></div>
    <!-- /END LOGO HEADER -->

        <!-- MENU SECTION END-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="page-head-line">Por favor faça o login para ganhar acesso </h4>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <form method="post" action="index.php">
                            <?php if (!empty($message)): ?>
                              <p class="warning"><?= $message ?></p>
                          <?php endif; ?>
                          <label>Login: </label>
                          <input type="text" name="login" class="form-control" />
                          <label>Senha:  </label>
                          <input type="password" name="senha" class="form-control" />
                          <br>
                          <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-user"></span> &nbsp;Entrar</a>&nbsp;
                          </form>
                      </div>

                  </div>

              </div>
          </div>
          <!-- CONTENT-WRAPPER SECTION END-->
          <!-- FOOTER SECTION END-->
          <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
          <!-- CORE JQUERY SCRIPTS -->
          <script src="assets/js/jquery-1.11.1.js"></script>
          <!-- BOOTSTRAP SCRIPTS  -->
          <script src="assets/js/bootstrap.js"></script>
      </body>
      </html>
