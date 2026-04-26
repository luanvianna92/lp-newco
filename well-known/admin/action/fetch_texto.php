 <?php  
 require_once '../../database.php';

 if (isset($_POST["idTexto"])) {

 	$sql ="SELECT * FROM texto WHERE idtexto = :idTexto";
 	
 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idTexto', $_POST["idTexto"]);
 	//CONFERE SE EXECUTOU COM SUCESSO
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