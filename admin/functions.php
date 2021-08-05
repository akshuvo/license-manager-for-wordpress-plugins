<?php

/**
 * Fetch Licenses
 *
 * @param  array  $args
 *
 * @return array
 */
function lmfwppt_get_licenses( $args = [] ) {
    global $wpdb;

    $defaults = [
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC'
    ];

    $args = wp_parse_args( $args, $defaults );

    $last_changed = wp_cache_get_last_changed( 'license' );
    $key          = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
    $cache_key    = "all:$key:$last_changed";

    $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}license_manager
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
            $args['offset'], $args['number']
    );

    $items = wp_cache_get( $cache_key, 'license' );

    if ( false === $items ) {
        $items = $wpdb->get_results( $sql );

        wp_cache_set( $cache_key, $items, 'license' );
    }

    return $items;
}

/**
 * Get the count of total list
 *
 * @return int
 */
function lmfwppt_license_count() {
    global $wpdb;

    $count = wp_cache_get( 'count', 'license' );

    if ( false === $count ) {
        $count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}license_manager" );

        wp_cache_set( 'count', $count, 'license' );
    }

    return $count;
}

// API URL
function lmfwppt_api_url(){
    return apply_filters( 'lmfwppt_api_url', home_url('/') );
}

// Get Product list
function lmfwppt_get_product_list( $product_type ){

    global $wpdb;
    
    $args = '';
    $defaults = [
        'number' => 20,
        'product_type' => $product_type
    ];

    $args = wp_parse_args( $args, $defaults );
     $product_list = $wpdb->prepare("SELECT id,name FROM {$wpdb->prefix}lmfwppt_products WHERE product_type = %s 
        LIMIT %d",
        $args['product_type'],$args['number']);

     $items = $wpdb->get_results( $product_list);
     return $items;
}

