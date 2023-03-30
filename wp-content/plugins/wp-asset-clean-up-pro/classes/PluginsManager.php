<?php
namespace WpAssetCleanUp;

/**
 * Class PluginsManager
 * @package WpAssetCleanUp
 */
class PluginsManager
{
    /**
     * @var array
     */
    public $data = array();

	/**
	 * PluginsManager constructor.
	 */
	public function __construct()
    {
        // Note: The rules update takes place in /pro/classes/UpdatePro.php
	    if (Misc::getVar('get', 'page') === WPACU_PLUGIN_ID . '_plugins_manager') {
		    add_action('wpacu_admin_notices', array($this, 'notices'));
	    }
    }

	/**
	 *
	 */
	public function page()
    {
    	// Get active plugins and their basic information
	    $this->data['active_plugins'] = self::getActivePlugins();
	    $this->data['plugins_icons']  = Misc::getAllActivePluginsIcons();

	    // [wpacu_pro]
	    $this->data['rules']          = self::getAllRules(); // get all rules from the database (for either the frontend or dash view)

	    $this->data['mu_file_missing']  = false; // default
	    $this->data['mu_file_rel_path'] = '/' . str_replace(Misc::getWpRootDirPath(), '', WPMU_PLUGIN_DIR)
	                                      . '/' . \WpAssetCleanUpPro\PluginPro::$muPluginFileName;

	    if ( ! is_file(WPMU_PLUGIN_DIR . '/' . \WpAssetCleanUpPro\PluginPro::$muPluginFileName) ) {
			$this->data['mu_file_missing']  = true; // alert the user in the "Plugins Manager" area
	    }
        // [/wpacu_pro]

	    Main::instance()->parseTemplate('admin-page-plugins-manager', $this->data, true);
    }

    // [wpacu_pro]
	/**
	 * @param false $fetchAllLocations (if set to true, it will return the rules for both the frontend and the backend
	 *
	 * @return array
	 */
	public static function getAllRules($fetchAllLocations = false)
	{
		$pluginsRulesDbListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($pluginsRulesDbListJson) {
			$regExDbList = @json_decode($pluginsRulesDbListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return array();
			}

			// 1) For listing them in "Overview"
			if ($fetchAllLocations) {
                $rulesList = array();

				if ( isset( $regExDbList['plugins'] ) && ! empty( $regExDbList['plugins'] ) ) {
					$rulesList['plugins'] = $regExDbList['plugins'];
				}

				if ( isset( $regExDbList['plugins_dash'] ) && ! empty( $regExDbList['plugins_dash'] ) ) {
					$rulesList['plugins_dash'] = $regExDbList['plugins_dash'];
				}

				return $rulesList;
            }

			// 2) For listing them within "Plugins Manager" -> "In Frontend View" or "In the Dashboard" when the admin is managing the rules
			$wpacuSubPage = ( isset($_GET['wpacu_sub_page']) && $_GET['wpacu_sub_page'] ) ? $_GET['wpacu_sub_page'] : 'manage_plugins_front';

			$mainGlobalKey = ($wpacuSubPage === 'manage_plugins_front') ? 'plugins' : 'plugins_dash';

			if ( isset( $regExDbList[$mainGlobalKey] ) && ! empty( $regExDbList[$mainGlobalKey] ) ) {
				return $regExDbList[$mainGlobalKey];
			}
		}

		return array();
	}
    // [/wpacu_pro]

