<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Handle Custom Post type
 * @class wphrgdpr_front_assets
 * @since 1.0.0
 * @author prism
 */
if ( !class_exists( 'wphrgdpr_admin_custom_post', false ) ) {
    class wphrgdpr_admin_custom_post
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            add_action( 'admin_init', array( $this, 'wphrgdpr_create_meta_field' ) );
            $this->wphrgdpr_register_post_type();
            add_action( 'save_post', array( $this, 'wphrgdpr_save_meta_field' ) );
            add_action( 'pre_post_update', array( $this, 'wphrgdpr_before_insert' ) );
            add_action( 'admin_menu', array( $this, 'wphrgdpr_add_custom_link_into_appearnace_menu' ) );
            add_action( 'admin_init', array( $this, 'wphrgdpr_form_save_handle' ) );
            add_action( 'admin_init', array( $this, 'wphrgdpr_form_save_handle' ) );
            add_action( "wp_ajax_wphrgdpr_post_sortable_handle", array( $this, 'wphrgdpr_post_sortable_handle' ) );
            add_action( 'admin_notices', array( $this, 'wphrgdpr_error_notice' ) );
            add_filter( 'parse_query', array( $this, 'wphrgdpr_privacy_list_handle' ) );
            add_action( 'edit_form_top', array( $this, 'add_wphrgdpr_heading' ) );
            add_action( 'edit_form_after_title', array( $this, 'wphrgdpr_replace_column_title_method_b' ) );
            add_filter(
                'enter_title_here',
                array( $this, 'wphrgdpr_change_title_placeholder' ),
                10,
                2
            );
            add_filter(
                'default_content',
                array( $this, 'wphrgdpr_post_content_placeholder' ),
                10,
                2
            );
        }
        
        function wphrgdpr_post_content_placeholder( $post_content, $post )
        {
            if ( $post->post_type != 'wp_hr_privacy_policy' ) {
                return $post_content;
            }
            if ( strlen( stripslashes( $post_content ) ) == 0 ) {
                $post_content = __( 'Add Internal Guidelines and Notes Here', 'wp-hr-gdpr' );
            }
            return $post_content;
        }
        
        function wphrgdpr_change_title_placeholder( $place_holder, $post )
        {
            if ( $post->post_type != 'wp_hr_privacy_policy' ) {
                return $place_holder;
            }
            $place_holder = __( 'Enter default title here', 'wp-hr-gdpr' );
            return $place_holder;
        }
        
        function add_wphrgdpr_heading( $post )
        {
            if ( $post->post_type != 'wp_hr_privacy_policy' ) {
                return;
            }
            echo  sprintf( '<div class="wphrgdpr_heading">%s</div>', __( 'Published Title', 'wp-hr-gdpr' ) ) ;
        }
        
        /**
         * Register Custom Post type
         */
        public function wphrgdpr_register_post_type()
        {
            register_post_type( 'wp_hr_privacy_policy', array(
                'labels'       => array(
                'name'               => __( 'WPHR GDPR Lite', "wphrgdpr" ),
                'singular_name'      => __( 'WPHR GDPR Lite', "wphrgdpr" ),
                'add_new'            => __( 'Create notice', "wphrgdpr" ),
                'add_new_item'       => __( 'Create New notice', "wphrgdpr" ),
                'edit_item'          => __( 'Edit notice', "wphrgdpr" ),
                'new_item'           => __( 'Create New notice', "wphrgdpr" ),
                'view_item'          => __( 'View notice', "wphrgdpr" ),
                'search_items'       => __( 'Search notice', "wphrgdpr" ),
                'not_found'          => __( 'No notice found', "wphrgdpr" ),
                'not_found_in_trash' => __( 'No notice found in trash', "wphrgdpr" ),
            ),
                'public'       => false,
                'show_ui'      => true,
                'has_archive'  => false,
                'query_var'    => true,
                'rewrite'      => false,
                'supports'     => array( 'title', 'editor', 'page-attributes' ),
                'map_meta_cap' => true,
                'show_in_menu' => 'admin.php?page=wphrgdpr',
            ) );
        }
        
        function wphrgdpr_replace_column_title_method_b( $post )
        {
            if ( $post->post_type != 'wp_hr_privacy_policy' || !is_admin() ) {
                return;
            }
            $post_id = $post->ID;
            $default_title = get_post_meta( $post_id, 'post_default_title', true );
            
            if ( $default_title ) {
                ?>
            <div class="wphrgdpr_default_title_div">
                <label class="wp_hedefault_title_label">
                    <strong><?php 
                _e( "Default title :", "wphrgdpr" );
                ?></strong>
                </label>
                <label class="wphrgdpr_default_title">
                    <?php 
                echo  $default_title ;
                ?>
                </label>
            </div>
        <?php 
            }
            
            echo  sprintf( '<div class="wphrgdpr_heading">%s</div>', __( 'Guidence Notes', 'wp-hr-gdpr' ) ) ;
        }
        
        public function wphrgdpr_post_sortable_handle()
        {
            $post_ids = filter_input(
                INPUT_POST,
                'post_ids',
                FILTER_DEFAULT,
                FILTER_REQUIRE_ARRAY
            );
            if ( !empty($post_ids) ) {
                foreach ( $post_ids as $position => $post_id ) {
                    
                    if ( $post_id ) {
                        $postarr = array(
                            "ID"         => $post_id,
                            "menu_order" => $position,
                        );
                        wp_update_post( $postarr );
                    }
                
                }
            }
            die;
        }
        
        function wphrgdpr_privacy_list_handle( $query )
        {
            global  $pagenow ;
            $post_type_input = filter_input( INPUT_GET, "post_type" );
            $post_type = ( $post_type_input ? $post_type_input : '' );
            
            if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'wp_hr_privacy_policy' ) {
                $query->query_vars['order'] = 'ASC';
                $query->query_vars['orderby'] = 'menu_order';
            }
        
        }
        
        public function wphrgdpr_add_custom_link_into_appearnace_menu()
        {
            global  $WPHRGDPR_GDRP ;
            $capability = 'manage_options';
            add_menu_page(
                __( 'WPHR GDRP', "wphrgdpr" ),
                __( 'WPHR GDRP', "wphrgdpr" ),
                'manage_options',
                'wphr-gdpr',
                '',
                'dashicons-hidden',
                74
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'Support', 'wp-hr-gdpr' ),
                __( 'Support', 'wp-hr-gdpr' ),
                'manage_options',
                'wphr-gdpr',
                array( $this, 'wphrgdpr_support_handle' ),
                'dashicons-hidden'
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'Privacy Notice', 'wp-hr-gdpr' ),
                __( 'Privacy Notice', 'wp-hr-gdpr' ),
                $capability,
                'edit.php?post_type=wp_hr_privacy_policy'
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'Create Notice', 'wp-hr-gdpr' ),
                __( 'Create Notice', 'wp-hr-gdpr' ),
                $capability,
                'post-new.php?post_type=wp_hr_privacy_policy'
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'Create Consent form', 'wp-hr-gdpr' ),
                __( 'Create Consent form', 'wp-hr-gdpr' ),
                $capability,
                'wphrgdpr_consent',
                array( $this, 'wphrgdpr_consent_form_handle' )
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'View Consents', 'wp-hr-gdpr' ),
                __( 'View Consents', 'wp-hr-gdpr' ),
                $capability,
                'wphrgdpr_consent_rec',
                array( $this, 'wphrgdpr_consent_form_rec_handle' )
            );
            add_submenu_page(
                'wphr-gdpr',
                __( 'Email Template', 'wp-hr-gdpr' ),
                __( 'Email Templates', 'wp-hr-gdpr' ),
                'manage_options',
                'wphrgdpr_email_template',
                array( $this, 'wphrgdpr_email_template' )
            );
        }
        
        function wphrgdpr_error_notice()
        {
            $error = filter_input( INPUT_GET, "error" );
            $success = filter_input( INPUT_GET, "success" );
            $action = filter_input( INPUT_GET, "action" );
            
            if ( !empty($error) && $error == 'true' ) {
                ?>
                <div class="error notice">
                    <p><?php 
                _e( 'Please fill all field / remove empty field', 'review_domain' );
                ?></p>
                </div>
                <?php 
            }
            
            
            if ( !empty($error) && $error == 'wphrgdpr_post_title' ) {
                ?>
                <div class="error notice">
                    <p><?php 
                _e( 'Please fill title / content', 'wp-hr-gdpr' );
                ?></p>
                </div>
                <?php 
            }
            
            
            if ( !empty($error) && $error == 'wphrgdpr_post_desc' ) {
                ?>
                <div class="error notice">
                    <p><?php 
                _e( 'Please Enter Description', 'wp-hr-gdpr' );
                ?></p>
                </div>
                <?php 
            }
            
            
            if ( !empty($success) && $success == 'true' ) {
                ?>
                <div class="updated notice">
                    <p><?php 
                _e( 'Content updated', 'wp-hr-gdpr' );
                ?></p>
                </div>
                <?php 
            }
            
            
            if ( !empty($action) && $action == 'delete' ) {
                ?>
                <div class="updated notice">
                    <p><?php 
                _e( 'Record deleted', 'wp-hr-gdpr' );
                ?></p>
                </div>
                <?php 
            }
        
        }
        
        function wphrgdpr_before_insert( $data )
        {
            $action = filter_input( INPUT_POST, "action" );
            $post_type = filter_input( INPUT_POST, "post_type" );
            $post_title = filter_input( INPUT_POST, "post_title" );
            $post_content = filter_input( INPUT_POST, "post_content" );
            if ( $action == 'editpost' && $post_type == 'wp_hr_privacy_policy' ) {
                
                if ( trim( $post_title ) == '' || trim( $post_content ) == '' ) {
                    wp_redirect( admin_url( 'post-new.php?post_type=wp_hr_privacy_policy&error=wphrgdpr_post_title' ) );
                    exit;
                }
            
            }
        }
        
        function wphrgdpr_support_handle()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-support-template.php';
        }
        
        function wphrgdpr_email_template()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-email-template.php';
        }
        
        function wphrgdpr_consent_form_rec_handle()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/custom_tables/wp-hr-consent-table.php';
            $obj = new wphrgdrplist_rec_list();
            ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php 
            _e( "Consent form responses summary", "wphrgdpr" );
            ?></h1>
                <hr class="wp-header-end">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder">
                        <form method="post">
                            <?php 
            $obj->prepare_items();
            $obj->display();
            ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
        }
        
        public function wphrgdpr_access_request_callback()
        {
            
            if ( isset( $_GET['request_no'] ) ) {
                global  $wpdb ;
                $table_fields = array(
                    'ID',
                    'name',
                    'created',
                    'status',
                    'dpo_manager',
                    'appointed_date',
                    'expiry_date',
                    'address',
                    'telephone',
                    'email',
                    'idenitity_proof_1',
                    'idenitity_proof_1_verified',
                    'idenitity_proof_2',
                    'idenitity_proof_2_verified',
                    'extention_reason',
                    'reviced_expiry_date',
                    'notified_date',
                    'additional_info',
                    'additional_info_date',
                    'unfounded_request',
                    'declined_request_reason',
                    'unfounded_request_date',
                    'third_party_data_request',
                    'requested_data_type',
                    'requested_form_data_characteristic',
                    'requested_form_completion_date',
                    'other_notes',
                    'user_position',
                    'location',
                    'employed_date',
                    'termination_date',
                    'job_application_date',
                    'job_interview_date',
                    'data'
                );
                $data = $form_fields = array();
                $visible_request_form = false;
                
                if ( $_GET['request_no'] ) {
                    $query = $wpdb->prepare( 'SELECT * FROM ' . WPHRGDPR_SUBJECT_ACCESS_TBL . ' WHERE ID = %d', $_GET['request_no'] );
                    $data = $wpdb->get_row( $query, 'ARRAY_A' );
                    $visible_request_form = ( $data['user_id'] ? true : false );
                }
                
                foreach ( $table_fields as $field ) {
                    $value = ( isset( $data[$field] ) ? $data[$field] : '' );
                    
                    if ( $value ) {
                        $validDate = $this->checkIsAValidDate( $value );
                        if ( (int) $validDate > 0 ) {
                            $value = date( 'm/d/Y', strtotime( $value ) );
                        }
                        if ( (int) $validDate < 0 ) {
                            $value = '';
                        }
                    }
                    
                    $form_fields[$field] = $value;
                }
                
                if ( empty($form_fields['expiry_date']) ) {
                    $date = new DateTime( $form_fields['created'] );
                    $date->modify( '+30 days' );
                    $form_fields['expiry_date'] = $date->format( 'm/d/Y' );
                }
                
                $sar_form_fields = array(
                    'name'                 => __( 'Employee name', 'wp-hr-gdpr' ),
                    'email'                => __( 'Email Address', 'wp-hr-gdpr' ),
                    'user_position'        => __( 'Employee Status', 'wp-hr-gdpr' ),
                    'location'             => __( 'Company location', 'wp-hr-gdpr' ),
                    'employed_date'        => __( 'Employment date', 'wp-hr-gdpr' ),
                    'termination_date'     => __( 'Termination date', 'wp-hr-gdpr' ),
                    'job_application_date' => __( 'Job application date', 'wp-hr-gdpr' ),
                    'job_interview_date'   => __( 'Job interview date', 'wp-hr-gdpr' ),
                    'data'                 => __( 'Requested data', 'wp-hr-gdpr' ),
                    'address'              => __( 'Employee Address', 'wp-hr-gdpr' ),
                    'telephone'            => __( 'Telephone Number', 'wp-hr-gdpr' ),
                    'idenitity_proof_1'    => __( 'ID Proof 1', 'wp-hr-gdpr' ),
                    'idenitity_proof_2'    => __( 'ID Proof 2', 'wp-hr-gdpr' ),
                );
                include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-new-access-request.php';
                return;
            }
            
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/custom_tables/wp-hr-request.php';
            $obj = new wphrgdrplist_request();
            ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php 
            _e( "Subject Access Requests", "wphrgdpr" );
            ?></h1>
                <hr class="wp-header-end">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder">
						<a href="admin.php?page=wphrgdpr_access_request&request_no=0" class="new_access_request_link button"><?php 
            _e( 'Create New Access Request Log Entry' );
            ?></a>
                        <form method="post">
                            <?php 
            $obj->prepare_items();
            $obj->display();
            ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
        }
        
        public function checkIsAValidDate( $data )
        {
            return strtotime( $data );
        }
        
        public function wphrgdpr_consent_form_handle()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-consent-form.php';
        }
        
        public function wphrgdpr_form_save_handle()
        {
            global  $wpdb ;
            // wphrgdpr_dd($GLOBALS['menu']);wphrgdpr_consent_form_submit
            $consent_nonce = filter_input( INPUT_POST, '_wpnonce' );
            
            if ( wp_verify_nonce( $consent_nonce, 'wphrgdpr_consent_form_submit' ) ) {
                $textbox_values = get_option( "wphrgdpr_consent_quetions", true );
                if ( !is_array( $textbox_values ) ) {
                    $textbox_values = array();
                }
                $textbox_values = filter_input(
                    INPUT_POST,
                    'consent_form_textarea',
                    FILTER_DEFAULT,
                    FILTER_REQUIRE_ARRAY
                );
                
                if ( !empty($textbox_values) ) {
                    $consert_data = array();
                    foreach ( $textbox_values as $single_value ) {
                        $single_value = trim( $single_value );
                        
                        if ( !empty($single_value) ) {
                            $consert_data[] = esc_html( $single_value );
                        } else {
                            wp_redirect( admin_url( 'admin.php?page=wphrgdpr_consent&error=true' ) );
                            exit;
                        }
                    
                    }
                    update_option( 'wphrgdpr_last_update_post', strtotime( date( 'Y-m-d H:i:s' ) ) );
                    update_option( "wphrgdpr_consent_quetions", $consert_data );
                }
                
                $form_contents['consent_form_title'] = filter_input( INPUT_POST, 'consent_form_title' );
                $form_contents['content_before_consent_form'] = filter_input( INPUT_POST, 'content_before_consent_form' );
                $form_contents['content_after_consent_form'] = filter_input( INPUT_POST, 'content_after_consent_form' );
                $form_contents['consent_form_privacy_text'] = filter_input( INPUT_POST, 'consent_form_privacy_text' );
                update_option( "wphrgdpr_consent_contents", $form_contents );
            }
            
            $email_nonce = filter_input( INPUT_POST, '_wpnonce' );
            
            if ( wp_verify_nonce( $email_nonce, 'wphrgdpr_email_template_submit' ) ) {
                $email_id = filter_input( INPUT_POST, "wphrgdpr_email_id" );
                $email_title_filter = filter_input( INPUT_POST, "wphrgdpr_email_title" );
                $email_title = esc_html( $email_title_filter );
                $email_desc_filter = filter_input( INPUT_POST, "wphrgdpr_email_desc" );
                $email_desc = esc_html( $email_desc_filter );
                $email_templates_data_old = get_option( "wphrgdpr_email_templates_data" );
                $email_templates_label_old = get_option( "wphrgdpr_email_templates_label" );
                $email_templates_data_old[$email_id] = $email_desc;
                $email_templates_label_old[$email_id] = $email_title;
                update_option( "wphrgdpr_email_templates_data", $email_templates_data_old );
                update_option( "wphrgdpr_email_templates_label", $email_templates_label_old );
                wp_redirect( admin_url( 'admin.php?page=wphrgdpr_email_template&editTemplate=' . $email_id . '&success=true' ) );
                exit;
            }
            
            // Save Subject Access Request Data
            $subject_access_request_register_nonce = filter_input( INPUT_POST, '_wpnonce' );
            
            if ( wp_verify_nonce( $subject_access_request_register_nonce, 'subject_access_request_register' ) ) {
                $table_fields = array(
                    'name',
                    'created',
                    'status',
                    'dpo_manager',
                    'appointed_date',
                    'expiry_date',
                    'address',
                    'telephone',
                    'email',
                    'idenitity_proof_1',
                    'idenitity_proof_1_verified',
                    'idenitity_proof_2',
                    'idenitity_proof_2_verified',
                    'extention_reason',
                    'reviced_expiry_date',
                    'notified_date',
                    'additional_info',
                    'additional_info_date',
                    'unfounded_request',
                    'declined_request_reason',
                    'unfounded_request_date',
                    'third_party_data_request',
                    'requested_data_type',
                    'requested_form_data_characteristic',
                    'requested_form_completion_date',
                    'other_notes'
                );
                $request_id = filter_input( INPUT_POST, "ID" );
                $form_fields = array();
                foreach ( $table_fields as $field ) {
                    $data = filter_input( INPUT_POST, $field );
                    $value = ( isset( $data ) ? $data : '' );
                    
                    if ( $value && $value != '-1' ) {
                        $validDate = $this->checkIsAValidDate( $value );
                        if ( $validDate ) {
                            $value = date( 'Y-m-d 00:00:00', strtotime( $value ) );
                        }
                    }
                    
                    $form_fields[$field] = $value;
                }
                
                if ( $request_id ) {
                    $result = $wpdb->update( WPHRGDPR_SUBJECT_ACCESS_TBL, $form_fields, array(
                        'ID' => $request_id,
                    ) );
                } else {
                    $result = $wpdb->insert( WPHRGDPR_SUBJECT_ACCESS_TBL, $form_fields );
                }
                
                $message = 'success';
                if ( $result === false ) {
                    $message = 'failed';
                }
                $data = array(
                    'page'           => 'wphrgdpr_access_request',
                    'access_request' => $message,
                );
                $link = add_query_arg( $data, admin_url( 'admin.php' ) );
                wp_redirect( $link );
                exit;
            }
        
        }
        
        /**
         * Create metabox for model answer
         */
        public function wphrgdpr_create_meta_field()
        {
            add_meta_box(
                'select_selection',
                __( 'Model Answer', 'wp-hr-gdpr' ),
                array( $this, 'wphrgdpr_create_meta_field_callback' ),
                'wp_hr_privacy_policy',
                'normal',
                'default'
            );
        }
        
        /**
         * Metabox design Callback function
         */
        public function wphrgdpr_create_meta_field_callback()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-meta-box-field.php';
        }
        
        /**
         * Metabox Save post Handle
         * @param int $post_id save post handle
         */
        public function wphrgdpr_save_meta_field( $post_id )
        {
            global  $wpdb ;
            $post_data = get_post( $post_id );
            if ( $post_data->post_type != 'wp_hr_privacy_policy' ) {
                return;
            }
            if ( !is_admin() ) {
                return;
            }
            $new_array = $value_checked = array();
            $selection_type = ( isset( $_POST['wphrgdpr_selection'] ) ? $_POST['wphrgdpr_selection'] : 0 );
            
            if ( $selection_type == 1 || $selection_type == 2 ) {
                if ( isset( $_POST['single_model_answer'] ) ) {
                    update_post_meta( $post_id, 'model_answers', $_POST['single_model_answer'] );
                }
                
                if ( $selection_type == 1 ) {
                    update_post_meta( $post_id, 'model_answer_type', 'editor' );
                } elseif ( $selection_type == 2 ) {
                    update_post_meta( $post_id, 'model_answer_type', 'checkbox' );
                }
                
                if ( isset( $_POST['chk_model_answer'] ) && !empty($_POST['chk_model_answer']) ) {
                    $new_array = $_POST['chk_model_answer'];
                }
                update_post_meta( $post_id, 'model_answers_chk', $new_array );
                
                if ( isset( $_POST['value_checked'] ) && !empty($_POST['value_checked']) ) {
                    $value_checked = $_POST['value_checked'];
                    update_post_meta( $post_id, 'value_checked', $value_checked );
                }
            
            }
            
            $users_data = $wpdb->get_results( "SELECT * FROM {$wpdb->usermeta} WHERE meta_key = 'wphrgdpr_consent_policy_submit'" );
            if ( $users_data ) {
                foreach ( $users_data as $key => $single_user ) {
                    $user_id = $single_user->user_id;
                    update_user_meta( $user_id, 'wphrgdpr_consent_policy_submit', 0 );
                }
            }
            update_option( 'wphrgdpr_last_update_post', strtotime( date( 'Y-m-d H:i:s' ) ) );
            /* if(isset($_POST['wphrgdpr_reuired_field']) && !empty($_POST['wphrgdpr_reuired_field']))
               {
               update_post_meta($post_id,'field_required',$_POST['wphrgdpr_reuired_field']);
               }
               else
               {
               update_post_meta($post_id,'field_required',0);
               } */
        }
        
        /**
         * Required Metabox design function
         */
        public function wphrgdpr_required_meta_field_callback()
        {
            $post_id = get_the_ID();
            $status = get_post_meta( $post_id, 'field_required', true );
            $checked = "";
            if ( $status == 'required' ) {
                $checked = 'checked';
            }
            ?>
            <label class="wphrgdpr_required_field">
                <input type="checkbox" name="wphrgdpr_reuired_field" value="required" <?php 
            echo  $checked ;
            ?>>&nbsp;<?php 
            _e( "Required", 'wphrgdpr-gdpr' );
            ?>
            </label>
            <?php 
        }
        
        /**
         * Display list of all training records
         */
        function wphrgdpr_training_records_callback()
        {
            include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/custom_tables/wp-hr-training.php';
            wphr_get_js_template( WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/training-add.php', 'wphr-gdpr-training-add' );
            $obj = new wphrgdrplist_training();
            ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php 
            _e( "Data protection training", "wphrgdpr" );
            ?></h1>
                <hr class="wp-header-end">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder">
						<a href="#" class="new_training_record_link button"><?php 
            _e( 'Add new record' );
            ?></a>
                        <form method="post">
                            <?php 
            $obj->prepare_items();
            $obj->display();
            ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
        }
    
    }
}
return new wphrgdpr_admin_custom_post();