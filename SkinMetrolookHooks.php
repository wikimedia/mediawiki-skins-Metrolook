<?php

/**
 * Hooks for Metrolook skin
 *
 * @file
 * @ingroup Skins
 */

class MetrolookHooks {

	/* Protected Static Members */

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
	 * @param $name string Name of the feature, should be a key of $name
	 * @return bool
	 */
	public static function isEnabled( $name ) {
		global $wgMetrolookFeatures, $wgUser;

		// Features with global set to true are always enabled
		if ( !isset( $wgMetrolookFeatures[$name] ) || $wgMetrolookFeatures[$name]['global'] ) {
			return true;
		}
		// Features with user preference control can have any number of preferences
		// to be specific values to be enabled
		if ( $wgMetrolookFeatures[$name]['user'] ) {
			if ( isset( self::$features[$name]['requirements'] ) ) {
				foreach ( self::$features[$name]['requirements'] as $requirement => $value ) {
					// Important! We really do want fuzzy evaluation here
					if ( $wgUser->getOption( $requirement ) != $value ) {
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
	 */
	public static function beforePageDisplay( $out, $skin ) {
		if ( $skin instanceof SkinMetrolook ) {
			// Add modules for enabled features
			foreach ( self::$features as $name => $feature ) {
				if ( isset( $feature['modules'] ) && self::isEnabled( $name ) ) {
					$out->addModules( $feature['modules'] );
				}
			}
		}
		return true;
	}

	/**
	 * GetPreferences hook
	 *
	 * Adds Vector-releated items to the preferences
	 *
	 * @param User current user $user
	 * @param array list of default user preference controls &$defaultPreferences
	 */
	public static function getPreferences( $user, &$defaultPreferences ) {
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
	 */
	public static function resourceLoaderGetConfigVars( &$vars ) {
		global $wgMetrolookFeatures, $wgMetrolookSearchBar;

		$configurations = [];
		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['configurations'] ) &&
				( !isset( $wgMetrolookFeatures[$name] ) || self::isEnabled( $name ) )
			) {
				foreach ( $feature['configurations'] as $configuration ) {
					global $$wgConfiguration;
					$configurations[$configuration] = $$wgConfiguration;
				}
			}
		}

		$vars['wgMetrolookSearch'] = $wgMetrolookSearchBar;

		if ( count( $configurations ) ) {
			$vars = array_merge( $vars, $configurations );
		}

		return true;
	}

	/**
	 * @param array &$vars
	 * @return bool
	 */
	public static function makeGlobalVariablesScript( &$vars ) {
		// Build and export old-style wgMetrolookEnabledModules object for back compat
		$enabledModules = [];
		foreach ( self::$features as $name => $feature ) {
			$enabledModules[$name] = self::isEnabled( $name );
		}

		$vars['wgMetrolookEnabledModules'] = $enabledModules;
		return true;
	}
}
