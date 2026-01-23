/**
 * Frontend JavaScript for SPARXSTAR Gluon Plugin
 *
 * Handles WordPress Consent API integration for privacy compliance.
 * This file manages user consent for cookies and tracking, implementing
 * GDPR and privacy law compliance through the WordPress Consent API.
 *
 * Features:
 * - WordPress Consent API integration
 * - Dynamic consent type setting
 * - Consent change event handling
 * - Marketing feature activation/deactivation based on consent
 * - Custom event dispatching for third-party integrations
 *
 * Build: This file will be minified to assets/js/index.min.js during build.
 *
 * @package    Starisian\Sparxstar\Gluon
 * @subpackage JavaScript
 * @since      1.0.0
 * @version    1.0.0
 * @author     Starisian Technologies (Max Barrett) <support@starisian.com>
 * @link       https://developer.wordpress.org/apis/consent-api/ WordPress Consent API
 */

/* global wp_has_consent, wp_set_consent */

(function () {
    'use strict';

    /**
     * WordPress Consent API Integration Object
     *
     * Main object that handles all consent-related functionality.
     * Provides methods for checking and managing user consent preferences.
     *
     * @namespace SparxstarConsent
     * @type {Object}
     */
    const SparxstarConsent = {
        /**
         * Initialize consent management system.
         *
         * Sets up the consent type, dispatches initialization events,
         * sets up event listeners, and checks initial consent state.
         * This should be called once when the DOM is ready.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @returns {void}
         */
        init() {
            // Dynamically set consent type if not already set
            if (typeof window.wp_consent_type === 'undefined') {
                window.wp_consent_type = 'optin';
            }

            // Dispatch event when consent type is defined
            const event = new CustomEvent('wp_consent_type_defined');
            document.dispatchEvent(event);

            // Set up event listeners
            this.setupConsentListeners();

            // Check initial consent state
            this.checkConsentState();
        },

        /**
         * Set up consent change event listeners.
         *
         * Registers an event listener for the 'wp_listen_for_consent_change' event
         * which is dispatched when user consent preferences change. Handles all
         * consent categories and triggers appropriate actions.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @returns {void}
         */
        setupConsentListeners() {
            // Listen to consent change event
            document.addEventListener('wp_listen_for_consent_change', (e) => {
                const changedConsentCategory = e.detail;

                for (const key in changedConsentCategory) {
                    if (
                        Object.prototype.hasOwnProperty.call(
                            changedConsentCategory,
                            key
                        )
                    ) {
                        this.handleConsentChange(
                            key,
                            changedConsentCategory[key]
                        );
                    }
                }
            });
        },

        /**
         * Handle consent category change events.
         *
         * Called when a consent preference changes. Activates or deactivates
         * features based on the user's consent choice for each category.
         * Currently handles 'marketing' consent category.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @param {string} category - The consent category that changed (e.g., 'marketing').
         * @param {string} value - The new consent value ('allow' or 'deny').
         * @returns {void}
         */
        handleConsentChange(category, value) {
            if (category === 'marketing' && value === 'allow') {
                console.log('Marketing consent granted, activating tracking');
                this.activateMarketing();
            } else if (category === 'marketing' && value === 'deny') {
                console.log('Marketing consent denied, disabling tracking');
                this.deactivateMarketing();
            }
        },

        /**
         * Check the initial consent state on page load.
         *
         * Queries the WordPress Consent API to determine if the user has
         * already granted consent. If consent exists, activates appropriate features.
         * Called during initialization.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @returns {void}
         */
        checkConsentState() {
            // Check if wp_has_consent function exists (from WordPress Consent API)
            if (typeof wp_has_consent === 'function') {
                if (wp_has_consent('marketing')) {
                    this.activateMarketing();
                    console.log('Marketing consent already granted');
                } else {
                    console.log('Marketing consent not granted');
                }
            }
        },

        /**
         * Set consent for a specific category.
         *
         * Programmatically sets user consent for a given category.
         * Uses the WordPress Consent API's wp_set_consent function if available.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @param {string} category - The consent category (e.g., 'marketing', 'functional').
         * @param {string} value - The consent value to set ('allow' or 'deny').
         * @returns {void}
         */
        setConsent(category, value) {
            // Check if wp_set_consent function exists
            if (typeof wp_set_consent === 'function') {
                wp_set_consent(category, value);
            }
        },

        /**
         * Activate marketing features when consent is granted.
         *
         * Called when the user grants marketing consent. This is where you should:
         * - Initialize analytics (Google Analytics, Facebook Pixel, etc.)
         * - Load marketing tracking scripts
         * - Enable advertising features
         * - Start data collection for marketing purposes
         *
         * Dispatches 'sparxstar_marketing_active' event for other scripts to listen to.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @returns {void}
         * @fires sparxstar_marketing_active
         */
        activateMarketing() {
            // Add your marketing activation code here
            // Examples:
            // - Initialize analytics
            // - Load marketing pixels
            // - Enable tracking scripts
            console.log('Marketing features activated');

            // Example: Dispatch custom event for other scripts to listen to
            const event = new CustomEvent('sparxstar_marketing_active');
            document.dispatchEvent(event);
        },

        /**
         * Deactivate marketing features when consent is denied/revoked.
         *
         * Called when the user denies or revokes marketing consent. This is where you should:
         * - Disable analytics and tracking
         * - Remove marketing cookies
         * - Stop data collection
         * - Clean up marketing-related resources
         *
         * Dispatches 'sparxstar_marketing_inactive' event for cleanup by other scripts.
         *
         * @since 1.0.0
         * @memberof SparxstarConsent
         * @returns {void}
         * @fires sparxstar_marketing_inactive
         */
        deactivateMarketing() {
            // Add your marketing deactivation code here
            // Examples:
            // - Disable analytics
            // - Remove marketing cookies
            // - Stop tracking
            console.log('Marketing features deactivated');

            // Example: Dispatch custom event
            const event = new CustomEvent('sparxstar_marketing_inactive');
            document.dispatchEvent(event);
        },
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            SparxstarConsent.init();
        });
    } else {
        SparxstarConsent.init();
    }

    // Expose to global scope if needed
    window.SparxstarConsent = SparxstarConsent;
})();
