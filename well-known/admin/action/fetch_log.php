<?php  
 require_once '../../database.php';

 if (isset($_POST["idlog"])) {

    //TESTA QUAL TABELA DE LOG DEVE SER BUSCADA
    switch ($_POST["table"]) {
        case 1:
            $sql ="SELECT acao, cod_anterior, cod_atual FROM log_produto WHERE idlog_prod = :idlog";
            break;
        case 2:
            $sql ="SELECT acao, cod_anterior, cod_atual FROM log_categoria WHERE idlog_cat = :idlog";
            break;
    }

 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idlog', $_POST["idlog"]);
 	
 	if ($stmt->execute()) {
 		$row = $stmt->fetch(PDO::FETCH_ASSOC);
 		echo json_encode($row);
 	}
 	else {
 		echo $stmt->error_info();
 	}

//CASO A VARIAVEL POST NÃO VENHA PREENCHIDA (PREVENIR ACESSO EXTERNO)
 } else {
 	header('location: ../../index.php');
 }
 ?> 