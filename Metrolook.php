<?php
/**
 * Metrolook - Modern UI version of Vector with a Metro UI look.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

$wgValidSkinNames['metrolook'] = 'Metrolook';

/**
 * SkinTemplate class for Vector skin
 * @ingroup Skins
 */
class SkinMetrolook extends SkinTemplate {

	var $skinname = 'Metrolook', $stylename = 'metrolook',
		$template = 'MetrolookTemplate', $useHeadElement = true;

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param $out OutputPage object to initialize
	 */
	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath, $wgRequest;

		parent::initPage( $out );

		// Append CSS which includes IE only behavior fixes for hover support -
		// this is better than including this in a CSS fille since it doesn't
		// wait for the CSS file to load before fetching the HTC file.
		$min = $wgRequest->getFuzzyBool( 'debug' ) ? '' : '.min';
		$out->addHeadItem( 'csshover',
			'<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
				htmlspecialchars( $wgLocalStylePath ) .
				"/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
		);

		$out->addModuleScripts( 'skins.metrolook' );
$out->addStyle('common/commonElements.css', 'screen');
$out->addStyle('common/commonContent.css', 'screen');
$out->addStyle('common/commonInterface.css', 'screen');
$out->addStyle('metrolook/screen.css', 'screen');
	}

	/**
	 * Load skin and user CSS files in the correct order
	 * fixes bug 22916
	 * @param $out OutputPage object
	 */
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.metrolook' );
	}
}

/**
 * QuickTemplate class for Vector skin
 * @ingroup Skins
 */
class MetrolookTemplate extends BaseTemplate {

	/* Members */

	/**
	 * @var Skin Cached skin object
	 */
	var $skin;

	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		global $wgLang, $wgVectorUseIconWatch;

		$this->skin = $this->data['skin'];

		// Build additional attributes for navigation urls
		//$nav = $this->skin->buildNavigationUrls();
		$nav = $this->data['content_navigation'];

		if ( $wgVectorUseIconWatch ) {
			$mode = $this->skin->getTitle()->userIsWatching() ? 'unwatch' : 'watch';
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
		if ( $wgLang->isRTL() ) {
			$this->data['view_urls'] =
				array_reverse( $this->data['view_urls'] );
			$this->data['namespace_urls'] =
				array_reverse( $this->data['namespace_urls'] );
			$this->data['personal_urls'] =
				array_reverse( $this->data['personal_urls'] );
		}
		// Output HTML Page
		$this->html( 'headelement' );
?>
    <style>
        body {
height:100%;
        }
        html {
height:100%;
        }
		html,
		body {
			margin: 0px 0px 0px 0px ;
			padding: 0px 0px 0px 0px ;
height:100%;
			}
		#top-tile-bar {
			background:transparent ;
			left: 0px ;
			height: 200px;
			position: fixed ;
			z-index:100 ;
			}
.tilebar {
position: fixed;
left: 0px;
top: 0px;
right: 0px;
bottom: 0px;
align:right;
color:#fff;background:#1D1D1D;width:100%;height:400px;
display:block;
z-index:102;
}
.tile:hover {
  outline: 3px #4A4A4A solid;
}
.onhoverbg:hover {
  background: #9F6F40;
}
.topleft {
        display: inline;
        position: relative;
    }
    .topright .hover {
        display: none;
        position: absolute;
        left:0;
        z-index: 2000;
	height:200px;
    }
    </style>

