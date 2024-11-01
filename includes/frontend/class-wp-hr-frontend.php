<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle all Front Requests
 *
 * @class wphrgdpr_frontend
 * @since 1.0.0
 * @author prism
 */
if (!class_exists('wphrgdpr_frontend', false)) :
class wphrgdpr_frontend {

    /**
     * Constructor.
     */
    public function __construct() {
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/class-wp-hr-frontend-assets.php';
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/class-wp-hr-shortcode-handler.php';
    }
   
}
endif;
return new wphrgdpr_frontend();
