<?php  
 require_once '../../database.php';

 if (isset($_POST["idadmin"])) {

 	$sql ="SELECT idadmin, login FROM admin WHERE idadmin = :idadmin";

 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idadmin', $_POST["idadmin"]);
 	
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