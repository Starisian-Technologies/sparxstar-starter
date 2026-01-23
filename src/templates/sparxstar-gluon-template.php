<?php
/**
 * Template File: Sparxstar Gluon Custom Template
 *
 * This is an example template file that can be rendered via shortcode.
 * Used in conjunction with the TemplateShortcode class to create
 * reusable template components.
 *
 * Available Variables:
 * - $args['title'] - The title passed via shortcode attribute
 *
 * Usage:
 * Add this shortcode to any post or page: [shortcode_name title="Your Title"]
 *
 * @package    Starisian\Sparxstar\Gluon\Templates
 * @subpackage Views
 * @since      1.0.0
 * @version    1.0.0
 * @author     Starisian Technologies (Max Barrett) <support@starisian.com>
 */

namespace Starisian\Sparxstar\Gluon\Gluon\templates;

// Ensure this file is being included by WordPress or within the plugin context
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class='custom-container'>
	<h2><?php echo esc_html( $args['title'] ); ?></h2>
	<p>Predictability and presence—not control—are the quiet glue of <br />
		enduring relationships, holding strongest under all conditions.</p>
</div>