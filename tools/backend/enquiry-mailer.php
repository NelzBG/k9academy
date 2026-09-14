<?php
declare(strict_types=1);

function k9_enquiry_email(array $v, array $config, bool $customer, string $reference): array {
    $bg = $v['language'] === 'bg';
    $e = static fn(string $s): string => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $programmes = $bg
        ? ['obedience'=>'Послушание','socialisation'=>'Социализация','correction'=>'Корекция на поведение','protection'=>'Охрана и защита','consultation'=>'Консултация']
        : ['obedience'=>'Obedience','socialisation'=>'Socialisation','correction'=>'Behaviour support','protection'=>'Protection','consultation'=>'Consultation'];
    $heading = $customer ? ($bg ? 'Благодарим за запитването.' : 'Thank you for getting in touch.') : ($bg ? 'Ново запитване от сайта.' : 'New website enquiry.');
    $intro = $customer
        ? ($bg ? 'Получихме вашето запитване. Екипът на K9 Academy ще прегледа информацията и ще се свърже с вас, за да обсъдите подходящата следваща стъпка.' : 'We have received your enquiry. The K9 Academy team will review your details and contact you to discuss the right next step.')
        : ($bg ? 'Ново запитване от k9academy.bg. Отговорете на този имейл, за да се свържете директно с клиента.' : 'A new enquiry from k9academy.bg. Reply to this email to contact the customer directly.');
    $labels = $bg ? ['name'=>'Име','phone'=>'Телефон','email'=>'Имейл','dog'=>'Куче','service'=>'Направление','message'=>'Съобщение'] : ['name'=>'Name','phone'=>'Phone','email'=>'Email','dog'=>'Dog','service'=>'Programme','message'=>'Message'];
    $details = '';
    $plain = "K9 ACADEMY\n".$heading."\n\n".$intro."\n\n";
    foreach ($labels as $key=>$label) {
        $value = $key === 'service' ? $programmes[$v[$key]] : ($v[$key] ?: ($bg ? 'Не е посочено' : 'Not supplied'));
        $details .= '<tr><td style="padding:13px 0;border-bottom:1px solid #e4e7dc"><div style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#626b58;margin-bottom:6px">'.$e($label).'</div><div style="font-size:15px;line-height:1.6;color:#172011;overflow-wrap:anywhere;word-break:break-word">'.nl2br($e($value)).'</div></td></tr>';
        $plain .= $label.": ".$value."\n\n";
    }
    $note = $customer
        ? ($bg ? 'Това е потвърждение на запитване, а не потвърден час. Часът и условията за обучение се уточняват отделно. Можете да отговорите на този имейл с допълнителна информация.' : 'This confirms your enquiry, not a booked appointment. Training times and terms are agreed separately. You can reply to this email with any additional information.')
        : ($bg ? 'Клиентът е дал съгласие да получи отговор на запитването. Данните са изпратени от посетителя на сайта.' : 'The customer consented to a response about this enquiry. These details were submitted by the website visitor.');
    $actionLabel = $customer ? ($bg ? 'Обадете ни се' : 'Call K9 Academy') : ($bg ? 'Отговорете на клиента' : 'Reply to the customer');
    $action = $customer ? 'tel:+359892360550' : 'mailto:'.$v['email'];
    $footer = $bg ? 'Ясна комуникация. По-силна връзка.' : 'Clear communication. A stronger bond.';
    $preheader = $customer ? ($bg ? 'Вашето запитване до K9 Academy е получено.' : 'Your K9 Academy enquiry has been received.') : $programmes[$v['service']];
    $html = '<!doctype html><html lang="'.$v['language'].'"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="margin:0;background:#eff1e8;font-family:Arial,Helvetica,sans-serif"><div style="display:none;max-height:0;overflow:hidden">'.$e($preheader).'</div><table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td align="center" style="padding:24px 12px"><table role="presentation" width="600" cellspacing="0" cellpadding="0" style="width:100%;max-width:600px"><tr><td style="background:#151c12;padding:28px;border-radius:20px 20px 0 0"><a href="https://www.k9academy.bg/" style="font-weight:bold;font-size:23px;letter-spacing:2px;color:#d9ff00;text-decoration:none">K9 ACADEMY</a><p style="margin:9px 0 0;font-size:11px;letter-spacing:1px;color:#d6dfcd">'.$e($footer).'</p></td></tr><tr><td style="background:#ffffff;padding:28px"><p style="font-size:11px;color:#62704c;letter-spacing:1px">'.$e($reference).'</p><h1 style="font-size:30px;line-height:1.15;color:#172011;margin:14px 0 20px">'.$e($heading).'</h1><p style="font-size:16px;line-height:1.65;color:#394330">'.$e($intro).'</p><table role="presentation" width="100%" cellspacing="0" cellpadding="0">'.$details.'</table><p style="font-size:14px;line-height:1.65;color:#59634e;margin:24px 0">'.$e($note).'</p><table role="presentation" cellspacing="0" cellpadding="0"><tr><td bgcolor="#d9ff00" style="border-radius:10px"><a href="'.$e($action).'" style="display:inline-block;padding:16px 22px;font-weight:bold;font-size:14px;color:#172011;text-decoration:none">'.$e($actionLabel).'</a></td></tr></table></td></tr><tr><td style="background:#151c12;padding:24px 28px;border-radius:0 0 20px 20px;color:#e4eadc;font-size:13px;line-height:1.8"><a href="tel:+359892360550" style="color:#e4eadc">+359 892 360 550</a><br><a href="mailto:'.$e($config['recipient']).'" style="color:#e4eadc">'.$e($config['recipient']).'</a><br><a href="https://www.k9academy.bg/'.($bg?'':'en/').'" style="color:#d9ff00">www.k9academy.bg</a><p style="font-size:11px;color:#b6c2a9;margin-bottom:0">K9 Academy · '.$e($reference).'</p></td></tr></table></td></tr></table></body></html>';
    $plain .= $note."\n\n".$reference."\n+359 892 360 550\n".$config['recipient']."\nhttps://www.k9academy.bg/\n";
    $subject = ($customer ? ($bg ? 'Получихме вашето запитване' : 'We received your enquiry') : ($bg ? 'Ново запитване' : 'New enquiry')).' | K9 Academy | '.$reference;
    $boundary = 'k9_'.bin2hex(random_bytes(16));
    $body = '--'.$boundary."\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($plain),76,"\r\n").'--'.$boundary."\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n".chunk_split(base64_encode($html),76,"\r\n").'--'.$boundary."--\r\n";
    return ['to'=>$customer?$v['email']:$config['recipient'], 'subject'=>mb_encode_mimeheader($subject,'UTF-8','B',"\r\n"), 'body'=>$body,
        'headers'=>['From'=>'K9 Academy <'.$config['from'].'>','Reply-To'=>$customer?$config['recipient']:$v['email'],'MIME-Version'=>'1.0','Content-Type'=>'multipart/alternative; boundary="'.$boundary.'"','Auto-Submitted'=>'auto-generated','X-Auto-Response-Suppress'=>'All'],
        'html'=>$html,'text'=>$plain];
}

function k9_deliver_enquiry(array $values, array $config, ?callable $transport = null): array {
    $reference='K9-'.gmdate('Ymd').'-'.strtoupper(bin2hex(random_bytes(4)));
    $transport ??= static fn(array $m): bool => mail($m['to'],$m['subject'],$m['body'],$m['headers'],'-f'.$config['from']);
    $team=k9_enquiry_email($values,$config,false,$reference);
    if (!$transport($team)) return ['ok'=>false,'customerCopy'=>false,'reference'=>$reference];
    $copy=k9_enquiry_email($values,$config,true,$reference);
    return ['ok'=>true,'customerCopy'=>$transport($copy),'reference'=>$reference];
}
