<?php
/**
 * Helpers de roteamento bilíngue PT/EN.
 *
 * Convenção do site:
 *   - Páginas PT vivem na raiz: /index.php, /produto.php
 *   - Páginas EN vivem em /en/: /en/index.php, /en/produto.php, /en/<categoria>.php
 *   - Os arquivos /en/<categoria>.php são legados (redundantes com /en/index.php)
 *     e mapeiam direto para uma categoria fixa em /produto.php?id=N na versão PT.
 */

if (!function_exists('lang_current')) {
    function lang_current(): string {
        return (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/en/') !== false) ? 'en' : 'pt';
    }
}

if (!function_exists('lang_alt_url')) {
    /**
     * URL equivalente da página atual no idioma alvo ('pt' ou 'en').
     */
    function lang_alt_url(string $target): string {
        $script = $_SERVER['SCRIPT_NAME']  ?? '';
        $query  = $_SERVER['QUERY_STRING'] ?? '';
        $isInEn = (strpos($script, '/en/') !== false);
        $current = $isInEn ? 'en' : 'pt';

        if ($current === $target) {
            return $script . ($query !== '' ? "?$query" : '');
        }

        $base = basename($script);

        // Páginas EN legadas (/en/adocantes.php etc.) → /produto.php?id=N
        $legacyMap = [
            'adocantes.php'  => 2,
            'frutas.php'     => 1,
            'funcionais.php' => 3,
            'oleos.php'      => 4,
        ];

        if ($isInEn && $target === 'pt' && isset($legacyMap[$base])) {
            return '/produto.php?id=' . $legacyMap[$base];
        }

        if ($target === 'en') {
            return '/en/' . $base . ($query !== '' ? "?$query" : '');
        }

        // target === 'pt' && estamos em /en/<algo>.php
        return '/' . $base . ($query !== '' ? "?$query" : '');
    }
}
