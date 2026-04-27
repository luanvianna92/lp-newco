<?php
header ('Content-type: text/html; charset=UTF-8');
require_once '../database.php';
require_once __DIR__ . '/../partials/lang.php';
$lang_current = lang_current();
?>

<!DOCTYPE HTML>

<html lang="en">
<head>
	<title>Other functional products | Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
	<!-- Redesign — frente B1 -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
	<link rel="stylesheet" href="../assets/css/redesign.css">
	<link rel="alternate" hreflang="pt-BR" href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>">
	<link rel="alternate" hreflang="en"    href="<?= htmlspecialchars(lang_alt_url('en'), ENT_QUOTES) ?>">
	<link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>">

	<style type="text/css">
		body:before {
			display: none;
		}
	</style>
</head>
<body>

	<!-- Header (redesign — frente B1) -->
	<?php require __DIR__ . '/../partials/header.php'; ?>

	<div class="container-fluid" id="content">
		<div class="container 90% panel">
			<h3><a href="index.php#produtos"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> / Other Functional Products</h3>
		</div>

		<div class="container 90% gallery caption-style-1">
			<div class="row 0% images">

				<!-- START FUNCIONAIS -->

				<?php
				//categoria:
				//1 = frutas e vegetais
				//2 = adoçantes
				//3 = outros produtos funcionais
				//4 = extração de óleos funcionais

				$sql = "SELECT * FROM produto WHERE categoria_idCategoria = 3";
				$stmt = $conn->prepare($sql);
				$stmt->execute();

				if ($stmt->rowCount() > 0) {
					while ($funcionais = $stmt->fetch(PDO::FETCH_ASSOC)){
						?>

						<li class="4u 6u(mobile) flex item" href="<?= $funcionais["short"]; ?>">
							<a class="image fit from-left"><img src="../admin/product_images/<?= $funcionais["imagem"]; ?>" title="<?= $funcionais["nome_en"]; ?>" alt="" /></a>
							<div class="caption">
								<div class="blur">
								</div>
								<div class="caption-text">
									<h1><?= $funcionais["nome"]; ?></h1>
								</div>
							</div>
						</li>
						<!-- MODAL -->
						<div id="<?= $funcionais["short"]; ?>" class="modal">
							<div class="modal-content">
								<div class="modal-header">
									<span class="close">×</span>
									<h2><?= $funcionais["nome"]; ?></h2>
								</div>
								<div class="modal-body">
									<?php if (!empty($funcionais["banner"])) {
										echo '<img src="../admin/banners/'.$funcionais["banner"].'" class="img-banner">';
									}
									?>
									<p><?= $funcionais["descricao_en"]; ?></p>
									<p><strong>Funcionalidade:</strong></p>
									<p><?= $funcionais["funcionalidade_en"]; ?></p>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						<?php 
					}
				}
				?>
				<!-- END FUNCIONAIS -->
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
	<script src="../assets/js/redesign.js"></script>

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