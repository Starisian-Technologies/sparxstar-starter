<?php
/**
 * Session Management for SPARXSTAR Gluon
 *
 * This file contains the SparxstarGluonSession class which handles
 * secure session management with cookie-based tokens and consent integration.
 *
 * @package    Starisian\Sparxstar\Gluon
 * @subpackage Frontend
 * @since      1.0.0
 * @author     Starisian Technologies
 * @license    GPL-2.0-or-later
 */

declare(strict_types=1);
namespace Starisian\Sparxstar\Gluon\frontend;

use Starisian\Sparxstar\Gluon\integrations\SparxstarGluonRules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SparxstarGluonSession
 *
 * Manages secure session tokens using HTTP-only cookies with consent API integration.
 * Implements secure cookie practices including the __Host- prefix for enhanced security.
 *
 * @package    Starisian\Sparxstar\Gluon\Frontend
 * @since      1.0.0
 * @final
 */
final class SparxstarGluonSession extends SparxstarGluonRules {


	/**
	 * Cookie name prefix for security.
	 *
	 * Using __Host- prefix ensures the cookie is:
	 * - Sent only to the host that set it
	 * - Only sent over HTTPS
	 * - Does not include a Domain attribute
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private const COOKIE_PREFIX = '__Host-SparxstarGluon';

	/**
	 * The full cookie name including prefix.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $cookieName;

	/**
	 * The consent category for cookie management.
	 *
	 * Determines which consent category this cookie belongs to
	 * when using WordPress Consent API.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $consentCategory = 'functional';

	/**
	 * Constructor - Initializes the session manager.
	 *
	 * Sets up the cookie name with security prefix and registers WordPress hooks.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cookieName      The base name for the session cookie.
	 * @param string $consentCategory Optional. The consent category for GDPR compliance.
	 *                                Default 'functional'.
	 */
	public function __construct( string $cookieName, string $consentCategory = 'functional' ) {
		$this->cookieName      = self::COOKIE_PREFIX . '-' . $cookieName;
		$this->consentCategory = $consentCategory;
		parent::__construct();
		$this->gluonCookieHooks();
	}

	/**
	 * Registers WordPress action hooks for session management.
	 *
	 * Sets up the initialization hook to create session tokens when appropriate.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function gluonCookieHooks(): void {
		\add_action( 'init', array( $this, 'maybeSetSessionToken' ) );
	}
	/**
	 * Creates a session token cookie if consent is granted and none exists.
	 *
	 * This method checks for user consent (if WordPress Consent API is available)
	 * and creates a secure session cookie with a UUID token if one doesn't exist.
	 * The cookie uses security best practices:
	 * - HTTP-only (not accessible via JavaScript)
	 * - Secure flag (HTTPS only)
	 * - SameSite=Lax (CSRF protection)
	 * - __Host- prefix (additional security)
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function maybeSetSessionToken(): void {

		// If Consent API is not present, allow by default
		if ( ! \function_exists( 'wp_has_consent' ) || \wp_has_consent( $this->consentCategory ) ) {

			if ( ! isset( $_COOKIE[ $this->cookieName ] ) ) {

				$token = \wp_generate_uuid4();

				\setcookie(
					$this->cookieName,
					$token,
					array(
						'expires'  => \time() + \DAY_IN_SECONDS,
						'path'     => '/',
						'secure'   => true,
						'httponly' => true,
						'samesite' => 'Lax',
					)
				);

			}
		}
	}
}
