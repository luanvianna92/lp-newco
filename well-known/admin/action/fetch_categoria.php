 <?php  
 require_once '../../database.php';

 if (isset($_POST["idcategoria"])) {

 	$sql ="SELECT * FROM categoria WHERE idcategoria = :idcategoria";

 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idcategoria', $_POST["idcategoria"]);
 	
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