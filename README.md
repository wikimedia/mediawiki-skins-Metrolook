Metrolook
=========

A skin for MediaWiki

The author of the skin is http://www.pidgi.net/wiki/Main_Page


Compatible with MediaWiki 1.24+.

If you would like compatibility with mediawiki 1.26, 1.25, 1.23, 1.22 or 1.21 please visit 

1.26

https://github.com/paladox/Metrolook/tree/master

1.25

https://github.com/paladox/Metrolook/tree/REL1_25

1.23

https://github.com/paladox/Metrolook/tree/REL1_23

1.22

https://github.com/paladox/Metrolook/tree/REL1_22

1.21

https://github.com/paladox/Metrolook/tree/REL1_21

Please be aware that there are issues in the codes if you see any could you point it out it would help. and there are things like logos already set sorry i will put a setting there.

A working demo of the skin is available at http://www.pidgi.net/metrolooktest/index.php/Main_Page . This is currently using MediaWiki 1.25wmf18 and a snapshot of the test branch of the skin.

## Installation

Download and save in skins/ folder 

Add this to LocalSettings.php

```php
require_once "$IP/skins/Metrolook/Metrolook.php";
```

## Settings

1.24 only

|Setting|Default|To Enable|To Disable|
|-------|-------|---------|----------|
|wgMetrolookLogo| `false` | `$wgMetrolookLogo = true;`| `$wgMetrolookLogo = false;`|
|wgMetrolookSiteName| `true` | `$wgMetrolookSiteName = true;`| `$wgMetrolookSiteName = false;`|
|wgMetrolookSiteNameText| `true` | `$wgMetrolookSiteNameText = true;`| `$wgMetrolookSiteNameText = false;`|
|wgMetrolookSiteText| `There is none` | `$wgMetrolookSiteNameText = false;` then do `$wgMetrolookSiteText = 'Enter text here';`| `$wgMetrolookSiteNameText = true;`|
|wgMetrolookSearchBar| `true` | `$wgMetrolookSearchBar = true;`| `$wgMetrolookSearchBar = false;`|
|wgMetrolookDownArrow| `true` | `$wgMetrolookDownArrow = true;`| `$wgMetrolookDownArrow = false;`|
|wgMetrolookLine| `true` | `$wgMetrolookLine = true;`| `$wgMetrolookLine = false;`|
|wgMetrolookUploadButton| `true` | `$wgMetrolookUploadButton = true;`| `$wgMetrolookUploadButton = false;`|
|wgMetrolookMobile| `true` | `$wgMetrolookMobile = true;`| `$wgMetrolookMobile = false;`|
|wgMetrolookBartile| `true` | `$wgMetrolookBartile = true;`| `$wgMetrolookBartile = false;`|
|`$wgMetrolookTileN`<br>Where `N` is between 1 to 4. | `true` | `$wgMetrolookTile1 = true;`| `$wgMetrolookTile1 = false;` |
|`$wgMetrolookTileN`<br>Where `N` is between 5 to 10. | | `$wgMetrolookTile5 = true;`| `$wgMetrolookTile5 = false;` |
|`$wgMetrolookURLN`, `$wgMetrolookImageN`<br>Where `N` is between 1 to 6. |  | `$wgMetrolookURL1 = link of website;`<br>`$wgMetrolookImage1 = image link;`| |
|wgMetrolookFeatures| `$wgMetrolookFeatures'] = array( 'collapsiblenav' => array( 'global' => false, 'user' => true ), );` | default is enabled | `$wgDefaultUserOptions['skinmetrolook-collapsiblenav'] = 0;` to disable it but allow users in preference to enable it. or `$wgMetrolookFeatures = array( 'collapsiblenav' => array( 'global' => false, 'user' => false ), );` to disable everywhere|

$wgBartile is now used to disable the default tiles or enable them so you can have the default tiles or set your self one.

$link and $picture were removed in favour of using $wgMetrolookBartile and $wgMetrolookURL1 and $wgMetrolookImage1

$logo was removed in favour of $wgMetrolookLogo and $wgMetrolookSiteName.


Note: Tile 5 to 10 is for when you disable bartile.

Note: Image setting should be set like this for example $wgMetrolookImage1 = file/to/image or can be set like http://example.com/image.png;

