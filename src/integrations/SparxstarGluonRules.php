<?php

declare(strict_types=1);

namespace Starisian\Sparxstar\Gluon\integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SPARXSTAR Gluon Rules and Compliance Class
 *
 * Manages plugin compliance with privacy regulations and WordPress standards.
 * Integrates with the WordPress Consent API to handle user consent for cookies
 * and data tracking in compliance with GDPR and other privacy laws.
 *
 * This class handles:
 * - Cookie registration with WordPress Consent API
 * - Consent type management (opt-in/opt-out)
 * - User consent verification
 * - Consent category customization
 * - Integration with WordPress consent mechanisms
 *
 * @package    Starisian\Sparxstar\Gluon\Integrations
 * @subpackage Rules
 * @since      1.0.0
 * @version    1.0.0
 * @author     Starisian Technologies (Max Barrett) <support@starisian.com>
 * @link       https://developer.wordpress.org/apis/consent-api/ WordPress Consent API
 */
class SparxstarGluonRules {


	/**
	 * Cookie token identifier for the plugin.
	 *
	 * Uses __Host- prefix for enhanced security (requires HTTPS, no domain/path specified).
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private const GLUON_PLUGIN_COOKIE_TOKEN = '__Host-SparxstarGluon-TOKEN';

	/**
	 * Constructor - Initialize rules and hooks.
	 *
	 * Sets up plugin compliance rules and registers necessary WordPress hooks
	 * for consent management.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->gluonComplyWithRules();
		$this->gluonRegisterHooks();
	}

	/**
	 * Register WordPress hooks for rules and consent management.
	 *
	 * Sets up action and filter hooks needed for consent API integration.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function gluonRegisterHooks(): void {
		// Register hooks related to plugin rules here.
		\add_action( 'plugins_loaded', array( $this, 'gluonRegisterCookies' ) );
		// sets the consent type (optin, optout, default false)
		\add_filter( 'wp_get_consent_type', array( $this, 'gluonSetConsentType' ), 10, 1 );
		// Modify consent categories
		\add_filter( 'wp_get_consent_categories', array( $this, 'gluonSetConsentCategories' ), 10, 1 );
	}

	/**
	 * Register plugin with WordPress Consent API.
	 *
	 * Declares that the plugin is registered with and complies with
	 * the WordPress Consent API standards.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gluonComplyWithRules(): void {
		$plugin = GLUON_PLUGIN_NAME;
		\add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );
		// Implement logic to check if the plugin complies with specific rules.
	}

	/**
	 * Register cookies with the WordPress Consent API.
	 *
	 * Registers plugin cookies so they can be shown to users on the front-end
	 * via wp_get_cookie_info(). This is required for GDPR compliance.
	 *
	 * Cookie is registered with:
	 * - Token: __Host-SparxstarGluon-TOKEN
	 * - Purpose: Session management / functional
	 * - Type: functional (required for site operation)
	 *
	 * @since 1.0.0
	 * @see   https://developer.wordpress.org/apis/consent-api/#register-cookies
	 * @return void
	 */
	public function gluonRegisterCookies(): void {
		// If the Consent API function exists
		if ( function_exists( 'wp_add_cookie_info' ) ) {
			// Register the plugin cookie with the Consent API
			wp_add_cookie_info(
				'__Host-SparxstarGluon-TOKEN',
				'SparxstarGluon',
				'functional',
				__( 'Session', 'sparxstar-gluon' ),
				__( 'Stores a unique session token.', 'sparxstar-gluon' ),
				false,
				false,
				false
			);
		}
	}

	/**
	 * Unregister plugin cookies from the Consent API.
	 *
	 * Removes the registered cookie information when needed.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function gluonUnregisterCookies(): void {
		if ( \function_exists( 'wp_remove_cookie_info' ) ) {
			\wp_remove_cookie_info( self::GLUON_PLUGIN_COOKIE_TOKEN );
		}
	}

	/**
	 * Check if user has given consent for a specific category.
	 *
	 * Verifies whether the current user has granted consent for a specific
	 * purpose category (functional, marketing, statistics, etc.).
	 *
	 * @since 1.0.0
	 * @param string $category Optional. The consent category to check. Default 'functional'.
	 *                         Valid values: 'functional', 'preferences', 'statistics',
	 *                         'statistics-anonymous', 'marketing'.
	 * @return bool True if user has consented, false otherwise.
	 */
	public function gluonIsConsentCategory( string $category = 'functional' ): bool {
		// check if user has given marketing consent. Possible consent categories/purposes:
		// functional, preferences', statistics', statistics-anonymous', statistics', marketing',
		$consentCats = array( 'functional', 'preferences', 'statistics', 'statistics-anonymous', 'marketing' );
		if ( ! in_array( $category, $consentCats, true ) ) {
			return false;
		}
		if ( ! \function_exists( 'wp_has_consent' ) ) {
			return false;
		}
		return \wp_has_consent( $category );
	}

	/**
	 * Get the current consent type.
	 *
	 * Returns the site's consent type (opt-in or opt-out).
	 * Falls back to 'optin' if not set or if function doesn't exist.
	 *
	 * @since 1.0.0
	 * @param string $type Optional. The default type to return. Default 'optin'.
	 * @return string|null The consent type ('optin' or 'optout') or null of no consent type is set.
	 */
	public function gluonGetUserConsent(): string|false {
		if ( function_exists( 'wp_get_consent_type' ) ) {
			return \wp_get_consent_type();
		}
		return false;
	}

	/**
	 * Filter callback to modify consent categories.
	 *
	 * Allows customization of which consent categories are available.
	 * Currently removes the 'preferences' category.
	 *
	 * @since 1.0.0
	 * @param array $consentcategories The available consent categories.
	 * @return mixed Modified consent categories.
	 */
	public function gluonSetConsentCategories( array $consentcategories ): mixed {
		unset( $consentcategories['preferences'] );
		return $consentcategories;
	}
}
