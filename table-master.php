<?php
/**
 * Table Master WordPress Plugin
 *
 * @package TableMaster
 *
 * Plugin Name: Table Master
 * Description: Elementor plugin to create a Table
 * Plugin URI:  https://www.shibbir.dev
 * Version:     1.0.0
 * Author:      Shibbir Ahmed
 * Author URI:  https://www.shibbir.dev
 * Text Domain: table-master
 */

define( 'ELEMENTOR_TABLEMASTER', __FILE__ );

/**
 * Include the Elementor_NaviageMaster class.
 */
require plugin_dir_path( ELEMENTOR_TABLEMASTER ) . 'class-table-master.php';
