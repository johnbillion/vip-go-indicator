<?php
/*
Plugin Name: VIP Go Indicator
Description: Adds an admin toolbar indicator which identifies the current WordPress.com VIP Go environment
Version:     1.0.0
Author:      John Blackbourn
Author URI:  https://johnblackbourn.com/
Text Domain: vip-go-indicator
Domain Path: /languages/
License:     GPL v2 or later
Network:     true

Copyright © 2017 John Blackbourn

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

namespace VIPGI;

/**
 * Adds the environment menu item to the admin toolbar.
 *
 * @param \WP_Admin_Bar $wp_admin_bar The admin toolbar instance
 *
 * @return void
 */
function admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ) {
	$env = get_environment();

	$wp_admin_bar->add_node( [
		'title'  => esc_html( ucwords( $env ) ),
		'id'     => 'vip-go-indicator',
		'parent' => 'top-secondary',
		'meta'   => [
			'class' => 'vip-go-indicator',
		],
	] );
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\admin_bar_menu', 1 );

/**
 * Adds the CSS to the head.
 *
 * @return void
 */
function head() {
	$color = get_environment_color( get_environment() );

	?>
	<style type="text/css">
		#wpadminbar .vip-go-indicator .ab-item {
			background-color: <?php echo esc_html( $color ); ?> !important;
			color: #eee !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', __NAMESPACE__ . '\head' );
add_action( 'wp_head', __NAMESPACE__ . '\head' );

/**
 * Return the VIP Go environment name. Defaults to `local` if there is none.
 *
 * @return string The environment name.
 */
function get_environment() {
	$env = ( defined( 'VIP_GO_ENV' ) && VIP_GO_ENV ) ? VIP_GO_ENV : 'local';

	/**
	 * Filter the current environment name.
	 *
	 * @param string $env The environment name.
	 */
	$env = apply_filters( 'vipgi/environment', $env );

	return $env;
}

/**
 * Return the CSS color for the environment. Defaults to the colour of the live environment, for visibility.
 *
 * @param string $env The envionrment name.
 *
 * @return string The CSS color for the environment.
 */
function get_environment_color( string $env ) {

	switch ( $env ) {

		case 'local':
			$color = '#555';
			break;

		case 'dev':
		case 'develop':
			$color = '#0a0';
			break;

		case 'preprod':
		case 'staging':
		case 'release':
		case 'si':
		case 'qa':
			$color = '#d80';
			break;

		case 'prod':
		case 'production':
		case 'master':
		default:
			$color = '#f00';
			break;

	}

	/**
	 * Filter the current environment color.
	 *
	 * @param string $color The environment color hex code.
	 * @param string $env   The environment name.
	 */
	$color = apply_filters( 'vipgi/color', $color, $env );

	return $color;
}
