<?php
/**
 * SPARXSTAR Gluon Uninstall Script
 *
 * This file is executed when the plugin is deleted through the WordPress admin.
 * Handles cleanup of plugin data based on the GLUON_PLUGIN_DELETE_ON_UNINSTALL setting.
 * Supports both single-site and multisite WordPress installations.
 *
 * Security: Only runs when called by WordPress uninstall process.
 *
 * @package   Starisian\Sparxstar\Gluon
 * @author    Starisian Technologies (Max Barrett) <support@starisian.com>
 * @license   MIT
 * @copyright Copyright (c) 2026 Starisian Technologies.
 * @since     1.0.0
 * @version   1.0.0
 */

// Security: Exit if not called by WordPress uninstall process
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Define delete on uninstall setting if not already defined.
 *
 * @since 1.0.0
 */
if ( ! defined( 'GLUON_PLUGIN_DELETE_ON_UNINSTALL' ) ) {
	define( 'GLUON_PLUGIN_DELETE_ON_UNINSTALL', false );
}

// Access WordPress database
global $wpdb;

/**
 * Handle Multisite Uninstall
 *
 * For multisite installations, loop through all sites and perform
 * cleanup on each one individually.
 */
if ( is_multisite() ) {

	// Get all site IDs in the network

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( $site_id );

		// Optional: cleanup logic
		if ( GLUON_PLUGIN_DELETE_ON_UNINSTALL ) {
			// Perform cleanup tasks like deleting options, custom tables, etc.
			delete_option( 'gluon_settings' );
		} else {
			// Retain data for potential future use
		}

		restore_current_blog();
	}
} else {

	/**
	 * Handle Single Site Uninstall
	 *
	 * For single-site installations, perform cleanup directly.
	 */

	// Optional: cleanup logic
	if ( GLUON_PLUGIN_DELETE_ON_UNINSTALL ) {
		// Perform cleanup tasks like deleting options, custom tables, etc.
		delete_option( 'gluon_settings' );
	} else {
		// Retain data for potential future use
	}
}
