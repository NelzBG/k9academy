<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/ebooks-lib.php';
function test(bool $condition,string $message):void{if(!$condition)throw new RuntimeException($message);}
$config=['from'=>'guides@example.test','replyTo'=>'help@example.test','products'=>['start'=>['cents'=>499,'stripeProduct'=>'prod_fixture','price'=>'price_fixture','name'=>['bg'=>'Добро начало','en'=>'A Better Start']]]];
$session=['id'=>'cs_test_fixture','metadata'=>['project'=>'k9academy-ebooks','product'=>'start','language'=>'bg'],'livemode'=>false,'mode'=>'payment','status'=>'complete','payment_status'=>'paid','currency'=>'eur','amount_total'=>499,'amount_subtotal'=>499,'customer_details'=>['email'=>'buyer@example.test'],'payment_intent'=>'pi_fixture'];
$items=[['quantity'=>1,'price'=>['id'=>'price_fixture','product'=>'prod_fixture','currency'=>'eur','unit_amount'=>499]]];
$order=k9_expected_order($session,$items,$config,false);test($order['paid']===true,'paid fixture');
foreach(['payment_status'=>'unpaid','amount_total'=>0,'currency'=>'usd','livemode'=>true,'status'=>'open','mode'=>'subscription','customer_details'=>['email'=>"bad\r\n@example.test"],'metadata'=>['project'=>'another-project','product'=>'start','language'=>'bg']] as $field=>$wrong){$bad=$session;$bad[$field]=$wrong;$rejected=false;try{k9_expected_order($bad,$items,$config,false);}catch(RuntimeException $e){$rejected=true;}test($rejected,'reject '.$field);}
foreach([['quantity'=>2],['price'=>['id'=>'price_other']],['price'=>['id'=>'price_fixture','product'=>'prod_other','currency'=>'eur','unit_amount'=>499]]] as $mutation){$bad=$items;$bad[0]=array_replace($bad[0],$mutation);$rejected=false;try{k9_expected_order($session,$bad,$config,false);}catch(RuntimeException $e){$rejected=true;}test($rejected,'reject wrong line item');}
$body='{"type":"checkout.session.completed"}';$secret='private_fixture_secret';$now=1700000000;$signature='t='.$now.',v1='.hash_hmac('sha256',$now.'.'.$body,$secret);
test(k9_signature_valid($body,$signature,$secret,$now),'valid webhook');test(!k9_signature_valid($body.'x',$signature,$secret,$now),'tampered event');test(!k9_signature_valid($body,$signature,$secret,$now+301),'expired event');test(!k9_signature_valid($body,$signature,'other',$now),'wrong webhook key');
$key=str_repeat('x',64);$id=$order['id'];$expires=$now+2592000;$sig=k9_download_signature($id,'bg',$expires,$key);
test(k9_download_valid($id,'bg',$expires,$sig,$key,$now),'valid download');test(!k9_download_valid('../private','bg',$expires,$sig,$key,$now),'traversal');test(!k9_download_valid($id,'en',$expires,$sig,$key,$now),'changed edition');test(!k9_download_valid($id,'bg',$expires,$sig,$key,$expires+1),'expired download');
$directory=sys_get_temp_dir().'/k9-fixture-'.bin2hex(random_bytes(8));mkdir($directory,0700);$pdf=$directory.'/pdf';mkdir($pdf,0700);foreach(['bg','en'] as $language)file_put_contents($pdf.'/k9-start-'.$language.'.pdf',"%PDF-1.7\nfixture");
$sent=0;$transport=static function(array $message)use(&$sent):bool{$sent++;test(substr_count($message['body'],'Content-Type: application/pdf')===2,'two PDF attachments');test(str_contains($message['headers']['Reply-To'],'help@example.test'),'support reply');return true;};
$saved=k9_persist_order($order,$config,$directory.'/orders',$transport,$key,$pdf);test($saved['mail']==='accepted','mail accepted');k9_persist_order($order,$config,$directory.'/orders',$transport,$key,$pdf);test($sent===1,'idempotent delivery');
foreach(glob($directory.'/orders/*.json')?:[] as $f)unlink($f);rmdir($directory.'/orders');foreach(glob($pdf.'/*.pdf')?:[] as $f)unlink($f);rmdir($pdf);rmdir($directory);
echo "PASS: paid/unpaid and price isolation, webhook signature/replay, signed download expiry/traversal, two attachments and idempotent delivery.\n";
