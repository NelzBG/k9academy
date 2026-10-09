<?php
$editorial = json_decode(file_get_contents(__DIR__ . '/editorial.json'), true, 512, JSON_THROW_ON_ERROR);
$products = json_decode(file_get_contents(__DIR__ . '/ebooks.json'), true, 512, JSON_THROW_ON_ERROR);
foreach (['bg', 'en'] as $locale) {
    $strings[$locale]['nav_puppy'] = $locale === 'bg' ? 'Кученца' : 'Puppies';
    $strings[$locale]['nav_breeds'] = $locale === 'bg' ? 'Избор на порода' : 'Choose a breed';
    $strings[$locale]['nav_blog'] = $locale === 'bg' ? 'Блог' : 'Blog';
    $strings[$locale]['cookie_copy'] = $locale === 'bg'
        ? 'Изборът ви се запазва на това устройство. Видеата от YouTube се зареждат само с ваше разрешение.'
        : 'Your choice is saved on this device. YouTube videos load only after you allow them.';
}
$navItems = ['home'=>['index.php','nav_home'], 'services'=>['services.php','nav_services'], 'puppy'=>['puppy.php','nav_puppy'], 'breeds'=>['breeds.php','nav_breeds'], 'training'=>['training.php','nav_training'], 'blog'=>['blog.php','nav_blog'], 'shop'=>['webshop.php','nav_shop'], 'about'=>['aboutus.php','nav_about'], 'contact'=>['contact.php','nav_contact']];
foreach (['puppy','breeds','blog','ebooks'] as $key) foreach (['bg','en'] as $locale) {
    $pageMeta[$key][$locale] = [$editorial['pages'][$key][$locale]['title'] . ' | K9 Academy', $editorial['pages'][$key][$locale]['intro']];
}
if (isset($articleSlug)) {
    $article = $editorial['articles'][$articleSlug];
    $pageMeta['article'][$lang] = [$article[$lang]['title'] . ' | K9 Academy', $article[$lang]['intro']];
    $extraSchema = ['@context'=>'https://schema.org', '@type'=>'BlogPosting', 'headline'=>$article[$lang]['title'], 'description'=>$article[$lang]['intro'], 'inLanguage'=>$lang, 'datePublished'=>'2026-10-09', 'dateModified'=>'2026-10-09', 'mainEntityOfPage'=>$production['origin'].k9_url($currentFile), 'author'=>['@type'=>'Organization','name'=>'K9 Academy','url'=>$production['origin'].'/'], 'publisher'=>['@type'=>'Organization','name'=>'K9 Academy','logo'=>['@type'=>'ImageObject','url'=>$production['origin'].'/assets/images/brand-20260905/logo.webp']], 'image'=>$production['origin'].'/assets/images/training-library/training-001-1280.webp'];
}
function k9_editorial_html(string $html): string {
    $links = ['puppy'=>'puppy.php','breeds'=>'breeds.php','services'=>'services.php','training'=>'training.php','contact'=>'contact.php','blog'=>'blog.php','ebooks'=>'ebooks.php'];
    foreach ($links as $key=>$file) $html = str_replace('{{'.$key.'}}', k9_url($file), $html);
    $html = preg_replace_callback('/\{\{([a-z]+)\|([^}]+)\}\}/u', static function($match) use ($links) {
        return isset($links[$match[1]]) ? '<a href="'.k9e(k9_url($links[$match[1]])).'">'.k9e($match[2]).'</a>' : $match[0];
    }, $html);
    return $html;
}
