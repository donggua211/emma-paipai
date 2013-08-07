<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Schedule actions
 *
 * @see odsea_cancel_order()
 * @see odsea_order_again()
 */
add_action( 'weibo2wp_synch_dailly_hook', 'weibo2wp_synch_dailly' );
