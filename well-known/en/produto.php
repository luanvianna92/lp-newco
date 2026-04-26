<?php 
	header ('Content-type: text/html; charset=UTF-8');
	require_once '../database.php';

	if (isset($_GET['id'])) {
		$id_categoria = $_GET['id'];
	} else {
		echo 'Erro: O parâmetro de ID não foi recebido. <a href="index.php">Voltar para o início.</a>';
		die();
	}

	$sql = "SELECT * FROM categoria WHERE idcategoria = :idcategoria";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':idcategoria', $id_categoria);
	if ($stmt->execute()) {
		$categoria = $stmt->fetch(PDO::FETCH_ASSOC);
	} else {
		echo $stmt->error_info();
		die();
	}
?>

<!DOCTYPE HTML>

<html>
<head>
	<title><?php echo $categoria['nome_cat_en'] ?> | Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<style type="text/css">
		body:before {
			display: none;
		}
	</style>
</head>
<body>

	<!-- Header -->
	<header id="header">

		<!-- Logo -->
		<h1 id="logo"><img src="../images/logo.png" class="img-responsive"></h1>

		<!-- Nav -->
		<nav id="nav">
			<ul>
				<li><a href="index.php">Home</a></li>
			</ul>
		</nav>

	</header>

	<div class="container-fluid" id="content">
		<div class="container 90% panel">
			<h3><a href="index.php#produtos"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> / <?php echo $categoria['nome_cat_en'] ?></h3>
		</div>

		<div class="container 90% gallery caption-style-1">
			<div class="row 0% images">

				<!-- START PRODUTOS -->
				<?php
				$sql = "SELECT * FROM produto WHERE status = 0 AND categoria_idCategoria = :categoria";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':categoria', $id_categoria);
				$stmt->execute();

				if ($stmt->rowCount() > 0) {
					while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)){
						?>

						<li class="4u 6u(mobile) flex produto" href="<?= $produto["short"]; ?>">
							<a class="image image-inner fit from-left"><img src="../admin/product_images/<?= $produto["imagem"]; ?>" title="<?= $produto["nome_en"]; ?>" alt="<?= $produto["nome_en"]; ?>" /></a>
							<div class="caption">
								<div class="blur">
								</div>
								<div class="caption-text">
									<h1><?= $produto["nome_en"]; ?></h1>
								</div>
							</div>
						</li>
						<!-- MODAL -->
						<div id="<?= $produto["short"]; ?>" class="modal">
							<div class="modal-content">
								<div class="modal-header">
									<span class="close">×</span>
									<h2><?= $produto["nome"]; ?></h2>
								</div>
								<div class="modal-body">
									<?php if (!empty($produto["banner"])) {
										echo '<img src="../admin/banners/'.$produto["banner"].'" class="img-banner">';
									}
									?>
									<p><?= $produto["descricao_en"]; ?></p>
									<p><strong>Funcionalidade:</strong></p>
									<p><?= $produto["funcionalidade_en"]; ?></p>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						<?php 
					}
				}
				?>
				<!-- END PRODUTO -->

			</div>
		</div>
	</div>


	<!-- Footer -->
	<footer id="footer">

		<!-- Icons -->
		<!-- Icons -->
		<ul class="actions">
			<li><a href="#" class="flag"><img src="../images/english_flag.png" title="Site in english"></a></li>
			<li><a href="../index.php" class="flag"><img src="../images/brazil_flag.png" title="Site em português"></a></li>
		</ul>

		<!-- Menu -->
		<ul class="menu">
			<li>&copy; 2016 Newco Brazil. Todos direitos reservados.</li>
		</ul>

	</footer>

	<!-- Scripts -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/jquery.poptrox.min.js"></script>
	<script src="../assets/js/jquery.scrolly.min.js"></script>
	<script src="../assets/js/jquery.scrollex.min.js"></script>
	<script src="../assets/js/skel.min.js"></script>
	<script src="../assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="../assets/js/main.js"></script>

	<script type="text/javascript">

	// When the user clicks the button, open the modal
	$(".produto").click(function(event) {
		var id = $(this).attr('href');
		var modal = document.getElementById(id);
		modal.style.display = "block";

	// When the user clicks on <span> (x), close the modal
	$(".close").click(function(event) {
		modal.style.display = "none";
	});

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
});

	function autoHeight() {
		$('#content').css('min-height', 0);
		$('#content').css('min-height', (
			$(document).height() 
			- $('#header').height() 
			- $('#footer').height()
			));
	}

 	// onDocumentReady function bind
 	$(document).ready(function() {
 		autoHeight();
 	});

 	// onResize bind of the function
 	$(window).resize(function() {
 		autoHeight();
 	});

 </script>

</body>
</html>