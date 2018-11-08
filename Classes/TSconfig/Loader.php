<?php
namespace Meteko\Autosite\TSconfig;
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
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Package\Exception\UnknownPackageException;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * Dynamically loads PageTSconfig from an extension. Is added AFTER a site's
 * The File needs to be put into
 * EXT:site_mysite/Configuration/PageTS/main.tsconfig
 */
class Loader
{
	/**
	 * Adds TSconfig
	 *
	 * @param array $TSdataArray
	 * @param int $id
	 * @param array $rootLine
	 * @param array $returnPartArray
	 * @return array
	 */
	public function addSiteConfiguration($TSdataArray, $id, $rootLine, $returnPartArray)
	{
		/** @var SiteFinder $siteFinder */
		$siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
		foreach ($rootLine as $level => $pageRecord) {
			try {
				$site = $siteFinder->getSiteByPageId($pageRecord['uid']);
				/** @var PackageManager $packageManager */
				$packageManager = GeneralUtility::makeInstance(PackageManager::class);
				/** @var Package $package */
				$package = $packageManager->getPackage($site->getIdentifier());
				$tsConfigFile = $package->getPackagePath() . 'Configuration/PageTS/main.tsconfig';
				if (file_exists($tsConfigFile)) {
					$fileContents = @file_get_contents($tsConfigFile);
					$TSdataArray['uid_' . $pageRecord['uid']] .= LF . $fileContents;
				}
			} catch (SiteNotFoundException $siteException) {
			} catch (UnknownPackageException $packageException) {
			}
		}
		return [$TSdataArray, $id, $rootLine, $returnPartArray];
	}
}
