<?php
declare(strict_types=1);

// Deploy on the existing PHP host, not GitHub Pages. Keep configuration outside public_html.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Vary: Origin');
function reply(int $status, string $code, bool $ok = false): never {
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'code' => $code], JSON_UNESCAPED_SLASHES);
    exit;
}
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = ['https://www.k9academy.bg'];
if (!in_array($origin, $allowedOrigins, true)) reply(403, 'origin');
header('Access-Control-Allow-Origin: ' . $origin);
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept');
    http_response_code(204);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') reply(405, 'method');
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 16384) reply(413, 'size');
if (!str_starts_with(strtolower($_SERVER['CONTENT_TYPE'] ?? ''), 'application/x-www-form-urlencoded')) reply(415, 'content_type');

$limits = ['language'=>2, 'name'=>100, 'phone'=>40, 'email'=>254, 'dog'=>180, 'service'=>24, 'message'=>3000, 'consent'=>3, 'website'=>200];
$values = [];
foreach ($limits as $field=>$limit) {
    $value = $_POST[$field] ?? '';
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8') || mb_strlen($value, 'UTF-8') > $limit) reply(422, 'validation');
    $values[$field] = trim(str_replace(["\r\n", "\r"], "\n", $value));
}
if ($values['website'] !== '') reply(422, 'validation');
if (!in_array($values['language'], ['bg','en'], true) || $values['name'] === '' || $values['message'] === '' || $values['consent'] !== 'yes') reply(422, 'validation');
if (!preg_match('/^[0-9+(). \\-]{6,40}$/D', $values['phone'])) reply(422, 'validation');
if ($values['email'] !== '' && (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $values['email']))) reply(422, 'validation');
if (!in_array($values['service'], ['obedience','socialisation','correction','protection','consultation'], true)) reply(422, 'validation');

$configFile = getenv('K9_ENQUIRY_CONFIG') ?: '/home/customer/k9academy-private/enquiry-config.php';
if (!is_file($configFile)) reply(503, 'not_configured');
$config = require $configFile;
if (!is_array($config) || !filter_var($config['recipient'] ?? '', FILTER_VALIDATE_EMAIL) || !filter_var($config['from'] ?? '', FILTER_VALIDATE_EMAIL)) reply(503, 'not_configured');

$rateDir = dirname($configFile) . '/enquiry-rates';
if (!is_dir($rateDir) && !mkdir($rateDir, 0700, true) && !is_dir($rateDir)) reply(503, 'unavailable');
$rateFile = $rateDir . '/' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '') . '.json';
$handle = fopen($rateFile, 'c+');
if (!$handle || !flock($handle, LOCK_EX)) reply(503, 'unavailable');
chmod($rateFile, 0600);
$now = time();
$state = json_decode(stream_get_contents($handle), true);
if (!is_array($state) || ($state['start'] ?? 0) < $now - 3600) $state = ['start'=>$now, 'count'=>0];
if (($state['count'] ?? 0) >= 5) { flock($handle, LOCK_UN); fclose($handle); reply(429, 'rate_limit'); }
$state['count']++;
rewind($handle);
ftruncate($handle, 0);
fwrite($handle, json_encode($state));
fflush($handle);
flock($handle, LOCK_UN);
fclose($handle);
// Remove only expired counters created by this endpoint; no enquiry bodies are stored.
foreach (glob($rateDir . '/*.json') ?: [] as $expired) {
    if (preg_match('/^[a-f0-9]{64}\\.json$/D', basename($expired)) && filemtime($expired) < $now - 86400) unlink($expired);
}
$body = "K9 Academy website enquiry\n\n";
foreach (['name'=>'Name','phone'=>'Phone','email'=>'Email','dog'=>'Dog','service'=>'Programme','message'=>'Message','language'=>'Language'] as $field=>$label) {
    $body .= $label . ": " . $values[$field] . "\n\n";
}
$body .= "Permission to respond: yes\nSubmitted: " . gmdate('c') . "\n";
$headers = ['From: K9 Academy <' . $config['from'] . '>', 'MIME-Version: 1.0', 'Content-Type: text/plain; charset=UTF-8'];
if ($values['email'] !== '') $headers[] = 'Reply-To: ' . $values['email'];
if (!mail($config['recipient'], 'K9 Academy website enquiry', $body, implode("\r\n", $headers))) reply(503, 'delivery_failed');
reply(200, 'accepted', true);
