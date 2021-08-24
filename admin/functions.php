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

    if ( !$product_type ) {
        return;
    }

    global $wpdb;

    $product_list = $wpdb->prepare("SELECT id,name FROM {$wpdb->prefix}lmfwppt_products WHERE product_type = %s ", $product_type );

     $items = $wpdb->get_results( $product_list );
     return $items;
}

function Download($path, $speed = null)
{
    if (is_file($path) === true)
    {
        $file = @fopen($path, 'rb');
        $speed = (isset($speed) === true) ? round($speed * 1024) : 524288;

        if (is_resource($file) === true)
        {
            set_time_limit(0);
            ignore_user_abort(false);

            while (ob_get_level() > 0)
            {
                ob_end_clean();
            }

            header('Expires: 0');
            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . sprintf('%u', filesize($path)));
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Transfer-Encoding: binary');

            while (feof($file) !== true)
            {
                echo fread($file, $speed);

                while (ob_get_level() > 0)
                {
                    ob_end_flush();
                }

                flush();
                sleep(1);
            }

            fclose($file);
        }

        exit();
    }

    return false;
}

function downloadUrlToFile($url, $outFileName = "test")
{   
    if(is_file($url)) {
        copy($url, $outFileName); 
    } else {
        $options = array(
          CURLOPT_FILE    => fopen($outFileName, 'w'),
          CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
          CURLOPT_URL     => $url
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
    }
}
