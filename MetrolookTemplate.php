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

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		global $wgMetrolookLogo, $wgMetrolookSearchBar, $wgMetrolookDownArrow, $wgMetrolookLine,
		$wgMetrolookUploadButton, $wgMetrolookSiteName, $wgMetrolookBartile, $wgMetrolookTile1, 
		$wgMetrolookTile2, $wgMetrolookTile3, $wgMetrolookTile4, $wgMetrolookTile5, $wgMetrolookTile6, 
		$wgMetrolookTile7, $MetrolookwgTile8, $wgMetrolookTile9, $wgMetrolookTile10, $wgMetrolookImage1, 
		$wgMetrolookURL1, $wgMetrolookImage2, $wgMetrolookURL2, $wgMetrolookImage3, $wgMetrolookURL3, 
		$wgMetrolookImage4, $wgMetrolookURL4, $wgMetrolookImage5, $wgMetrolookURL5, $wgMetrolookImage6, 
		$wgMetrolookURL6, $wgMetrolookMobile;

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
		// Output HTML Page
		$this->html( 'headelement' );
?>

<?php if ( $wgMetrolookMobile ): ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php else: ?>
<?php endif; ?>

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
			<h1 id="firstHeading" class="firstHeading" lang="<?php
			$this->data['pageLanguage'] =
				$this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
			$this->text( 'pageLanguage' );
			?>"><?php $this->html( 'title' ) ?></h1>
			<?php $this->html( 'prebodyhtml' ) ?>
			<div id="bodyContent" class="mw-body-content">
				<?php
				if ( $this->data['isarticle'] ) {
					?>
					<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<?php
				}
				?>
				<div id="contentSub"<?php
				$this->html( 'userlangattributes' )
				?>><?php $this->html( 'subtitle' ) ?></div>
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
					<a href="#mw-navigation"><?php
						$this->msg( 'jumptonavigation' )
						?></a><?php
					$this->msg( 'comma-separator' )
					?>
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
				</div>
				<?php $this->html( 'bodycontent' ) ?>
				<?php
				if ( $this->data['printfooter'] ) {
					?>
					<div class="printfooter">
						<?php $this->html( 'printfooter' ); ?>
					</div>
				<?php
				}
				?>
				<?php
				if ( $this->data['catlinks'] ) {
					?>
					<?php
					$this->html( 'catlinks' );
					?>
				<?php
				}
				?>
<br clear="all" />
		<div id="footer" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
