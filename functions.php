<?php
/**
 * Financio functions and definitions
 *
 * @author Jegstudio
 * @package financio
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'FINANCIO_VERSION' ) || define( 'FINANCIO_VERSION', '1.1.3' );
defined( 'FINANCIO_DIR' ) || define( 'FINANCIO_DIR', trailingslashit( get_template_directory() ) );
defined( 'FINANCIO_URI' ) || define( 'FINANCIO_URI', trailingslashit( get_template_directory_uri() ) );

require get_parent_theme_file_path( 'inc/autoload.php' );
require get_parent_theme_file_path( 'inc/helper.php' );
require get_parent_theme_file_path( 'inc/wptt-webfont-loader.php' );

Financio\Init::instance();
