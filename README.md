Demo TYPO3 site setup
--

First install
-------------
`composer install --no-scripts`

`composer dump-autoload`

`./vendor/bin/typo3cms install:setup`

`composer run post-install-cmd`


Subsequent updates/installs
---------------------------
`composer update` or `composer install`

