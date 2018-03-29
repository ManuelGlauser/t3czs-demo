<?php
namespace Acme\DemoSite\Command;

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

use Helhum\Typo3Console\Mvc\Controller\CommandController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Commands for (un)restricting backend access.
 */
class ConfigurationCommandController extends CommandController
{
    const PACKAGE_KEY = 'demosite';
    const ADDITIONAL_CONFIGURATION_FILE_LOCATION = 'Configuration/SiteConfiguration.php';

    /**
     * Includes the site package's SiteConfiguration.php into the local 'AdditionalConfiguration.php'
     */
    public function includeSiteConfigurationCommand()
    {
        /** @var \TYPO3\CMS\Core\Configuration\ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ConfigurationManager::class);
        
        $configurationFilePath = realpath(
            sprintf(
                '%s/ext/%s/%s',
                PATH_typo3conf,
                self::PACKAGE_KEY,
                self::ADDITIONAL_CONFIGURATION_FILE_LOCATION
            )
        );
        $inclusionLine = sprintf('include \'%s\';', $configurationFilePath);
        $additionalConfigurationPath = $configurationManager->getAdditionalConfigurationFileLocation();

        // Output processing info
        $this->outputFormatted(
            PHP_EOL
            . ' Checking, whether %s/%s'
            . PHP_EOL
            . ' is included in this installation yet... '
            . PHP_EOL,
            [
                self::PACKAGE_KEY,
                self::ADDITIONAL_CONFIGURATION_FILE_LOCATION
            ]
        );
        sleep(2);

        // Check current status (whether the AdditionalConfiguration.php exists and
        // whether it already includes the AdditionalConfiguration.php of the site package.
        $currentConfigurationInclusionStatus = self::getInclusionStatus(
            $additionalConfigurationPath,
            $inclusionLine
        );

        // Possibly create / extend AdditionalConfiguration.php
        switch ($currentConfigurationInclusionStatus) {
            case 'nonexistent':
                $this->outputFormatted(
                    ' => Local %s file doesn\'t exist! Creating new,' . PHP_EOL . ' including the %s package!' . PHP_EOL,
                    [
                        $additionalConfigurationPath,
                        self::ADDITIONAL_CONFIGURATION_FILE_LOCATION
                    ]
                );
                self::extendAdditionalConfiguration($additionalConfigurationPath, $inclusionLine);
                break;

            case 'notincluded':
                $this->output(' => Is not yet included! Extending local ' . PHP_EOL . ' AdditionalConfiguration.php... ');
                self::extendAdditionalConfiguration($additionalConfigurationPath, $inclusionLine);
                break;

            case 'ok':
                $this->outputFormatted(
                    ' => OK! %s/%s' . PHP_EOL . ' seems included in the local AdditionalConfiguration.php.' . PHP_EOL,
                    [
                        self::PACKAGE_KEY,
                        self::ADDITIONAL_CONFIGURATION_FILE_LOCATION
                    ]
                );
                break;

            default:
                throw new \Exception(
                    sprintf(
                        'No valid status gotten (%s) from \'getInclusionStatus\'!',
                        $currentConfigurationInclusionStatus
                    ),
                    1501677721
                );
        }

        $this->output(PHP_EOL);
    }

    /**
     * Checks, whether 'AdditionalConfiguration.php' exists and includes
     * the SiteConfiguration.php yet
     *
     * @param string $filePath
     * @param string $inclusionCode
     * @return string
     */
    protected static function getInclusionStatus($filePath, $inclusionCode)
    {
        if (!file_exists($filePath)) {
            return 'nonexistent';
        }

        $currentContents = file_get_contents($filePath);
        $additionalConfigurationIsIncluded = strpos($currentContents, $inclusionCode);

        if ($additionalConfigurationIsIncluded) {
            return 'ok';
        }
        return 'notincluded';
    }

    /**
     * Extends and/or creates typo3conf/AdditionalConfiguration.php
     *
     * @param $filePath
     * @param $code
     * @return bool|int
     */
    protected static function extendAdditionalConfiguration($filePath, $code)
    {
        $code = sprintf(
                PHP_EOL . '// Written by %s on %s ' . PHP_EOL . '%s',
                __FILE__,
                date('Y-m-d H:i:s'),
                $code
            );

        if (!file_exists($filePath)) {
            $code = '<?php' . $code;
        }
        $fileWritten = file_put_contents($filePath, $code, FILE_APPEND);

        return $fileWritten;
    }
}
