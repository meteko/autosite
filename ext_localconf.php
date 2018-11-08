<?php
defined('TYPO3_MODE') or die();


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Core/TypoScript/TemplateService']['runThroughTemplatesPostProcessing']['autosite'] = \Meteko\Autosite\TypoScript\Loader::class . '->addSiteConfiguration';
