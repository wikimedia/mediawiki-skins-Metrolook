Changelog
=========

0.2.3
===

### New features

Refractured code in metrolook.js and MetrolookTemplate.php

* New metrolook.search.js that holds search js that was in metrolook.js.

New options

$wgMetrolookSiteNameText

and

$wgMetrolookSiteText

To use it please do the following

$wgMetrolookSiteNameText = false;

$wgMetrolookSiteText = 'Enter text here';


### Bug Fixes

* Fixed js issue with searchbar on iPad.

* Some bug fixes.


### 0.2.2

You can now disable CollapsibleNav new configuations and how to enable and disable it.

$wgMetrolookFeatures

To enable it

$wgMetrolookFeatures'] = array( 'collapsiblenav' => array( 'global' => false, 'user' => true ), ); Users can disable in preference if they want to.

to disable it 

$wgDefaultUserOptions['skinmetrolook-collapsiblenav'] = 0; to disable it but allow users in preference to enable it. or $wgMetrolookFeatures = array( 'collapsiblenav' => array( 'global' => false, 'user' => false ), ); to disable everywhere

### 0.2.1

More improvements to mobile desgn on ipad.

### 0.2.0

First release for MediaWiki 1.21.

Includes mobile desgn.
