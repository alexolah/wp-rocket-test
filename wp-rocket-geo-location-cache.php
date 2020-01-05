<?php
/**
 * Plugin Name: Serve cache based on cookie value
 * Description: Serve WP Rocket cache based on cookie 'origin_country' value.
 * Author:      Justin Ahinon
 * Author URI:  https://segbedji.com
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
/**
 * Add country cookie to dynamic caches.
 *
 * @link https://github.com/wp-media/wp-rocket/blob/7ff6b932752c910ef9b8ea73433bc58fad72535b/inc/functions/options.php#L332
 *
 */
function wprscboc_add_cookie_to_dynamic_caches( $cookies ) {
	$cookies[] = 'origin_country';
	return $cookies;
}
add_filter( 'rocket_cache_dynamic_cookies', 'wprscboc_add_cookie_to_dynamic_caches' );
/**
 * Prevent serving the cache until a cookie named origin_country is set on the visitor browser.
 */
function wprscboc_prevent_serving_cache_if_country_cookie_not_set() {
	if ( ! isset( $_COOKIE['origin_country'] ) ) {
		add_action( 'template_redirect', 'wprscboc_donotcache' );
	}
}
/**
 * Serve specific cache based on 'origin_country' value by adding it as a mandatory cookie.
 *
 * @link https://github.com/wp-media/wp-rocket/blob/7ff6b932752c910ef9b8ea73433bc58fad72535b/inc/functions/options.php#L308
 *
 */
function wprscboc_add_origin_country_as_mandatory_cookie( $cookies ) {
	$cookies[] = 'origin_country';
	return $cookies;
}
add_filter( 'rocket_cache_mandatory_cookies', 'wprscboc_add_origin_country_as_mandatory_cookie' );
/**
 * Disable caching.
 */
function wprscboc_donotcache() {
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	return true;
}