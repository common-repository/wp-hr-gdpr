<?php
/**
 * WPHRGDPR_GDRP setup
 *
 * @package WP_HR GDPR Lite
 * @since    1.0.0
 */
defined('ABSPATH') || exit;

/**
 * WPHR_GDPR_USER_PROFILE Class.
 *
 * @class WPHR_GDPR_USER_PROFILE
 * @author prism
 */
class WPHR_GDPR_USER_PROFILE{

    /**
     * WPHR_GDPR_USER_PROFILE Constructor.
     */	
	function __construct(){
		add_filter( 'wphr_hr_employee_single_tabs', array( $this, 'tab' ), 20, 2 );
		add_filter( 'wphr_hr_localize_script', array( $this, 'script_variable_name' )  );
		add_filter( 'wphr_email_classes', array( $this, 'gdpr_email_classes' ), 1 );
	}
	

    /**
     * Add new "Data Protection" tab to employee profile page.
     *
     * @since 1.0.0
	 *
	 * @param arrray $tab_heading list of tab heading.
     * @return array - new tab heading array.
     */
	function tab( $tab_heading, $employee ){
		if ( absint( $employee->id ) === get_current_user_id() ) {
			$tab_heading['data_protection'] = array(
            	'title'    => __( 'Data Protection', 'wp-hr-gdpr' ),
				'callback' => 'wphr_hr_employee_single_tab_data_protection'
			);
		}
		return $tab_heading;
	}

    /**
     * Add new text for script variable
     *
     * @since 1.0.0
	 *
	 * @param arrray $list list of text which is used in javascript.
     * @return array - new list with text.
     */	
	
	function script_variable_name( $list ){
		$list['popup']['view_consent_aggrement'] = __( 'Consent Agreement', 'wp-hr-gdpr' );
		$list['popup']['view_sar_request']		 = __( 'Subject Access Request Details', 'wp-hr-gdpr' );
		$list['popup']['trainig_record']		 = __( 'Data Protection Training Record', 'wp-hr-gdpr' );
		return $list;
	}
	
	/**
	* Add new email classes for GDPR.
	*
    * @since 1.0.0
	* 
	* @param array $emails list of email class objects.
	* @return array - list of email objects
	*/
	
	function gdpr_email_classes( $emails ){
		include_once WPHRGDPR_PLUGIN_PATH . 'includes/emails/class-email-data-protection-form.php';
		include_once WPHRGDPR_PLUGIN_PATH . 'includes/emails/class-email-subject-access-request.php';
		$emails['Data_Protection_Form'] = new WPHR\HR_MANAGER\HRM\Emails\Data_Protection_Form();
		$emails['Subject_Access_Request'] = new WPHR\HR_MANAGER\HRM\Emails\Subject_Access_Request();
		return $emails;
	}
	
}
new WPHR_GDPR_USER_PROFILE();	
?>