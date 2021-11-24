[![](https://img.shields.io/packagist/v/fritzmg/contao-newslist-extended.svg)](https://packagist.org/packages/fritzmg/contao-newslist-extended)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-newslist-extended.svg)](https://packagist.org/packages/fritzmg/contao-newslist-extended)

Contao Newslist Extended
=====================

Contao extension to add a few more features to the news list module.

### Override redirect page

This enables you to define a redirect page other than the one defined in the news archive of the news entry. This can be useful in situations where you have just one news archive, but you want to display the entries of that news archive over multiple domains or languages. This also automatically adds a canonical tag on the news reader page, if the page is not the one defined in the news archive. This feature is also available in the news _archive_ module.

### News reader module

This enables you to define a news reader module, just like in the news archive module. As usual, the defined news reader module will be displayed in place of the news list module, if the redirect page of a news entry points to the same page where the news list module is located.

_Note:_ this feature is already present in Contao 4.7 and up.

### Image size for featured news

This allows you to choose a different image size setting in the news modules for featured news (e.g. when featured news should have a larger image).
