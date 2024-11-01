<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Handle all Shortcode Requests
 * @class wphrgdpr_shortcode_handler
 * @since 1.0.0
 * @author prism
 */
class wphrgdpr_shortcode_handler
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_shortcode( "consent_form", array( $this, "wphrgdpr_consent_form_shortcode" ) );
        add_shortcode( "privacy_policy", array( $this, "wphrgdpr_privacy_form_shortcode" ) );
        add_shortcode( "subject_access_request", array( $this, "wphrgdpr_subject_access_request_form_shortcode" ) );
        add_action( 'init', array( $this, 'save_subject_access_request_summary' ) );
        add_action( "wp_ajax_privacy_form_shortcode_save", array( $this, 'wphrgdpr_privacy_form_shortcode_save' ) );
        add_action( "wp_ajax_nopriv_privacy_form_shortcode_save", array( $this, 'wphrgdpr_privacy_form_shortcode_save' ) );
        add_action( "wp_ajax_wphrgdpr_consent_form_save_front", array( $this, 'wphrgdpr_consent_form_save_front' ) );
        add_action( "wp_ajax_nopriv_wphrgdpr_consent_form_save_front", array( $this, 'wphrgdpr_consent_form_save_front' ) );
    }
    
    /*
     * Subject Access Request
     */
    public function wphrgdpr_subject_access_request_form_shortcode( $atts )
    {
        global  $current_user ;
        $user = wp_get_current_user();
        $user_name = $user->first_name;
        if ( $user->last_name != '' ) {
            $user_name .= ' ' . $user->last_name;
        }
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/shortcode-template/subject-access-request.php';
    }
    
    /**
     * Privacy form Shortcode
     */
    public function wphrgdpr_privacy_form_shortcode( $atts )
    {
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/shortcode-template/privacy-form-template.php';
    }
    
    /*
     * Consent Form Shortcode
     */
    public function wphrgdpr_consent_form_shortcode( $atts )
    {
        global  $current_user ;
        $user = wp_get_current_user();
        $user_name = '';
        
        if ( $user ) {
            $user_name = $user->first_name;
            if ( $user->last_name != '' ) {
                $user_name .= ' ' . $user->last_name;
            }
        }
        
        include_once WPHRGDPR_PLUGIN_PATH . 'includes/frontend/shortcode-template/consent-form-template.php';
    }
    
    /*
     * Consent form Save method handle
     */
    function wphrgdpr_consent_form_save_front()
    {
        /* @var $_REQUEST type */
        $nonce = filter_input( INPUT_POST, '_wpnonce' );
        
        if ( wp_verify_nonce( $nonce, 'wphrgdpr_consent_page' ) ) {
            $class_obj = new wphrgdpr_manage_db( WPHRGDPR_CONSENT_TBL );
            $is_validate = $this->wphrgdpr_conset_form_validate( $_POST );
            $url = filter_input( INPUT_POST, 'conset_page_url' );
            
            if ( empty($is_validate) ) {
                $headers[] = "MIME-Version: 1.0" . "\r\n";
                $headers[] = "Content-type: text/html; charset=UTF-8" . "\r\n";
                $admin_email = WPHRGDPR_ADMIN_EMAIL;
                $email_templates_data = get_option( "wphrgdpr_email_templates_data" );
                $email_templates_label = get_option( "wphrgdpr_email_templates_label" );
                $consent_form_rec_db = get_option( "wphrgdpr_consent_quetions" );
                $consent_form_data = array();
                $consent_form_data_count = 0;
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
                
                $consent_form_rec = nl2br( $consent_form_rec_data );
                $page_ids = get_option( "wphrgdpr_page_ids" );
                $linkToPrivacy = '<a target="_blank" href="' . get_the_permalink( $page_ids['privacy_form'] ) . '">' . __( "Click here for Data Privacy Policy", "wphrgdpr" ) . '</a>';
                $admin_email_subject = $email_templates_label[2];
                $admin_email_desc = $email_templates_data[2];
                $user_email_subject = $email_templates_label[1];
                $user_email_desc = $email_templates_data[1];
                $name = filter_input( INPUT_POST, 'consent_user_name' );
                $email = sanitize_email( filter_input( INPUT_POST, 'consent_email' ) );
                $current_user_id = get_current_user_id();
                $last_post_updated = get_option( 'wphrgdpr_last_update_post' );
                $insert_data_arr = array(
                    'name'    => $name,
                    'email'   => $email,
                    'created' => date( 'Y-m-d H:i:s' ),
                    'data'    => serialize( $consent_form_data ),
                    'status'  => $status,
                    'user_id' => $current_user_id,
                );
                $userdetails = '<br>' . __( 'User Details', 'wp-hr-gdpr' ) . '<br>' . __( "Name ", "wphrgdpr" ) . ': ' . $name . '<br>' . __( "Email", "wphrgdpr" ) . ': ' . $email . '<br>';
                $replace_array_admin = array(
                    '{user_details}'         => $userdetails,
                    '{copy_of_form_content}' => $consent_form_rec,
                    '{privacy_notice_link}'  => $linkToPrivacy,
                );
                $replace_array_user = array(
                    '{copy_of_form_content}' => $consent_form_rec,
                    '{privacy_notice_link}'  => $linkToPrivacy,
                );
                $admin_email_content_tr = strtr( $admin_email_desc, $replace_array_admin );
                $admin_email_content = nl2br( $admin_email_content_tr );
                $user_email_content_tr = strtr( $user_email_desc, $replace_array_user );
                $user_email_content = nl2br( $user_email_content_tr );
                $already_error = __( "Already Submited", "wphrgdpr" );
                $error_array = json_encode( array(
                    'data'       => $already_error,
                    'type'       => 'error',
                    'reset_form' => true,
                ) );
                $prev_email = '';
                if ( $current_user_id ) {
                    $prev_email = $class_obj->wphrgdpr_get_by(
                        array(
                        'user_id' => $current_user_id,
                    ),
                        '=',
                        false,
                        false,
                        false,
                        'created'
                    );
                }
                
                if ( $prev_email ) {
                    foreach ( $prev_email as $key => $single_rec ) {
                        
                        if ( $key == 0 ) {
                            $created = $single_rec->created;
                            
                            if ( strtotime( $created ) < $last_post_updated ) {
                                $consent_id = $class_obj->insert( $insert_data_arr );
                                wp_mail(
                                    $admin_email,
                                    $admin_email_subject,
                                    $admin_email_content,
                                    $headers
                                );
                                wp_mail(
                                    $email,
                                    $user_email_subject,
                                    $user_email_content,
                                    $headers
                                );
                                die( json_encode( array(
                                    'data' => $url,
                                    'type' => 'redirect',
                                ) ) );
                            } else {
                                die( $error_array );
                            }
                        
                        } else {
                            die( $error_array );
                        }
                    
                    }
                } else {
                    $consent_id = $class_obj->insert( $insert_data_arr );
                    wp_mail(
                        $admin_email,
                        $admin_email_subject,
                        $admin_email_content,
                        $headers
                    );
                    wp_mail(
                        $email,
                        $user_email_subject,
                        $user_email_content,
                        $headers
                    );
                    die( json_encode( array(
                        'data' => $url,
                        'type' => 'redirect',
                    ) ) );
                }
            
            } else {
                $error_array = json_encode( array(
                    'data'       => $is_validate,
                    'type'       => 'error',
                    'reset_form' => false,
                ) );
                die( $error_array );
            }
        
        }
    
    }
    
    public function wphrgdpr_conset_form_validate( $consent_data )
    {
        $erros_class = array();
        /*$textbox_values = get_option("wphrgdpr_consent_quetions", true);
          if ($textbox_values) {
              foreach ($textbox_values as $key => $val) {
                  if (!isset($consent_data['consent_chk_' . $key]))
                      $erros_class[] = 'consent_chk_policy';
              }
          }*/
        $consent_username = trim( $consent_data['consent_user_name'] );
        $consent_email = trim( $consent_data['consent_email'] );
        if ( isset( $consent_data['consent_user_name'] ) && empty($consent_username) ) {
            $erros_class[] = 'consent_user_name';
        }
        if ( !isset( $consent_data['consent_chk_policy'] ) ) {
            $erros_class[] = 'consent_chk_policy';
        }
        if ( isset( $consent_data['consent_email'] ) && empty($consent_email) || !is_email( $consent_data['consent_email'] ) ) {
            $erros_class[] = 'consent_email';
        }
        return $erros_class;
    }
    
    /**
     * Save subject access request summary
     * 
     * @return obj
     */
    public function save_subject_access_request_summary()
    {
        
        if ( isset( $_POST['action'] ) && $_POST['action'] == 'subject_access_request' ) {
            $consent_nonce = filter_input( INPUT_POST, '_wpnonce' );
            
            if ( wp_verify_nonce( $consent_nonce, 'subject_access_request' ) ) {
                global  $wpdb ;
                $form_fields = array(
                    'name',
                    'email',
                    'user_position',
                    'location',
                    'employed_date',
                    'termination_date',
                    'job_application_date',
                    'job_interview_date',
                    'data',
                    'address',
                    'telephone'
                );
                $user_id = get_current_user_id();
                $data['user_id'] = $user_id;
                $data['status'] = __( 'New', 'wp-hr-gdpr' );
                $valid_form = true;
                foreach ( $form_fields as $field ) {
                    $key = $field;
                    if ( $field == 'name' ) {
                        $field = 'employee_name';
                    }
                    $value = filter_input( INPUT_POST, $field );
                    $validDate = checkIsAValidDate( $value );
                    
                    if ( (int) $validDate > 0 ) {
                        $data[$key] = date( 'Y-m-d', strtotime( $value ) );
                    } else {
                        $data[$key] = filter_input( INPUT_POST, $field );
                    }
                    
                    if ( empty($data[$key]) ) {
                        $valid_form = false;
                    }
                }
                
                if ( !$valid_form ) {
                    $page_url = get_the_permalink();
                    $form_submit = 'unvalid_form';
                    wp_redirect( add_query_arg( 'status', $form_submit, $page_url ) );
                    exit;
                }
                
                
                if ( !$user_id ) {
                    if ( !function_exists( 'wp_handle_upload' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/file.php';
                    }
                    $uploadedfile = $_FILES['idenitity_proof_1'];
                    $upload_overrides = array(
                        'test_form' => false,
                    );
                    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                    if ( $movefile && !isset( $movefile['error'] ) ) {
                        $data['idenitity_proof_1'] = $movefile['url'];
                    }
                    $uploadedfile = $_FILES['idenitity_proof_2'];
                    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                    if ( $movefile && !isset( $movefile['error'] ) ) {
                        $data['idenitity_proof_2'] = $movefile['url'];
                    }
                }
                
                $inserted_id = $wpdb->insert( WPHRGDPR_SUBJECT_ACCESS_TBL, $data );
                $page_url = get_the_permalink();
                $form_submit = 'failed';
                
                if ( $inserted_id ) {
                    $form_submit = 'success';
                    $emailer = wphr()->emailer->get_email( 'Subject_Access_Request' );
                    if ( is_a( $emailer, 'WPHR\\HR_MANAGER\\Email' ) ) {
                        $emailer->trigger( $wpdb->insert_id );
                    }
                }
                
                wp_redirect( add_query_arg( 'status', $form_submit, $page_url ) );
                exit;
            }
        
        }
    
    }

}
return new wphrgdpr_shortcode_handler();