<?php
declare(strict_types=1);

// Deploy on the existing PHP host, not GitHub Pages. Keep configuration outside public_html.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Vary: Origin');
function reply(int $status, string $code, bool $ok = false): never {
    http_response_code($status);
    if (($_POST['kind'] ?? null)==='callback' && str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html')) {
        header('Content-Type: text/html; charset=utf-8');
        $bg=($_POST['language'] ?? '')!=='en';
        $title=$ok?($bg?'Заявката е изпратена.':'Request sent.'):($bg?'Заявката не е потвърдена.':'Request not confirmed.');
        $copy=$ok?($bg?'Благодарим ви! Изпратихме заявката за обратно обаждане до K9 Academy.':'Thank you! Your callback request has been sent to K9 Academy.'):
            ($code==='validation'?($bg?'Въведете валиден телефонен номер.':'Enter a valid phone number.'):($bg?'Можете да ни се обадите на +359 892 360 550.':'You can call us on +359 892 360 550.'));
        $back='https://www.k9academy.bg/'.($bg?'':'en/').'#call-me-back';
        echo '<!doctype html><html lang="'.($bg?'bg':'en').'"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>K9 Academy</title><style>body{margin:0;background:#f4f0e7;color:#172011;font:17px/1.6 Arial,sans-serif}main{max-width:650px;margin:12vh auto;padding:32px}h1{font-size:clamp(30px,6vw,50px);line-height:1.1}a{color:inherit}a.button{display:inline-block;background:#d9ff00;color:#172011;padding:14px 22px;border-radius:12px;margin:15px 12px 0 0;font-weight:bold;text-decoration:none}</style></head><body><main><p>K9 ACADEMY</p><h1>'.$title.'</h1><p>'.$copy.'</p><a class="button" href="'.$back.'">'.($bg?'Към сайта':'Back to the website').'</a><a href="tel:+359892360550">+359 892 360 550</a></main></body></html>';
        exit;
    }

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

$limits = ['language'=>2, 'name'=>100, 'phone'=>40, 'email'=>254, 'dog'=>180, 'service'=>24, 'message'=>3000, 'consent'=>3, 'website'=>200, 'kind'=>12, 'page'=>2048];
$values = [];
foreach ($limits as $field=>$limit) {
    $value = $_POST[$field] ?? '';
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8') || mb_strlen($value, 'UTF-8') > $limit) reply(422, 'validation');
    $values[$field] = trim(str_replace(["\r\n", "\r"], "\n", $value));
}
if ($values['website'] !== '') reply(422, 'validation');
$kind=$values['kind'] ?: 'enquiry';
if (!in_array($kind,['enquiry','callback'],true)) reply(422,'validation');
if ($kind==='callback') {
    if (!in_array($values['language'],['bg','en'],true) || !preg_match('/^\+?[0-9(). \-]{7,40}$/D',$values['phone'])) reply(422,'validation');
    $digits=preg_replace('/[^0-9]/','',$values['phone']);
    if (strlen($digits)<7 || strlen($digits)>15) reply(422,'validation');
    if ($values['page']!=='') {
        $page=parse_url($values['page']);
        if (!filter_var($values['page'],FILTER_VALIDATE_URL) || !is_array($page) || ($page['scheme']??'')!=='https' || ($page['host']??'')!=='www.k9academy.bg' || isset($page['user']) || isset($page['pass']) || isset($page['port']) || isset($page['query']) || isset($page['fragment'])) reply(422,'validation');
    }
} else {
    if (!in_array($values['language'], ['bg','en'], true) || $values['name'] === '' || $values['message'] === '' || $values['consent'] !== 'yes') reply(422, 'validation');
    if (!preg_match('/^[0-9+(). \\-]{6,40}$/D', $values['phone'])) reply(422, 'validation');
    if ($values['email'] === '' || (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $values['email']))) reply(422, 'validation');
    if (!in_array($values['service'], ['obedience','socialisation','correction','protection','consultation'], true)) reply(422, 'validation');
}

$configFile = getenv('K9_ENQUIRY_CONFIG') ?: '/home/customer/k9academy-private/enquiry-config.php';
if (!is_file($configFile)) reply(503, 'not_configured');
$config = require $configFile;
if (!is_array($config) || !filter_var($config['recipient'] ?? '', FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+$/D', $config['from'] ?? '')) reply(503, 'not_configured');

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
require __DIR__ . '/enquiry-mailer.php';
if ($kind==='callback') {
    $result=k9_deliver_callback($values,$config);
    if (!$result['ok']) reply(503,'delivery_failed');
    reply(200,'accepted',true);
}
$result = k9_deliver_enquiry($values, $config);
if (!$result['ok']) reply(503, 'delivery_failed');
http_response_code(200);
echo json_encode($result + ['code'=>$result['customerCopy'] ? 'accepted' : 'accepted_copy_failed'], JSON_UNESCAPED_SLASHES);