<hr />
			<?php
			foreach ( $this->getFooterLinks() as $category => $links ) {
				?>
				<ul id="footer-<?php
				echo $category
				?>">
					<?php
					foreach ( $links as $link ) {
						?>
						<li id="footer-<?php
						echo $category
						?>-<?php
						echo $link
						?>"><?php
							$this->html( $link )
							?></li>
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
						<li id="footer-<?php
						echo htmlspecialchars( $blockName ); ?>ico">
							<?php
							foreach ( $footerIcons as $icon ) {
								?>
								<?php
								echo $this->getSkin()->makeFooterIcon( $icon );
								?>

							<?php
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
					?>
					<?php
					$this->html( 'dataAfterContent' );
					?>
				<?php
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
<a href="#" style="text-decoration:none;"><span id="username-top"><span id="username-text"><?php
if ($_SERVER["REMOTE_ADDR"] == htmlspecialchars($this->getSkin()->getUser()->getName())) {
echo wfMessage( 'metrolook-guest' )->text();
}
else {
echo htmlspecialchars( $this->getSkin()->getUser()->getName() );
}
 ?></span><span style="word-spacing:4px;"> </span><span id="userIcon20"><img class="userIcon20" style="position:relative;top:0.3em;" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" /></span>
<span style="word-spacing:4px;"> </span><span id="userIcon40"><img class="userIcon40" style="position:relative;top:0.4em;" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
</span></span></a>
<div class="menu" style="position:absolute;top:40px;right:0px;margin:0;width:200px;">
<?php $this->renderNavigation( 'PERSONAL' ); ?>
</div>
</div>
</div>
<div id="echoNotifications">
	<ul>
	<?php
		echo $this->mPersonalToolsEcho;
	?>
	</ul>
</div>

<div id="hamburgerIcon"><img class="hamburger" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" height="40px" width="40px"></img></div>
<?php if ( $wgMetrolookSiteName ): ?><div style="padding-left:10px;"><div id="siteLogoBar" class="lighthover" style="height:40px;float:left;"><div class="onhoverbg" style="height:40px;float:left;"><h4 class="title-name"><a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>"><div class="title-name" style="font-size: 0.9em; padding-left:0.4em;padding-right:0.4em;color:white;max-width: auto;height:auto; max-height:700px; display: inline-block; vertical-align:middle;"><?php echo $GLOBALS['wgSitename'] ?></div></a></h4></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookLine ): ?><img class="line" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" style="float:left;" /><?php else: ?><?php endif; ?><?php if ( $wgMetrolookDownArrow ): ?><img class="downarrow" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" style="height:60px;width:27px;cursor:pointer;" /><?php else: ?><?php endif; ?><?php if ( $wgMetrolookSiteName ): ?></div></div><?php else: ?><?php endif; ?>

<?php if ( $wgMetrolookDownArrow ): ?>
	<div id="top-tile-bar" class="fixed-position">

<div style="vertical-align:top;align:left;">
<div class="topleft">
<div style="align:left;margin-left:auto;margin-right:auto;display:none;" class="tilebar" id="bartile"><div id="tilegrouptable"><div id="tilegroup">
<?php if ( $wgMetrolookBartile ): ?>

<?php if ( $wgMetrolookTile1 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.pidgi.net/wiki/"><img src="http://images.pidgi.net/pidgiwikitiletop.png" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile2 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.pidgi.net/press/"><img src="http://images.pidgi.net/pidgipresstiletop.png" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile3 ): ?><div style="float:left;padding:5px;" id="jcctile"><div class="tile"><a href="http://www.pidgi.net/jcc/"><img src="http://images.pidgi.net/jcctiletop.png" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile4 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="http://www.petalburgwoods.com/"><img src="http://images.pidgi.net/pwntiletop.png" /></a></div></div><?php else: ?><?php endif; ?>

<?php else: ?>

<?php if ( $wgMetrolookTile5 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL1'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage1'] ?>" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile6 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL2'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage2'] ?>" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile7 ): ?><div style="float:left;padding:5px;" id="jcctile"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL3'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage4'] ?>" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile8 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL4'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage4'] ?>" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile9 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL5'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage5'] ?>" /></a></div></div><?php else: ?><?php endif; ?><?php if ( $wgMetrolookTile10 ): ?><div style="float:left;padding:5px;"><div class="tile"><a href="<?php echo $GLOBALS['$wgMetrolookURL6'] ?>"><img src="<?php echo $GLOBALS['$wgMetrolookImage6'] ?>" /></a></div></div><?php else: ?><?php endif; ?>

<?php endif; ?>

</div></div></div>
</div>

</div></div>
<?php else: ?>
<?php endif; ?>
			<div id="left-navigation">
				<?php if ( $wgMetrolookUploadButton ): ?><a href="<?php echo $this->data['nav_urls']['upload']['href']; ?>"><div class="onhoverbg" style="padding-left:0.8em;padding-right:0.8em;float:left;height:40px;font-size:10pt;"><img class="uploadbutton" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" /> <span style="color:#fff;position:relative;top:3px; "><?php $this->msg('uploadbtn') ?></span></div></a><?php else: ?><?php endif; ?><?php $this->renderNavigation( array( 'NAMESPACES', 'VARIANTS', 'VIEWS', 'ACTIONS' ) ); ?>
			</div>

				<div id="editbutton"><img class="editbutton" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" /></div>
				<?php if ( $wgMetrolookSearchBar ): ?>
				<img class="searchbar" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath( 'images/Transparent.gif' ) ) ?>" />
				<?php else: ?>
				<?php endif; ?>

			<div id="right-navigation">
				<?php if ( $wgMetrolookSearchBar ): ?>
				<?php $this->renderNavigation( array( 'SEARCH' ) ); ?>
				<?php else: ?>
				<?php endif; ?>
			</div>
		</div>

			<?php if ( $wgMetrolookSearchBar ): ?>
			<div id="mw-panel">
			<?php if ( $wgMetrolookLogo ): ?>
				<div id="p-logo" role="banner"><a style="background-image: url(<?php
					$this->text( 'logopath' )
					?>);" href="<?php
					echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
					?>" <?php
					echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
					?>></a></div>
				<?php else: ?>
				<?php endif; ?>
				<?php $this->renderPortals( $this->data['sidebar'] ); ?>
			</div>
			<?php else: ?>
			<div id="mw-panel-custom">
			<?php if ( $wgMetrolookLogo ): ?>
				<div id="p-logo" role="banner"><a style="background-image: url(<?php
					$this->text( 'logopath' )
					?>);" href="<?php
					echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
					?>" <?php
					echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
					?>></a></div>
				<?php else: ?>
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
		global $wgMetrolookSearchBar;
		
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		?>
		<?php if ( $wgMetrolookSearchBar ): ?>
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
		<?php else: ?>
		<div class="portal-custom" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
		<?php endif; ?>	
			<h5<?php
			$this->html( 'userlangattributes' )
			?> id='<?php
			echo Sanitizer::escapeId( "p-$name-label" )
			?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h5>

			<?php if ( $wgMetrolookSearchBar ): ?>
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
							?>
							<?php echo $this->makeListItem( $key, $val ); ?>

						<?php
						}
						if ( $hook !== null ) {
							wfRunHooks( $hook, array( &$this, true ) );
						}
						?>
					</ul>
				<?php
				} else {
					?>
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
	 * @param array $elements
	 */
	protected function renderNavigation( $elements ) {
		global $wgMetrolookSearchBar;

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
								<li <?php
								echo $link['attributes']
								?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key']
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
						<h5 id="p-variants-label"><span><?php echo htmlspecialchars( $variantLabel ) ?></span><a href="#"></a></h5>

						<div class="menu">
							<ul>
								<?php
								foreach ( $this->data['variant_urls'] as $link ) {
									?>
									<li<?php
									echo $link['attributes']
									?>><a href="<?php
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
						<ul<?php
						$this->html( 'userlangattributes' )
						?>>
							<?php
							foreach ( $this->data['view_urls'] as $link ) {
								?>
								<li<?php
								echo $link['attributes']
								?>><span><a href="<?php
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
									<li<?php
									echo $link['attributes']
									?>>
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
					<?php if ( $wgMetrolookSearchBar ): ?>
					<div id="p-search" role="search">
						<h5<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h5>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<?php
							if ( $this->config->get( 'VectorUseSimpleSearch' ) ) {
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
					<?php else: ?>
					<div id="p-searchSearch" role="search">
						<h5<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h5>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<?php
							if ( $this->config->get( 'VectorUseSimpleSearch' ) ) {
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
					<?php endif; ?> 
					<?php

					break;
			}
		}
	}
}
