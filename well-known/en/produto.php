<?php
	header ('Content-type: text/html; charset=UTF-8');
	require_once '../database.php';
	require_once __DIR__ . '/../partials/segmentos.php';

	if (isset($_GET['id'])) {
		$id_categoria = $_GET['id'];
	} else {
		echo 'Error: missing ID parameter. <a href="index.php">Back to home.</a>';
		die();
	}

	$sql = "SELECT * FROM categoria WHERE idcategoria = :idcategoria";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':idcategoria', $id_categoria);
	$stmt->execute();
	$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt_p = $conn->prepare("SELECT * FROM produto WHERE status = 0 AND categoria_idcategoria = :categoria");
	$stmt_p->bindParam(':categoria', $id_categoria);
	$stmt_p->execute();
	$produtos = $stmt_p->fetchAll(PDO::FETCH_ASSOC);
	$produto_ids = array_map(fn($p) => (int) $p['idproduto'], $produtos);
	$segmentos_map = segmentos_por_produto($conn, $produto_ids);
	$segmentos = segmentos_ativos($conn);
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
	<link rel="stylesheet" href="../assets/css/secoes.css">
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

		<!-- Frente C1: filtros por segmento -->
		<?php if (count($segmentos) > 0): ?>
		<div class="container 90%">
			<div class="rd-filtros" id="rd-filtros-segmento">
				<span class="rd-filtros__label">Filter by application:</span>
				<button type="button" class="rd-filtro is-active" data-segmento="">All</button>
				<?php foreach ($segmentos as $seg): ?>
					<button type="button" class="rd-filtro" data-segmento="<?= htmlspecialchars($seg['slug'], ENT_QUOTES, 'UTF-8') ?>">
						<?= htmlspecialchars($seg['nome_en'], ENT_QUOTES, 'UTF-8') ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="container 90% gallery caption-style-1">
			<div class="row 0% images">

				<!-- START PRODUTOS -->
				<?php foreach ($produtos as $produto):
					$pid = (int) $produto['idproduto'];
					$segs = $segmentos_map[$pid] ?? [];
					$slugs_str = implode(' ', array_map(fn($s) => $s['slug'], $segs));
				?>

					<li class="4u 6u(mobile) flex produto" href="<?= $produto["short"]; ?>" data-segmentos="<?= htmlspecialchars($slugs_str, ENT_QUOTES, 'UTF-8') ?>">
						<a class="image image-inner fit from-left"><img src="../admin/product_images/<?= $produto["imagem"]; ?>" title="<?= $produto["nome_en"]; ?>" alt="<?= $produto["nome_en"]; ?>" /></a>
						<div class="caption">
							<div class="blur"></div>
							<div class="caption-text">
								<h1><?= $produto["nome_en"]; ?></h1>
								<?php if (!empty($segs)): ?>
									<div class="rd-tags-segmento">
										<?php foreach ($segs as $s): ?>
											<span class="rd-tag-segmento" style="background: <?= htmlspecialchars($s['cor_hex'] ?? '#1d4d3a', ENT_QUOTES, 'UTF-8') ?>;">
												<?= htmlspecialchars($s['nome_en'], ENT_QUOTES, 'UTF-8') ?>
											</span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</li>
					<!-- MODAL -->
					<div id="<?= $produto["short"]; ?>" class="modal">
						<div class="modal-content">
							<div class="modal-header">
								<span class="close">×</span>
								<h2><?= $produto["nome_en"]; ?></h2>
							</div>
							<div class="modal-body">
								<?php if (!empty($produto["banner"])) {
									echo '<img src="../admin/banners/'.$produto["banner"].'" class="img-banner">';
								} ?>
								<?php if (!empty($segs)): ?>
									<div class="rd-tags-segmento" style="margin-bottom: 1rem;">
										<?php foreach ($segs as $s): ?>
											<span class="rd-tag-segmento" style="background: <?= htmlspecialchars($s['cor_hex'] ?? '#1d4d3a', ENT_QUOTES, 'UTF-8') ?>;">
												<?= htmlspecialchars($s['nome_en'], ENT_QUOTES, 'UTF-8') ?>
											</span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
								<p><?= $produto["descricao_en"]; ?></p>
								<p><strong>Functionality:</strong></p>
								<p><?= $produto["funcionalidade_en"]; ?></p>

								<?php if (!empty($produto['ficha_pdf'])): ?>
								<div class="rd-ficha-form-wrapper">
									<h4><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download technical sheet</h4>
									<p>Get this product's technical sheet by e-mail. Your information stays confidential.</p>
									<form class="rd-ficha-form" action="../baixar_ficha.php" method="POST">
										<input type="hidden" name="produto_short" value="<?= htmlspecialchars($produto['short'], ENT_QUOTES, 'UTF-8') ?>">
										<input type="hidden" name="idioma" value="en">
										<input type="text"  name="nome"    placeholder="Your name (optional)">
										<input type="text"  name="empresa" placeholder="Company (optional)">
										<input type="email" name="email"   placeholder="Your e-mail (required)" required>
										<button type="submit"><i class="fa fa-download" aria-hidden="true"></i> Download technical sheet</button>
									</form>
								</div>
								<?php endif; ?>

								<div class="clear"></div>
							</div>
						</div>
					</div>
					<!-- END MODAL -->
				<?php endforeach; ?>
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

	// Frente C1: segment filter
	(function () {
		var filtros = document.querySelectorAll('#rd-filtros-segmento .rd-filtro');
		var produtos = document.querySelectorAll('.produto[data-segmentos]');
		filtros.forEach(function (btn) {
			btn.addEventListener('click', function () {
				filtros.forEach(function (b) { b.classList.remove('is-active'); });
				btn.classList.add('is-active');
				var alvo = btn.getAttribute('data-segmento');
				produtos.forEach(function (p) {
					var segs = (p.getAttribute('data-segmentos') || '').split(' ');
					if (!alvo || segs.indexOf(alvo) !== -1) {
						p.classList.remove('is-hidden');
					} else {
						p.classList.add('is-hidden');
					}
				});
			});
		});
	})();

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