    <script>
var openDiv;
function toggleDiv(divID) {
    $("#" + divID).fadeToggle(150, function() {
        openDiv = $(this).is(':visible') ? divID : null;
    });
}
$(document).click(function(e) {
    if (!$(e.target).closest('#'+openDiv).length) {
        toggleDiv(openDiv);
    }
});
$(function () {
  $('.usermenu > div').toggleClass('no-js js');
  $('.usermenu .js div').hide();
  $('.usermenu .js').click(function(e) {
    $('.usermenu .js div').fadeToggle(150);
    $('.usermenu').toggleClass('active');
    e.stopPropagation();
  });
  $(document).click(function() {
    if ($('.usermenu .js div').is(':visible')) {
      $('.usermenu .js div', this).fadeOut(150);
      $('.usermenu').removeClass('active');
    }
  });
});

$(function () {
  $('.actionmenu > div').toggleClass('no-js js');
  $('.actionmenu .js div').hide();
  $('.actionmenu .js').click(function(e) {
    $('.actionmenu .js div').fadeToggle(150);
    $('.clicker').toggleClass('active');
    e.stopPropagation();
  });
  $(document).click(function() {
    if ($('.actionmenu .js div').is(':visible')) {
      $('.actionmenu .js div', this).fadeOut(150);
      $('.clicker').removeClass('active');
    }
  });
});
    </script>
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:700' defer="defer" rel='stylesheet' type='text/css'>
<meta name="msapplication-TileImage" content="http://www.pidgi.net/new/public/images/pidgiwiki.png"/>
<meta name="msapplication-TileColor" content="#BE0027"/>
<script src="http://files.pidgi.net/overthrow.js"></script>
		<div id="mw-page-base" class="noprint"></div>
		<div id="mw-head-base" class="noprint"></div>
		<!-- content -->
		<div id="content" class="overthrow">
			<a id="top"></a>
			<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
				<?php if( $this->data['newtalk'] ): ?>
				<!-- newtalk -->
				<div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
				<!-- /newtalk -->
				<?php endif; ?>
<div style="text-align:center;" align="center"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Sitenotice -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9225557705415007"
     data-ad-slot="2149272158"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></div>
			<?php if ( $this->data['sitenotice'] ): ?>
			<!-- sitenotice -->
			<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
			<!-- /sitenotice -->
			<?php endif; ?>
			<!-- firstHeading -->
			<div id="firstHeading" class="firstHeading"><?php $this->html( 'title' ) ?><br /><hr /></div>
			<!-- /firstHeading -->
			<!-- bodyContent -->
			<div id="bodyContent">
				<!-- subtitle -->
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
				<!-- /subtitle -->
				<?php if ( $this->data['undelete'] ): ?>
				<!-- undelete -->
				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<!-- /undelete -->
				<?php endif; ?>
				<?php if ( $this->data['showjumplinks'] ): ?>
				<!-- jumpto -->
				<div id="jump-to-nav">
					<?php $this->msg( 'jumpto' ) ?> <a href="#mw-head"><?php $this->msg( 'jumptonavigation' ) ?></a>,
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
				</div>
				<!-- /jumpto -->
				<?php endif; ?>
				<!-- bodycontent -->
				<?php $this->html( 'bodycontent' ) ?>
				<!-- /bodycontent -->
				<?php if ( $this->data['printfooter'] ): ?>
				<!-- printfooter -->
				<div class="printfooter">
				<?php $this->html( 'printfooter' ); ?>
				</div>
				<!-- /printfooter -->
				<?php endif; ?>
				<?php if ( $this->data['catlinks'] ): ?>
				<!-- catlinks -->
				<?php $this->html( 'catlinks' ); ?>
				<!-- /catlinks -->
				<?php endif; ?>
<br clear="all" />
<!-- footer -->
		<div id="footer"<?php $this->html( 'userlangattributes' ) ?>>
<hr />
			<?php foreach( $this->getFooterLinks() as $category => $links ): ?>
				<ul id="footer-<?php echo $category ?>">
					<?php foreach( $links as $link ): ?>
						<li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
			<?php $footericons = $this->getFooterIcons("icononly");
			if ( count( $footericons ) > 0 ): ?>
				<ul id="footer-icons" class="noprint">
<?php			foreach ( $footericons as $blockName => $footerIcons ): ?>
					<li id="footer-<?php echo htmlspecialchars( $blockName ); ?>ico">
<?php				foreach ( $footerIcons as $icon ): ?>
						<?php echo $this->skin->makeFooterIcon( $icon ); ?>
<?php				endforeach; ?>
					</li>
<?php			endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<!-- /footer -->
				<?php if ( $this->data['dataAfterContent'] ): ?>
				<!-- dataAfterContent -->
				<?php $this->html( 'dataAfterContent' ); ?>
				<!-- /dataAfterContent -->
				<?php endif; ?>
				<div class="visualClear"></div>
				<!-- debughtml -->
				<?php $this->html( 'debughtml' ); ?>
				<!-- /debughtml -->
			</div>
			<!-- /bodyContent -->
		</div>
		<!-- /content -->
		<!-- header -->
		<div id="mw-head" class="noprint">
			<div class="vectorMenu usermenu" style="float:right;background-image:none;vertical-align:middle;height:40px;padding-left:10px;padding-right:10px;position:absolute;top:0px;right:10px;width:auto;text-align:right;">
  <div class="no-js">
<a href="javascript:void(0);" style="text-decoration:none;"><span id="username-top"><?php
if ($_SERVER["REMOTE_ADDR"] == htmlspecialchars($this->getSkin()->getUser()->getName())) {
echo "Guest";
}
else {
echo htmlspecialchars( $this->getSkin()->getUser()->getName() );
}
 ?><span style="word-spacing:4px;"> </span><img style="position:relative;top:-1px;" src="<?php
$default = 'http://www.pidgi.net/wiki/skins/metrolook/images/user-icon.png';
$grav_url = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim( $this->getSkin()->getUser()->getEmail() ) ) ) . '?d=' . urlencode ( $default ) . '&s=' . 20;
echo $grav_url;
?>" /></span></a>
<div class="menu" style="position:absolute;top:40px;right:0px;margin:0;width:200px;">
<?php $this->renderNavigation( 'PERSONAL' ); ?>
</div>
</div>
</div>
<div style="padding-left:10px;"><div class="lighthover" style="height:40px;float:left;"><div class="onhoverbg" style="height:40px;float:left;"><a href="http://www.pidgi.net/wiki/Main_Page"><img src="http://images.pidgi.net/pidgiwiki.png" /></a></div><img src="http://images.pidgi.net/line.png" style="float:left;" /><div class="onhoverbg" style="height:40px;float:left;"><img src="http://images.pidgi.net/downarrow.png" style="cursor:pointer;" onclick="toggleDiv('bartile');"></div></div></div>
	<div id="top-tile-bar" class="fixed-position">
