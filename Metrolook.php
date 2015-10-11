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
	'namemsg' => 'skinname-metrolook',
	'descriptionmsg' => 'metrolook-desc',
	'version' => '2.7.0',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'Paladox', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$GLOBALS['wgAutoloadClasses']['MetrolookHooks'] = __DIR__ . '/SkinMetrolookHooks.php';
$GLOBALS['wgAutoloadClasses']['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$GLOBALS['wgAutoloadClasses']['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

$GLOBALS['wgHooks']['BeforePageDisplay'][] = 'MetrolookHooks::beforePageDisplay';
$GLOBALS['wgHooks']['GetPreferences'][] = 'MetrolookHooks::getPreferences';
$GLOBALS['wgHooks']['ResourceLoaderGetConfigVars'][] = 'MetrolookHooks::resourceLoaderGetConfigVars';
$GLOBALS['wgHooks']['MakeGlobalVariablesScript'][] = 'MetrolookHooks::makeGlobalVariablesScript';

$GLOBALS['wgMessagesDirs']['Metrolook'] =  __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['Metrolook'] = __DIR__.'/Metrolook.i18n.php';

// Register skin
$GLOBALS['wgValidSkinNames']['metrolook'] = 'Metrolook';

// Register config
$GLOBALS['wgConfigRegistry']['metrolook'] = 'GlobalVarConfig::newInstance';

// Configuration options

// Each module may be configured individually to be globally on/off or user preference based
$GLOBALS['wgMetrolookFeatures'] = array(
	'collapsiblenav' => array( 'global' => false, 'user' => true ),
);

/* 
 * Setting default option.
 *
 * Do not remove this.
 *
 * Bug T76314
 *
 * You can add this to your localsettings.php file and change from 1 to 0 to disbale it globaly.
 */
$GLOBALS['wgDefaultUserOptions']['skinmetrolook-collapsiblenav'] = 1;

/**
 * Search form look.
 *  - true = use an icon search button
 *  - false = use Go & Search buttons
 */
$GLOBALS['wgMetrolookUseSimpleSearch'] = true;

/**
 * Watch and unwatch as an icon rather than a link.
 *  - true = use an icon watch/unwatch button
 *  - false = use watch/unwatch text link
 */
$GLOBALS['wgMetrolookUseIconWatch'] = true;

/**
 * Logo
 *  - true = Logo will show
 *  - false = Logo will not show
 */

$GLOBALS['wgMetrolookLogo'] = true;

$GLOBALS['wgMetrolookSiteName'] = true;

$GLOBALS['wgMetrolookSiteNameText'] = true;

$GLOBALS['wgMetrolookSiteText'] = '';

/* to enable search bar on the sidebar and disables the search bar on the top bar */
$GLOBALS['wgMetrolookSearchBar'] = true;

$GLOBALS['wgMetrolookDownArrow'] = true;

$GLOBALS['wgMetrolookLine'] = true;

$GLOBALS['wgMetrolookUploadButton'] = true;

$GLOBALS['wgMetrolookMobile'] = true;

$GLOBALS['wgMetrolookBartile'] = true;

// Register modules
$GLOBALS['wgResourceModules']['skins.metrolook.styles'] = array(
	'styles' => array(
		'screen.less' => array( 'media' => 'screen' ),
		'screen-hd.less' => array( 'media' => 'screen and (min-width: 982px)' ),
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
$GLOBALS['wgResourceModules']['skins.metrolook.js'] = array(
	'scripts' => array(
		'js/collapsibleTabs.js',
		'js/metrolook.js',
		'js/metrolook.search.js',
		'js/vector.js',
		'js/mediawiki.searchSuggest.custom.js',
		'js/overthrow.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.throttle-debounce',
		'jquery.tabIndex',
		'mediawiki.searchSuggest',
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
$GLOBALS['wgResourceModules']['skins.metrolook.collapsibleNav'] = array(
	'scripts' => array(
		'js/collapsibleNav.js',
	),
	'styles' => array(
		'components/collapsibleNav.less',
	),
	'position' => 'bottom',
	'dependencies' => array(
		'jquery.client',
		'jquery.cookie',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);

// Apply module customizations
$GLOBALS['wgResourceModuleSkinStyles']['metrolook'] = array(
	'jquery.tipsy' => 'skinStyles/jquery.tipsy.less',
	'jquery.ui.core' => array(
		'skinStyles/jquery.ui/jquery.ui.core.css',
		'skinStyles/jquery.ui/jquery.ui.theme.css',
	),
	'jquery.ui.accordion' => 'skinStyles/jquery.ui/jquery.ui.accordion.css',
	'jquery.ui.autocomplete' => 'skinStyles/jquery.ui/jquery.ui.autocomplete.css',
	'jquery.ui.button' => 'skinStyles/jquery.ui/jquery.ui.button.css',
	'jquery.ui.datepicker' => 'skinStyles/jquery.ui/jquery.ui.datepicker.css',
	'jquery.ui.dialog' => 'skinStyles/jquery.ui/jquery.ui.dialog.css',
	'jquery.ui.menu' => 'skinStyles/jquery.ui/jquery.ui.menu.css',
	'jquery.ui.progressbar' => 'skinStyles/jquery.ui/jquery.ui.progressbar.css',
	'jquery.ui.resizable' => 'skinStyles/jquery.ui/jquery.ui.resizable.css',
	'jquery.ui.selectable' => 'skinStyles/jquery.ui/jquery.ui.selectable.css',
	'jquery.ui.slider' => 'skinStyles/jquery.ui/jquery.ui.slider.css',
	'jquery.ui.spinner' => 'skinStyles/jquery.ui/jquery.ui.spinner.css',
	'jquery.ui.tabs' => 'skinStyles/jquery.ui/jquery.ui.tabs.css',
	'jquery.ui.tooltips' => 'skinStyles/jquery.ui/jquery.ui.tooltips.css',
	'mediawiki.notification' => 'skinStyles/mediawiki.notification.less',
	'mediawiki.special' => 'skinStyles/mediawiki.special.less',
	'mediawiki.special.preferences' => 'skinStyles/mediawiki.special.preferences.less',
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
