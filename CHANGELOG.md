Changelog
=========

### 3.0 beta 16

Big release with new desgn for mobile and minor improvements to desktop desgn.

Please see test branch for updates to beta 16 release once it looks like there arnt many issues and all things have been added to it, it will be released to the master branch.

Bump required mediawiki version to mediawiki 1.25 wmf 18

Mainly new features and desgn are

* Mobile desgn (This is a preview of mobile desgn please report bug in issue section and fix bugs if you know how to thanks. and please also suggest improvements to the desktop and mobile desgn.)

* Cleaned up MetrolookTemplate.php file.

* Cleaned up setting names.

* Settings that were renamed

$logo renamed to $wgLogoImage<br>$SearchBar renamed to $wgSearchBar<br>$DownArrow renamed to $wgDownArrow<br>$Line renamed to $wgLine<br>$UploadButton renamed to $wgUploadButton<br>$wgURL1 and $wgImage1 where not renamed instead there settings were changed for url you put in the url to website for image you put in the path to image or url to image.

* Settings that were removed.

 $link and  $picture

* New settings that were added

$wgBartile

$wgTile1<br>$wgTile2<br>$wgTile3<br>$wgTile4<br>$wgTile5<br>$wgTile6<br>$wgTile7<br>$wgTile8<br>$wgTile9<br>$wgTile10

Note: Please see settings section in README.md for more information on how to enable and disable it.

* Remove styles and js from the main MetrolookTemplate.php and seperated it into own files.

* More new things and changes comming soon to this release. /* This will be removed once this is ready to be published */



Revision 3 brings these fixes /* This may be removed near to time of release. */

* Fixes for ipad desgn.

* Disabled searchbar js for ipad.

* Fixes for mobile desgn.

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