<div style="vertical-align:top;align:left;">
<div class="topleft">
<div style="align:left;margin-left:auto;margin-right:auto;display:none;height:200px;" class="tilebar" id="bartile"><div style="height:200px;display:table;"><div style="vertical-align:middle;display:table-cell;padding-left:36px;">
<div style="float:left;padding:5px;"><span class="tile"><a href="http://www.pidgi.net/wiki/"><img src="http://images.pidgi.net/pidgiwikitiletop.png" /></a></span></div><div style="float:left;padding:5px;"><span class="tile"><a href="http://www.pidgi.net/press/"><img src="http://images.pidgi.net/pidgipresstiletop.png" /></a></span></div><div style="float:left;padding:5px;"><span class="tile"><a href="http://www.pidgi.net/jcc/"><img src="http://images.pidgi.net/jcctiletop.png" /></a></span></div><div style="float:left;padding:5px;"><span class="tile"><a href="http://www.petalburgwoods.com/"><img src="http://images.pidgi.net/pwntiletop.png" /></a></span></div>

</div></div></div>
</div>

</div></div>
			<div id="left-navigation">
				<a href="http://www.pidgi.net/wiki/Special:Upload"><div class="onhoverbg" style="padding-left:0.8em;padding-right:0.8em;float:left;height:40px;font-size:10pt;"><img src="http://images.pidgi.net/uploadlogo.png" /> <span style="color:#fff;position:relative;top:1px;">Upload</span></div></a><?php $this->renderNavigation( array( 'NAMESPACES', 'VARIANTS', 'VIEWS', 'ACTIONS' ) ); ?>
			</div>
		</div>
		<!-- /header -->
		<!-- panel -->
			<div id="mw-panel" class="noprint">
				<?php $this->renderNavigation( array( 'SEARCH' ) ); ?>
				<br style="clear:both;line-height:0%;" />
