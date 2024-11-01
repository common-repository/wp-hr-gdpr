<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Handle Front Scripts and StyleSheets
 * @class wphrgdpr_front_assets
 * @since 1.0.0
 * @author prism
 */
if ( !class_exists( 'wphrgdpr_admin_assets', false ) ) {
    class wphrgdpr_frontend_assets
    {
        /**
         * Hook in tabs.
         */
        public function __construct()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'wphrgdpr_frontend_styles' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'wphrgdpr_frontend_scripts' ) );
            add_action( 'template_redirect', array( $this, 'wphrgdpr_template_redirect' ) );
            add_action( 'after_wphr_recruitment_job_application_form', array( $this, 'wphrgdpr_job_application_form' ) );
            add_action( 'wphr_rec_applied_job', array( $this, 'wphrgdpr_job_application_handler' ) );
        }
        
        /**
         * Enqueue styles.
         */
        public function wphrgdpr_frontend_styles()
        {
            wp_enqueue_style( 'jquery-ui', WPHR_ASSETS . '/vendor/jquery-ui/jquery-ui-1.9.1.custom.css' );
            $cssVersion = filemtime( WPHRGDPR_PLUGIN_PATH . 'assets/css/wp_hr_front_custom_css.css' );
            wp_enqueue_style(
                'wphrgdpr_custom-style',
                WPHRGDPR_PLUGIN_URL . 'assets/css/wp_hr_front_custom_css.css',
                array(),
                $cssVersion
            );
            wp_enqueue_style( 'font-awsome_css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
        }
        
        /**
         * Enqueue scripts.
         */
        public function wphrgdpr_frontend_scripts()
        {
            //wp_enqueue_media();
            $jsVersion = filemtime( WPHRGDPR_PLUGIN_PATH . 'assets/js/wp_hr_front_custom.js' );
            wp_register_script(
                'wphrgdpr_custom-js-file',
                WPHRGDPR_PLUGIN_URL . 'assets/js/wp_hr_front_custom.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                $jsVersion,
                true
            );
            wp_enqueue_script( "wphrgdpr_custom-js-file" );
            wp_localize_script( 'wphrgdpr_custom-js-file', 'frontend_veriables', array(
                'ajax_url'               => WPHRGDPR_ADMIN_AJAX_URL,
                'home_url'               => WPHRGDPR_HOME_URL,
                'site_url'               => WPHRGDPR_SITE_URL,
                'required_field'         => __( "Required field", "wphrgdpr" ),
                'required_field_chk'     => __( "Select atleast one option", "wphrgdpr" ),
                'required_field_chk_one' => __( "Please select option", "wphrgdpr" ),
                'email_err_msg'          => __( "Enter Valid Email", "wphrgdpr" ),
                'policy_submit'          => __( "Privacy answers saved. Please wait", "wphrgdpr" ),
                'consent_submit'         => __( "Consent data submited. Please wait", "wphrgdpr" ),
                'name_err_msg'           => __( "Enter name", "wphrgdpr" ),
            ) );
        }
        
        /**
         * wphrgdpr_template_redirect.
         * Handle redirect to login page for consent form.
         *
         * @since 1.0.0.
         *
         */
        function wphrgdpr_template_redirect( $template )
        {
        }
        
        /**
         * wphrgdpr_job_application_form
         * Add consent form to job application form if WPHR Recruitment plugin is active
         *
         * @since 1.0.0
         *
         */
        function wphrgdpr_job_application_form()
        {
            global  $job_app_consent_form ;
            $postid = get_the_ID();
            $display_consent_form = get_post_meta( $postid, '_consent_form', true );
            
            if ( $display_consent_form ) {
                $job_app_consent_form = true;
                echo  do_shortcode( '[consent_form]' ) ;
            }
        
        }
        
        /**
         * wphrgdpr_job_application_handler
         * Handle consent form request from job application form
         * 
         * @since 1.0.0
         */
        function wphrgdpr_job_application_handler( $data )
        {
            global  $wpdb ;
            $job_id = $data['job_id'];
            $jobseeker_id = $data['applicant_id'];
            $display_consent_form = get_post_meta( $job_id, '_consent_form', true );
            if ( !$display_consent_form ) {
                return;
            }
            $consent_form_rec_db = get_option( "wphrgdpr_consent_quetions" );
            $consent_form_data = array();
            $status = $consent_form_data_count = 0;
            if ( $consent_form_rec_db ) {
                foreach ( $consent_form_rec_db as $key => $form_rec ) {
                    $checked = 0;
                    
                    if ( isset( $_POST['consent_chk_' . $key] ) ) {
                        $checked = 1;
                        $consent_form_data_count++;
                        $consent_form_rec_data .= $form_rec . PHP_EOL;
                    }
                    
                    $consent_form_data[$form_rec] = $checked;
                }
            }
            
            if ( $consent_form_data_count == 0 ) {
                $status = 3;
            } elseif ( $consent_form_data_count == count( $consent_form_rec_db ) ) {
                $status = 1;
            } else {
                $status = 2;
            }
            
            $data = array(
                'wphr_people_id' => $jobseeker_id,
                'meta_key'       => 'consent_data',
                'meta_value'     => serialize( $consent_form_data ),
            );
            $format = array( '%d', '%s', '%s' );
            $wpdb->insert( $wpdb->prefix . 'wphr_peoplemeta', $data, $format );
            $data = array(
                'wphr_people_id' => $jobseeker_id,
                'meta_key'       => 'consent_status',
                'meta_value'     => $status,
            );
            $format = array( '%d', '%s', '%s' );
            $wpdb->insert( $wpdb->prefix . 'wphr_peoplemeta', $data, $format );
        }
    
    }
}
return new wphrgdpr_frontend_assets();