<?php

$setUpTheDemoSite = function ($_EXTKEY) {

    // Include page TSconfig
    // Include constants

    /******************************
     * Register TypoScript hook
     * for automatic inclusion
     * of our setup & constants.
     ******************************/
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Core/TypoScript/TemplateService']['runThroughTemplatesPostProcessing'][1501684692] =
        \Acme\DemoSite\Hooks\TsTemplateHook::class . '->addTypoScriptTemplate';

    /******************************
     * Register custom commands
     ******************************/
    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] =
            \Acme\DemoSite\Command\ConfigurationCommandController::class;
    }
};

$setUpTheDemoSite($_EXTKEY);
unset($setUpTheDemoSite);
