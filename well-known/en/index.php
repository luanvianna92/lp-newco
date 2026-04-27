<?php
header ('Content-type: text/html; charset=UTF-8');
//CONECTA COM O BANCO DE DADOS
require_once '../database.php';
require_once __DIR__ . '/../partials/secao_blocos.php';

//CARREGAR SEÇÕES INSTITUCIONAIS DO BANCO DE DADOS (slug => row)
$sql = "SELECT * FROM secao WHERE ativo = 1 ORDER BY ordem ASC";
$stmt_secao = $conn->prepare($sql);
if (!$stmt_secao->execute()) {
	die('Error loading sections.');
}
$secao = [];
foreach ($stmt_secao->fetchAll(\PDO::FETCH_ASSOC) as $row) {
	$secao[$row['slug']] = $row;
}

$blocos_tecnologia      = secao_blocos_por_slug($conn, 'tecnologia');
$blocos_localizacao     = secao_blocos_por_slug($conn, 'localizacao');
$blocos_sustentabilidade = secao_blocos_por_slug($conn, 'sustentabilidade');

//CARREGAR INFORMAÇÕES DE CONTATO DO BANCO DE DADOS
$sql = "SELECT * FROM contato";
$stmt_contato = $conn->prepare($sql);
if ($stmt_contato->execute()) {
	$contato = $stmt_contato->fetch(PDO::FETCH_ASSOC);
}
else {
	echo $stmt_contato->error_info();
	die();
}

//CARREGAR CATEGORIAS DE PRODUTOS
$sql = "SELECT * FROM categoria WHERE status = 0";
$stmt_categoria = $conn->prepare($sql);
if (!$stmt_categoria->execute()) {
	echo $stmt_categoria->error_info();
	die;
}

?>

<!DOCTYPE HTML>

<html>
<head>
	<title>Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="../assets/css/main.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
	<link rel="stylesheet" href="../assets/css/secoes.css">
