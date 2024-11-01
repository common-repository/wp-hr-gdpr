<?php

/*
Plugin Name: WP-HR GDPR
Plugin URI: http://www.wphrmanager.com/plugins/wp-hr-gdpr/
Description: Create a GDPR data protection policy (called the ‘Data Privacy Notice’) and record consents to this from employees.
Version: 0.9
Author: Black and White Digital Ltd
Author URI: http://www.wphrmanager.com
License: GPLv2
Text Domain:wp-hr-gdpr
*  @package WP_HR GDPR Pro
* Copyright (c) 2018 Black and White Digital Ltd (email: support@wphrmanager.com). All rights reserved.
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

// Create a helper function for easy SDK access.

if ( !function_exists( 'wphr_gdpr' ) ) {
    function wphr_gdpr()
    {
        global  $wphr_gdpr ;
        
        if ( !isset( $wphr_gdpr ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wphr_gdpr = fs_dynamic_init( array(
                'id'             => '2056',
                'slug'           => 'wp-hr-gdpr',
                'type'           => 'plugin',
                'public_key'     => 'pk_a5c11bcec546fade87ac5a34550e0',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'           => 'wphr-gdpr',
                'override_exact' => true,
                'first-path'     => 'admin.php?page=wphr-gdpr',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wphr_gdpr;
    }
    
    // Init Freemius.
    wphr_gdpr();
    // Signal that SDK was initiated.
    do_action( 'wphr_gdpr_loaded' );
    function wphr_gdpr_settings_url()
    {
        return admin_url( 'admin.php?page=wphr-gdpr' );
    }
    
    wphr_gdpr()->add_filter( 'connect_url', 'wphr_gdpr_settings_url' );
    wphr_gdpr()->add_filter( 'after_skip_url', 'wphr_gdpr_settings_url' );
    wphr_gdpr()->add_filter( 'after_connect_url', 'wphr_gdpr_settings_url' );
    wphr_gdpr()->add_filter( 'after_pending_connect_url', 'wphr_gdpr_settings_url' );
    //Fremius ends here
    // Define WPHRGDPR_PLUGIN_FILE.
    if ( !defined( 'WPHRGDPR_PLUGIN_FILE' ) ) {
        define( 'WPHRGDPR_PLUGIN_FILE', __FILE__ );
    }
    // Include the main WPHRGDPR_GDRP class.
    if ( !class_exists( 'WPHRGDPR_GDRP' ) ) {
        include_once dirname( __FILE__ ) . '/includes/class-wp-hr-gdrp.php';
    }
    /**
     * Main instance of WP_HR GDRP.
     *
     * Returns main instance of WP_HR GDRP to prevent the need to use globals.
     *
     * @since 1.0.0
     * @return WPHRGDPR_GDRP
     */
    function WPHRGDPR_GDRP_getInstance()
    {
        return WPHRGDPR_GDRP::wphrgdpr_instance();
    }
    
    $GLOBALS['WPHRGDPR_GDRP'] = WPHRGDPR_GDRP_getInstance();
}
