 <?php  
 require_once '../../database.php';

 if (isset($_POST["idProduto"])) {

 	$sql ="SELECT * FROM produto WHERE idproduto = :idProduto";

 	$stmt = $conn->prepare($sql);
 	$stmt->bindParam(':idProduto', $_POST["idProduto"]);
 	
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