</head>
<body>

	<!-- Header -->
	<header id="header">

		<!-- Logo -->
		<h1 id="logo"><img src="../images/logo.png" class="img-responsive"></h1>

		<!-- Nav -->
		<nav id="nav">
			<ul>
				<li><a href="#intro">Home</a></li>
				<li><a href="#quem">Who we are</a></li>
				<li><a href="#oque">What we do</a></li>
				<li><a href="#produtos">Our products</a></li>
				<li><a href="#contact">Contact us</a></li>
			</ul>
		</nav>

	</header>


	<!-- SEÇÃO inicio -->
	<!-- Intro -->
	<section id="intro" class="main style1 dark fullscreen">
		<div class="content container 75%">
			<header>
				<h2><img src="../images/logo.png"></h2>
			</header>
			<?php echo $secao['inicio']['conteudo_en'] ?? ''; ?>
			<footer>
				<a href="#quem" class="button style2 down">Continue</a>
			</footer>
		</div>
	</section>



	<!-- SEÇÃO quem-somos -->
	<!-- quem -->
	<section id="quem" class="main style2 left dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?php echo $secao['quem-somos']['titulo_en'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['quem-somos']['conteudo_en'] ?? '';
			if (!empty(trim($secao['quem-somos']['conteudo_modal_en'] ?? ''))) {
				echo '<span class="abrirModal" name="modal1"> Learn more.</span>';
			}
			 ?>
		</div>
		<a href="#oque" class="button style2 down anchored">Next</a>
	</section>

	<!-- MODAL -->
	<div id="modal1" class="modal">

		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">×</span>
				<h2><?php echo $secao['quem-somos']['titulo_en'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['quem-somos']['conteudo_modal_en'] ?? ''; ?>
			</div>
		</div>

	</div>
	<!-- END MODAL -->
	<!-- END QUEM SOMOS -->



	<!-- SEÇÃO oque-fazemos -->
	<!-- oque -->
	<section id="oque" class="main style2 right dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?php echo $secao['oque-fazemos']['titulo_en'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['oque-fazemos']['conteudo_en'] ?? '';
			if (!empty(trim($secao['oque-fazemos']['conteudo_modal_en'] ?? ''))) {
				echo '<span class="abrirModal" name="modal2"> Learn more.</span>';
			}
			 ?>
		</div>
		<a href="#produtos" class="button style2 down anchored">Next</a>
	</section>

	<!-- MODAL -->
	<div id="modal2" class="modal">

		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">×</span>
				<h2><?php echo $secao['oque-fazemos']['titulo_en'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['oque-fazemos']['conteudo_modal_en'] ?? ''; ?>
			</div>
		</div>

	</div>
	<!-- END MODAL -->
	<!-- END O QUE FAZEMOS -->



	<!-- SEÇÃO tecnologia (A1: spray-drying) -->
	<?php if (!empty($secao['tecnologia'])): ?>
	<section id="tecnologia" class="main style2 right dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?= htmlspecialchars($secao['tecnologia']['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h2>
				<?php if (!empty($secao['tecnologia']['subtitulo_en'])): ?>
					<p class="rd-secao-subtitulo"><?= htmlspecialchars($secao['tecnologia']['subtitulo_en'], ENT_QUOTES, 'UTF-8') ?></p>
				<?php endif; ?>
			</header>
			<?= $secao['tecnologia']['conteudo_en'] ?? '' ?>

			<?php if (!empty($blocos_tecnologia)): ?>
				<div class="rd-blocos-grid">
					<?php foreach ($blocos_tecnologia as $b): ?>
						<div class="rd-bloco">
							<?php if (!empty($b['icone'])): ?>
								<span class="rd-bloco__icone"><i class="fa <?= htmlspecialchars($b['icone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i></span>
							<?php endif; ?>
							<h3 class="rd-bloco__titulo"><?= htmlspecialchars($b['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h3>
							<div class="rd-bloco__conteudo"><?= $b['conteudo_en'] ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<a href="#produtos" class="button style2 down anchored">Next</a>
	</section>
	<?php endif; ?>
	<!-- END TECNOLOGIA -->



	<!-- SEÇÃO localizacao (A3: Sul de Minas Dry Port) -->
	<?php if (!empty($secao['localizacao'])): ?>
	<section id="localizacao" class="main style2 left dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?= htmlspecialchars($secao['localizacao']['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h2>
				<?php if (!empty($secao['localizacao']['subtitulo_en'])): ?>
					<p class="rd-secao-subtitulo"><?= htmlspecialchars($secao['localizacao']['subtitulo_en'], ENT_QUOTES, 'UTF-8') ?></p>
				<?php endif; ?>
			</header>
			<?= $secao['localizacao']['conteudo_en'] ?? '' ?>

			<?php if (!empty($blocos_localizacao)): ?>
				<div class="rd-blocos-grid rd-blocos-grid--kpis">
					<?php foreach ($blocos_localizacao as $b): ?>
						<div class="rd-bloco rd-bloco--kpi">
							<?php if (!empty($b['icone'])): ?>
								<span class="rd-bloco__icone"><i class="fa <?= htmlspecialchars($b['icone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i></span>
							<?php endif; ?>
							<?php if (!empty($b['valor_destaque_en'])): ?>
								<div class="rd-bloco__valor"><?= htmlspecialchars($b['valor_destaque_en'], ENT_QUOTES, 'UTF-8') ?></div>
							<?php endif; ?>
							<h3 class="rd-bloco__titulo"><?= htmlspecialchars($b['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h3>
							<div class="rd-bloco__conteudo"><?= $b['conteudo_en'] ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<a href="#produtos" class="button style2 down anchored">Next</a>
	</section>
	<?php endif; ?>
	<!-- END LOCALIZACAO -->



	<!-- SEÇÃO sustentabilidade (A4) -->
	<?php if (!empty($secao['sustentabilidade'])): ?>
	<section id="sustentabilidade" class="main style2 right dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?= htmlspecialchars($secao['sustentabilidade']['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h2>
				<?php if (!empty($secao['sustentabilidade']['subtitulo_en'])): ?>
					<p class="rd-secao-subtitulo"><?= htmlspecialchars($secao['sustentabilidade']['subtitulo_en'], ENT_QUOTES, 'UTF-8') ?></p>
				<?php endif; ?>
			</header>
			<?= $secao['sustentabilidade']['conteudo_en'] ?? '' ?>

			<?php if (!empty($blocos_sustentabilidade)): ?>
				<div class="rd-blocos-grid">
					<?php foreach ($blocos_sustentabilidade as $b): ?>
						<div class="rd-bloco">
							<?php if (!empty($b['icone'])): ?>
								<span class="rd-bloco__icone"><i class="fa <?= htmlspecialchars($b['icone'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></i></span>
							<?php endif; ?>
							<h3 class="rd-bloco__titulo"><?= htmlspecialchars($b['titulo_en'], ENT_QUOTES, 'UTF-8') ?></h3>
							<div class="rd-bloco__conteudo"><?= $b['conteudo_en'] ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<a href="#produtos" class="button style2 down anchored">Next</a>
	</section>
	<?php endif; ?>
	<!-- END SUSTENTABILIDADE -->



	<!-- SEÇÃO produtos -->
	<!-- produtos -->
	<section id="produtos" class="main style3 primary">
		<div class="content container">
			<header>
				<h2><?php echo $secao['produtos']['titulo_en'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['produtos']['conteudo_en'] ?? '';
			if (!empty(trim($secao['produtos']['conteudo_modal_en'] ?? ''))) {
				echo '<span class="abrirModal" name="modal3"> Learn more.</span>';
			}
			?>

			<!-- Lightbox Gallery  -->
			<div class="container 75% gallery caption-style-1">
				<div class="row 0% images">
					<?php
						if ($stmt_categoria->rowCount() > 0) { 
							while ($categoria = $stmt_categoria->fetch(PDO::FETCH_ASSOC)){
								echo '
								<li class="6u 12u(mobile) flex item" href="produto.php?id=' . $categoria["idcategoria"] . '">
									<a class="image fit from-left"><img src="../images/categorias/' . $categoria["capa"] . '" alt="' . $categoria["nome_cat"] . '" /></a>
									<div class="caption">
										<div class="blur">
										</div>
										<div class="caption-text">
											<h1>' . $categoria["nome_cat_en"] . '</h1>
										</div>
									</div>
								</li>';
							}
						}
					?>
				</div>
			</div>

		</div>
	</section>

	<!-- MODAL -->
	<div id="modal3" class="modal">
		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">×</span>
				<h2><?php echo $secao['produtos']['titulo_en'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['produtos']['conteudo_modal_en'] ?? ''; ?>
			</div>
		</div>
	</div>
	<!-- END MODAL -->
	<!-- END PRODUTOS -->

	

	<!-- Contact -->
	<section id="contact" class="main style3 secondary">
		<div class="content container">
			<header>
				<h2>Contact us</h2>
			</header>
			<div class="box container 75%">

				<!-- Contact Form -->
				<form method="POST" name="contactform" action="../EnviaEmail.php"> 
					<div class="row 100%">
						<div class="6u 12u(mobile)"><input type="text" name="name" placeholder="Name (required)" required /></div>
						<div class="6u 12u(mobile)"><input type="email" name="email" placeholder="Email (required)" required /></div>
					</div>
					<div class="row 100%">
						<div class="12u"><textarea name="message" placeholder="Message" rows="6"></textarea></div>
					</div>
					<div class="row">
						<div class="12u">
							<ul class="actions">
								<li><input type="submit" value="Send message" /></li>
							</ul>
						</div>
					</div>
				</form>

				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3708.8086783684016!2d-45.418091885057095!3d-21.632377685674506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94caedb47782e89f%3A0x1d796dbeb9d468fa!2sNewco+Brazil+Ind%C3%BAstria+de+Produtos+Funcionais!5e0!3m2!1spt-BR!2sbr!4v1470004254797" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>

				<div class="row 0%">

				<div class="6u 12u(mobile) info">
					<div class="linha">
						<i class="fa fa-map-marker" aria-hidden="true"></i>
						<?php echo '<p>'.$contato["endereco"].'</p>'; ?>
					</div>

					<div class="linha">
						<i class="fa fa-phone" aria-hidden="true"></i>
						<?php echo '<p>'.$contato["telefone1"].'</p>'; ?>
					</div>

					<?php if (!empty($contato["telefone2"])) {
					echo '<div class="linha">
						<i class="fa fa-phone" aria-hidden="true"></i>
						<p>'.$contato["telefone2"].'</p></div>';
					}
					?>

					<div class="linha">
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<?php echo '<p>'.$contato["email"].'</p>'; ?>
					</div>
				</div>

				<div class="6u 12u(mobile) parceiro">
					<h2>Group</h2>
					<a href="http://www.coccamp.com.br" target="_blank"><img src="../images/coccamp.png"></a>
				</div>

			</div>

		</div>
	</section>

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
		$(".item").click(function(event) {
			window.location.href = $(this).attr('href');

			event.preventDefault();
		});

		// Get the modal
		var modal;
		$(document).on('click', '.abrirModal', function(){
		    var text = $(this).attr('name');				//NOME DO LINK QUE FOI CLICADO
		    modal = document.getElementById(text);			//A ID DA MODAL É IGUAL AO NOME DO SEU LINK
			modal.style.display = "block";
		 });

		//BOTÃO QUE FECHA A MODAL
		$(document).on('click', '.close', function(){
			modal.style.display = "none";
		});


		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	</script>

</body>
</html>