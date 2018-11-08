<?php
defined('TYPO3_MODE') or die();


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Core/TypoScript/TemplateService']['runThroughTemplatesPostProcessing']['autosite'] = \Meteko\Autosite\TypoScript\Loader::class . '->addSiteConfiguration';


\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
	\TYPO3\CMS\Backend\Utility\BackendUtility::class,
	'getPagesTSconfigPreInclude',
	Meteko\Autosite\TSconfig\Loader::class,
	'addSiteConfiguration'
);

