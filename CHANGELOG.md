Changelog
=========

1.6.0
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

Added missing js file and fix js code.

1.5.0
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

* Fix for metrolook in MediaWiki 1.23.

* Fix collapsiblenav.


### 1.4.2

You can now disable CollapsibleNav new configuations and how to enable and disable it.

$wgMetrolookFeatures

To enable it

$wgMetrolookFeatures'] = array( 'collapsiblenav' => array( 'global' => false, 'user' => true ), ); Users can disable in preference if they want to.

to disable it 

$wgDefaultUserOptions['skinmetrolook-collapsiblenav'] = 0; to disable it but allow users in preference to enable it. or $wgMetrolookFeatures = array( 'collapsiblenav' => array( 'global' => false, 'user' => false ), ); to disable everywhere

### 1.4.1

More improvements to mobile desgn on ipad.

### 1.4

Big release with new desgn for mobile and minor improvements to desktop desgn.

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


Improvements to ipad desgn.

* Bug fixes.

* Fixes for desktop desgn.



### 1.3.11

* Adding copying file for license.

* Add namemsg for skin name.

* Localisation updates.

### 1.3.10

* Added scroll for sidebar. Will automatically show if content goes out of screen. Note this will only work for people who do not have the search bar in the sidebar enabled. Reason because width is a problem and maybe in future releases it will be added to search bar in sidebar.

* Updated collapsiblenav css code.

* Updated i18n and added some more languges.

* Added bullet-circle-icon.png and svg.

* Added bullet-icon.svg.

* Updated Metrolook.php, SkinMetrolook and MetrolookTemplate.php.

* Added changelog.

* Convert space to tabs.
