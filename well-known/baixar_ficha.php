<?php
/**
 * Frente A2: handler de download de ficha técnica.
 *
 * Fluxo:
 *  1. Recebe POST com `email` (obrigatório), `produto_short` ou `produto_id`,
 *     opcionalmente `nome` e `empresa`.
 *  2. Valida email; se inválido, devolve JSON de erro.
 *  3. Registra a solicitação em `download_request` (lead generation B2B).
 *  4. Serve o arquivo PDF como download (Content-Disposition: attachment).
 *  5. Em modo AJAX (header `X-Requested-With: XMLHttpRequest`) devolve JSON
 *     com `download_url` em vez de servir direto — útil para SPA.
 */

require_once __DIR__ . '/database.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método não permitido.']);
    exit;
}

$email   = trim($_POST['email'] ?? '');
$nome    = trim($_POST['nome'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$idioma  = (isset($_POST['idioma']) && in_array($_POST['idioma'], ['pt', 'en'], true))
    ? $_POST['idioma'] : 'pt';
$short   = trim($_POST['produto_short'] ?? '');
$pid     = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'E-mail inválido.']);
    exit;
}

if ($pid <= 0 && $short === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Produto não informado.']);
    exit;
}

// Localiza o produto
if ($pid > 0) {
    $stmt = $conn->prepare('SELECT idproduto, nome, ficha_pdf FROM produto WHERE idproduto = :id AND status = 0');
    $stmt->bindParam(':id', $pid, PDO::PARAM_INT);
} else {
    $stmt = $conn->prepare('SELECT idproduto, nome, ficha_pdf FROM produto WHERE short = :short AND status = 0');
    $stmt->bindParam(':short', $short);
}
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Produto não encontrado.']);
    exit;
}

if (empty($produto['ficha_pdf'])) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Ficha técnica indisponível para este produto.']);
    exit;
}

$pdf_path = __DIR__ . '/admin/fichas/' . basename($produto['ficha_pdf']);
if (!is_file($pdf_path)) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'Arquivo da ficha não encontrado no servidor.']);
    exit;
}

// Registra a solicitação
try {
    $insert = $conn->prepare(
        'INSERT INTO download_request
            (produto_id, email, nome_solicitante, empresa, ip, user_agent, idioma)
         VALUES
            (:produto_id, :email, :nome, :empresa, :ip, :ua, :idioma)'
    );
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
    $insert->execute([
        ':produto_id' => (int) $produto['idproduto'],
        ':email'      => $email,
        ':nome'       => $nome ?: null,
        ':empresa'    => $empresa ?: null,
        ':ip'         => $ip,
        ':ua'         => $ua,
        ':idioma'     => $idioma,
    ]);
} catch (PDOException $e) {
    error_log('[A2] Falha ao registrar download_request: ' . $e->getMessage());
    // Falha no log não impede o download.
}

// Modo AJAX → devolve URL
$is_ajax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
if ($is_ajax) {
    $url = '/admin/fichas/' . rawurlencode($produto['ficha_pdf']);
    echo json_encode(['ok' => true, 'download_url' => $url]);
    exit;
}

// Modo POST tradicional → serve o PDF direto
$nome_seguro = preg_replace('/[^A-Za-z0-9_-]/', '_', $produto['nome']);
$filename    = $nome_seguro . '_ficha-tecnica.pdf';

header_remove('Content-Type');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($pdf_path));
header('Cache-Control: private, no-cache, must-revalidate');
readfile($pdf_path);
exit;
