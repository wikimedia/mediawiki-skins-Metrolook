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
	/* Functions */

	/** @var string $mPersonalTools Saves the personal Tools */
	private $mPersonalTools = '';
	/** @var string $mPersonalToolsEcho Saves Echo notifications */
	private $mPersonalToolsEcho = '';

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		if ( $this->config->get( 'VectorUseIconWatch' ) ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() )
				? 'unwatch'
				: 'watch';

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

		$this->data['pageLanguage'] =
			$this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

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

			<?php
			if ( $this->data['sitenotice'] ) {
				?>
				<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
			<?php
			}
			?>
			<?php
			if ( is_callable( array( $this, 'getIndicators' ) ) ) {
				echo $this->getIndicators();
			}
			// Loose comparison with '!=' is intentional, to catch null and false too, but not '0'
			if ( $this->data['title'] != '' ) {
			?>
			<h1 id="firstHeading" class="firstHeading" lang="<?php $this->text( 'pageLanguage' ); ?>"><?php
				$this->html( 'title' )
			?></h1>
			<?php
			} ?>
			<?php $this->html( 'prebodyhtml' ) ?>
			<div id="bodyContent" class="mw-body-content">
				<?php
				if ( $this->data['isarticle'] ) {
					?>
					<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<?php
				}
				?>
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php
					$this->html( 'subtitle' )
				?></div>
				<?php
				if ( $this->data['undelete'] ) {
					?>
					<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<?php
				}
				?>
				<?php
				if ( $this->data['newtalk'] ) {
					?>
					<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
				<?php
				}
				?>
				<div id="jump-to-nav" class="mw-jump">
					<?php $this->msg( 'jumpto' ) ?>
					<a href="#mw-head"><?php
						$this->msg( 'jumptonavigation' )
					?></a><?php $this->msg( 'comma-separator' ) ?>
					<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
					<?php else: ?>
					<a href="#p-searchSearch"><?php $this->msg( 'jumptosearch' ) ?></a>
					<?php endif; ?>
				</div>
				<?php
				$this->html( 'bodycontent' );
				if ( $this->data['printfooter'] ) {
					?>
					<div class="printfooter">
						<?php $this->html( 'printfooter' ); ?>
					</div>
				<?php
				}
				if ( $this->data['catlinks'] ) {
					$this->html( 'catlinks' );
				}
				?>
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
				<?php
				if ( $this->data['dataAfterContent'] ) {
					$this->html( 'dataAfterContent' );
				}
				?>
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
								<img class="userIcon20" style="position:relative;top:0.3em;" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
							</span>
							<span style="word-spacing:4px;"> </span>
							<span id="userIcon40">
								<img class="userIcon40" style="position:relative;top:0.4em;" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
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
				<img class="hamburger" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" height="40px" width="40px" />
			</div>

			<?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?><div style="padding-left:10px;"><div id="siteLogoBar" class="lighthover" style="height:40px;float:left;"><div class="onhoverbg" style="height:40px;float:left;"><h4 class="title-name"><a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>"><div class="title-name" style="font-size: 0.9em; padding-left:0.4em;padding-right:0.4em;color:white;max-width: auto;height:auto; max-height:700px; display: inline-block; vertical-align:middle;"><?php echo $GLOBALS['wgSitename'] ?></div></a></h4></div><?php endif; ?><?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?></div></div><?php endif; ?>
			<?php if ( $this->config->get( 'MetrolookLine' ) ): ?><?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?><div id="siteLogoBar" class="lighthover" style="height:40px;float:left;"><?php endif; ?><img class="line" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" style="float:left;" /><?php endif; ?><?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?></div><?php endif; ?>
			<?php if ( $this->config->get( 'MetrolookDownArrow' ) ): ?><?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?><div id="siteLogoBar" class="lighthover" style="height:40px;float:left;"><?php endif; ?><img class="downarrow" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" style="cursor:pointer;" /><?php endif; ?><?php if ( $this->config->get( 'MetrolookSiteName' ) ): ?></div><?php endif; ?>

			<?php if ( $this->config->get( 'MetrolookDownArrow' ) ): ?>
			<div id="top-tile-bar" class="fixed-position">
				<div style="vertical-align:top;align:left;">
					<div class="topleft">
						<div style="align:left;margin-left:auto;margin-right:auto;display:none;" class="tilebar" id="bartile">
							<div id="tilegrouptable">
								<div id="tilegroup">
								<?php if ( $this->config->get( 'MetrolookBartile' ) ): ?>
									<?php if ( $this->config->get( 'MetrolookTile1' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.pidgi.net/wiki/"><img src="http://images.pidgi.net/pidgiwikitiletop.png" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile2' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.pidgi.net/press/"><img src="http://images.pidgi.net/pidgipresstiletop.png" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile3' ) ): ?><div style="float:left;padding:5px;" id="jcctile"><div class="tile"><a href="http://www.pidgi.net/jcc/"><img src="http://images.pidgi.net/jcctiletop.png" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile4' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.petalburgwoods.com/"><img src="http://images.pidgi.net/pwntiletop.png" /></a></div></div><?php endif; ?>
								<?php else: ?>
									<?php if ( $this->config->get( 'MetrolookTile5' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL1'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage1'] ?>" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile6' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL2'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage2'] ?>" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile7' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL3'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage3'] ?>" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile8' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL4'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage4'] ?>" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile9' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL5'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage5'] ?>" /></a></div></div><?php endif; ?>
									<?php if ( $this->config->get( 'MetrolookTile10' ) ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['wgMetrolookURL6'] ?>"><img src="<?php echo $GLOBALS['wgMetrolookImage6'] ?>" /></a></div></div><?php endif; ?>
								<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div id="left-navigation">
				<?php if ( $this->config->get( 'MetrolookUploadButton' ) ): ?><a href="<?php echo $this->data['nav_urls']['upload']['href']; ?>"><div class="onhoverbg" style="padding-left:0.8em;padding-right:0.8em;float:left;height:40px;font-size:10pt;"><img class="uploadbutton" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" /> <span style="color:#fff;position:relative;top:3px; "><?php $this->msg('uploadbtn') ?></span></div></a><?php endif; ?>
				<?php $this->renderNavigation( array( 'NAMESPACES', 'VARIANTS', 'VIEWS', 'ACTIONS' ) ); ?>
			</div>

				<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
				<img class="searchbar" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
				<?php endif; ?>
				<img class="editbutton" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />


			<div id="right-navigation">
				<?php
				if ( $this->config->get( 'MetrolookSearchBar' ) ) {
					$this->renderNavigation( array( 'SEARCH' ) );
				}
				?>
			</div>
		</div>

			<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
			<div id="mw-panel">
			<?php if ( $this->config->get( 'MetrolookLogo' ) ): ?>
				<div id="p-logo" role="banner"><a class="mw-wiki-logo" href="<?php
					echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
					?>" <?php
					echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
					?>></a></div>
				<?php endif; ?>
				<?php $this->renderPortals( $this->data['sidebar'] ); ?>
			</div>
			<?php else: ?>
			<div id="mw-panel-custom">
			<?php if ( $this->config->get( 'MetrolookLogo' ) ): ?>
				<div id="p-logo" role="banner"><a class="mw-wiki-logo" href="<?php
					echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
					?>" <?php
					echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
					?>></a></div>
				<?php endif; ?>
				<?php $this->renderNavigation( array( 'SEARCH' ) ); ?>
				<?php $this->renderPortals( $this->data['sidebar'] ); ?>
			</div>
			<?php endif; ?>
		</div>

		<?php $this->printTrail(); ?>

	</body>
</html>
<?php
	}

	/**
	 * Render a series of portals
	 *
	 * @param array $portals
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

			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

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
	 * @param string $name
	 * @param array $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		$labelId = Sanitizer::escapeId( "p-$name-label" );
		?>
		<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo $labelId ?>'>
			<h5<?php $this->html( 'userlangattributes' ) ?> id='<?php echo $labelId ?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h5>
		<?php else: ?>
		<div class="portal-custom" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo $labelId ?>'>
			<h5<?php $this->html( 'userlangattributes' ) ?> id='<?php echo $labelId ?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h5>
		<?php endif; ?>
			<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
			<div class="body">
			<?php else: ?>
			<div class="body-custom">
			<?php endif; ?>
				<?php
				if ( is_array( $content ) ) {
					?>
					<ul>
						<?php
						foreach ( $content as $key => $val ) {
							echo $this->makeListItem( $key, $val );
						}
						if ( $hook !== null ) {
							Hooks::run( $hook, array( &$this, true ) );
						}
						?>
					</ul>
				<?php
				} else {
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
	 * @param array $elements
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
					<div id="p-namespaces" role="navigation" class="vectorTabs<?php
					if ( count( $this->data['namespace_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-namespaces-label">
						<h5 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h5>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['namespace_urls'] as $link ) {
								?>
								<li <?php echo $link['attributes'] ?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'];
										if ( isset ( $link['rel'] ) ) {
											echo ' rel="' . htmlspecialchars( $link['rel'] ) . '"';
										}
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'VARIANTS':
					?>
					<div id="p-variants" role="navigation" class="vectorMenu<?php
					if ( count( $this->data['variant_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-variants-label">
						<?php
						// Replace the label with the name of currently chosen variant, if any
						$variantLabel = $this->getMsg( 'variants' )->text();
						foreach ( $this->data['variant_urls'] as $link ) {
							if ( stripos( $link['attributes'], 'selected' ) !== false ) {
								$variantLabel = $link['text'];
								break;
							}
						}
						?>
						<h5 id="p-variants-label">
							<span><?php echo htmlspecialchars( $variantLabel ) ?></span><a href="#"></a>
						</h5>

						<div class="menu">
							<ul>
								<?php
								foreach ( $this->data['variant_urls'] as $link ) {
									?>
									<li<?php echo $link['attributes'] ?>><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" lang="<?php
										echo htmlspecialchars( $link['lang'] )
										?>" hreflang="<?php
										echo htmlspecialchars( $link['hreflang'] )
										?>" <?php
										echo $link['key']
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
					break;
				case 'VIEWS':
					?>
					<div id="p-views" role="navigation" class="vectorTabs<?php
					if ( count( $this->data['view_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-views-label">
						<h5 id="p-views-label"><?php $this->msg( 'views' ) ?></h5>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['view_urls'] as $link ) {
								?>
								<li<?php echo $link['attributes'] ?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'];
										if ( isset ( $link['rel'] ) ) {
											echo ' rel="' . htmlspecialchars( $link['rel'] ) . '"';
										}
										?>><?php
											// $link['text'] can be undefined - bug 27764
											if ( array_key_exists( 'text', $link ) ) {
												echo array_key_exists( 'img', $link )
													? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />'
													: htmlspecialchars( $link['text'] );
											}
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'ACTIONS':
					?>
					<div id="p-cactions" role="navigation" class="vectorMenu actionmenu<?php
					if ( count( $this->data['action_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-cactions-label">
						<h5 id="p-cactions-label"><span><?php
							$this->msg( 'actions' )
						?></span><a href="#"></a></h5>

						<div class="menu">
							<ul<?php $this->html( 'userlangattributes' ) ?>>
								<?php
								foreach ( $this->data['action_urls'] as $link ) {
									?>
									<li<?php echo $link['attributes'] ?>>
										<a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] )
											?></a>
									</li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
					break;
				case 'PERSONAL':
					?>
					<div id="p-personal" role="navigation" class="<?php
					if ( count( $this->data['personal_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-personal-label">
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
					<?php if ( $this->config->get( 'MetrolookSearchBar' ) ): ?>
					<div id="p-search" role="search">
						<h5<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h5>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<div<?php echo $this->config->get( 'VectorUseSimpleSearch' ) ? ' id="simpleSearch"' : '' ?>>
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
					<?php else: ?>
					<div id="p-searchSearch" role="search">
						<h5<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h5>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<div<?php echo $this->config->get( 'VectorUseSimpleSearch' ) ? ' id="simpleSearchSearch"' : '' ?>>
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
					<?php endif; ?>
					<?php

					break;
			}
		}
	}
}
