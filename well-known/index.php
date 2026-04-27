<?php
header ('Content-type: text/html; charset=UTF-8');
//CONECTA COM O BANCO DE DADOS
require_once 'database.php';

//CARREGAR SEÇÕES INSTITUCIONAIS DO BANCO DE DADOS (slug => row)
$sql = "SELECT * FROM secao WHERE ativo = 1 ORDER BY ordem ASC";
$stmt_secao = $conn->prepare($sql);
if (!$stmt_secao->execute()) {
	die('Erro ao carregar seções institucionais.');
}
$secao = [];
foreach ($stmt_secao->fetchAll(PDO::FETCH_ASSOC) as $row) {
	$secao[$row['slug']] = $row;
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

<html>
<head>
	<title>Newco Brazil</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
</head>
<body>

	<!-- Header -->
	<header id="header">

		<!-- Logo -->
		<h1 id="logo"><img src="images/logo.png" class="img-responsive"></h1>

		<!-- Nav -->
		<nav id="nav">
			<ul>
				<li><a href="#intro">Início</a></li>
				<li><a href="#quem">Quem somos</a></li>
				<li><a href="#oque">O que fazemos</a></li>
				<li><a href="#produtos">Produtos</a></li>
				<li><a href="#contact">Contato</a></li>
			</ul>
		</nav>

	</header>


	<!-- SEÇÃO inicio -->
	<!-- Intro -->
	<section id="intro" class="main style1 dark fullscreen">
		<div class="content container 75%">
			<header>
				<h2><img src="images/logo.png"></h2>
			</header>
			<?php echo $secao['inicio']['conteudo'] ?? ''; ?>
			<footer>
				<a href="#quem" class="button style2 down">Continuar</a>
			</footer>
		</div>
	</section>



	<!-- SEÇÃO quem-somos -->
	<!-- quem -->
	<section id="quem" class="main style2 left dark fullscreen">
		<div class="content box style2">
			<header>
				<h2><?php echo $secao['quem-somos']['titulo'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['quem-somos']['conteudo'] ?? '';
			if (!empty(trim($secao['quem-somos']['conteudo_modal'] ?? ''))) {
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
				<h2><?php echo $secao['quem-somos']['titulo'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['quem-somos']['conteudo_modal'] ?? ''; ?>
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
				<h2><?php echo $secao['oque-fazemos']['titulo'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['oque-fazemos']['conteudo'] ?? '';
			if (!empty(trim($secao['oque-fazemos']['conteudo_modal'] ?? ''))) {
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
				<h2><?php echo $secao['oque-fazemos']['titulo'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['oque-fazemos']['conteudo_modal'] ?? ''; ?>
			</div>
		</div>

	</div>
	<!-- END MODAL -->
	<!-- END O QUE FAZEMOS -->


	<!-- SEÇÃO produtos -->
	<!-- produtos -->
	<section id="produtos" class="main style3 primary">
		<div class="content container">
			<header>
				<h2><?php echo $secao['produtos']['titulo'] ?? ''; ?></h2>
			</header>
			<?php echo $secao['produtos']['conteudo'] ?? '';
			if (!empty(trim($secao['produtos']['conteudo_modal'] ?? ''))) {
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
				<h2><?php echo $secao['produtos']['titulo'] ?? ''; ?></h2>
			</div>
			<div class="modal-body">
				<?php echo $secao['produtos']['conteudo_modal'] ?? ''; ?>
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
