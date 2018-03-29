<?php
namespace Acme\DemoSite\Hooks;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class TsTemplateHook
{

    /**
    * Hooks into TemplateService to add a virtual TS template
     *
    * @param array $parameters
    * @param \TYPO3\CMS\Core\TypoScript\TemplateService $parentObject
    */
    public function addTypoScriptTemplate($parameters, $parentObject)
    {

        // Disable the inclusion of ext_typoscript_setup.txt of all extensions
        $parameters['processExtensionStatics'] = false;

        // Read any constants / setup that may have been set via an actual
        // sys_template record. Append those values later to our <INCLUDE_TYPOSCRIPT>
        // These values *override* values that may be set via the extension
        // TypoScript!
        $constantOverrides = $parentObject->constants;
        $setupOverrides = $parentObject->config;

        // Add a custom, fake 'sys_template' record
        $row = [
            'uid' => 'demosite',
            'constants' =>
                '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:demosite/Configuration/TypoScript/constants.ts">' . LF
                . implode(LF, $constantOverrides) . LF,
            'config' => '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:demosite/Configuration/TypoScript/setup.ts">' . LF
                . implode(LF, $setupOverrides) . LF,
            'clear' => 3,
            'nextlevel' => 0,
            'static_file_mode' => 1,
            'title' => 'Virtual TS root template'
        ];
        $parentObject->processTemplate(
            $row,
            'sys_' . $row['uid'],
            $parameters['absoluteRootLine'][0]['uid'],
            'sys_' . $row['uid']
        );
        $parentObject->rootId = $parameters['absoluteRootLine'][0]['uid'];
        $parentObject->rootLine[] = $parameters['absoluteRootLine'][0];
    }
}
