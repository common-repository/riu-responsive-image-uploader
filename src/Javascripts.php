<?php
/*
    This file is part of RIU - Responsive Image Uploader.

    RIU is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    RIU is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Load RIU javascripts in frontend
 */

namespace riu;

/*
 * Frontend
 */
function enqueueHeadScripts( $pid ) {
	$headScript = file_get_contents( plugin_dir_path( __DIR__ ) . 'js/riu.head.js' );
	echo "<script>$headScript</script>";
}

add_action( 'wp_head', 'riu\enqueueHeadScripts' );

function enqueueFooterScripts( $pid ) {
	$path = plugin_dir_url( __DIR__ ) . 'js/riu.js';
	echo "<script src='$path'></script>";
}

add_action( 'wp_footer', 'riu\enqueueFooterScripts' );

/*
 * Admin
 */
function enqueueAdminScript() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-draggable');
}

add_action('admin_enqueue_scripts', 'riu\enqueueAdminScript');

function enqueueAdminStyle($pid) {
	$path = plugin_dir_url( __DIR__ ).'css/style.css';
	echo "<link rel='stylesheet' href='$path' type='text/css'>";
	$path = plugin_dir_url( __DIR__ ).'css/jquery-ui.css';
	wp_enqueue_style( 'jquery-ui-css', $path, false, '1.10.4', false);
}

add_action( 'admin_head', 'riu\enqueueAdminStyle' );

