<?php
/**
 * Metrolook - Metro look for website.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

$GLOBALS['wgExtensionCredits']['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Metrolook',
	'descriptionmsg' => 'metrolook-desc',
	'version' => '0.3.6',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'paladox2015', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$GLOBALS['wgAutoloadClasses']['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$GLOBALS['wgAutoloadClasses']['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

$GLOBALS['wgExtensionMessagesFiles']['MetrolookTemplate'] = __DIR__.'/Metrolook.i18n.php';

// Register skin
$GLOBALS['wgValidSkinNames']['metrolook'] = 'Metrolook';

/* Logo is off by default to turn it on plase see README.md. Note that if enabled it will not show properly.*/
$GLOBALS['logo'] = false;

/* to enable search bar on the sidebar and disables the search bar on the top bar */
$GLOBALS['SearchBar'] = true;

$GLOBALS['DownArrow'] = true;

$GLOBALS['Line'] = true;

$GLOBALS['link1'] = true;

$GLOBALS['image1'] = true;

$GLOBALS['link2'] = true;

$GLOBALS['image2'] = true;

$GLOBALS['link3'] = true;

$GLOBALS['image3'] = true;

$GLOBALS['link4'] = true;

$GLOBALS['image4'] = true;

$GLOBALS['link5'] = false;

$GLOBALS['image5'] = false;

$GLOBALS['link6'] = false;

$GLOBALS['image6'] = false;

$GLOBALS['UploadButton'] = false;

// Register modules
$GLOBALS['wgResourceModules']['skins.metrolook'] = array(
	'styles' => array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
		'Metrolook/styles.less',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$GLOBALS['wgResourceModules']['skins.metrolook.beta'] = array(
	'styles' => array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
	    'Metrolook/styles-beta.less',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$GLOBALS['wgResourceModules']['skins.metrolook.js'] = array(
	'scripts' => array(
		'Metrolook/collapsibleTabs.js',
		'Metrolook/vector.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.delayedBind',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$GLOBALS['wgResourceModules']['skins.metrolook.collapsibleNav'] = array(
	'scripts' => array(
		'Metrolook/collapsibleNav.js',
	),
	'position' => 'bottom',
	'dependencies' => array(
			'jquery.client',
			'jquery.cookie',
			'jquery.tabIndex',
		),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