	/**
	 * @return array
	 */
	public static function getActivePlugins()
	{
		$activePluginsFinal = array();

		if ( isset($GLOBALS['wpacu_original_active_plugins']) && is_array($GLOBALS['wpacu_original_active_plugins']) && ! empty($GLOBALS['wpacu_original_active_plugins']) ) {
			$activePlugins = array();

			foreach ( $GLOBALS['wpacu_original_active_plugins'] as $plugin ) {
				if ( ! validate_file( $plugin )                     // $plugin must validate as file.
				     && '.php' === substr( $plugin, - 4 )             // $plugin must end with '.php'.
				     && file_exists( WP_PLUGIN_DIR . '/' . $plugin ) // $plugin must exist.
				) {
					$activePlugins[] = WP_PLUGIN_DIR . '/' . $plugin;
				}
			}
		} else {
			// Get active plugins and their basic information
			$activePlugins = wp_get_active_and_valid_plugins();
		}

		// Also check any network activated plugins in case we're dealing with a MultiSite setup
		if ( is_multisite() ) {
			$activeNetworkPlugins = wp_get_active_network_plugins();

			if ( ! empty( $activeNetworkPlugins ) ) {
				foreach ( $activeNetworkPlugins as $activeNetworkPlugin ) {
					$activePlugins[] = $activeNetworkPlugin;
				}
			}
		}

		$activePlugins = array_unique($activePlugins);

		foreach ($activePlugins as $pluginPath) {
			// Skip Asset CleanUp as it's obviously needed for the functionality
			if (strpos($pluginPath, 'wp-asset-clean-up') !== false) {
				continue;
			}

			$networkActivated = isset($activeNetworkPlugins) && in_array($pluginPath, $activeNetworkPlugins);

			$pluginRelPath = trim(str_replace(WP_PLUGIN_DIR, '', $pluginPath), '/');

			$pluginData = get_plugin_data($pluginPath);

			$activePluginsFinal[] = array(
                'title'             => $pluginData['Name'],
                'path'              => $pluginRelPath,
                'network_activated' => $networkActivated
			);
		}

		usort($activePluginsFinal, static function($a, $b)
		{
			return strcmp($a['title'], $b['title']);
		});

		return $activePluginsFinal;
	}

	// [wpacu_pro]
	/**
	 * Make sure there is a status for the rule, otherwise it's likely set to "Load it",
	 * thus the rule wouldn't count
	 * @param bool $checkIfPluginIsActive
	 * @param bool $getRulesForAllLocations
     *
	 * @return array
	 */
	public static function getPluginRulesFiltered($checkIfPluginIsActive = true, $getRulesForAllLocations = false)
    {
	    $pluginsWithRules = array();

		$pluginsAllDbRules = self::getAllRules($getRulesForAllLocations);

		// Are there any load exceptions / unload RegExes?
	    if (! empty( $pluginsAllDbRules ) ) {
	        foreach ($pluginsAllDbRules as $locationKey => $pluginsRules) {
		        foreach ( $pluginsRules as $pluginPath => $pluginData ) {
			        // Only the rules for the active plugins are retrieved
			        if ( $checkIfPluginIsActive && ! Misc::isPluginActive( $pluginPath ) ) {
				        continue;
			        }

			        // 'status' refers to the Unload Status (any option that was chosen)
			        $pluginStatus = isset( $pluginData['status'] ) && ! empty( $pluginData['status'] ) ? $pluginData['status'] : array();

			        if ( ! empty( $pluginStatus ) ) {
				        $pluginsWithRules[ $locationKey ][ $pluginPath ] = $pluginData;
			        }
		        }
	        }

		    }

	    return $pluginsWithRules;
    }

	/**
	 *
	 */
	public function notices()
	{
		// After "Save changes" is clicked
		if (get_transient('wpacu_plugins_manager_updated')) {
			delete_transient('wpacu_plugins_manager_updated');

			$appliedForText = '';
			if ( isset($_GET['wpacu_sub_page']) ) {
				if ( $_GET['wpacu_sub_page'] === 'manage_plugins_front' ) {
					$appliedForText = 'the frontend view';
				} elseif ( $_GET['wpacu_sub_page'] === 'manage_plugins_dash' ) {
					$appliedForText = 'the Dashboard view (/wp-admin/)';
				}
			}

			if ($appliedForText !== '') {
			?>
			<div style="margin-bottom: 15px; margin-left: 0; width: 90%;" class="notice notice-success is-dismissible">
				<p><span class="dashicons dashicons-yes"></span> <?php echo sprintf(__('The plugins\' rules were successfully applied within %s.', 'wp-asset-clean-up'), $appliedForText); ?></p>
			</div>
			<?php
            }
		}
	}
	// [/wpacu_pro]
}
