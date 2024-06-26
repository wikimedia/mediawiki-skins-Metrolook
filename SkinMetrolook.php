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

use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Output\OutputPage;

/**
 * SkinTemplate class for Metrolook skin
 * @ingroup Skins
 */
class SkinMetrolook extends SkinTemplate {
	/** @var string */
	public $skinname = 'metrolook';
	/** @var string */
	public $stylename = 'Metrolook';
	/** @var string */
	public $template = 'MetrolookTemplate';

	private Config $metrolookConfig;
	private HookContainer $hookContainer;

	/**
	 * @param ConfigFactory $configFactory
	 * @param HookContainer $hookContainer
	 * @param array $options
	 */
	public function __construct(
		ConfigFactory $configFactory,
		HookContainer $hookContainer,
		$options
	) {
		$options['bodyOnly'] = true;
		parent::__construct( $options );
		$this->metrolookConfig = $configFactory->makeConfig( 'metrolook' );
		$this->hookContainer = $hookContainer;
	}

	/** @inheritDoc */
	public function getPageClasses( $title ) {
		$className = parent::getPageClasses( $title );
		if ( $this->metrolookConfig->get( 'MetrolookExperimentalPrintStyles' ) ) {
			$className .= ' metrolook-experimental-print-styles';
		}
		$className .= ' metrolook-nav-directionality';
		return $className;
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

		// Print styles are feature flagged.
		// This flag can be removed when T169732 is resolved.
		if ( $this->metrolookConfig->get( 'MetrolookExperimentalPrintStyles' ) ) {
			// Note, when deploying (T169732) we'll want to fold the stylesheet into
			// skins.metrolook.styles and remove this module altogether.
			$out->addModuleStyles( 'skins.metrolook.styles.experimental.print' );
		}

		$out->addModules( [ 'skins.metrolook.js' ] );
	}

	/**
	 * Loads skin and user CSS files.
	 * @return array Array of modules with helper keys for easy overriding
	 */
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		$styles = [];
		if ( $this->metrolookConfig->get( 'MetrolookMobile' ) &&
			!$this->metrolookConfig->get( 'MetrolookSearchBar' )
		) {
			$styles = [
				'skins.metrolook.interface',
				'skins.metrolook.styles.custom',
				'skins.metrolook.styles.mobile.custom',
				'skins.metrolook.styles.theme.custom',
			];
		} elseif ( $this->metrolookConfig->get( 'MetrolookMobile' ) &&
			$this->metrolookConfig->get( 'MetrolookSearchBar' )
		) {
			$styles = [
				'skins.metrolook.interface',
				'skins.metrolook.styles',
				'skins.metrolook.styles.mobile',
				'skins.metrolook.styles.theme.custom',
			];
		} elseif ( !$this->metrolookConfig->get( 'MetrolookMobile' ) &&
			$this->metrolookConfig->get( 'MetrolookSearchBar' )
		) {
			$styles = [
				'skins.metrolook.interface',
				'skins.metrolook.styles',
				'skins.metrolook.styles.theme.custom',
			];
		} elseif ( !$this->metrolookConfig->get( 'MetrolookMobile' ) &&
			!$this->metrolookConfig->get( 'MetrolookSearchBar' )
		) {
			$styles = [
				'skins.metrolook.interface',
				'skins.metrolook.styles.custom',
				'skins.metrolook.styles.theme.custom',
			];
		}
		$this->hookContainer->run( 'SkinMetrolookStyleModules', [ $this, &$styles ] );
		$modules['styles']['skin'] = $styles;
		return $modules;
	}

	/**
	 * Override to pass our Config instance to it
	 * @inheritDoc
	 */
	public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname( $this->metrolookConfig );
	}

	/**
	 * Whether the logo should be preloaded with an HTTP link header or not
	 * @since 1.29
	 * @return bool
	 */
	public function shouldPreloadLogo() {
		return true;
	}
}
