<?php

/**
 * Hooks for Metrolook skin
 *
 * @file
 * @ingroup Skins
 */

use MediaWiki\Config\Config;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Output\Hook\MakeGlobalVariablesScriptHook;
use MediaWiki\Preferences\Hook\GetPreferencesHook;
use MediaWiki\ResourceLoader\Hook\ResourceLoaderGetConfigVarsHook;

class SkinMetrolookHooks implements
	BeforePageDisplayHook,
	GetPreferencesHook,
	ResourceLoaderGetConfigVarsHook,
	MakeGlobalVariablesScriptHook
{

	/* Protected Static Members */

	/** @var array */
	protected static $features = [
		'collapsiblenav' => [
			'preferences' => [
				'skinmetrolook-collapsiblenav' => [
					'type' => 'toggle',
					'label-message' => 'skinmetrolook-collapsiblenav-preference',
					'section' => 'rendering/advancedrendering',
				],
			],
			'requirements' => [
				'skinmetrolook-collapsiblenav' => true,
			],
			'modules' => [ 'skins.metrolook.collapsibleNav' ],
		]
	];

	/* Static Methods */

	/**
	 * Checks if a certain option is enabled
	 *
	 * This method is public to allow other extensions that use CollapsibleVector to use the
	 * same configuration as CollapsibleVector itself
	 *
	 * @param string $name Name of the feature, should be a key of $name
	 * @return bool
	 */
	public function isEnabled( string $name ): bool {
		global $wgMetrolookFeatures;

		// Features with global set to true are always enabled
		if ( !isset( $wgMetrolookFeatures[$name] ) || $wgMetrolookFeatures[$name]['global'] ) {
			return true;
		}
		// Features with user preference control can have any number of preferences
		// to be specific values to be enabled
		if ( $wgMetrolookFeatures[$name]['user'] ) {
			if ( isset( self::$features[$name]['requirements'] ) ) {
				$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
				$user = RequestContext::getMain()->getUser();
				foreach ( self::$features[$name]['requirements'] as $requirement => $value ) {
					// Important! We really do want fuzzy evaluation here
					if ( $userOptionsLookup->getOption( $user, $requirement ) != $value ) {
						return false;
					}
				}
			}
			return true;
		}
		// Features controlled by $wgMetrolookFeatures with both global and user
		// set to false are awlways disabled
		return false;
	}

	/* Static Methods */

	/**
	 * BeforePageDisplay hook
	 *
	 * Adds the modules to the page
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return void This hook must not abort, it must return no value
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( $skin instanceof SkinMetrolook ) {
			// Add modules for enabled features
			foreach ( self::$features as $name => $feature ) {
				if ( isset( $feature['modules'] ) && $this->isEnabled( $name ) ) {
					$out->addModules( $feature['modules'] );
				}
			}
		}
	}

	/**
	 * GetPreferences hook
	 *
	 * Adds Metrolook-related items to the preferences
	 *
	 * @param User $user Current user
	 * @param array &$defaultPreferences List of default user preference controls
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetPreferences( $user, &$defaultPreferences ) {
		global $wgMetrolookFeatures;

		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['preferences'] ) &&
				( !isset( $wgMetrolookFeatures[$name] ) || $wgMetrolookFeatures[$name]['user'] )
			) {
				foreach ( $feature['preferences'] as $key => $options ) {
					$defaultPreferences[$key] = $options;
				}
			}
		}
		return true;
	}

	/**
	 * ResourceLoaderGetConfigVars hook
	 *
	 * Adds enabled/disabled switches for Vector modules
	 * @param array &$vars
	 * @param string $skin
	 * @param Config $config
	 * @return void This hook must not abort, it must return no value
	 */
	public function onResourceLoaderGetConfigVars( array &$vars, $skin, Config $config ): void {
		global $wgMetrolookFeatures, $wgMetrolookSearchBar;

		$configurations = [];
		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['configurations'] ) &&
				( !isset( $wgMetrolookFeatures[$name] ) || $this->isEnabled( $name ) )
			) {
				foreach ( $feature['configurations'] as $configuration ) {
					// @phan-suppress-next-line PhanUndeclaredVariable
					global $$wgConfiguration;
					$configurations[$configuration] = $$wgConfiguration;
					// @phan-suppress-previous-line PhanUndeclaredVariable
				}
			}
		}

		$vars['wgMetrolookSearch'] = $wgMetrolookSearchBar;

		if ( count( $configurations ) ) {
			$vars = array_merge( $vars, $configurations );
		}
	}

	/**
	 * @param array &$vars
	 * @param OutputPage $out OutputPage which called the hook, can be used to get the real title
	 * @return void This hook must not abort, it must return no value
	 */
	public function onMakeGlobalVariablesScript( &$vars, $out ): void {
		// Build and export old-style wgMetrolookEnabledModules object for back compat
		$enabledModules = [];
		foreach ( self::$features as $name => $feature ) {
			$enabledModules[$name] = $this->isEnabled( $name );
		}

		$vars['wgMetrolookEnabledModules'] = $enabledModules;
	}
}
