<?php
declare(strict_types=1);
ini_set('display_errors','0');
const K9_PRIVATE = '/home/customer/k9academy-private/ebooks';
const K9_API = 'https://www.nextgen.run/demo/k9/api/ebooks';
const K9_ORIGIN = 'https://www.k9academy.bg';

function k9_config(): array {
    $file=K9_PRIVATE.'/catalog.json';
    if(!is_readable($file))throw new RuntimeException('not_configured');
    $config=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    if(!isset($config['products'],$config['from'],$config['replyTo'])||!filter_var($config['from'],FILTER_VALIDATE_EMAIL)||!filter_var($config['replyTo'],FILTER_VALIDATE_EMAIL)||preg_match('/[\r\n]/',$config['from'].$config['replyTo']))throw new RuntimeException('not_configured');
    return $config;
}
function k9_stripe(string $path,array $parameters=[],string $method='GET',?string $idempotency=null): array {
    if(!preg_match('#^/(checkout/sessions(?:/[A-Za-z0-9_]+(?:/line_items)?)?|prices/[A-Za-z0-9_]+|products/[A-Za-z0-9_]+|webhook_endpoints(?:/[A-Za-z0-9_]+)?)$#D',$path))throw new RuntimeException('unexpected_api_path');
    $key=trim(file_get_contents('/home/customer/private/siteground-revenue/stripe_restricted.key'));
    if($key==='')throw new RuntimeException('merchant_unavailable');
    $query=http_build_query($parameters,'','&',PHP_QUERY_RFC3986);
    $curl=curl_init('https://api.stripe.com/v1'.$path.($method==='GET'&&$query!==''?'?'.$query:''));
    $headers=['Stripe-Version: 2025-02-24.acacia'];
    if($idempotency!==null)$headers[]='Idempotency-Key: '.$idempotency;
    curl_setopt_array($curl,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>25,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_USERPWD=>$key.':',CURLOPT_HTTPHEADER=>$headers]);
    if($method==='POST')curl_setopt_array($curl,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$query]);
    $body=curl_exec($curl);$status=curl_getinfo($curl,CURLINFO_RESPONSE_CODE);curl_close($curl);
    $data=json_decode((string)$body,true);
    if($status<200||$status>=300||!is_array($data))throw new RuntimeException('merchant_request_failed_'.$status);
    return $data;
}
function k9_expected_order(array $session,array $items,array $config,bool $live=true): array {
    $meta=$session['metadata']??[];
    if(($meta['project']??'')!=='k9academy-ebooks')throw new RuntimeException('wrong_project');
    $slug=$meta['product']??'';
    $product=$config['products'][$slug]??null;
    if(!$product||!in_array($meta['language']??'', ['bg','en'],true))throw new RuntimeException('unknown_product');
    if(($session['livemode']??null)!==$live||($session['mode']??'')!=='payment'||($session['status']??'')!=='complete'||($session['payment_status']??'')!=='paid')throw new RuntimeException('not_paid');
    if(($session['currency']??'')!=='eur'||($session['amount_total']??null)!==$product['cents']||($session['amount_subtotal']??null)!==$product['cents'])throw new RuntimeException('wrong_total');
    if(count($items)!==1||($items[0]['quantity']??0)!==1)throw new RuntimeException('wrong_quantity');
    $price=$items[0]['price']??[];
    if(($price['id']??'')!==$product['price']||($price['product']??'')!==$product['stripeProduct']||($price['currency']??'')!=='eur'||($price['unit_amount']??null)!==$product['cents'])throw new RuntimeException('wrong_price');
    $email=$session['customer_details']['email']??'';
    if(!is_string($email)||!filter_var($email,FILTER_VALIDATE_EMAIL)||preg_match('/[\r\n]/',$email))throw new RuntimeException('invalid_email');
    if(!preg_match('/^cs_(?:live|test)_[A-Za-z0-9]+$/D',$session['id']??''))throw new RuntimeException('invalid_session');
    return ['id'=>hash('sha256',$session['id']),'session'=>$session['id'],'product'=>$slug,'language'=>$meta['language'],'email'=>$email,'paid'=>true,'active'=>true,'paymentIntent'=>$session['payment_intent']??null,'created'=>time(),'mail'=>'pending'];
}
function k9_signature_valid(string $body,string $header,string $secret,int $now): bool {
    if($secret===''||strlen($header)>2048)return false;
    $time=null;$signatures=[];
    foreach(explode(',',$header) as $part){$pair=explode('=',trim($part),2);if(count($pair)!==2)continue;if($pair[0]==='t'&&ctype_digit($pair[1]))$time=(int)$pair[1];elseif($pair[0]==='v1')$signatures[]=$pair[1];}
    if($time===null||abs($now-$time)>300)return false;
    $expected=hash_hmac('sha256',$time.'.'.$body,$secret);
    foreach($signatures as $signature)if(hash_equals($expected,$signature))return true;
    return false;
}
function k9_download_signature(string $order,string $language,int $expires,string $key): string {
    return hash_hmac('sha256',$order.'|'.$language.'|'.$expires,$key);
}
function k9_download_valid(string $order,string $language,int $expires,string $signature,string $key,int $now): bool {
    return strlen($key)>=32&&preg_match('/^[a-f0-9]{64}$/D',$order)===1&&in_array($language,['bg','en'],true)&&$expires>=$now&&$expires<=$now+2678400&&strlen($signature)===64&&hash_equals(k9_download_signature($order,$language,$expires,$key),$signature);
}
function k9_links(array $order,?string $key=null): array {
    $key??=trim(file_get_contents(K9_PRIVATE.'/signing.key'));$expires=time()+2592000;$links=[];
    foreach(['bg','en'] as $language)$links[$language]=K9_API.'/download.php?'.http_build_query(['order'=>$order['id'],'language'=>$language,'expires'=>$expires,'signature'=>k9_download_signature($order['id'],$language,$expires,$key)]);
    return $links;
}
function k9_receipt(array $order,array $config,array $links,string $pdfDirectory,bool $test=false): array {
    $product=$config['products'][$order['product']];$bg=$order['language']==='bg';
    $subject=($bg?'Вашите ръководства':'Your guides').' | K9 Academy';
    $body="K9 ACADEMY\n\n".($bg?'Благодарим за покупката. Вашите две езикови PDF издания са приложени.':'Thank you for your purchase. Your two language PDF editions are attached.')."\n\n".$product['name'][$order['language']]."\n".number_format($product['cents']/100,2).' EUR'."\n\nBG: ".$links['bg']."\nEN: ".$links['en']."\n\n".($bg?'Линковете са валидни 30 дни. След изтегляне запазете файловете за лично ползване. За помощ отговорете на този имейл. Покупката не включва индивидуални обучителни сесии.':'Links are valid for 30 days. After downloading, keep the files for personal use. Reply to this email for help. Individual training sessions are not included.')."\n\n".$config['replyTo']."\nhttps://www.k9academy.bg/\n";
    if($test){$subject='K9 Academy - PDF delivery test - no payment';$body="K9 ACADEMY / DELIVERY TEST\n\nThis is the PDF delivery test requested by the website owner. No purchase was created and no payment has been taken.\n\nBoth Bulgarian and English editions of A Better Start are attached. Please confirm receipt and open both PDF files.\n\nSupport: ".$config['replyTo']."\nhttps://www.k9academy.bg/\n";}
    $boundary='k9_'.bin2hex(random_bytes(18));
    $e=static fn(string $value):string=>htmlspecialchars($value,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');
    $heading=$test?'PDF delivery test':($bg?'Вашите ръководства са готови.':'Your guides are ready.');
    $intro=$test?'This is the website owner’s requested delivery test. No payment was taken.':($bg?'Благодарим за покупката. Двата езикови PDF файла са приложени към този имейл.':'Thank you for your purchase. Both language PDFs are attached to this email.');
    $html='<!doctype html><html lang="'.($bg?'bg':'en').'"><head><meta charset="utf-8"></head><body style="margin:0;background:#f4f2e9;font-family:Arial,sans-serif;color:#172011"><table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="padding:24px 12px"><table role="presentation" width="600" style="width:100%;max-width:600px" cellpadding="0" cellspacing="0"><tr><td style="background:#172011;padding:28px;color:#d9ff00;font-weight:bold;font-size:22px">K9 ACADEMY</td></tr><tr><td style="background:white;padding:28px"><h1 style="font-size:28px;line-height:1.2">'.$e($heading).'</h1><p style="line-height:1.7">'.$e($intro).'</p><h2 style="font-size:21px">'.$e($product['name'][$order['language']]).'</h2>';
    if(!$test){$html.='<p>'.number_format($product['cents']/100,2).' EUR</p>';foreach($links as $language=>$url)$html.='<p><a style="display:inline-block;padding:15px 20px;background:#d9ff00;color:#172011;text-decoration:none;font-weight:bold" href="'.$e($url).'">'.($language==='bg'?'PDF на български':'English PDF').'</a></p>';}
    $html.='<p style="font-size:14px;line-height:1.7">'.($test?'Please open both attached PDF files and confirm receipt.':($bg?'Линковете са валидни 30 дни. Запазете файловете за лично ползване. Покупката не включва обучителни сесии.':'Links are valid for 30 days. Keep the files for personal use. Training sessions are not included.')).'</p><p style="line-height:1.7">'.($bg?'За помощ отговорете на този имейл.':'For help, reply to this email.').'</p></td></tr><tr><td style="padding:24px 28px;background:#172011;color:#f4f2e9;font-size:13px;line-height:1.7"><a style="color:#d9ff00" href="https://www.k9academy.bg/">www.k9academy.bg</a><br>'.$e($config['replyTo']).'<br>'.($bg?'Плащания чрез Hondpro и Stripe.':'Payments through Hondpro and Stripe.').'</td></tr></table></td></tr></table></body></html>';
    $alternative='k9_alt_'.bin2hex(random_bytes(12));
    $mime='--'.$boundary."\r\nContent-Type: multipart/alternative; boundary=\"".$alternative."\"\r\n\r\n--".$alternative."\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($body),76,"\r\n").'--'.$alternative."\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($html),76,"\r\n").'--'.$alternative."--\r\n";
    foreach(['bg','en'] as $language){
        $filename='k9-'.$order['product'].'-'.$language.'.pdf';$path=$pdfDirectory.'/'.$filename;
        if(!is_file($path)||filesize($path)>10000000||!str_starts_with((string)file_get_contents($path,false,null,0,5),'%PDF-'))throw new RuntimeException('file_unavailable');
        $mime.='--'.$boundary."\r\nContent-Type: application/pdf; name=\"".$filename."\"\r\nContent-Disposition: attachment; filename=\"".$filename."\"\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode(file_get_contents($path)),76,"\r\n");
    }
    $mime.='--'.$boundary."--\r\n";
    return ['to'=>$order['email'],'subject'=>mb_encode_mimeheader($subject,'UTF-8','B',"\r\n"),'body'=>$mime,'headers'=>['From'=>'K9 Academy <'.$config['from'].'>','Reply-To'=>$config['replyTo'],'MIME-Version'=>'1.0','Content-Type'=>'multipart/mixed; boundary="'.$boundary.'"','Auto-Submitted'=>'auto-generated','X-Auto-Response-Suppress'=>'All']];
}
function k9_persist_order(array $verified,array $config,string $directory,callable $transport,?string $signingKey=null,?string $pdfDirectory=null): array {
    if(!is_dir($directory)&&!mkdir($directory,0700,true)&&!is_dir($directory))throw new RuntimeException('order_store_unavailable');
    $file=$directory.'/'.$verified['id'].'.json';$handle=fopen($file,'c+');if(!$handle||!flock($handle,LOCK_EX))throw new RuntimeException('order_store_unavailable');chmod($file,0600);
    try {
        $raw=stream_get_contents($handle);$current=json_decode($raw,true);if($raw!==''&&!is_array($current))throw new RuntimeException('order_corrupt');$order=is_array($current)?$current:$verified;
        if(empty($order['paid'])||empty($order['active']))throw new RuntimeException('order_inactive');
        if($order['mail']==='pending'){
            // Save before SMTP/mail dispatch. An uncertain outcome is reviewed instead of resent automatically.
            $order['mail']='sending';rewind($handle);ftruncate($handle,0);fwrite($handle,json_encode($order));fflush($handle);
            try{$message=k9_receipt($order,$config,k9_links($order,$signingKey),$pdfDirectory??K9_PRIVATE.'/pdf');$order['mail']=$transport($message)?'accepted':'failed';}catch(Throwable $error){$order['mail']='failed';}
            $order['mailUpdated']=time();rewind($handle);ftruncate($handle,0);fwrite($handle,json_encode($order));fflush($handle);
        }
        return $order;
    }finally{flock($handle,LOCK_UN);fclose($handle);}
}
function k9_fulfill(string $id): array {
    if(!preg_match('/^cs_live_[A-Za-z0-9]+$/D',$id))throw new RuntimeException('invalid_session');
    $config=k9_config();$session=k9_stripe('/checkout/sessions/'.$id);$items=k9_stripe('/checkout/sessions/'.$id.'/line_items',['limit'=>2]);
    $order=k9_expected_order($session,$items['data']??[],$config);
    return k9_persist_order($order,$config,K9_PRIVATE.'/orders',static fn(array $m):bool=>mail($m['to'],$m['subject'],$m['body'],$m['headers'],'-f'.$config['from']));
}
function k9_revoke(string $paymentIntent,string $reason): void {
    if(!preg_match('/^pi_[A-Za-z0-9]+$/D',$paymentIntent))return;
    foreach(glob(K9_PRIVATE.'/orders/*.json')?:[] as $file){
        $handle=fopen($file,'r+');if(!$handle||!flock($handle,LOCK_EX))continue;
        try{$order=json_decode(stream_get_contents($handle),true);if(($order['paymentIntent']??'')===$paymentIntent){$order['active']=false;$order['revoked']=$reason;rewind($handle);ftruncate($handle,0);fwrite($handle,json_encode($order));fflush($handle);}}finally{flock($handle,LOCK_UN);fclose($handle);}
    }
}
function k9_rate_limit(string $type,int $limit): bool {
    $directory=K9_PRIVATE.'/rates';if(!is_dir($directory)&&!mkdir($directory,0700,true)&&!is_dir($directory))return false;
    $file=$directory.'/'.hash('sha256',$type.'|'.($_SERVER['REMOTE_ADDR']??'unknown').'|'.gmdate('YmdH')).'.json';
    $handle=fopen($file,'c+');if(!$handle||!flock($handle,LOCK_EX))return false;chmod($file,0600);
    try{$count=(int)stream_get_contents($handle);if($count>=$limit)return false;rewind($handle);ftruncate($handle,0);fwrite($handle,(string)($count+1));fflush($handle);return true;}finally{flock($handle,LOCK_UN);fclose($handle);}
}
function k9_headers(): void {
    header('Cache-Control: no-store, private');header('X-Content-Type-Options: nosniff');header('X-Robots-Tag: noindex, nofollow');header('Referrer-Policy: no-referrer');
}
function k9_error(int $status,string $message,string $language='en'): never {
    k9_headers();http_response_code($status);header('Content-Type: text/html; charset=utf-8');$bg=$language==='bg';
    $e=static fn(string $v):string=>htmlspecialchars($v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');
    echo '<!doctype html><html lang="'.($bg?'bg':'en').'"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>K9 Academy</title><style>body{font:18px/1.6 system-ui;background:#f4f2e9;color:#172011;margin:0}main{max-width:650px;margin:10vh auto;padding:30px}a{color:#253e12}h1{font-size:2rem} .btn{background:#d9ff00;padding:14px 22px;display:inline-block;border-radius:30px;text-decoration:none}</style></head><body><main><p>K9 ACADEMY</p><h1>'.$e($message).'</h1><p>'.($bg?'За помощ: ':'For help: ').'<a href="mailto:mail.k9shop@gmail.com">mail.k9shop@gmail.com</a></p><a class="btn" href="'.K9_ORIGIN.'/'.($bg?'':'en/').'ebooks/">'.($bg?'Към ръководствата':'Back to the guides').'</a></main></body></html>';exit;
}
