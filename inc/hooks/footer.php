<?php

/**
 *
 * PHP version 5
 *
 * Created: 7/5/16, 1:17 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * htdocs
 * footer.php
 */


function wpsaas_dev_show_template() {

	$site = get_option('siteurl' );

	if ( $site == 'http://wpsaas.dev' ) {
		global $template;

		$template = strstr( $template, 'themes/' );
		$template = str_replace( 'themes/', '', $template );

		echo '<div style="position: fixed; bottom:20px; right: 200px;">' . $template . '</div>';
	}
}

if(! is_admin()) {
	add_action( 'wp_footer', 'wpsaas_dev_show_template' );
}

// End footer.php