Demo TYPO3 site setup
--

First install
-------------
`composer run preCoreInstallCmd --timeout 900`

`./vendor/bin/typo3cms install:setup`

`composer run postCoreInstallCmd`

Subsequent installs / updates
-----------------------------
`composer install` / `composer update`