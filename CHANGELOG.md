Changelog
=========

3.2.0
===

### New features

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

Renamed settings

VectorUseSimpleSearch -> MetrolookUseSimpleSearch

VectorUseIconWatch -> MetrolookUseIconWatch


Remove settings

$wgMetrolookTile1-10

### 3.1

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

* Fix collapsibleNav.

* Some bug fixes.

### 3.0

First stable release for MediaWiki 1.25

You can now disable CollapsibleNav new configuations and how to enable and disable it.

$wgMetrolookFeatures

To enable it

$wgMetrolookFeatures'] = array( 'collapsiblenav' => array( 'global' => false, 'user' => true ), ); Users can disable in preference if they want to.

to disable it 

$wgDefaultUserOptions['skinmetrolook-collapsiblenav'] = 0; to disable it but allow users in preference to enable it. or $wgMetrolookFeatures = array( 'collapsiblenav' => array( 'global' => false, 'user' => false ), ); to disable everywhere


### 3.0 rc 2

More improvements to mobile desgn on ipad.

Please note i18n shim are no longer avalible in this release.

Skin.json is now used.

### 3.0 rc 1

Improvement to mobile and desktop desgn.

Includes skin.json. Requires MediaWiki 1.25+. Please read README.md on how to use new extension registration

### 3.0 beta 16

Big release with new desgn for mobile and minor improvements to desktop desgn.


Bump required mediawiki version to mediawiki 1.25 wmf 18

Mainly new features and desgn are

* Mobile desgn

* Full logo support.

* Cleaned up MetrolookTemplate.php file.

* Cleaned up setting names.

* Settings that were renamed

$logo renamed to $wgMetrolookLogo<br>$SearchBar renamed to $wgMetrolookSearchBar<br>$DownArrow renamed to $wgMetrolookDownArrow<br>$Line renamed to $wgMetrolookLine<br>$UploadButton renamed to $wgMetrolookUploadButton<br>$wgURL1 and $wgImage1 where renamed to $wgMetrolookURL1 and $wgMetrolookImage1 and there settings were changed. for url you put in the url to website for image you put in the path to image or url to image.

* Settings that were removed.

 $link and  $picture

* New settings that were added

$wgMetrolookBartile

$wgMetrolookTile1<br>$wgMetrolookTile2<br>$wgMetrolookTile3<br>$wgMetrolookTile4<br>$wgMetrolookTile5<br>$wgMetrolookTile6<br>$wgMetrolookTile7<br>$wgMetrolookTile8<br>$wgMetrolookTile9<br>$wgMetrolookTile10

$wgMetrolookSiteName

$wgMetrolookMobile



Note: Please see settings section in README.md for more information on how to enable and disable it.

* Remove styles and js from the main MetrolookTemplate.php and seperated it into own files.

* More new things and changes comming soon to this release. /* This will be removed once this is ready to be published */


Improvements to ipad desgn.

* Bug fixes.

* Fixes for desktop desgn.



### 3.0 beta 15

* Adding copying file for license.

* Adding namemsg for skin name.

* Localisation updates.

* Update license to GPL-2.0+ in Metrolook.php.


### 3.0 beta 14

* Added scroll for sidebar. Will automatically show if content goes out of screen. Note this will only work for people who do not have the search bar in the sidebar enabled. Reason because width is a problem and maybe in future releases it will be added to search bar in sidebar.

* Updated collapsiblenav css code.

* Updated i18n and added some more languges.

* Added bullet-circle-icon.png and svg.

* Added bullet-icon.svg.

* Updated Metrolook.php, SkinMetrolook and MetrolookTemplate.php.

* Updated SkinStyles/jquery.ui.

* Added changelog.

* Convert space to tabs.
