<?php
$EM_CONF[$_EXTKEY] = [
  'title' => 'Autosite',
  'description' => 'Automatically load TypoScript and PageTS to a pagetree, based on your "sites" configuration. Give your site the same identifier as a extension and configuration is automatically loaded.',
  'category' => 'site',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'modify_tables' => '',
  'clearCacheOnLoad' => 0,
  'author' => 'Soren Malling',
  'author_email' => 'soren@meteko.dk',
  'author_company' => 'Meteko Aps',
  'version' => '1.0.0',
  'constraints' => [
    'depends' => [
      'typo3' => '9.5.0-9.5.99',
    ],
    'conflicts' => [
    ],
    'suggests' => [
    ],
  ],
];
