Changelog
=========

### 4.0 alpha 3

You can now disable CollapsibleNav new configuations and how to enable and disable it.

$wgMetrolookFeatures

To enable it

$wgMetrolookFeatures'] = array( 'collapsiblenav' => array( 'global' => false, 'user' => true ), ); Users can disable in preference if they want to.

to disable it 

$wgDefaultUserOptions['skinmetrolook-collapsiblenav'] = 0; to disable it but allow users in preference to enable it. or $wgMetrolookFeatures = array( 'collapsiblenav' => array( 'global' => false, 'user' => false ), ); to disable everywhere

### 4.0 alpha 2

More improvements to mobile desgn on ipad.

Please note i18n shim are no longer avalible in this release.

Skin.json is now used.

### 4.0 alpha 1

First release of Metrolook to support MediaWiki 1.26.

Includes improvements to mobile desgn and desktop desgn.

Includes skin.json. New extension/skin registration in mediawiki starting from mediawiki 1.25. Please read the README.md on how to do it. when using extension.json please remove require_once from localsettings.php for metrolook.
