<?php
declare(strict_types=1);
require __DIR__.'/backend/enquiry-mailer.php';
function verify(bool $condition,string $message):void{if(!$condition)throw new RuntimeException($message);}
$config=['recipient'=>'mail.k9shop@gmail.com','from'=>'website@nextgen.run'];
foreach(['bg','en'] as $lang){
 $v=['language'=>$lang,'name'=>'QA <script>alert(1)</script>','phone'=>'+359892360550','email'=>'customer@example.invalid','dog'=>'Shepherd & handler','service'=>'consultation','message'=>"TEST — preview only.\nNo appointment requested.",'consent'=>'yes'];
 $sent=[];$result=k9_deliver_enquiry($v,$config,function($m)use(&$sent){$sent[]=$m;return true;});
 verify($result['ok']&&$result['customerCopy']&&count($sent)===2,'Both messages accepted');
 verify($sent[0]['to']===$config['recipient']&&$sent[1]['to']===$v['email'],'Correct recipients');
 verify($sent[0]['headers']['Reply-To']===$v['email']&&$sent[1]['headers']['Reply-To']===$config['recipient'],'Correct reply routing');
 foreach($sent as $i=>$m){
  verify(!str_contains($m['html'],'<script>'),'Escaped visitor content');
  verify(str_contains($m['html'],'&lt;script&gt;'),'HTML content preserved safely');
  verify(str_contains($m['body'],'Content-Type: text/plain')&&str_contains($m['body'],'Content-Type: text/html'),'Multipart alternatives');
  verify(str_contains($m['headers']['Content-Type'],'multipart/alternative'),'MIME header');
  verify(!str_contains($m['subject'],"\nBcc:"),'Safe subject');
  $preview=getenv('K9_EMAIL_PREVIEW_DIR');
  if($preview)file_put_contents($preview.'/k9-email-'.$lang.'-'.($i===0?'team':'customer').'.html',$m['html']);
 }
 $calls=0;$failed=k9_deliver_enquiry($v,$config,function($m)use(&$calls){$calls++;return false;});
 verify(!$failed['ok']&&$calls===1,'No acknowledgement when team delivery fails');
 $calls=0;$partial=k9_deliver_enquiry($v,$config,function($m)use(&$calls){return ++$calls===1;});
 verify($partial['ok']&&!$partial['customerCopy']&&$calls===2,'Partial failure retains accepted enquiry');
}
echo "PASS: bilingual MIME templates, escaping, recipients, reply routing and full/partial delivery failures. No mail sent.\n";
