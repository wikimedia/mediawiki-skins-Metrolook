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

/**
 * QuickTemplate class for Metrolook skin
 * @ingroup Skins
 */
class MetrolookTemplate extends BaseTemplate {
	/* Members */

	/** @var string $mPersonalTools Saves the personal Tools */
	private $mPersonalTools = '';
	/** @var string $mPersonalToolsEcho Saves Echo notifications */
	private $mPersonalToolsEcho = '';

	private function getTiles( $messageName = 'metrolook-tiles' ) {
		/**
		 * The message's format is:
		 * * URL to the site|alternative text|image URL
		 *
		 * For example:
		 * * http://www.pidgi.net/wiki/|PidgiWiki|http://images.pidgi.net/pidgiwikitiletop.png
		 * * http://www.pidgi.net/press/|PidgiWiki Press|http://images.pidgi.net/pidgipresstiletop.png
		 * * http://www.pidgi.net/jcc/|The JCC|http://images.pidgi.net/jcctiletop.png
		 * * http://www.petalburgwoods.com/|Petalburg Woods|http://images.pidgi.net/pwntiletop.png
		 */
		$tileMessage = $this->getSkin()->msg( $messageName );
		$tiles = '';
		if ( $tileMessage->isDisabled() ) {
			return $tiles;
		}

		$lines = explode( "\n", $tileMessage->inContentLanguage()->text() );

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			} else {
				$line = explode( '|', trim( $line, '* ' ), 3 );
				$siteURL = $line[0];
				$altText = $line[1];

				// Maybe it's the name of a MediaWiki: message? I18n is
				// always nice, so at least try it and see what happens...
				$linkMsgObj = $this->getSkin()->msg( $altText );
				if ( !$linkMsgObj->isDisabled() ) {
					$altText = $linkMsgObj->parse();
				}

				$imageURL = $line[2];
				$tiles .= '<div class="tile-wrapper"><div class="tile">' .
					'<a href="' . htmlspecialchars( $siteURL, ENT_QUOTES ) . '"><img src="' .
					htmlspecialchars( $imageURL, ENT_QUOTES ) .
					'" alt="' . htmlspecialchars( $altText, ENT_QUOTES ) . '" /></a>' .
				'</div></div>';
			}
		}

		return $tiles;
	}

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		if ( $GLOBALS['wgMetrolookUseIconWatch'] ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
			if ( isset( $nav['actions'][$mode] ) ) {
				$nav['views'][$mode] = $nav['actions'][$mode];
				$nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
				$nav['views'][$mode]['primary'] = true;
				unset( $nav['actions'][$mode] );
			}
		}

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];

		// Reverse horizontally rendered navigation elements
		if ( $this->data['rtl'] ) {
			$this->data['view_urls'] =
				array_reverse( $this->data['view_urls'] );
			$this->data['namespace_urls'] =
				array_reverse( $this->data['namespace_urls'] );
			$this->data['personal_urls'] =
				array_reverse( $this->data['personal_urls'] );
		}
		$personalTools = $this->getPersonalTools();
		foreach ( $personalTools as $key => $item ) {
			if ( $key !== 'notifications' ) {
				$this->mPersonalTools .= $this->makeListItem( $key, $item );
			} else {
				$this->mPersonalToolsEcho .= $this->makeListItem( $key, $item );
			}
		}

		// User name (or "Guest") to be displayed at the top right (on LTR
		// interfaces) portion of the skin
		$user = $this->getSkin()->getUser();
		if ( !$user->isLoggedIn() ) {
			$userNameTop = $this->getSkin()->msg( 'metrolook-guest' )->text();
		} else {
			$userNameTop = htmlspecialchars( $user->getName(), ENT_QUOTES );
		}

		// Output HTML Page
		$this->html( 'headelement' );
		?>

		<div id="mw-page-base" class="noprint"></div>
		<div id="mw-head-base" class="noprint"></div>
		<div id="content" class="mw-body" class="overthrow" role="main">
			<a id="top"></a>

			<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
			<?php if ( $this->data['sitenotice'] ) { ?>
			<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
			<?php } ?>
			<h1 id="firstHeading" class="firstHeading" lang="<?php
				$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
				$this->text( 'pageLanguage' );
			?>"><span dir="auto"><?php $this->html( 'title' ) ?></span></h1>
			<?php $this->html( 'prebodyhtml' ) ?>
			<div id="bodyContent">
				<?php if ( $this->data['isarticle'] ) { ?>
				<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<?php } ?>
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
				<?php if ( $this->data['undelete'] ) { ?>
				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<?php } ?>
				<?php if ( $this->data['newtalk'] ) { ?>
				<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
				<?php } ?>
				<div id="jump-to-nav" class="mw-jump">
					<?php $this->msg( 'jumpto' ) ?>
					<a href="#mw-navigation"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?>
					<?php
					if ( $GLOBALS['wgMetrolookSearchBar'] ) {
						?>
						<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
					<?php
					} else {
					?>
						<a href="#p-searchSearch"><?php $this->msg( 'jumptosearch' ) ?></a>
					<?php
					}
					?>
				</div>
				<?php $this->html( 'bodycontent' ) ?>
				<?php if ( $this->data['printfooter'] ) { ?>
				<div class="printfooter">
				<?php $this->html( 'printfooter' ); ?>
				</div>
				<?php } ?>
				<?php if ( $this->data['catlinks'] ) { ?>
				<?php $this->html( 'catlinks' ); ?>
				<?php } ?>
				<br clear="all" />
					<div id="footer" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
				<hr />
			<?php
			foreach ( $this->getFooterLinks() as $category => $links ) {
				?>
				<ul id="footer-<?php echo $category ?>">
					<?php
					foreach ( $links as $link ) {
						?>
						<li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
					<?php
					}
					?>
				</ul>
			<?php
			}
			?>
			<?php $footericons = $this->getFooterIcons( "icononly" );
			if ( count( $footericons ) > 0 ) {
				?>
				<ul id="footer-icons" class="noprint">
					<?php
					foreach ( $footericons as $blockName => $footerIcons ) {
						?>
						<li id="footer-<?php echo htmlspecialchars( $blockName ); ?>ico">
							<?php
							foreach ( $footerIcons as $icon ) {
								echo $this->getSkin()->makeFooterIcon( $icon );
							}
							?>
						</li>
					<?php
					}
					?>
				</ul>
			<?php
			}
			?>
			<div style="clear:both"></div>
		</div>
				<?php if ( $this->data['dataAfterContent'] ) { ?>
				<?php $this->html( 'dataAfterContent' ); ?>
				<?php } ?>
				<div class="visualClear"></div>
				<?php $this->html( 'debughtml' ); ?>
			</div>
		</div>
		<div id="mw-navigation">
			<h2><?php $this->msg( 'navigation-heading' ) ?></h2>

		<div id="mw-head">
			<div class="vectorMenu" id="usermenu">
				<div class="no-js">
					<a href="#" style="text-decoration:none;">
						<span id="username-top">
							<span id="username-text"><?php echo $userNameTop ?></span>
							<span class="username-space" style="word-spacing: 4px;"> </span>
							<span id="userIcon20">
								<img
								class="userIcon20"
								style="position:relative;top:0.3em;"
								src="<?php
								echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) )
								?>"
								/>
							</span>
							<span style="word-spacing:4px;"> </span>
							<span id="userIcon40">
								<img
								class="userIcon40"
								style="position:relative;top:0.4em;"
								src="<?php
								echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) )
								?>"
								/>
							</span>
						</span>
					</a>
					<div class="menu" style="position:absolute;top:40px;right:0px;margin:0;width:200px;">
						<?php $this->renderNavigation( 'PERSONAL' ); ?>
					</div>
				</div>
			</div>
			<div id="echoNotifications">
				<ul>
					<?php echo $this->mPersonalToolsEcho; ?>
				</ul>
			</div>

			<div id="hamburgerIcon">
				<img
				class="hamburger"
				alt=""
				src="<?php echo htmlspecialchars(
					$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
			</div>

			<?php
			if ( $GLOBALS['wgMetrolookSiteName'] ) {
				?>
				<div style="padding-left:10px;">
					<div class="lighthover siteLogoBar">
						<div class="onhoverbg" style="height:40px;float:left;">
							<h4 class="title-name">
								<a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>">
									<span class="title-name">
										<?php
										if ( $GLOBALS['wgMetrolookSiteNameText'] ) {
											?>
											<?php echo $GLOBALS['wgSitename'] ?>
										<?php
										} else {
											?>
											<?php echo $GLOBALS['wgMetrolookSiteText'] ?>
										<?php
										}
										?>
									</span>
								</a>
							</h4>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			<?php
			if ( $GLOBALS['wgMetrolookLine'] ) {
				?>
				<?php
				if ( $GLOBALS['wgMetrolookSiteName'] ) {
					?>
					<div class="lighthover siteLogoBar">
				<?php
				}
				?>
				<img
				class="line" 
				src="<?php echo htmlspecialchars(
					$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>"
				style="float:left;"
				/>
				<?php
				if ( $GLOBALS['wgMetrolookSiteName'] ) {
					?>
					</div>
				<?php
				}
				?>
			<?php
			}
			?>
			<?php
			if ( $GLOBALS['wgMetrolookDownArrow'] ) {
				?>
				<?php
				if ( $GLOBALS['wgMetrolookSiteName'] ) {
					?>
					<div class="lighthover siteLogoBar">
				<?php
				}
				?>
				<img
				class="downarrow"
				src="<?php echo htmlspecialchars(
					$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>"
				/>
				<?php
				if ( $GLOBALS['wgMetrolookSiteName'] ) {
					?>
					</div>
				<?php
				}
				?>
			<?php
			}
			?>

			<div id="left-navigation">
				<?php
				if ( $GLOBALS['wgMetrolookUploadButton'] && $user->isAllowed( 'upload' ) ) {
					if (
						isset( $this->data['nav_urls']['upload']['href'] ) &&
						$this->data['nav_urls']['upload']['href']
					)
					{
						$uploadURL = $this->data['nav_urls']['upload']['href'];
					} else {
						$upURL = SpecialPage::getTitleFor( 'Upload' )->getFullURL();
						$uploadURL = htmlspecialchars( $upURL, ENT_QUOTES );
					}
					?>
					<a href="<?php echo $uploadURL; ?>">
						<div class="onhoverbg" id="uploadbutton">
							<img
							class="uploadbutton"
							alt=""
							src="<?php echo htmlspecialchars(
								$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
								<span class="uploadbutton">
									<?php $this->msg( 'uploadbtn' ) ?>
								</span>
						</div>
					</a>
				<?php
				}
				?>
				<?php $this->renderNavigation( array( 'NAMESPACES', 'VARIANTS', 'VIEWS', 'ACTIONS' ) ); ?>
			</div>

				<?php
				if ( $GLOBALS['wgMetrolookSearchBar'] ) {
					?>
					<img
					class="searchbar"
					src="<?php echo htmlspecialchars(
						$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
				<?php
				}
				?>
				<img
				class="editbutton"
				src="<?php echo htmlspecialchars(
					$this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />


			<div id="right-navigation">
				<?php
				if ( $GLOBALS['wgMetrolookSearchBar'] ) {
					$this->renderNavigation( array( 'SEARCH' ) );
				}
				?>
			</div>
		</div>

			<?php
			if ( $GLOBALS['wgMetrolookSearchBar'] ) {
				?>
				<div id="mw-panel">
					<?php
					if ( $GLOBALS['wgMetrolookLogo'] ) {
						?>
						<div id="p-logo" role="banner"><a style="background-image: url(<?php
							$this->text( 'logopath' )
							?>);" href="<?php
							echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
							?>" <?php
							echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
							?>></a></div>
					<?php
					}
					?>
					<?php $this->renderPortals( $this->data['sidebar'] ); ?>
				</div>
			<?php
			} else {
				?>
				<div id="mw-panel-custom">
					<?php
					if ( $GLOBALS['wgMetrolookLogo'] ) {
						?>
						<div id="p-logo-custom" role="banner"><a style="background-image: url(<?php
							$this->text( 'logopath' )
							?>);" href="<?php
							echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
							?>" <?php
							echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo-custom' ) )
							?>></a></div>
					<?php
					}
					?>
					<?php $this->renderNavigation( array( 'SEARCH' ) ); ?>
					<?php $this->renderPortals( $this->data['sidebar'] ); ?>
				</div>
			<?php
			}
			?>

			<?php
			if ( $GLOBALS['wgMetrolookDownArrow'] ) {
				?>
				<div class="top-tile-bar-inner-container">
					<div class="topleft">
						<div class="tilebar" id="bartile">
							<div id="tilegrouptable">
								<div id="tilegroup">
									<?php
									if ( $GLOBALS['wgMetrolookBartile'] ) {
										echo $this->getTiles();
									} else {
										echo $this->getTiles( 'metrolook-tiles-second' );
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>

		<?php $this->printTrail(); ?>

	</body>
</html>


	<?php
	}

	/**
	 * Render a series of portals
	 *
	 * @param $portals array
	 */
	protected function renderPortals( $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals['SEARCH'] ) ) {
			$portals['SEARCH'] = true;
		}
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false ) {
				continue;
			}

			switch ( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
				break;
			}
		}
	}

	/**
	 * @param $name string
	 * @param $content array
	 * @param $msg null|string
	 * @param $hook null|string|array
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		?>
		<?php
		if ( $GLOBALS['wgMetrolookSearchBar'] ) {
			?>
			<div class="portal" role="navigation" id='<?php echo Sanitizer::escapeId( "p-$name" ) ?>'<?php echo Linker::tooltip( 'p-' . $name ) ?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
		<?php
		} else {
			?>
			<div class="portal-custom" role="navigation" id='<?php echo Sanitizer::escapeId( "p-$name" ) ?>'<?php echo Linker::tooltip( 'p-' . $name ) ?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
		<?php
		}
		?>
		<h5<?php $this->html( 'userlangattributes' ) ?> id='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ); ?></h5>
		<?php
		if ( $GLOBALS['wgMetrolookSearchBar'] ) {
			?>
			<div class="body">
		<?php
		} else {
			?>
			<div class="body-custom">
		<?php
		}
		?>
<?php
		if ( is_array( $content ) ) { ?>
		<ul>
<?php
			foreach ( $content as $key => $val ) { ?>
			<?php echo $this->makeListItem( $key, $val ); ?>

<?php
			}
			if ( $hook !== null ) {
				wfRunHooks( $hook, array( &$this, true ) );
			}
			?>
		</ul>
<?php
		} else { ?>
		<?php
			echo $content; /* Allow raw HTML block to be defined by extensions */
		}

		$this->renderAfterPortlet( $name );
		?>
	</div>
