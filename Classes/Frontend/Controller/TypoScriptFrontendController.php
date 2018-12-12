<?php

namespace Meteko\Autosite\Frontend\Controller;

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptFrontendController extends \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController {


	/**
	 * @inheritdoc
	 */
	public function getPagesTSconfig()
	{
		/** @var SiteFinder $siteFinder */
		$siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
		foreach ($this->rootLine as $key => $value) {
			$site = $siteFinder->getSiteByPageId($value['uid']);

			if (!($site instanceof Site)) {
				continue;
			}

			/** @var PackageManager $packageManager */
			$packageManager = GeneralUtility::makeInstance(PackageManager::class);
			/** @var Package $package */
			$package = $packageManager->getPackage($site->getIdentifier());
			$tsConfigFile = $package->getPackagePath() . 'Configuration/PageTS/main.tsconfig';
			if (file_exists($tsConfigFile)) {
				$fileContents = @file_get_contents($tsConfigFile);
				$this->rootLine[$key]['TSconfig'] .= LF . $fileContents;
			}
		}

		return parent::getPagesTSconfig();
	}
}
