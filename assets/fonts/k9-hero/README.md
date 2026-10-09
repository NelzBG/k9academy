# K9 website fonts

The original licensed Manrope and Oswald TTF and full WOFF2 files remain here with their SIL Open Font Licenses. `font-*-website.woff2` contains the current website characters, Latin input characters, Cyrillic U+0400-U+045F, punctuation and currency symbols supported by each original font. Its internal family names are K9InterfaceWebsite and K9DisplayWebsite. Original copyright and licence metadata are preserved.

The static head preloads all five small website subsets with CORS enabled. They remain separately cacheable WOFF2 assets, so bilingual navigation reuses the font files instead of repeating their binary data in each HTML document. Layout dimensions remain reserved in CSS. The browser uses its normal fallback for characters outside the subsets. Regenerate and verify the subsets when adding another script or language.