</div>
<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 *
	 * @param $elements array
	 */
	protected function renderNavigation( $elements ) {

		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
		// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data['rtl'] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {
				case 'NAMESPACES':
?>
<div id="p-namespaces" role="navigation" class="vectorTabs<?php if ( count( $this->data['namespace_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-namespaces-label">
	<h5 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['namespace_urls'] as $link ) { ?>
			<li <?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></span></li>
		<?php } ?>
	</ul>
</div>
<?php
				break;
				case 'VARIANTS':
?>
<div id="p-variants" role="navigation" class="vectorMenu<?php if ( count( $this->data['variant_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-variants-label">
	<h5 id="mw-vector-current-variant">
	<?php foreach ( $this->data['variant_urls'] as $link ) { ?>
		<?php if ( stripos( $link['attributes'], 'selected' ) !== false ) { ?>
			<?php echo htmlspecialchars( $link['text'] ) ?>
		<?php } ?>
	<?php } ?>
	</h5>
	<h5 id="p-variants-label"><span><?php $this->msg( 'variants' ) ?></span><a href="#"></a></h5>
	<div class="menu">
		<ul>
			<?php foreach ( $this->data['variant_urls'] as $link ) { ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" lang="<?php echo htmlspecialchars( $link['lang'] ) ?>" hreflang="<?php echo htmlspecialchars( $link['hreflang'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php
				break;
				case 'VIEWS':
?>
<div id="p-views" role="navigation" class="vectorTabs<?php if ( count( $this->data['view_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-views-label">
	<h5 id="p-views-label"><?php $this->msg( 'views' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['view_urls'] as $link ) { ?>
			<li<?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php
				// $link['text'] can be undefined - bug 27764
				if ( array_key_exists( 'text', $link ) ) {
					echo array_key_exists( 'img', $link ) ? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />' : htmlspecialchars( $link['text'] );
				}
				?></a></span></li>
		<?php } ?>
	</ul>
</div>
<?php
				break;
				case 'ACTIONS':
?>
<div id="p-cactions" role="navigation" class="vectorMenu actionmenu<?php if ( count( $this->data['action_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-cactions-label">
	<h5 id="p-cactions-label"><span><?php $this->msg( 'actions' ) ?></span><a href="#"></a></h5>
	<div class="menu">
		<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php foreach ( $this->data['action_urls'] as $link ) { ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php
				break;
				case 'PERSONAL':
?>
<div id="p-personal" role="navigation" class="<?php if ( count( $this->data['personal_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-personal-label">
	<h5 id="p-personal-label"><?php $this->msg( 'personaltools' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
<?php
							echo $this->mPersonalTools;
?>
	</ul>
</div>
<?php
				break;
				case 'SEARCH':
					?>
					<?php
					if ( $GLOBALS['wgMetrolookSearchBar'] ) {
						?>
						<div id="p-search" role="search">
							<h5<?php $this->html( 'userlangattributes' ) ?>>
								<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
							</h5>

							<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
								<?php
								if ( $GLOBALS['wgMetrolookUseSimpleSearch'] ) {
								?>
								<div id="simpleSearch">
									<?php
								} else {
								?>
									<div>
										<?php
								}
								?>
								<?php
								echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
								echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
								// We construct two buttons (for 'go' and 'fulltext' search modes),
								// but only one will be visible and actionable at a time (they are
								// overlaid on top of each other in CSS).
								// * Browsers will use the 'fulltext' one by default (as it's the
								//   first in tree-order), which is desirable when they are unable
								//   to show search suggestions (either due to being broken or
								//   having JavaScript turned off).
								// * The mediawiki.searchSuggest module, after doing tests for the
								//   broken browsers, removes the 'fulltext' button and handles
								//   'fulltext' search itself; this will reveal the 'go' button and
								//   cause it to be used.
								echo $this->makeSearchButton(
									'fulltext',
									array( 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' )
								);
								echo $this->makeSearchButton(
									'go',
									array( 'id' => 'searchButton', 'class' => 'searchButton' )
								);
								?>
									</div>
							</form>
						</div>
					<?php
					} else {
						?>
						<div id="p-searchSearch" role="search">
							<h5<?php $this->html( 'userlangattributes' ) ?>>
								<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
							</h5>

							<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
								<?php
								if ( $GLOBALS['wgMetrolookUseSimpleSearch'] ) {
								?>
								<div id="simpleSearchSearch">
									<?php
								} else {
								?>
									<div>
										<?php
								}
								?>
								<?php
								echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
								echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
								// We construct two buttons (for 'go' and 'fulltext' search modes),
								// but only one will be visible and actionable at a time (they are
								// overlaid on top of each other in CSS).
								// * Browsers will use the 'fulltext' one by default (as it's the
								//   first in tree-order), which is desirable when they are unable
								//   to show search suggestions (either due to being broken or
								//   having JavaScript turned off).
								// * The mediawiki.searchSuggest module, after doing tests for the
								//   broken browsers, removes the 'fulltext' button and handles
								//   'fulltext' search itself; this will reveal the 'go' button and
								//   cause it to be used.
								echo $this->makeSearchButton(
									'fulltext',
									array( 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' )
								);
								echo $this->makeSearchButton(
									'go',
									array( 'id' => 'searchButton', 'class' => 'searchButton' )
								);
								?>
									</div>
							</form>
						</div>
					<?php
					}
					?>
					<?php

				break;
			}
		}
	}
}
