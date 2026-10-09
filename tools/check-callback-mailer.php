<?php
declare(strict_types=1);
require __DIR__.'/backend/enquiry-mailer.php';
function callback_verify(bool $condition,string $message):void{if(!$condition)throw new RuntimeException($message);}
$config=['recipient'=>'mail.k9shop@gmail.com','from'=>'website@nextgen.run'];
foreach(['bg','en'] as $lang){
 $v=['language'=>$lang,'phone'=>'+359 892 360 550','page'=>'https://www.k9academy.bg/en/puppy-training/'];
 $sent=[];$result=k9_deliver_callback($v,$config,function($mail)use(&$sent){$sent[]=$mail;return true;});
 callback_verify($result['ok']&&count($sent)===1,'Exactly one team callback email');
 $mail=$sent[0];callback_verify($mail['to']===$config['recipient'],'Same configured contact recipient');
 callback_verify($mail['headers']['Reply-To']===$config['recipient'],'No invented customer email');
 callback_verify(str_contains($mail['html'],'tel:+359892360550'),'Clickable normalized phone');
 callback_verify(str_contains($mail['text'],$v['phone'])&&str_contains($mail['text'],$v['page']),'Phone and page retained');
 callback_verify(str_contains($mail['body'],'Content-Type: text/plain')&&str_contains($mail['body'],'Content-Type: text/html'),'Multipart email');
 callback_verify(str_contains($mail['html'],$lang==='bg'?'Заявка за обратно обаждане':'Callback request'),'Localized template');
 $unsafe=$v;$unsafe['phone']='"><script>alert(1)</script>';
 $escaped=k9_callback_email($unsafe,$config,'K9-CB-TEST');
 callback_verify(!str_contains($escaped['html'],'<script>')&&str_contains($escaped['html'],'&lt;script&gt;'),'Escaped visitor content');
 $count=0;$failed=k9_deliver_callback($v,$config,function()use(&$count){$count++;return false;});
 callback_verify(!$failed['ok']&&$count===1,'Failure is not accepted or retried');
}
echo "PASS: bilingual callback MIME, same recipient, one team email, clickable phone, escaping and failure handling. No mail sent.\n";