You can shorten youre site name for the top bar with $wgMetrolookSiteText. To set it please enable $wgMetrolookSiteNameText by doing $wgMetrolookSiteNameText = false then set this $wgMetrolookSiteText = 'Enter text here';


## Mobile desgn

Mobile desgn now included in the latest release for MediaWiki 1.21+. Also to get mobile desgn please download Metrolook release 0.2.0, 0.3.10, 1.4, 2.5 or 3.0 beta 16 or higher please.

And please report feedback in the issues tab. And if you could help fix the problem and or improve the desgn please open and pull task.


## Customizing top bar color

To customise top bar colour ether add it to theme.css which is in metrolook skin folder or MediaWiki:Metrolook.css from web browser.

and all you need to do is edit background-colour and the top bar should change colour but please remember there is also hover which is when you hover it goes a different colour.

```css

/* Add your custom theme overrides here */

/* Top Bar colour and hover colour start */

@import "mediawiki.mixins";

#mw-page-base {
	height: 2.5em;
	background-color: dodgerBlue;
	background-position: bottom left;
	background-repeat: repeat-x;
	/* This image is only a fallback (for IE 6-9), so we do not @embed it. */
	background-image: url('images/page-fade.png');
	min-width: auto;
}

@media (max-width:768px) {
#mw-page-base {
	height: 2.5em;
	background-color: dodgerBlue;
	background-position: bottom left;
	background-repeat: repeat-x;
	/* This image is only a fallback (for IE 6-9), so we do not @embed it. */
	background-image: url('images/page-fade.png');
	min-width: 24em;
}
}

@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
#mw-page-base {
	height: 2.5em;
	background-color: dodgerBlue;
	background-position: bottom left;
	background-repeat: repeat-x;
	/* This image is only a fallback (for IE 6-9), so we do not @embed it. */
	background-image: url('images/page-fade.png');
	min-width: 25em;
}
}

@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {
#mw-page-base {
	height: 2.5em;
	background-color: dodgerBlue;
	background-position: bottom left;
	background-repeat: repeat-x;
	/* This image is only a fallback (for IE 6-9), so we do not @embed it. */
	background-image: url('images/page-fade.png');
	min-width: 64.7em;
}
}

div.vectorTabs a:hover {
	background-color: blue;
}

div.onhoverbg:hover {
	background-color: blue;
}

img.downarrow:hover{
	background-color: blue;
}

div.vectorMenu:hover h5 a {
	background-color: blue;
}

div.vectorMenu h5 a {
	display: inline-block;
	width: 24px;
	height: 2em;
	background-color: dodgerBlue; 
	.background-image-svg('images/arrow-down-icon.svg', 'images/arrow-down-icon.png');
	background-position: 50% 50%; 
	background-repeat: no-repeat;
	.transition(background-position 250ms);
}

div.vectorMenu:hover {
	background-color: blue;
}

div.vectorMenu ul {
	border: solid 2px dodgerBlue;
	border-top: none;

}

@media (max-width: 768px) {
#hamburgerIcon:hover {
	background-color: blue;
}

img.editbutton:hover {
	background-color: blue;
}

div.actionmenu ul {
	border-top: solid 2px dodgerBlue;
}

#left-navigation {
	background-color: dodgerBlue;
}
}
/* To change bullet icon to a circle */

ul {
	list-style-type: disc;
	.list-style-image-svg('images/bullet-circle-icon.png');
}

/* Top Bar colour and hover colour end */
```

## Known Issues

* Sometimes clicking of bartitle on mobile, it wont let you click off sometimes.

## Comming soon

Note plans may change.

## Version

4.x.x requires MediaWiki 1.26.

3.x.x requires MediaWiki 1.25.

2.x.x requires MediaWiki 1.24.

1.x.x requires MediaWiki 1.23.

0.3.x requires MediaWiki 1.22.

0.2.x requires MediaWiki 1.21.


## Removed things

Please do not remove this section. it is for things that have been removed and used for if something goes wrong and the problem was because it was removed then they can use the things here.

```html
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:700' defer="defer" rel='stylesheet' type='text/css'>
<meta name="msapplication-TileImage" content="http://www.pidgi.net/new/public/images/pidgiwiki.png"/>
<meta name="msapplication-TileColor" content="#BE0027"/>
```
