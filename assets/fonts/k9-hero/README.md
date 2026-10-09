# K9 website fonts

The original licensed Manrope and Oswald TTF and full WOFF2 files remain here with their SIL Open Font Licenses. `font-*-website.woff2` contains the current website characters, Latin input characters, Cyrillic U+0400-U+045F, punctuation and currency symbols supported by each original font. Its internal family names are K9InterfaceWebsite and K9DisplayWebsite. Original copyright and licence metadata are preserved.

The build embeds the five small website subsets in the combined stylesheet, then in each static document. This removes font round trips and late swaps at the cost of repeating the compressed subset data across page navigations. The browser uses its normal fallback for characters outside the subsets. Regenerate and verify the subsets when adding another script or language.
