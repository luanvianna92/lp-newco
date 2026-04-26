<?php 
header ('Content-type: text/html; charset=UTF-8');
require_once '../database.php';
?>

<!DOCTYPE HTML>

<html>
<head>
	<title>Sweeteners | Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
	
	<style type="text/css">
		body:before {
			display: none;
		}

		p {
			margin-bottom: 0;
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
			<h3><a href="index.php#produtos"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> / Sweeteners</h3>
			<p>Newco produce sweeteners with the ingredients below, amongst many others: </p>
		</div>

		<div class="container 90% gallery caption-style-1">
			<div class="row 0% images">

				<!-- START ADOÇANTES -->

				<?php
				//categoria:
				//1 = frutas e vegetais
				//2 = adoçantes
				//3 = outros produtos funcionais
				//4 = extração de óleos funcionais

				$sql = "SELECT * FROM produto WHERE categoria_idCategoria = 2";
				$stmt = $conn->prepare($sql);
				$stmt->execute();

				if ($stmt->rowCount() > 0) {
					while ($adocantes = $stmt->fetch(PDO::FETCH_ASSOC)){
						?>

						<li class="4u 6u(mobile) flex item" href="<?= $adocantes["short"]; ?>">
							<a class="image fit from-left"><img src="../admin/product_images/<?= $adocantes["imagem"]; ?>" title="<?= $adocantes["nome_en"]; ?>" alt="" /></a>
							<div class="caption">
								<div class="blur">
								</div>
								<div class="caption-text">
									<h1><?= $adocantes["nome"]; ?></h1>
								</div>
							</div>
						</li>
						<!-- MODAL -->
						<div id="<?= $adocantes["short"]; ?>" class="modal">
							<div class="modal-content">
								<div class="modal-header">
									<span class="close">×</span>
									<h2><?= $adocantes["nome"]; ?></h2>
								</div>
								<div class="modal-body">
									<?php if (!empty($adocantes["banner"])) {
										echo '<img src="../admin/banners/'.$adocantes["banner"].'" class="img-banner">';
									}
									?>
									<p><?= $adocantes["descricao_en"]; ?></p>
									<p><strong>Funcionalidade:</strong></p>
									<p><?= $adocantes["funcionalidade_en"]; ?></p>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						<?php 
					}
				}
				?>
				<!-- END ADOÇANTES -->

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
			<li>&copy; 2016 Newco Brazil. All rights reserved.</li>
		</ul>

	</footer>

	<!-- Scripts -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/jquery.poptrox.min.js"></script>
	<script src="../assets/js/jquery.scrolly.min.js"></script>
	<script src="../assets/js/jquery.scrollex.min.js"></script>
	<script src="../assets/js/skel.min.js"></script>
	<script src="../assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="../assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="../assets/js/main.js"></script>

	<script type="text/javascript">

	// When the user clicks the button, open the modal
	$(".item").click(function(event) {
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