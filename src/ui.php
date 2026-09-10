<?php
/** Shared, custom image-generated artwork. No third-party icon font or icon set. */
function k9_icon(string $name = 'arrow', string $class = ''): string
{
    $files = ['arrow' => 'arrow.webp', 'menu' => 'menu.webp', 'close' => 'close.webp', 'sun' => 'sun.webp', 'moon' => 'moon.webp', 'logo' => 'logo.webp'];
    if (!isset($files[$name])) return '';
    return '<img class="k9-icon ' . k9e($class) . '" src="assets/images/brand-20260905/' . $files[$name] . '" width="32" height="32" alt="" aria-hidden="true" decoding="async">';
}
