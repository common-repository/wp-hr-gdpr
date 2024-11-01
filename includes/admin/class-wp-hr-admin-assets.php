<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Admin Scripts and StyleSheets
 * @class wphrgdpr_admin_assets
 * @since 1.0.0
 * @author prism
 */
if (!class_exists('wphrgdpr_admin_assets', false)) :

    class wphrgdpr_admin_assets {

        /**
         * Hook in tabs.
         */
        public function __construct() {
            add_action('admin_enqueue_scripts', array($this, 'wphrgdpr_admin_styles'));
            add_action('admin_enqueue_scripts', array($this, 'wphrgdpr_admin_scripts'));
			add_action('after_edit_recruitment', array( $this, 'wphrgdpr_edit_recruitment_fields' ) );
			add_action('after_add_recruitment', array( $this, 'wphrgdpr_add_recruitment_fields' ) );
			add_action('wphr_rec_opened_recruitment', array( $this, 'wphrgdpr_save_recruitment_fields' ) );
			add_action('add_job_information', array( $this, 'wphrgdpr_add_job_information' ) );
			add_action('wphr_applicant_details_data_section', array( $this, 'consent_form_data_for_applicant' ) );
			
			add_filter('wphr_applicant_details_field_section', array( $this, 'consent_form_field_for_applicant' ) );
        }
		
        /**
         * Enqueue styles.
         */
        public function wphrgdpr_admin_styles() {
            $cssVersion = filemtime(WPHRGDPR_PLUGIN_PATH . 'assets/css/wp_hr_admin_custom_css.css');
            wp_enqueue_style('wphrgdpr_custom-style', WPHRGDPR_PLUGIN_URL . 'assets/css/wp_hr_admin_custom_css.css', array(), $cssVersion);

            wp_enqueue_style('font-awsome_css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        }

        /**
         * Enqueue scripts.
         */
        public function wphrgdpr_admin_scripts() {

            $jsVersion = filemtime(WPHRGDPR_PLUGIN_PATH . 'assets/js/wp_hr_admin_custom.js');
            wp_register_script('wphrgdpr_custom-js-file', WPHRGDPR_PLUGIN_URL . 'assets/js/wp_hr_admin_custom.js', array('jquery','jquery-ui-sortable', 'jquery-ui-datepicker'), $jsVersion, true);
            
            if(isset($_REQUEST['post_type']) && $_REQUEST['post_type']=='wphrgdpr_privacy_policy')
            {
                
              //  wp_enqueue_script('wphrgdpr_jquery-sortable', WPHRGDPR_PLUGIN_URL . 'assets/js/wp_hr_jquery-sortable.js', array('jquery'), $jsVersion, true);
            }
            
            wp_enqueue_script("wphrgdpr_custom-js-file");
            wp_localize_script('wphrgdpr_custom-js-file', 'admin_veriables', array('ajax_url' => WPHRGDPR_ADMIN_AJAX_URL, 'home_url' => WPHRGDPR_HOME_URL, 'site_url' => WPHRGDPR_SITE_URL,'checkbox_msg'=>__("Checkbox Options","wphrgdpr"),'checked_msg'=>__("Checked","wphrgdpr"),'unchecked_msg'=>__("Unchecked","wphrgdpr")));
        }
		
		/** 
		* wphrgdpr_edit_recruitment_fields.
		* Add new field to manage GDPR to application form.
		*
		* @since 1.0.0
		*/
		function wphrgdpr_edit_recruitment_fields(){
			global $post;
			$display_consent_form        = get_post_meta( $post->ID, '_consent_form', true );
			include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/edit_recruitment.php';	
		}
		
		/** 
		* wphrgdpr_add_recruitment_fields.
		* Add new field to manage GDPR to application form.
		*
		* @since 1.0.0
		*/
		function wphrgdpr_add_recruitment_fields(){
			$postid = isset( $_REQUEST['postid'] ) ? intval( $_REQUEST['postid'] ) : 0;
			$display_consent_form        = get_post_meta( $postid, '_consent_form', true );
			include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/add_recruitment.php';	
		}
		
		/**
		* wphrgdpr_save_recruitment_fields.
		* Display consent form in application form or not.
		*
		* @since 1.0.0
		*/
		function wphrgdpr_save_recruitment_fields(){
			global $post;
			$consent_form                     = ( isset( $_POST['consent_form'] ) ) ? 1 : 0;
			update_post_meta( $post->ID, '_consent_form', $consent_form );
		}
		
		/**
		* wphrgdpr_add_job_information
		* Display consent form in application form or not.
		*
		* @since 1.0.0
		*/
		function wphrgdpr_add_job_information(){
	        if ( !isset( $_POST['hidden_job_information'] ) ) {
	            return;
	        }

	        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'job_information' ) ) {
	            wp_die( __( 'Cheating?', 'wphr-rec' ) );
	        }

	        $postid             = isset( $_POST['postid'] ) ? $_POST['postid'] : 0;
	        $consent_form       = ( isset( $_POST['consent_form'] ) ) ? 1 : 0;
			update_post_meta( $postid, '_consent_form', $consent_form );
		}
		
		/**
		* consent_form_field_for_applicant.
		* Add new tab for display consent form data.
		* 
		* @since 1.0.1
		*/
		function consent_form_field_for_applicant( $tabs ){
			$tabs['section-consent-form'] = __( 'Consent Fields', 'wp-hr-gdpr' );
			return $tabs;
		}
		
		/**
		* consent_form_data_for_applicant
		* Display consent form data of applicant
		* 
		* @since 1.0.1
		*/
		function consent_form_data_for_applicant( $applicant_id ){
			$consent_data          = wphr_people_get_meta($applicant_id, 'consent_data', true);
			include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/applicant_consent_form.php';	
		}
		
    }
    endif;
return new wphrgdpr_admin_assets();