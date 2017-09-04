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

	private function getTiles( $messageName = 'metrolook-tiles' ) {
		/**
		 * The message's format is:
		 * * URL to the site|alternative text|image URL
		 *
		 * For example:
		 * * https://www.pidgi.net/wiki/|PidgiWiki|https://images.pidgi.net/pidgiwikitiletop.png
		 * * https://www.pidgi.net/press/|PidgiWiki Press|https://images.pidgi.net/pidgipresstiletop.png
		 * * https://www.pidgi.net/jcc/|The JCC|https://images.pidgi.net/jcctiletop.png
		 * * https://www.petalburgwoods.com/|Petalburg Woods|https://images.pidgi.net/pwntiletop.png
		 */
		$tileMessage = $this->getSkin()->msg( $messageName );
		$tiles = '';
		if ( $tileMessage->isDisabled() ) {
			return $tiles;
		}

		$lines = explode( "\n", $tileMessage->text() );

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
	 * Get the current user's avatar image.
	 *
	 * @param int $width 20 or 40, used for the img ID and class attributes
	 * @return string Valid <\img\> tag suitable for output
	 */
	private function getAvatar( $width ) {
		$skin = $this->getSkin();

		// Default avatar is what we start with
		$avatarImage = Html::element(
			'img',
			[
				'class' => 'userIcon' . (int)$width,
				'src' => htmlspecialchars( $skin->getSkinStylePath( 'images/Transparent.gif' ) ),
				'alt' => ''
			]
		);

		if ( $this->config->get( 'MetrolookDisableAvatar' ) ) {
			if ( class_exists( 'wAvatar' ) ) {
				// SocialProfile is installed
				$avatar = new wAvatar( $skin->getUser()->getId(), 'l' );
				$avatarImage = $avatar->getAvatarURL( [
					'width' => (int)$width,
					'class' => 'userIcon' . (int)$width . ' socialprofile-avatar'
				] );
			}
		}

		return $avatarImage;
	}

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		$skin = $this->getSkin();
		$this->data['namespace_urls'] = $this->data['content_navigation']['namespaces'];
		$this->data['view_urls'] = $this->data['content_navigation']['views'];
		$this->data['action_urls'] = $this->data['content_navigation']['actions'];
		$this->data['variant_urls'] = $this->data['content_navigation']['variants'];

		// Move the watch/unwatch star outside of the collapsed "actions" menu to the main "views" menu
		if ( $this->config->get( 'MetrolookUseIconWatch' ) ) {
			$mode = $skin->getUser()->isWatched( $skin->getRelevantTitle() )
				? 'unwatch'
				: 'watch';

			if ( isset( $this->data['action_urls'][$mode] ) ) {
				$this->data['view_urls'][$mode] = $this->data['action_urls'][$mode];
				unset( $this->data['action_urls'][$mode] );
			}
		}

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
			if ( $key !== 'notifications-alert' ) {
				$this->mPersonalTools .= $this->makeListItem( $key, $item );
			} else {
				$this->mPersonalToolsEcho .= $this->makeListItem( $key, $item );
			}
		}

		$this->data['pageLanguage'] =
			$skin->getTitle()->getPageViewLanguage()->getHtmlCode();

		// User name (or "Guest") to be displayed at the top right (on LTR
		// interfaces) portion of the skin
		$user = $skin->getUser();
		if ( $user->isLoggedIn() ) {
			$userNameTop = htmlspecialchars( $user->getName(), ENT_QUOTES );
		} else {
			$userNameTop = $skin->msg( 'metrolook-guest' )->text();
		}

		// Output HTML Page
		$this->html( 'headelement' );
		?>

		<div id="mw-page-base" class="noprint"></div>
		<div id="mw-head-base" class="noprint"></div>
		<div id="content" class="mw-body overthrow" role="main">
			<a id="top"></a>

			<?php
			if ( $this->data['sitenotice'] ) {
				?>
				<div id="siteNotice" class="mw-body-content"><?php $this->html( 'sitenotice' ) ?></div>
			<?php
			}
			?>
			<?php
			if ( is_callable( [ $this, 'getIndicators' ] ) ) {
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
					<div id="siteSub" class="noprint"><?php $this->msg( 'tagline' ) ?></div>
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
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
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
				<div class="visualClear"></div>
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
								echo $skin->makeFooterIcon( $icon );
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
			<div class="visualClear"></div>
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
			<div id="hamburgerIcon">
				<img
				class="hamburger"
				alt=""
				src="<?php echo htmlspecialchars(
					$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
			</div>

			<?php
			if ( $this->config->get( 'MetrolookSiteName' ) ) {
				?>
				<div class="siteLogoBarContainer">
					<div class="lighthover siteLogoBar">
						<div class="onhoverbg">
							<h4 class="title-name">
								<a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>">
									<span class="title-name">
										<?php
										if ( $this->config->get( 'MetrolookSiteNameLogo' ) ) {
											?>
											<div class="sitename-logo">
												<a href="<?php
													echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
												?>">
													<img src="<?php echo $this->text( 'logopath' ) ?>" alt="" />
												</a>
											</div>
										<?php
										} else {
											if ( $this->config->get( 'MetrolookSiteNameText' ) ) {
												echo $GLOBALS['wgMetrolookSiteText'];
											} else {
												echo $GLOBALS['wgSitename'];
											}
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
			if ( $this->config->get( 'MetrolookLine' ) ) {
				?>
				<?php
				if ( $this->config->get( 'MetrolookSiteName' ) ) {
					?>
					<div class="lighthover siteLogoBar">
				<?php
				}
				?>
				<img
				class="line"
				alt=""
				src="<?php echo htmlspecialchars(
					$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>"
				/>
				<?php
				if ( $this->config->get( 'MetrolookSiteName' ) ) {
					?>
					</div>
				<?php
				}
				?>
			<?php
			}
			?>
			<?php
			if ( $this->config->get( 'MetrolookDownArrow' ) ) {
				?>
				<?php
				if ( $this->config->get( 'MetrolookSiteName' ) ) {
					?>
					<div class="lighthover siteLogoBar">
				<?php
				}
				?>
				<img
				class="downarrow"
				alt=""
				src="<?php echo htmlspecialchars(
					$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>"
				/>
				<?php
				if ( $this->config->get( 'MetrolookSiteName' ) ) {
					?>
					</div>
				<?php
				}
				?>
			<?php
			}
			?>

			<div class="vectorMenu" id="usermenu">
				<div class="no-js">
					<a href="#" class="user-icon-container">
						<span id="username-top">
							<span id="username-text"><?php echo $userNameTop ?></span>
							<span class="username-space spacer"> </span>
							<span id="userIcon20"><?php echo $this->getAvatar( 20 ) ?></span>
							<span class="spacer"> </span>
							<span id="userIcon40"><?php echo $this->getAvatar( 40 ) ?></span>
						</span>
					</a>
					<div class="menu personal-menu">
						<?php $this->renderNavigation( 'PERSONAL' ); ?>
					</div>
				</div>
			</div>
			<div id="echoNotifications">
				<ul>
					<?php echo $this->mPersonalToolsEcho; ?>
				</ul>
			</div>

			<div id="left-navigation">
				<?php
				if ( $this->config->get( 'MetrolookUploadButton' ) && $user->isAllowed( 'upload' ) ) {
					if (
						isset( $this->data['nav_urls']['upload']['href'] ) &&
						$this->data['nav_urls']['upload']['href']
					) {
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
								$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
								<span class="uploadbutton">
									<?php $this->msg( 'uploadbtn' ) ?>
								</span>
						</div>
					</a>
				<?php
				}
				?>
				<?php $this->renderNavigation( [ 'NAMESPACES', 'VARIANTS', 'VIEWS', 'ACTIONS' ] ); ?>
			</div>

				<?php
				if ( $this->config->get( 'MetrolookSearchBar' ) ) {
					?>
					<img
					class="searchbar"
					alt=""
					src="<?php echo htmlspecialchars(
						$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
				<?php
				}
				?>
				<img
				class="editbutton"
				alt=""
				src="<?php echo htmlspecialchars(
					$skin->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />


			<div id="right-navigation">
				<?php
				if ( $this->config->get( 'MetrolookSearchBar' ) ) {
					$this->renderNavigation( [ 'SEARCH' ] );
				}
				?>
			</div>
		</div>

			<div id="mw-panel">
				<?php
				if ( !$this->config->get( 'MetrolookSiteNameLogo' ) &&
					$this->config->get( 'MetrolookLogo' )
				) {
					?>
					<div id="p-logo" role="banner"><a class="mw-wiki-logo" href="<?php
						echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
						?>" <?php
						echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
						?>></a></div>
				<?php
				}
				if ( !$this->config->get( 'MetrolookSearchBar' ) ) {
					$this->renderNavigation( [ 'SEARCH' ] );
				}
				$this->renderPortals( $this->data['sidebar'] );
				?>
			</div>

			<?php
			if ( $this->config->get( 'MetrolookDownArrow' ) ) {
				?>
				<div class="top-tile-bar-inner-container">
					<div class="topleft">
						<div class="tilebar" id="bartile">
							<div id="tilegrouptable">
								<div id="tilegroup">
									<?php
									if ( $this->config->get( 'MetrolookBartile' ) ) {
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

		<div id="mw-panel-right" class="noprint">
			<?php
			// Hook point for the ShoutWiki Ads extension
			$skin = $this;
			Hooks::run( 'MetrolookRightPanel', [ &$skin ] );
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
					// Hook point for the ShoutWiki Ads extension
					$skin = $this;
					Hooks::run( 'MetrolookAfterToolbox', [ &$skin ] );
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
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo $labelId ?>'>
			<h5<?php $this->html( 'userlangattributes' ) ?> id='<?php echo $labelId ?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h5>
			<div class="body">
				<?php
				if ( is_array( $content ) ) {
					?>
					<ul>
						<?php
						foreach ( $content as $key => $val ) {
							echo $this->makeListItem( $key, $val );
						}
						if ( $hook !== null ) {
							$skin = $this;
							Hooks::run( $hook, [ &$skin, true ] );
						}
						?>
					</ul>
				<?php
				} else {
					// Allow raw HTML block to be defined by extensions
					echo $content;
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
			$elements = [ $elements ];
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
							foreach ( $this->data['namespace_urls'] as $key => $item ) {
								echo "\t\t\t\t\t\t\t" . $this->makeListItem( $key, $item, [
									'metrolook-wrap' => true,
								] ) . "\n";
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
						foreach ( $this->data['variant_urls'] as $item ) {
							if ( isset( $item['class'] ) && stripos( $item['class'], 'selected' ) !== false ) {
								$variantLabel = $item['text'];
								break;
							}
						}
						?>
						<h5 id="p-variants-label">
							<span><?php echo htmlspecialchars( $variantLabel ) ?></span>
						</h5>

						<div class="menu">
							<ul>
								<?php
								foreach ( $this->data['variant_urls'] as $key => $item ) {
									echo "\t\t\t\t\t\t\t\t" . $this->makeListItem( $key, $item ) . "\n";
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
							foreach ( $this->data['view_urls'] as $key => $item ) {
								echo "\t\t\t\t\t\t\t" . $this->makeListItem( $key, $item, [
									'metrolook-wrap' => true,
									'metrolook-collapsible' => true,
								] ) . "\n";
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
						<h5 id="p-cactions-label"><span></span></h5>

						<div class="menu">
							<ul<?php $this->html( 'userlangattributes' ) ?>>
								<?php
								foreach ( $this->data['action_urls'] as $key => $item ) {
									echo "\t\t\t\t\t\t\t\t" . $this->makeListItem( $key, $item ) . "\n";
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
					<div id="p-search" role="search">
						<h5<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h5>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<div
								<?php echo $this->config->get( 'MetrolookUseSimpleSearch' ) ? ' id="simpleSearch"' : '' ?>
							>
							<?php
							echo $this->makeSearchInput( [ 'id' => 'searchInput' ] );
							echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
							/* We construct two buttons (for 'go' and 'fulltext' search modes),
							 * but only one will be visible and actionable at a time (they are
							 * overlaid on top of each other in CSS).
							 * * Browsers will use the 'fulltext' one by default (as it's the
							 *   first in tree-order), which is desirable when they are unable
							 *   to show search suggestions (either due to being broken or
							 *   having JavaScript turned off).
							 * * The mediawiki.searchSuggest module, after doing tests for the
							 *   broken browsers, removes the 'fulltext' button and handles
							 *   'fulltext' search itself; this will reveal the 'go' button and
							 *   cause it to be used.
							 */
							echo $this->makeSearchButton(
								'fulltext',
								[ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ]
							);
							echo $this->makeSearchButton(
								'go',
								[ 'id' => 'searchButton', 'class' => 'searchButton' ]
							);
							?>
							</div>
						</form>
					</div>

					<?php
					break;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function makeLink( $key, $item, $options = [] ) {
		$html = parent::makeLink( $key, $item, $options );
		// Add an extra wrapper because our CSS is weird
		if ( isset( $options['metrolook-wrap'] ) && $options['metrolook-wrap'] ) {
			$html = Html::rawElement( 'span', [], $html );
		}
		return $html;
	}
	/**
	 * @inheritdoc
	 */
	public function makeListItem( $key, $item, $options = [] ) {
		// For fancy styling of watch/unwatch star
		if (
			$this->config->get( 'MetrolookUseIconWatch' )
			&& ( $key === 'watch' || $key === 'unwatch' )
		) {
			$item['class'] = rtrim( 'icon ' . $item['class'], ' ' );
			$item['primary'] = true;
		}
		// Add CSS class 'collapsible' to links which are not marked as "primary"
		if (
			isset( $options['metrolook-collapsible'] ) && $options['metrolook-collapsible']
			&& !( isset( $item['primary'] ) && $item['primary'] )
		) {
			$item['class'] = rtrim( 'collapsible ' . $item['class'], ' ' );
		}
		// We don't use this, prevent it from popping up in HTML output
		unset( $item['redundant'] );
		return parent::makeListItem( $key, $item, $options );
	}
}
