<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
$_GET['lang']='bg';$_SERVER['SCRIPT_NAME']='/index.php';
ob_start();require __DIR__.'/../src/error.php';$html=ob_get_clean();
echo preg_replace('~(?<=[\"\' ,])(?:\\./)?assets/~','/assets/',$html);
