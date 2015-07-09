Changelog
=========

### 4.0 alpha 5

New features
===

To set tiles now please do the following

go to MediaWiki:metrolook-tiles


Then add

for example

Doint forget to add * in front of the code. It wont work without doing * in fornt of code.

* URL to the site|alternative text|image URL

* http://example.com|Example name|http://example.com/example.png

You can now set different tiles for different languges.

(for french)

For example MediaWiki:metrolook-tiles/fr


Remove settings

$wgMetrolookTile1-10

### 4.0 alpha 4

New features
===

Refractured code in metrolook.js and MetrolookTemplate.php

* New metrolook.search.js that holds search js that was in metrolook.js.

New options

$wgMetrolookSiteNameText

and

$wgMetrolookSiteText

To use it please do the following

$wgMetrolookSiteNameText = false;

$wgMetrolookSiteText = 'Enter text here';


Bug Fixes
===

* Fixed js issue with searchbar on iPad.

* Some bug fixes.


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
