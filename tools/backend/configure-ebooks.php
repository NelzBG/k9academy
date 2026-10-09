<?php
// Run only through SSH, after deploying the endpoint and private PDFs.
declare(strict_types=1);
if(PHP_SAPI!=='cli')exit(1);
require __DIR__.'/ebooks-lib.php';
$products=[
 'start'=>['cents'=>499,'stripeProduct'=>'prod_VPVLSDW2PapYpC','price'=>'price_1UOgKiB57hMeY0DPNToRPsjS','name'=>['bg'=>'Добро начало','en'=>'A Better Start']],
 'puppy'=>['cents'=>999,'stripeProduct'=>'prod_VPVYCstDlyQDoS','price'=>'price_1UOgX8B57hMeY0DPFuoQVYrh','name'=>['bg'=>'Кученце: 30 дни','en'=>'Puppy: 30 Days']],
 'complete'=>['cents'=>4999,'stripeProduct'=>'prod_VPVk9X9ljs1iql','price'=>'price_1UOgjQB57hMeY0DPGeR1vOUA','name'=>['bg'=>'Заедно в реалния живот','en'=>'Together in Real Life']]
];
try{
 foreach($products as $slug=>$product){
  $price=k9_stripe('/prices/'.$product['price']);
  if(empty($price['active'])||($price['unit_amount']??0)!==$product['cents']||($price['currency']??'')!=='eur'||($price['product']??'')!==$product['stripeProduct']||($price['type']??'')!=='one_time'||($price['tax_behavior']??'')!=='inclusive')throw new RuntimeException('price_validation_failed');
  foreach(['bg','en'] as $language){$file=K9_PRIVATE.'/pdf/k9-'.$slug.'-'.$language.'.pdf';if(!is_file($file)||filesize($file)<50000||!str_starts_with((string)file_get_contents($file,false,null,0,5),'%PDF-'))throw new RuntimeException('private_pdf_missing');}
 }
 $mail=require '/home/customer/k9academy-private/enquiry-config.php';
 if(!filter_var($mail['from']??'',FILTER_VALIDATE_EMAIL)||!filter_var($mail['recipient']??'',FILTER_VALIDATE_EMAIL))throw new RuntimeException('mail_config_missing');
 if(!is_dir(K9_PRIVATE))mkdir(K9_PRIVATE,0700,true);chmod(K9_PRIVATE,0700);
 $config=['products'=>$products,'from'=>$mail['from'],'replyTo'=>$mail['recipient']];
 file_put_contents(K9_PRIVATE.'/catalog.json',json_encode($config,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)."\n",LOCK_EX);chmod(K9_PRIVATE.'/catalog.json',0600);
 if(!is_file(K9_PRIVATE.'/signing.key')){file_put_contents(K9_PRIVATE.'/signing.key',bin2hex(random_bytes(32)),LOCK_EX);chmod(K9_PRIVATE.'/signing.key',0600);}
 $hooks=k9_stripe('/webhook_endpoints',['limit'=>100]);$existing=array_values(array_filter($hooks['data']??[],static fn($hook)=>($hook['url']??'')===K9_API.'/webhook.php'));
 if(count($existing)>1)throw new RuntimeException('duplicate_k9_endpoints');
 if(!$existing){
  $hook=k9_stripe('/webhook_endpoints',['url'=>K9_API.'/webhook.php','description'=>'K9 Academy paid PDF delivery','enabled_events'=>['checkout.session.completed','checkout.session.async_payment_succeeded','charge.refunded','charge.dispute.created'],'metadata'=>['project'=>'k9academy-ebooks']],'POST','k9-ebooks-webhook-20261009');
  if(!isset($hook['secret']))throw new RuntimeException('webhook_secret_missing');
  file_put_contents(K9_PRIVATE.'/webhook.secret',$hook['secret'],LOCK_EX);chmod(K9_PRIVATE.'/webhook.secret',0600);
  file_put_contents(K9_PRIVATE.'/webhook-id.txt',$hook['id'],LOCK_EX);chmod(K9_PRIVATE.'/webhook-id.txt',0600);
 }else{$hook=$existing[0];if(!is_readable(K9_PRIVATE.'/webhook.secret'))throw new RuntimeException('existing_webhook_private_secret_required');}
 echo json_encode(['ready'=>true,'pricesValidated'=>3,'privatePdfs'=>6,'webhook'=>$hook['id'],'webhookStatus'=>$hook['status']??null,'keyStayedOnHost'=>true],JSON_PRETTY_PRINT)."\n";
}catch(Throwable $error){echo json_encode(['ready'=>false,'reason'=>$error->getMessage()])."\n";exit(1);}
