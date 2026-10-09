<?php
$relatedCopy = [
    'home' => [
        'bg' => 'Планирате първите седмици с кученце? Започнете с {{puppy|обучение на кученца}}. Ако още избирате спътник, прочетете {{breeds|как да изберете подходяща порода}}. Разгледайте {{services|направленията за обучение}} и нашите {{blog|практични съвети за кучета}}.',
        'en' => 'Planning the first weeks with a puppy? Start with {{puppy|puppy training}}. If you are still choosing a companion, explore {{breeds|choosing the right dog breed}}. Browse our {{services|dog training programmes}} and {{blog|practical dog training articles}}.'
    ],
    'about' => [
        'bg' => 'Нашият подход започва с вашето ежедневие. Разгледайте {{training|обучението на хора и кучета}}, подгответе {{puppy|първите стъпки с кученце}} и прочетете {{blog|съветите за спокойно общуване}}. За индивидуална посока използвайте {{contact|контактите на K9 Academy}}.',
        'en' => 'Our approach starts with your daily life. Explore {{training|training for people and dogs}}, prepare for {{puppy|your puppy’s first steps}} and read our {{blog|guides to calm communication}}. For individual advice, use {{contact|K9 Academy’s contact options}}.'
    ],
    'services' => [
        'bg' => 'За младо куче разгледайте {{puppy|основите на обучението на кученца}}. За бъдещ собственик е полезна {{breeds|консултацията за избор на порода}}. Нашият {{blog|блог за поведение и обучение}} помага за подготовката, а {{contact|разговорът с треньор}} уточнява следващата стъпка.',
        'en' => 'For a young dog, explore {{puppy|the foundations of puppy training}}. Future owners can start with {{breeds|guidance on choosing a breed}}. Our {{blog|behaviour and training blog}} supports your preparation, while {{contact|a conversation with a trainer}} defines the next step.'
    ],
    'training' => [
        'bg' => 'Приложете идеите постепенно: започнете с {{puppy|кратки упражнения за кученца}}, разгледайте {{services|индивидуалните обучителни направления}} и прочетете {{blog|подробните ръководства за ежедневието}}. Ако имате нужда от помощ, {{contact|свържете се с K9 Academy}}.',
        'en' => 'Apply the ideas gradually: start with {{puppy|short exercises for puppies}}, explore {{services|individual training programmes}} and read our {{blog|detailed everyday training guides}}. For help with your own dog, {{contact|contact K9 Academy}}.'
    ],
    'shop' => [
        'bg' => 'Добрата екипировка подкрепя ясен план. Разгледайте {{puppy|подготовката за кученце}}, {{training|нашия подход към обучението}} и {{blog|практичните статии за разходки}}. За упражнения у дома вижте {{ebooks|дигиталните ръководства на K9 Academy}}.',
        'en' => 'Good equipment supports a clear plan. Explore {{puppy|preparing for a puppy}}, {{training|our training approach}} and {{blog|practical articles about walks}}. For exercises at home, see {{ebooks|K9 Academy’s digital guides}}.'
    ],
    'contact' => [
        'bg' => 'Подгответе разговора с {{puppy|плана за обучение на кученце}} или с {{breeds|въпросите за избор на порода}}. Прегледайте {{services|обучителните услуги}} и {{blog|съветите за поведение}}, за да ни разкажете какво искате да постигнете.',
        'en' => 'Prepare for our conversation with {{puppy|the puppy training plan}} or {{breeds|questions about choosing a breed}}. Review {{services|our training services}} and {{blog|behaviour guides}} to help explain what you would like to achieve.'
    ],
    'privacy' => [
        'bg' => 'За въпроси относно данните използвайте {{contact|контактите ни}}. Вижте {{ebooks|информацията за дигиталните покупки}}, {{services|как се уговаря обучение}} и {{training|образователното съдържание на сайта}}.',
        'en' => 'For questions about your data, use {{contact|our contact options}}. Review {{ebooks|digital purchase information}}, {{services|how training is arranged}} and {{training|the website’s educational content}}.'
    ],
    'terms' => [
        'bg' => 'Преди да изберете {{ebooks|дигитално ръководство}}, прочетете описанието му. За {{services|обучителни услуги}} условията се уточняват лично. Разгледайте {{training|нашия подход}} и използвайте {{contact|контактите ни}} за конкретни въпроси.',
        'en' => 'Before choosing {{ebooks|a digital guide}}, read its description. Terms for {{services|training services}} are agreed individually. Explore {{training|our approach}} and use {{contact|our contact options}} for specific questions.'
    ]
];
?>
<aside class="learning-links k9-textured" aria-label="<?= $lang === 'bg' ? 'Свързани ръководства' : 'Related guidance' ?>"><div class="site-container"><p class="eyebrow"><?= $lang === 'bg' ? 'Следваща стъпка' : 'Next step' ?></p><p><?= k9_editorial_html($relatedCopy[$pageKey][$lang]) ?></p></div></aside>
