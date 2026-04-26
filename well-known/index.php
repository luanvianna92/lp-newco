<?php
header ('Content-type: text/html; charset=UTF-8');
//CONECTA COM O BANCO DE DADOS
require_once 'database.php';
require_once __DIR__ . '/partials/lang.php';
$lang_current = lang_current();

//CARREGAR TEXTOS DO BANCO DE DADOS
$sql = "SELECT * FROM texto";
$stmt_texto = $conn->prepare($sql);
if ($stmt_texto->execute()) {
	$texto = $stmt_texto->fetchAll(PDO::FETCH_ASSOC);
} else {
	echo $stmt_texto->error_info();
	die();
}

//CARREGAR INFORMAÇÕES DE CONTATO DO BANCO DE DADOS
$sql = "SELECT * FROM contato";
$stmt_contato = $conn->prepare($sql);
if ($stmt_contato->execute()) {
	$contato = $stmt_contato->fetch(PDO::FETCH_ASSOC);
} else {
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

<html lang="pt-BR">
<head>
	<title>Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<!-- Redesign — frente B1 -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
	<link rel="stylesheet" href="assets/css/redesign.css">
	<link rel="alternate" hreflang="pt-BR" href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>">
	<link rel="alternate" hreflang="en"    href="<?= htmlspecialchars(lang_alt_url('en'), ENT_QUOTES) ?>">
	<link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>">
</head>
<body>

	<!-- Header (redesign — frente B1) -->
	<?php require __DIR__ . '/partials/header.php'; ?>

	<!-- Nav legado (anchors da home) — será reposicionado no redesign do hero -->
	<nav id="nav">
		<ul>
			<li><a href="#intro">Início</a></li>
			<li><a href="#quem">Quem somos</a></li>
			<li><a href="#oque">O que fazemos</a></li>
			<li><a href="#produtos">Produtos</a></li>
			<li><a href="#contact">Contato</a></li>
		</ul>
	</nav>


	<!-- ARRAY PHP POSIÇÃO 0 = INÍCIO -->
	<!-- Intro -->
	<section id="intro" class="main style1 dark fullscreen">
		<div class="content container 75%">
			<header>
				<h2><img src="images/logo.png"></h2>
			</header>
			<?php echo $texto[0]["texto"]; ?>
			<footer>
				<a href="#quem" class="button style2 down">Continuar</a>
			</footer>
		</div>
	</section>



	<!-- ARRAY PHP POSIÇÃO 1 = QUEM SOMOS -->
	<!-- quem -->
	<section id="quem" class="main style2 left dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?php echo $texto[1]["titulo"]; ?></h2>
			</header>
			<?php echo $texto[1]["texto"];
			if (!empty(trim($texto[1]["texto_modal"]))) {			//VERIFICA SE EXISTE TEXTO EXTRA PARA EXIBIR OU NÃO O MODAL
				echo '<span class="abrirModal" name="modal1"> Saiba mais.</span>';
			}
			 ?>
		</div>
		<a href="#oque" class="button style2 down anchored">Próximo</a>
	</section>

	<!-- MODAL -->
	<div id="modal1" class="modal">

		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">×</span>
				<h2><?php echo $texto[1]["titulo"]; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $texto[1]["texto_modal"]; ?>
			</div>
		</div>

	</div>
	<!-- END MODAL -->
	<!-- END QUEM SOMOS -->


	<!-- ARRAY PHP POSIÇÃO 2 = O QUE FAZEMOS -->
	<!-- oque -->
	<section id="oque" class="main style2 right dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?php echo $texto[2]["titulo"]; ?></h2>
			</header>
			<?php echo $texto[2]["texto"];
			if (!empty(trim($texto[2]["texto_modal"]))) {			//VERIFICA SE EXISTE TEXTO EXTRA PARA EXIBIR OU NÃO O MODAL
				echo '<span class="abrirModal" name="modal2"> Saiba mais.</span>';
			}
			 ?>
		</div>
		<a href="#produtos" class="button style2 down anchored">Próximo</a>
	</section>

	<!-- MODAL -->
	<div id="modal2" class="modal">

		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<span class="close">×</span>
				<h2><?php echo $texto[2]["titulo"]; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $texto[2]["texto_modal"]; ?>
			</div>
		</div>

	</div>
	<!-- END MODAL -->
	<!-- END O QUE FAZEMOS -->


	<!-- ARRAY PHP POSIÇÃO 3 = PRODUTOS -->
	<!-- produtos -->
	<section id="produtos" class="main style3 primary">
		<div class="content container">
			<header>
				<h2><?php echo $texto[3]["titulo"]; ?></h2>
			</header>
			<?php echo $texto[3]["texto"];
			if (!empty(trim($texto[3]["texto_modal"]))) {			//VERIFICA SE EXISTE TEXTO EXTRA PARA EXIBIR OU NÃO O MODAL
				echo '<span class="abrirModal" name="modal3"> Saiba mais.</span>';
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
									<a class="image fit from-left"><img src="images/categorias/' . $categoria["capa"] . '" alt="' . $categoria["nome_cat"] . '" /></a>
									<div class="caption">
										<div class="blur">
										</div>
										<div class="caption-text">
											<h1>' . $categoria["nome_cat"] . '</h1>
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
				<h2><?php echo $texto[3]["titulo"]; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $texto[3]["texto_modal"]; ?>
			</div>
		</div>
	</div>
	<!-- END MODAL -->
	<!-- END PRODUTOS -->


	<!-- Contact -->
	<section id="contact" class="main style3 secondary">
		<div class="content container">
			<header>
				<h2>Entre em contato</h2>
			</header>
			<div class="box container 75%">

				<!-- Contact Form -->
				<form method="POST" name="contactform" action="EnviaEmail.php"> 
					<div class="row 100%">
						<div class="6u 12u(mobile)"><input type="text" name="name" placeholder="Nome (obrigatório)" required /></div>
						<div class="6u 12u(mobile)"><input type="email" name="email" placeholder="Email (obrigatório)" required /></div>
					</div>
					<div class="row 100%">
						<div class="12u"><textarea name="message" placeholder="Mensagem" rows="6"></textarea></div>
					</div>
					<div class="row">
						<div class="12u">
							<ul class="actions">
								<li><input type="submit" value="Enviar mensagem" /></li>
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
					<h2>Grupo</h2>
					<a href="http://www.coccamp.com.br" target="_blank"><img src="images/coccamp.png"></a>
				</div>

			</div>

		</div>
	</section>

	<!-- Footer -->
	<footer id="footer">

		<!-- Icons -->
		<!-- Icons -->
		<ul class="actions">
			<li><a href="en/index.php" class="flag"><img src="images/english_flag.png" title="Site in english"></a></li>
			<li><a href="#" class="flag"><img src="images/brazil_flag.png" title="Site em português"></a></li>
		</ul>

		<!-- Menu -->
		<ul class="menu">
			<li>&copy; 2016 Newco Brazil. Todos direitos reservados.</li>
		</ul>

	</footer>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.poptrox.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/jquery.scrollex.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>
	<script src="assets/js/redesign.js"></script>

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
