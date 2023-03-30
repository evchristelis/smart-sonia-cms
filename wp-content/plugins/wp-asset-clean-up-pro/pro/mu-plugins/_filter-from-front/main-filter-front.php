<?php
if (! isset($originalActivePluginsList, $activePlugins, $activePluginsToUnload, $tagName, $wpacuAlreadyFilteredName)) {
	exit;
}

if (! defined('WPACU_EARLY_TRIGGERS_CALLED')) {
	require_once dirname(dirname(WPACU_MU_FILTER_PLUGIN_DIR)) . '/early-triggers.php';
}

if (assetCleanUpNoLoad()) {
	// Is the assets list fetched via AJAX?
	if ( isset( $_REQUEST['wpassetcleanup_load'] ) && $_REQUEST['wpassetcleanup_load'] ) {
		$hasNoLoadMatches = assetCleanUpHasNoLoadMatches();

		if ( 'is_set_in_settings' === $hasNoLoadMatches ) {
			$msg = sprintf( __( 'This page\'s URL is matched by one of the RegEx rules you have in <em>"Settings"</em> -&gt; <em>"Plugin Usage Preferences"</em> -&gt; <em>"Do not load the plugin on certain pages"</em>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and reload this page.',
				'wp-asset-clean-up' ), WPACU_PLUGIN_TITLE );
			exit( $msg );
		}

		if ( 'is_set_in_page' === $hasNoLoadMatches ) {
			$msg = sprintf( __( 'This page\'s URI is matched by the rule you have in the "Page Options", thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please uncheck the following option shown below: <em>"Do not load Asset CleanUp Pro on this page (this will disable any functionality of the plugin"</em>.',
				'wp-asset-clean-up' ), WPACU_PLUGIN_TITLE );
			exit( $msg );
		}
	}

	// "total_no_load" would remove Asset CleanUp plugins from "active_plugins"
	// It can lead to the plugins deactivated completely IF there are plugins that wrongly the "active_plugins" filter (e.g. by re-updating the option with the filtered plugin list instead of the original list)
	// "early_no_load" is the most reliable to avoid wrongfully site-wide deactivation of the plugin
	$wpacuPluginNoLoadMethod = 'early_no_load';

	if ($wpacuPluginNoLoadMethod === 'early_no_load') {
		add_filter( 'wpacu_plugin_no_load', '__return_true' );
	} elseif ($wpacuPluginNoLoadMethod === 'total_no_load') {
		// Do not load Asset CleanUp Pro (including the Lite version if it's also activated) at all due to the rules from /early-triggers.php OR due to the request in the settings (e.g. via /?wpacu_no_load query string)
		// As a result, no other plugin rules (e.g from "Plugins Manager") should be triggered either
		// Stop here with the plugin filtering
		if ( array_key_exists( 'wp-asset-clean-up/wpacu.php', $activePlugins ) ) {
			$activePluginsToUnload[] = 'wp-asset-clean-up/wpacu.php';
		}

		$activePluginsToUnload[] = 'wp-asset-clean-up-pro/wpacu.php';
	}
} else {
	// Any /?wpacu_filter_plugins=[...] /?wpacu_only_load_plugins=[...] requests
	// Any plugins from /?wpacu_debug marked for unloading (the system will only mark those ones for debugging purposes)
	$wpacuOnlyLoadPluginsQueryStringUsed = false; // default
	$wpacuIsUnloadPluginsViaDebugForm = (isset($_POST['wpacu_filter_plugins']) && is_array($_POST['wpacu_filter_plugins']) && ! empty($_POST['wpacu_filter_plugins'])) || (isset($_POST['wpacu_debug']) && $_POST['wpacu_debug'] === 'on');

	if ( isset( $_GET['wpacu_filter_plugins'] ) || isset( $_GET['wpacu_only_load_plugins'] ) || $wpacuIsUnloadPluginsViaDebugForm ) {
		$wpacuAllowPluginFilterViaDebugForGuests = defined( 'WPACU_FILTER_PLUGINS_VIA_QUERY_STRING_FOR_GUESTS' ) && WPACU_FILTER_PLUGINS_VIA_QUERY_STRING_FOR_GUESTS;

		if ( $wpacuAllowPluginFilterViaDebugForGuests ) {
			// Non-logged visitors can also do the query string filtering
			require WPACU_MU_FILTER_PLUGIN_DIR . '/_common/_filter-via-debug.php';
		} else {
			// Only the admin can do the query string filtering (default)
			if ( ! defined( 'WPACU_PLUGGABLE_LOADED' ) ) {
				require_once WPACU_MU_FILTER_PLUGIN_DIR . '/pluggable-custom.php';
				define( 'WPACU_PLUGGABLE_LOADED', true );
			}

			if ( function_exists( 'wpacu_current_user_can' ) && wpacu_current_user_can( 'administrator' ) ) {
				require WPACU_MU_FILTER_PLUGIN_DIR . '/_common/_filter-via-debug.php';
			}
		}
	}

	// Neither of the following was used for debugging purposes?
	// 1) /?wpacu_only_load_plugins=
	// 2) The plugin unload form from /?wpacu_debug
	// As a result, go through the unload rules from "Plugins Manager" -> "IN FRONTEND VIEW (your visitors)"
	if ( ! $wpacuOnlyLoadPluginsQueryStringUsed && ! $wpacuIsUnloadPluginsViaDebugForm ) {
		// Is "Test Mode" disabled OR enabled but the admin is viewing the page? Continue
		// Fetch the existing rules (unload, load exceptions, etc.)
		require __DIR__ . '/_filter-from-rules-front.php';
	}
}
