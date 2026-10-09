<?php
declare(strict_types=1);
require __DIR__.'/ebooks-lib.php';
k9_headers();
if(!in_array($_SERVER['REQUEST_METHOD']??'', ['GET','HEAD'],true))k9_error(405,'Invalid download request.');
try{
    $id=$_GET['order']??'';$language=$_GET['language']??'';$rawExpires=$_GET['expires']??'';$signature=$_GET['signature']??'';
    if(!is_string($id)||!is_string($language)||!is_string($rawExpires)||!ctype_digit($rawExpires)||!is_string($signature))throw new RuntimeException('invalid_link');
    $key=trim(file_get_contents(K9_PRIVATE.'/signing.key'));
    if(!k9_download_valid($id,$language,(int)$rawExpires,$signature,$key,time()))throw new RuntimeException('invalid_link');
    $file=K9_PRIVATE.'/orders/'.$id.'.json';$handle=fopen($file,'r');if(!$handle||!flock($handle,LOCK_SH))throw new RuntimeException('order_unavailable');
    try{$order=json_decode(stream_get_contents($handle),true);if(empty($order['paid'])||empty($order['active'])||($order['id']??'')!==$id)throw new RuntimeException('order_inactive');}finally{flock($handle,LOCK_UN);fclose($handle);}
    $config=k9_config();if(!isset($config['products'][$order['product']??'']))throw new RuntimeException('unknown_product');
    $name='k9-'.$order['product'].'-'.$language.'.pdf';$path=K9_PRIVATE.'/pdf/'.$name;if(!is_file($path))throw new RuntimeException('file_unavailable');
    header('Content-Type: application/pdf');header('Content-Disposition: attachment; filename="'.$name.'"');header('Content-Length: '.filesize($path));
    if($_SERVER['REQUEST_METHOD']==='GET')readfile($path);
}catch(Throwable $error){k9_error(403,'This download link is invalid or has expired. Please contact K9 Academy for help.');}