<a href="http://www.pidgi.net/wiki/PidgiWiki:Requests" style="text-decoration:none;" class="hoverbox"><div style="width:142px;margin-left:10px;margin-top:4px;margin-bottom:4px;padding:8px;text-align:center;color:#fff;font-size:80%;" class="hoverbox">Send in a request</div></a>
				<?php $this->renderPortals( $this->data['sidebar'] ); ?>
<!-- feature -->
<div class="portal expanded" id='p-Feature'>
	<h5>Featured media</h5>
	<div class="body">
				<ul style="padding-right:20px;">
	<a href="<?php $this->msg('sidebar-feature-url') ?>">
	<img width="100%" title="<?php $this->msg('sidebar-feature-alttext') ?>" 
		alt="<?php $this->msg('sidebar-feature-alttext') ?>" 
		src="<?php $this->msg('sidebar-feature-imgsrc') ?>" /></a>
				</ul>
			</div>
</div>
<!-- /feature -->
<div id="p-Ads">
	<div class="body">
				<ul style="padding-right:20px;">
<!-- PidgiWiki left -->
<ins class="adsbygoogle"
     style="display:inline-block;width:120px;height:240px"
     data-ad-client="ca-pub-9225557705415007"
     data-ad-slot="2042690559"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
				</ul>
			</div>
