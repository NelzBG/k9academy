<?php
declare(strict_types=1);
require __DIR__.'/ebooks-lib.php';
k9_headers();header('Content-Type: application/json');
if(($_SERVER['REQUEST_METHOD']??'')!=='POST'){http_response_code(405);echo '{"ok":false}';exit;}
if((int)($_SERVER['CONTENT_LENGTH']??0)>1048576){http_response_code(413);exit;}
$body=(string)file_get_contents('php://input');$secret=is_readable(K9_PRIVATE.'/webhook.secret')?trim(file_get_contents(K9_PRIVATE.'/webhook.secret')):'';
if(!k9_signature_valid($body,$_SERVER['HTTP_STRIPE_SIGNATURE']??'',$secret,time())){http_response_code(400);echo '{"ok":false}';exit;}
try{
    $event=json_decode($body,true,512,JSON_THROW_ON_ERROR);$object=$event['data']['object']??[];$type=$event['type']??'';
    if(in_array($type,['checkout.session.completed','checkout.session.async_payment_succeeded'],true)){
        if(($object['metadata']['project']??'')!=='k9academy-ebooks'){echo '{"ok":true,"ignored":true}';exit;}
        if(($object['payment_status']??'')!=='paid'){echo '{"ok":true,"pending":true}';exit;}
        $order=k9_fulfill($object['id']??'');
        if($order['mail']==='failed'){http_response_code(500);echo '{"ok":false,"delivery":"review_required"}';exit;}
    }elseif(in_array($type,['charge.refunded','charge.dispute.created'],true)){
        $intent=$object['payment_intent']??'';
        if(is_string($intent))k9_revoke($intent,$type);
    }
    echo '{"ok":true}';
}catch(Throwable $error){http_response_code(500);echo '{"ok":false}';}
