 <?php
 session_start();
 require_once '../../database.php';

 if (isset($_POST["idproduto"])) {
      //PREPARA O COMANDO PARA DELETAR O PRODUTO
      $sql = "UPDATE produto SET status = 1 WHERE idproduto = :idproduto";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':idproduto', $_POST["idproduto"]);

      //PREPARA O LOG
      // 0 = INSERÇÃO | 1 = ALTERAÇÃO | 3 = EXCLUSÃO
      $acao = 3;

      //PEGA O QUE CONSTA ATUALMENTE NO BANCO PARA GRAVAR NO LOG
      $sql_produto = "SELECT nome, short, banner, imagem, nome_en, categoria_idcategoria AS categoria, descricao, descricao_en, funcionalidade, funcionalidade_en FROM produto WHERE idproduto = " . $_POST['idproduto'];
      $stmt_produto = $conn->prepare($sql_produto);
      $stmt_produto->execute();
      $cod_anterior = $stmt_produto->fetch(PDO::FETCH_ASSOC);

      //CONVERTE O REGISTRO PARA JSON PARA ARMAZENAR NO BANCO
      $cod_anterior_json = json_encode($cod_anterior);
      //NÃO HÁ CODIGO ATUAL
      $cod_atual = null;

      //ID DO ADMIN LOGADO
      $id_admin = $_SESSION['id_admin'];

      //COMANDO DE INSERÇÃO DO LOG
      $sql_log = "INSERT INTO log_produto (acao, cod_anterior, cod_atual, hora, produto_idproduto, admin_idadmin) VALUES (:acao, :cod_anterior, :cod_atual, :hora, :produto, :idadmin)";

      //HORÁRIO DO REGISTRO
      date_default_timezone_set('America/Sao_Paulo');
      $hora = date("Y-m-d H:i:s");

      //ATRIBUIÇÃO DAS VARIÁVEIS DO LOG
      $stmt_log = $conn->prepare($sql_log);
      $stmt_log->bindParam(':acao', $acao);
      $stmt_log->bindParam(':cod_anterior', $cod_anterior_json);
      $stmt_log->bindParam(':cod_atual', $cod_atual);
      $stmt_log->bindParam(':hora', $hora);
      $stmt_log->bindParam(':produto', $_POST['idproduto']);
      $stmt_log->bindParam(':idadmin', $id_admin);

      $conn->query("SET FOREIGN_KEY_CHECKS=0");
      
      if ($stmt->execute()) {
         //GRAVA O LOG
         if (!$stmt_log->execute()) {
               echo "Problema para registrar o log! Por favor contate o desenvolvedor.";
         } else {
            echo '1';  	//RETORNA 1 PARA CONFIRMAR O SUCESSO
         }
      } 
      else {
         echo $stmt->error_info(); 	//RETORNA O ERRO CASO TENHA FALHADO
      }

      $conn->query("SET FOREIGN_KEY_CHECKS=1");
      
   //CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
 } else {
 	header('location: ../../index.php'); //PÁGINA INICIAL DO SITE
 }
 ?>