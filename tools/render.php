<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
[$script, $file, $language] = $argv;
$routes = json_decode(file_get_contents(__DIR__ . '/../src/routes.json'), true, 512, JSON_THROW_ON_ERROR);
if (!array_key_exists($file, $routes) || !in_array($language, ['bg', 'en'], true)) throw new RuntimeException('Unknown page or language');
$_GET['lang'] = $language;
$_SERVER['SCRIPT_NAME'] = '/' . $file;
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
require __DIR__ . '/../src/' . $file;
$html = ob_get_clean();
// Resolve shared assets from both language route trees, including srcset and import maps.
$html = preg_replace('~(?<=[\"\' ,])(?:\\./)?assets/~', '/assets/', $html);
echo $html;
