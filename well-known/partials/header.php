<?php
// Espera $lang_current ('pt'|'en') definido pelo arquivo que inclui este partial.
$is_en   = (($lang_current ?? 'pt') === 'en');
$base    = $is_en ? '../' : '';
$home    = $is_en ? '../en/index.php' : 'index.php';
?>
<header class="rd-header" id="rd-header" role="banner">
    <div class="rd-header__inner">
        <a href="<?= htmlspecialchars($home, ENT_QUOTES) ?>" class="rd-logo" aria-label="Newco Brazil">
            <img src="<?= $base ?>images/logo.png" alt="Newco Brazil" />
        </a>
        <nav class="rd-lang-toggle" aria-label="<?= $is_en ? 'Language' : 'Idioma' ?>">
            <a href="<?= htmlspecialchars(lang_alt_url('pt'), ENT_QUOTES) ?>"
               class="rd-lang-toggle__item<?= !$is_en ? ' is-active' : '' ?>"
               <?= !$is_en ? 'aria-current="page"' : '' ?>
               hreflang="pt-BR" lang="pt-BR">PT</a>
            <span class="rd-lang-toggle__sep" aria-hidden="true">·</span>
            <a href="<?= htmlspecialchars(lang_alt_url('en'), ENT_QUOTES) ?>"
               class="rd-lang-toggle__item<?= $is_en ? ' is-active' : '' ?>"
               <?= $is_en ? 'aria-current="page"' : '' ?>
               hreflang="en" lang="en">EN</a>
        </nav>
    </div>
</header>
