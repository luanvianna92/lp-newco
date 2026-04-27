<?php
// Header legado (#header azul-marinho do main.css) com toggle PT/EN
// integrado como últimos itens do nav. Espera $lang_current ('pt'|'en').
$is_en = (($lang_current ?? 'pt') === 'en');
$base  = $is_en ? '../' : '';
$home  = $is_en ? 'index.php' : 'index.php';

$labels = $is_en ? [
    'home'     => 'Home',
    'who'      => 'Who we are',
    'what'     => 'What we do',
    'tech'     => 'Technology',
    'loc'      => 'Location',
    'sus'      => 'Sustainability',
    'products' => 'Products',
    'contact'  => 'Contact',
] : [
    'home'     => 'Início',
    'who'      => 'Quem somos',
    'what'     => 'O que fazemos',
    'tech'     => 'Tecnologia',
    'loc'      => 'Localização',
    'sus'      => 'Sustentabilidade',
    'products' => 'Produtos',
    'contact'  => 'Contato',
];
?>
<header id="header">
    <h1 id="logo">
        <a href="<?= htmlspecialchars($home, ENT_QUOTES) ?>">
            <img src="<?= $base ?>images/logo.png" class="img-responsive" alt="Newco Brazil">
        </a>
    </h1>
    <nav id="nav">
        <ul>
            <li><a href="#intro"><?= $labels['home'] ?></a></li>
            <li><a href="#quem"><?= $labels['who'] ?></a></li>
            <li><a href="#oque"><?= $labels['what'] ?></a></li>
            <li><a href="#produtos"><?= $labels['products'] ?></a></li>
            <li><a href="#contact"><?= $labels['contact'] ?></a></li>
            <li class="rd-lang rd-lang--first">
                <a href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>"
                   class="<?= !$is_en ? 'is-active' : '' ?>"
                   hreflang="pt-BR" lang="pt-BR"
                   <?= !$is_en ? 'aria-current="page"' : '' ?>>PT</a>
            </li>
            <li class="rd-lang">
                <a href="<?= htmlspecialchars(lang_alt_url('en'), ENT_QUOTES) ?>"
                   class="<?= $is_en ? 'is-active' : '' ?>"
                   hreflang="en" lang="en"
                   <?= $is_en ? 'aria-current="page"' : '' ?>>EN</a>
            </li>
        </ul>
    </nav>
</header>
