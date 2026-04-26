 <?php  
 require_once '../../database.php';

 if (isset($_POST["idContato"])) {

 	$sql ="SELECT * FROM contato WHERE idcontato = :idContato";
 	
 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idContato', $_POST["idContato"]);
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