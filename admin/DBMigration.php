<?php
class LMFWPPT_DBMigration {
	
	var $db_version = 12; // initial db version, don't use floats
    var $db_version_key = "lmfwppt_db_version";

	function __construct(){
		add_action('admin_init', [$this,'run_migration']);
	}

	function get_db_version(){
		return $this->db_version;
	}

	function run_migration(){
		if ( version_compare( get_option( 'lmfwppt_db_version', "1" ), $this->get_db_version(), '<' ) || 0 ) {
			
			global $wpdb;

	        $charset_collate = $wpdb->get_charset_collate();
	        $table_name_lmfwppt_products = $wpdb->prefix . 'lmfwppt_products';
			

	        $schema[] = "CREATE TABLE `{$table_name_lmfwppt_products}` (
	          `id` int(11) NOT NULL AUTO_INCREMENT,
	          `name` varchar(100) NOT NULL DEFAULT '',
	          `slug` varchar(100) NOT NULL DEFAULT '',
	          `product_type` varchar(30) DEFAULT NULL,
	          `version` varchar(30) DEFAULT NULL,
	          `tested` varchar(30) DEFAULT NULL,
	          `requires` varchar(30) DEFAULT NULL,
	          `requires_php` varchar(30) DEFAULT NULL,
	          `download_link` varchar(255) DEFAULT NULL,
	          `banners` varchar(250) DEFAULT NULL,
	          `sections` varchar(200) DEFAULT NULL,
	          `created_by` int(20) unsigned NOT NULL,
	          `dated` datetime NOT NULL DEFAULT NOW(),
	          PRIMARY KEY (`id`),
	          UNIQUE (`slug`)
	        ) $charset_collate";


	        $schema[] = "CREATE TABLE `{$wpdb->prefix}lmfwppt_licenses` (
	          `id` int NOT NULL AUTO_INCREMENT,
	          `license_key` varchar(255) NOT NULL DEFAULT '',
	          `package_id` varchar(100) NOT NULL,
	          `order_id` int,
	          `end_date` int,
	          `dated` datetime NOT NULL DEFAULT NOW(),
	          PRIMARY KEY (`id`)
	        ) $charset_collate";


	        $schema[] = "CREATE TABLE `{$wpdb->prefix}lmfwppt_license_packages` (
	          `id` int(11) NOT NULL AUTO_INCREMENT,
	          `product_id` int(11) NOT NULL,
	          `label` varchar(100) NOT NULL,
	          `package_id` varchar(100) NOT NULL,
	          `update_period` int(30) NOT NULL,
	          `domain_limit` int(30) NOT NULL,
	          PRIMARY KEY (`id`),
	          UNIQUE (`package_id`),
	          FOREIGN KEY (`product_id`) REFERENCES `{$table_name_lmfwppt_products}` (`id`)
	        ) $charset_collate";

	        if ( ! function_exists( 'dbDelta' ) ) {
	            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	        }

	        dbDelta( $schema );
			
			update_option( 'lmfwppt_db_version', $this->get_db_version() );
		}
	}

	/**
	 * Initialization
	 */
	public static function init(){
     	static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
	}
}

LMFWPPT_DBMigration::init();