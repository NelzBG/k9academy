<?php
declare(strict_types=1);
require __DIR__.'/ebooks-lib.php';
k9_headers();
$language=in_array($_POST['language']??'', ['bg','en'],true)?$_POST['language']:'en';
if(($_SERVER['REQUEST_METHOD']??'')!=='POST')k9_error(405,'Please choose a guide on the K9 Academy website.',$language);
if(($_SERVER['HTTP_ORIGIN']??'')!==K9_ORIGIN)k9_error(403,'Please return to the K9 Academy website.',$language);
if((int)($_SERVER['CONTENT_LENGTH']??0)>2048||!str_starts_with(strtolower($_SERVER['CONTENT_TYPE']??''),'application/x-www-form-urlencoded'))k9_error(400,'Invalid request.',$language);
try{
    $config=k9_config();$slug=$_POST['product']??'';if(!is_string($slug)||!isset($config['products'][$slug]))k9_error(422,'Choose a valid guide.',$language);
    if(!k9_rate_limit('checkout',20))k9_error(429,$language==='bg'?'Опитайте отново по-късно.':'Please try again later.',$language);
    $product=$config['products'][$slug];
    $session=k9_stripe('/checkout/sessions',['mode'=>'payment','locale'=>$language==='bg'?'bg':'en','line_items'=>[['price'=>$product['price'],'quantity'=>1]],'success_url'=>K9_API.'/complete.php?session_id={CHECKOUT_SESSION_ID}','cancel_url'=>K9_ORIGIN.'/'.($language==='en'?'en/':'').'ebooks/#book-'.$slug,'metadata'=>['project'=>'k9academy-ebooks','product'=>$slug,'language'=>$language],'payment_intent_data'=>['metadata'=>['project'=>'k9academy-ebooks','product'=>$slug]],'billing_address_collection'=>'auto','expires_at'=>time()+3600,'custom_text'=>['submit'=>['message'=>$language==='bg'?'Еднократна покупка на PDF на български и английски. След потвърдено плащане: изтегляне и имейл. Обучителни сесии не са включени. Плащането се обработва от Hondpro.':'One-time purchase of Bulgarian and English PDFs. After confirmed payment: download and email. Training sessions are not included. Payment is processed by Hondpro.']]],'POST');
    $url=$session['url']??'';if(!preg_match('#^https://checkout\.stripe\.com/#',$url))throw new RuntimeException('checkout_unavailable');
    header('Location: '.$url,true,303);exit;
}catch(Throwable $error){k9_error(503,$language==='bg'?'Плащането временно не е налично. Моля, свържете се с нас.':'Checkout is temporarily unavailable. Please contact us.',$language);}
