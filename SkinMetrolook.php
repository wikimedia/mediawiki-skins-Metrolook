<?php
/**
 * Metrolook - Metro look for website
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

/**
 * SkinTemplate class for Metrolook skin
 * @ingroup Skins
 */
class SkinMetrolook extends SkinTemplate {
	public $skinname = 'metrolook';
	public $stylename = 'Metrolook';
	public $template = 'MetrolookTemplate';
	/**
	 * @var Config
	 */
	private $metrolookConfig;

	public function __construct() {
		$this->metrolookConfig = ConfigFactory::getDefaultInstance()->makeConfig( 'metrolook' );
	}

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param OutputPage $out Object to initialize
	 */
	public function initPage( OutputPage $out ) {

		parent::initPage( $out );

		if ( $this->metrolookConfig->get( 'MetrolookMobile' ) ) {
			$out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
		}

		// Append CSS which includes IE only behavior fixes for hover support -
		// this is better than including this in a CSS file since it doesn't
		// wait for the CSS file to load before fetching the HTC file.
		$min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';
		$out->addHeadItem( 'csshover',
			'<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
				htmlspecialchars( $this->getConfig()->get( 'LocalStylePath' ) ) .
				"/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
		);

		$out->addModules( array( 'skins.metrolook.js' ) );
	}

	/**
	 * Loads skin and user CSS files.
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		if ( $this->metrolookConfig->get( 'MetrolookMobile' ) ) {
			$styles = array( 'mediawiki.skinning.interface', 'skins.metrolook.styles.responsive' );
		} else {
			$styles = array( 'mediawiki.skinning.interface', 'skins.metrolook.styles' );
		}
		Hooks::run( 'SkinMetrolookStyleModules', array( $this, &$styles ) );
		$out->addModuleStyles( $styles );
	}

	/**
	 * Override to pass our Config instance to it
	 */
	public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname( $this->metrolookConfig );
	}
}
