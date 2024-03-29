<?php
/**
 * ResourceLoader module for print styles.
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
 */

namespace Metrolook;

use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\FileModule;
use Wikimedia\Minify\CSSMin;

/**
 * ResourceLoader module for print styles.
 */
class ResourceLoaderLessModule extends FileModule {
	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @param Context $context
	 * @return array
	 */
	protected function getLessVars( Context $context ) {
		$lessVars = parent::getLessVars( $context );
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'metrolook' );
		$printLogo = $config->get( 'MetrolookPrintLogo' );
		if ( $printLogo ) {
			$lessVars[ 'printLogo' ] = true;
			$lessVars[ 'printLogoUrl' ] = CSSMin::buildUrlValue(
				CSSMin::encodeImageAsDataURI( $printLogo['url'] ) );
			$lessVars[ 'printLogoWidth' ] = intval( $printLogo['width'] );
			$lessVars[ 'printLogoHeight' ] = intval( $printLogo['height'] );
		} else {
			$lessVars[ 'printLogo' ] = false;
		}
		return $lessVars;
	}
}
