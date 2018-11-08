Introduction
============

A follow up extension using the newly introduced `sites` concept. The goal is to build a connection between
the given identifier a site has and a extension with the same name. In this way, no need for inclusion of typoscript
as it's done automatically


Preparations
============

THERE IS ABSOLUTELY NO WARRANTY associated with this script! It is completely on your OWN RISK that you run it.

 * Create a extension with the extension key that you expect to give your site as identifier
 * Create typoscript setup in `<extension-key>/Configuration/TypoScript/setup.typoscript`
 * Create typoscript constants in `<extension-key>/Configuration/TypoScript/constants.typoscript`
 * Create a new `site` with the same `identifier` as your extension key


How it works
==================

Your typoscript configuration is automatically loaded in both backend and frontend, when you browse
a part of the pagetree that your `sites` configuration is attached to.

You can browse the object browser and see the configuration - all should be there :-)

Big thanks to
==================

Big thanks to @bmack and the the `bolt` extension that has served as 100% inspiration and reuse of code

https://github.com/CMSExperts/bolt

And to the whole core team om TYPO3 for making 9.5 so great!