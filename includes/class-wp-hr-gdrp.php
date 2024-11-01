<?php

/**
 * WPHRGDPR_GDRP setup
 *
 * @package WP_HR GDPR Lite
 * @since    1.0.0
 */
defined( 'ABSPATH' ) || exit;
/**
 * Main WPHRGDPR_GDRP Lite Class.
 *
 * @class WPHRGDPR_GDRP
 * @author prism
 */
final class WPHRGDPR_GDRP
{
    /**
     * WPHRGDPR_GDRP Lite version.
     *
     * @var string
     */
    public  $version = '1.0.0' ;
    /**
     * The single instance of the class.
     *
     * @var WooCommerce
     * @since 2.1
     */
    protected static  $_instance = null ;
    /**
     * Session instance.
     *
     * @var WPHRGDPR_GDRP_Session|WPHRGDPR_GDRP_Session_Handler
     */
    public  $session = null ;
    /**
     * Query instance.
     *
     * @var WPHRGDPR_GDRP_Query
     */
    public  $query = null ;
    /*
     *
     */
    public  $consent_status = array() ;
    /**
     * Main WPHRGDPR_GDRP Instance.
     *
     * Ensures only one instance of WPHRGDPR_GDRP is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WPHRGDPR_GDRP_getInstance()
     * @return WPHRGDPR_GDRP - Main instance.
     */
    public static function wphrgdpr_instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * WPHRGDPR_GDRP Constructor.
     */
    public function __construct()
    {
        $this->wphrgdpr_define_constants();
        $this->consent_status = array( __( 'Agreed', 'wp-hr-gdpr' ), __( 'Partial', 'wp-hr-gdpr' ), __( 'Revoked', 'wp-hr-gdpr' ) );
        $this->wphrgdpr_includes();
        $this->wphrgdpr_init_hooks();
    }
    
    /*
     * Raised error when WP HR plugin is not active	
     */
    public function dependency_error()
    {
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/dependency_error.php';
    }
    
