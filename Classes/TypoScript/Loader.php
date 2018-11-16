<?php
namespace Meteko\Autosite\TypoScript;

/**
 * This file is part of the "autosite" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Package\Exception\UnknownPackageException;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Loader
{
	/**
	 *  $hookParameters = [
	 *      'extensionStaticsProcessed' => &$this->extensionStaticsProcessed,
	 *      'isDefaultTypoScriptAdded'  => &$this->isDefaultTypoScriptAdded,
	 *      'absoluteRootLine' => &$this->absoluteRootLine,
	 *      'rootLine'         => &$this->rootLine,
	 *      'startTemplateUid' => $start_template_uid,
	 *  ];
	 * @param array $hookParameters
	 * @param TemplateService $templateService
	 * @return void
	 */
	public function addSiteConfiguration(&$hookParameters, $templateService)
	{
		/** @var \TYPO3\CMS\Core\Http\ServerRequest $request */
		$request = $GLOBALS['TYPO3_REQUEST'];
		/** @var Site $site */
		$site = $request->getAttribute('site');

		if (!($site instanceof Site)) {
			return;
		}

		// let's copy the rootline value, as $templateService->processTemplate() might reset it
		$rootLine = $hookParameters['rootLine'];
		if (!is_array($rootLine) || empty($rootLine)) {
			return;
		}
		/** @var PackageManager $packageManager */
		$packageManager = GeneralUtility::makeInstance(PackageManager::class);
		foreach ($rootLine as $level => $pageRecord) {
			try {
				$package = $packageManager->getPackage($site->getIdentifier());
				$constantsFile = $package->getPackagePath() . 'Configuration/TypoScript/constants.typoscript';
				$setupFile = $package->getPackagePath() . 'Configuration/TypoScript/setup.typoscript';

				if (file_exists($constantsFile)) {
					$constants = (string)@file_get_contents($constantsFile);
				} else {
					$constants = '';
				}

				if (file_exists($setupFile)) {
					$setup = (string)@file_get_contents($setupFile);
				} else {
					$setup = '';
				}

				// pre-process the lines of the constants and setup and check for "@" syntax
				// @import
				// @sitetitle
				// @clear
				// are the currently allowed syntax (must be on the head of each line)
				$hasRootTemplate = (bool)$this->getRootId($templateService);
				$fakeRow = [
					'config' => $setup,
					'constants' => $constants,
					'nextLevel' => 0,
					'static_file_mode' => 1,
					'tstamp' => filemtime($setupFile),
					'uid' => 'autosite_' . $package->getPackageKey(),
					'title' => $package->getPackageKey(),
					// make this the root template
					'root' => !$hasRootTemplate
				];
				$templateService->processTemplate($fakeRow, 'autosite_' . $package->getPackageKey(), (int)$pageRecord['uid'], 'autosite_' . $package->getPackageKey());
				if (!$hasRootTemplate) {
					// $templateService->processTemplate() adds the constants and setup info
					// to the very end however, we like to add ours add root template
					array_pop($templateService->constants);
					array_unshift($templateService->constants, $constants);
					array_pop($templateService->config);
					array_unshift($templateService->config, $setup);
					// when having the 'root' flag, set $processTemplate resets the rootline -> we don't want that.
					$hookParameters['rootLine'] = $rootLine;
				}
			} catch (UnknownPackageException $exception) {
			}

		}
	}
	/**
	 * $templateService->rootId is protected in TYPO3 v9, so it has to be evaluated differently.
	 *
	 * @param TemplateService $templateService
	 * @return int
	 */
	protected function getRootId(TemplateService $templateService)
	{
		if (method_exists($templateService, 'getRootId')) {
			return $templateService->getRootId();
		}
		return $templateService->rootId;
	}
}