</div>
			</div>
		<!-- /panel -->
			<div id="mw-panel-right" class="noprint">
				<a href="http://www.cardteam.co.uk/?acc=8e296a067a37563370ded05f5a3bf3ec&bannerid=6" target="_blank"><img src="http://www.cardteam.co.uk/js/magestore/affiliateplus/banner.php?id=6&account_id=26&store_id=1" title="Pokemon (160x600) Wide Skyscraper" width="160" height="600" /></a>
			</div>
		<!-- fixalpha -->
		<script type="<?php $this->text( 'jsmimetype' ) ?>"> if ( window.isMSIE55 ) fixalpha(); </script>
		<!-- /fixalpha -->
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
	private function renderPortals( $portals ) {
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
			if ( $content === false )
				continue;

			echo "\n<!-- {$name} -->\n";
			switch( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
				break;
			}
			echo "\n<!-- /{$name} -->\n";
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
		?>
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
			<h5<?php
			$this->html( 'userlangattributes' )
			?> id='<?php
			echo Sanitizer::escapeId( "p-$name-label" )
			?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h5>
	<div class="body">
<?php
		if ( is_array( $content ) ): ?>
		<ul>
<?php
			foreach( $content as $key => $val ): ?>
			<?php echo $this->makeListItem( $key, $val ); ?>

<?php
			endforeach;
			if ( isset( $hook ) ) {
				wfRunHooks( $hook, array( &$this, true ) );
			}
			?>
		</ul>
<?php
		else: ?>
		<?php echo $content; /* Allow raw HTML block to be defined by extensions */ ?>
<?php
		endif; ?>
	</div>
</div>
<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 */
	private function renderNavigation( $elements ) {
		global $wgVectorUseSimpleSearch, $wgVectorShowVariantName, $wgUser, $wgLang;

		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
		// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $wgLang->isRTL() ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			echo "\n<!-- {$name} -->\n";
			switch ( $element ) {
				case 'NAMESPACES':
?>
<div id="p-namespaces" class="vectorTabs<?php if ( count( $this->data['namespace_urls'] ) == 0 ) echo ' emptyPortlet'; ?>">
	<h5><?php $this->msg( 'namespaces' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['namespace_urls'] as $link ): ?>
			<li <?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></span></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
				break;
				case 'VARIANTS':
?>
<div id="p-variants" class="vectorMenu<?php if ( count( $this->data['variant_urls'] ) == 0 ) echo ' emptyPortlet'; ?>">
	<?php if ( $wgVectorShowVariantName ): ?>
		<h4>
		<?php foreach ( $this->data['variant_urls'] as $link ): ?>
			<?php if ( stripos( $link['attributes'], 'selected' ) !== false ): ?>
				<?php echo htmlspecialchars( $link['text'] ) ?>
			<?php endif; ?>
		<?php endforeach; ?>
		</h4>
	<?php endif; ?>
	<h5><span><?php $this->msg( 'variants' ) ?></span><a href="#"></a></h5>
	<div class="menu">
		<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php foreach ( $this->data['variant_urls'] as $link ): ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
				break;
				case 'VIEWS':
?>
<div id="p-views" class="vectorTabs<?php if ( count( $this->data['view_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>">
	<h5><?php $this->msg('views') ?></h5>
	<ul<?php $this->html('userlangattributes') ?>>
		<?php foreach ( $this->data['view_urls'] as $link ): ?>
			<li<?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php
				// $link['text'] can be undefined - bug 27764
				if ( array_key_exists( 'text', $link ) ) {
					echo array_key_exists( 'img', $link ) ?  '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />' : htmlspecialchars( $link['text'] );
				}
				?></a></span></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
				break;
				case 'ACTIONS':
?>
<div id="p-cactions" class="vectorMenu actionmenu<?php if ( count( $this->data['action_urls'] ) == 0 ) echo ' emptyPortlet'; ?>">
   <div class="no-js">
	<h5><a href="javascript:void(0);"><span><?php $this->msg( 'actions' ) ?></span></a></h5>
	<div class="menu">
		<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php foreach ( $this->data['action_urls'] as $link ): ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
   </div>
</div>
<?php
				break;
				case 'PERSONAL':
?>
<div id="p-personal" class="<?php if ( count( $this->data['personal_urls'] ) == 0 ) echo ' emptyPortlet'; ?>">
	<h5><?php $this->msg( 'personaltools' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
<?php			foreach( $this->getPersonalTools() as $key => $item ) { ?>
		<?php echo $this->makeListItem( $key, $item ); ?>

<?php			} ?>
	</ul>
</div>
<?php
				break;
				case 'SEARCH':
?>
<div id="p-search">
	<h5<?php $this->html( 'userlangattributes' ) ?>><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h5>
	<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
		<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
		<?php if ( $wgVectorUseSimpleSearch && $wgUser->getOption( 'vector-simplesearch' ) ): ?>
		<div id="simpleSearch">
			<?php if ( $this->data['rtl'] ): ?>
			<?php echo $this->makeSearchButton( 'image', array( 'id' => 'searchButton', 'src' => $this->skin->getSkinStylePath( 'images/search-rtl.png' ) ) ); ?>
			<?php endif; ?>
			<?php echo $this->makeSearchInput( array( 'id' => 'searchInput', 'type' => 'text' ) ); ?>
			<?php if ( !$this->data['rtl'] ): ?>
			<?php echo $this->makeSearchButton( 'image', array( 'id' => 'searchButton', 'src' => $this->skin->getSkinStylePath( 'images/search-ltr.png' ) ) ); ?>
			<?php endif; ?>
		</div>
		<?php else: ?>
		<?php echo $this->makeSearchInput( array( 'id' => 'searchInput' ) ); ?>
		<?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) ); ?>
		<?php echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton' ) ); ?>
		<?php endif; ?>
	</form>
</div>
<!-- Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];

(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();

_qevents.push({
qacct:"p-UKb2rEmzCK5k3"
});
</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-UKb2rEmzCK5k3.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38107770-1']);
  _gaq.push(['_setDomainName', 'pidgi.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php

				break;
			}
			echo "\n<!-- /{$name} -->\n";
		}
	}
}