    /*
     * Check plugin dependencies 
     */
    function validate_dependencies()
    {
        $arrActivePlugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        if ( in_array( 'wp-hr-manager/wp-hr-manager.php', $arrActivePlugins ) || in_array( 'wp-hr-manager-premium/wp-hr-manager.php', $arrActivePlugins ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Define Constants
     */
    function wphrgdpr_define_constants()
    {
        global  $wpdb ;
        $upload_dir = wp_upload_dir();
        $random_number = random_int( 111111, 999999999 );
        $this->wphrgdpr_define( "WPHRGDPR_NAME", 'WP-HR GDPR' );
        /*
         * plugin constants
         */
        $this->wphrgdpr_define( "WPHRGDPR_PLUGIN_URL", trailingslashit( plugin_dir_url( __DIR__ ) ) );
        $this->wphrgdpr_define( "WPHRGDPR_PLUGIN_PATH", trailingslashit( plugin_dir_path( __DIR__ ) ) );
        $this->wphrgdpr_define( 'WPHRGDPR_VERSION', $this->version );
        /*
         * urls and site info
         */
        $this->wphrgdpr_define( "WPHRGDPR_ADMIN_URL", trailingslashit( admin_url() ) );
        $this->wphrgdpr_define( "WPHRGDPR_ADMIN_AJAX_URL", admin_url( 'admin-ajax.php' ) );
        $this->wphrgdpr_define( "WPHRGDPR_HOME_URL", trailingslashit( home_url() ) );
        $this->wphrgdpr_define( "WPHRGDPR_SITE_URL", trailingslashit( site_url() ) );
        $this->wphrgdpr_define( "WPHRGDPR_SITE_NAME", get_bloginfo( 'name' ) );
        $this->wphrgdpr_define( "WPHRGDPR_BLOG_NAME", wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );
        $this->wphrgdpr_define( "WPHRGDPR_SITE_DESC", get_bloginfo( 'description' ) );
        $this->wphrgdpr_define( "WPHRGDPR_ADMIN_EMAIL", get_bloginfo( 'admin_email' ) );
        $this->wphrgdpr_define( 'WPHRGDPR_ENABLE', 1 );
        $this->wphrgdpr_define( 'WPHRGDPR_DISABLE', 0 );
        $this->wphrgdpr_define( 'WPHRGDPR_CONSENT_TBL', $wpdb->prefix . 'wphrgdprlite_consent_records' );
        $this->wphrgdpr_define( 'WPHRGDPR_RANDOM_NUMBER', $random_number );
    }
    
    /**
     * Define constant if not already set.
     * 
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function wphrgdpr_define( $name, $value )
    {
        if ( !defined( $name ) ) {
            define( $name, $value );
        }
    }
    
    /**
     * What type of request is this?
     *
     * @param  string $type admin or frontend.
     * @return bool
     */
    private function wphrgdpr_is_request( $type )
    {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'frontend':
                return !is_admin() || defined( 'DOING_AJAX' );
        }
    }
    
    /**
     * Include required core files used in admin and on the frontend.
     */
    function wphrgdpr_includes()
    {
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/libs/class_db_opration.php';
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/functions.php';
        if ( $this->wphrgdpr_is_request( 'admin' ) ) {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/class-wp-hr-admin.php';
        }
        if ( $this->wphrgdpr_is_request( 'frontend' ) ) {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/class-wp-hr-frontend.php';
        }
        do_action( 'wphr_loaded' );
    }
    
    function wphrgdpr_init_hooks()
    {
        register_activation_hook( WPHRGDPR_PLUGIN_FILE, array( $this, 'wphrgdpr_install_plugin' ), 999 );
    }
    
    function wphrgdpr_install_plugin()
    {
        $this->wphrgdpr_custom_table();
        $this->wpgdprpro_default_email_contents();
        /*
         * Create Consent Form Quetion when plugin is activated
         */
        $consent_form_rec_old = get_option( "wphrgdpr_consent_quetions" );
        $consent_form_rec = array( "I accept that the Company holds personal data about me and I hereby consent to the processing by the Company or any associated company of my personal data for any purpose related to the performance of my contract of employment or my continuing employment or its termination or the conduct of the Company’s business, including, but not limited to, payroll, human resources and business continuity planning purposes." . PHP_EOL, "I also explicitly consent to the Company or any associated company processing any sensitive personal data relating to me, for example sickness absence records, medical reports, particular health needs, details of criminal convictions and equal opportunities monitoring data, as necessary for the performance of my contract of employment or my continuing employment or its termination or the conduct of the Company’s business." . PHP_EOL, "Finally, I consent to the Company providing my personal data to a third party where this is necessary for the performance of my contract of employment or my continuing employment or its termination or the conduct of the Company’s business, for example to a pension scheme provider in relation to my membership of a pension scheme or to an insurance company in relation to the provision of insured benefits." . PHP_EOL );
        if ( empty($consent_form_rec_old) ) {
            update_option( "wphrgdpr_consent_quetions", $consent_form_rec );
        }
        /*
         * Set Email Template
         */
        $email_templates_data_old = get_option( "wphrgdpr_email_templates_data" );
        $email_templates_label_old = get_option( "wphrgdpr_email_templates_label" );
        $email_template_label = array(
            1 => "Copy of Data Protection Form Submission",
            "Data Protection Form Submission (Admin)",
        );
        $email_templates = array(
            1 => "Thank you for acknowledging our Data Privacy Notice and, if applicable, consenting to us processing your data.  Here is a record of the submission:" . PHP_EOL . "{copy_of_form_content}" . PHP_EOL . "If you would like to re-read the Data Privacy Notice or if you have any queries about your data our would like to contact us to change your consent status you can find contact details for a team member responsible for data issues here {privacy_notice_link}.",
            "[USER DETAILS]<br>Here is a record of the submission:" . PHP_EOL . "{copy_of_form_content}" . PHP_EOL . "If you would like to re-read the Data Privacy Notice or if you have any queries about your data our would like to contact us to change your consent status you can find contact details for a team member responsible for data issues here {privacy_notice_link}.",
        );
        
        if ( empty($email_templates_data_old) ) {
            update_option( "wphrgdpr_email_templates_data", $email_templates );
        } else {
            $replaceFrom = array( '[COPY OF FORM CONTENT]', '[LINK TO DATA PRIVACY NOTICE]', '[USER DETAILS]' );
            $replaceTo = array( '{copy_of_form_content}', '{privacy_notice_link}', '{user_details}' );
            foreach ( $email_templates as $key => $template ) {
                $email_templates[$key] = str_replace( $replaceFrom, $replaceTo, $template );
            }
            update_option( "wphrgdpr_email_templates_data", $email_templates );
        }
        
        if ( empty($email_templates_label_old) ) {
            update_option( "wphrgdpr_email_templates_label", $email_template_label );
        }
        /*
         * Create Privacy / Consent page(s)
         */
        $privacy_page_content = '[privacy_policy title="GDPR Privacy Policy"]';
        $consent_page_content = '[consent_form title="Data Privacy Policy Consent Form"]';
        $subject_access_page_content = '[subject_access_request title="Subject Access Request"]';
        $page_ids = get_option( "wphrgdpr_page_ids" );
        $free_plugin_data_page_ids = get_option( "wphrgdrplite_page_ids" );
        if ( !is_array( $page_ids ) ) {
            $page_ids = array();
        }
        if ( !is_array( $free_plugin_data_page_ids ) ) {
            $free_plugin_data_page_ids = array();
        }
        $privacyPage_args = array(
            'post_title'   => "Privacy Policy Page",
            'post_content' => $privacy_page_content,
            'post_status'  => "publish",
            'post_type'    => 'page',
            'post_author'  => get_current_user_id(),
        );
        $consentPage_args = array(
            'post_title'   => "Consent Form",
            'post_content' => $consent_page_content,
            'post_status'  => "publish",
            'post_type'    => 'page',
            'post_author'  => get_current_user_id(),
        );
        $subjectAccessRequestPage_args = array(
            'post_title'   => "Subject Access Request Summary",
            'post_content' => $subject_access_page_content,
            'post_status'  => "publish",
            'post_type'    => 'page',
            'post_author'  => get_current_user_id(),
        );
        
        if ( !isset( $page_ids['privacy_form'] ) && !$free_plugin_data_page_ids ) {
            $privacy_page_id = wp_insert_post( $privacyPage_args );
            $page_ids['privacy_form'] = $privacy_page_id;
        } else {
            $page_ids['privacy_form'] = $free_plugin_data_page_ids['privacy_form'];
        }
        
        
        if ( !isset( $page_ids['consent_form'] ) && !$free_plugin_data_page_ids ) {
            $consent_page_id = wp_insert_post( $consentPage_args );
            $page_ids['consent_form'] = $consent_page_id;
        } else {
            $page_ids['consent_form'] = $free_plugin_data_page_ids['consent_form'];
        }
        
        
        if ( !isset( $page_ids['subject_access_summary'] ) ) {
            $subject_access_page_id = wp_insert_post( $subjectAccessRequestPage_args );
            $page_ids['subject_access_summary'] = $subject_access_page_id;
        }
        
        update_option( "wphrgdpr_page_ids", $page_ids );
        /**
         * Add consent form contents
         */
        $form_contents = get_option( "wphrgdpr_consent_contents" );
        
        if ( !$form_contents ) {
            $form_contents['consent_form_title'] = __( 'Data Privacy Policy Consent Form', 'wp-hr-gdpr' );
            $form_contents['content_before_consent_form'] = sprintf( __( "<p>IMPORTANT:  Please read our Data Privacy Policy <a target='_blank href='%s'>here</a>.</p><p>Where Consent has been given as the basis for processing my information in the Data Privacy Policy:</p>", "wphrgdpr" ), get_the_permalink( $page_ids['privacy_form'] ) );
            $form_contents['content_after_consent_form'] = __( "Where Consent has been given as the basis for processing my information in the Data Privacy Policy:", "wphrgdpr" );
            $form_contents['consent_form_privacy_text'] = __( "I have read the Data Privacy Policy and understand that my data is processed on a basis other than my consent.", "wphrgdpr" );
            update_option( "wphrgdpr_consent_contents", $form_contents );
        }
        
        /*
         * import default posts
         */
        $free_plugin_data_posts = get_option( 'wphrgdrplite_created_posts' );
        
        if ( is_array( $free_plugin_data_posts ) ) {
            update_option( "wphrgdpr_created_posts", $free_plugin_data_posts );
            update_option( 'wphrgdpr_last_update_post', strtotime( date( 'Y-m-d H:i:s' ) ) );
            return;
        }
        
        $filePath = WPHRGDPR_PLUGIN_PATH . 'assets/data/wp_hr_privacy_policy.csv';
        $user_id = get_current_user_id();
        $file = fopen( $filePath, "r" );
        $created_posts = get_option( "wphrgdpr_created_posts" );
        if ( !is_array( $created_posts ) ) {
            $created_posts = array();
        }
        $posts = array();
        
        if ( $file && empty($created_posts) ) {
            $i = $j = 1;
            while ( !feof( $file ) ) {
                $single_rec = fgetcsv( $file );
                
                if ( $i > 1 ) {
                    $post_title = $single_rec[2];
                    $post_content = $single_rec[1];
                    $answer_type = $single_rec[5];
                    $model_answers = $single_rec[6];
                    $value_checked = $single_rec[7];
                    $model_answer_chk = $single_rec[8];
                    $post_title_strip = wp_strip_all_tags( $post_title );
                    $post_args = array(
                        'post_title'   => $post_title_strip,
                        'post_content' => wp_strip_all_tags( $post_content ),
                        'post_status'  => "publish",
                        'post_type'    => 'wp_hr_privacy_policy',
                        'post_author'  => $user_id,
                        'menu_order'   => $j,
                    );
                    
                    if ( $post_title_strip ) {
                        $post_id = wp_insert_post( $post_args );
                        update_post_meta( $post_id, 'model_answer_type', $answer_type );
                        update_post_meta( $post_id, 'model_answers', $model_answers );
                        update_post_meta( $post_id, 'model_default_answers', $model_answers );
                        update_post_meta( $post_id, 'value_checked', $value_checked );
                        update_post_meta( $post_id, 'model_answers_chk', $model_answer_chk );
                        update_post_meta( $post_id, 'model_answers_default_chk', $model_answer_chk );
                        update_post_meta( $post_id, 'post_default_title', $post_title_strip );
                        $posts[] = $post_id;
                    }
                    
                    $j++;
                }
                
                $i++;
            }
            update_option( "wphrgdpr_created_posts", $posts );
            update_option( 'wphrgdpr_last_update_post', strtotime( date( 'Y-m-d H:i:s' ) ) );
            fclose( $file );
        }
    
    }
    
    /**
     * Create custom tables
     * 
     * @return bool
     */
    function wphrgdpr_custom_table()
    {
        global  $wpdb ;
        $table_name = WPHRGDPR_CONSENT_TBL;
        $charset_collate = $wpdb->get_charset_collate();
        
        if ( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {
            $sql = "CREATE TABLE `" . $table_name . "` ( `ID` BIGINT NOT NULL AUTO_INCREMENT, `name` VARCHAR(100) NOT NULL, `email` VARCHAR(100) NOT NULL, `created` DATETIME NOT NULL,PRIMARY KEY (`ID`) ) {$charset_collate};";
            $db_res = $wpdb->query( $sql );
        }
    
    }
    
    /**
     * Add Default email contents for GDPR.
     * 
     * @since 1.0
     */
    function wpgdprpro_default_email_contents()
    {
        $data_protection_form = [
            'subject' => 'Copy of Data Protection Form Submission',
            'heading' => 'Data Protection Form Submission',
            'body'    => 'Thank you for acknowledging our Data Privacy Notice and, if applicable, consenting to us processing your data.  Here is a record of the submission:

{copy_of_form_content}

If you would like to re-read the Data Privacy Notice or if you have any queries about your data our would like to contact us to change your consent status you can find contact details for a team member responsible for data issues <a href="{privacy_notice_link}">here</a>.
If you would like to request a copy of the information we hold on you (known as a Subject Access Request’, you can also contact us using the information <a href="{privacy_notice_link}">here</a> or via our Subject Access Request Form <a href="{sar_form_link}">here</a>.',
        ];
        update_option( 'wphr_email_settings_data-protection-form', $data_protection_form );
        $sar_form = [
            'subject' => 'Subject Access Request',
            'heading' => 'New Subject Access Request Submitted',
            'body'    => 'A new Subject Access Request has been submitted with the following details:
{sar_content}',
        ];
        update_option( 'wphr_email_settings_subject-access-request', $sar_form );
    }

}