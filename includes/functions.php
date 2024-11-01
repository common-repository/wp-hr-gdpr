<?php

/** 
* Add "Data Protection" content to user profile.
* 
* @since 1.0.0
* 
*/

function wphr_hr_employee_single_tab_data_protection( $employee ){
	$wphrgdpr_page_ids = get_option('wphrgdpr_page_ids');
	$privacy_notice = get_the_permalink( $wphrgdpr_page_ids['privacy_form'] );
	$subject_access_summary = get_the_permalink( $wphrgdpr_page_ids['subject_access_summary'] );
	$consent_form = get_the_permalink( $wphrgdpr_page_ids['consent_form'] );
	
	$tbl_consent = new wphrgdpr_manage_db(WPHRGDPR_CONSENT_TBL);
	$consent_record = $tbl_consent->wphrgdpr_get_by(array('user_id' => $employee->ID), '=', true, false, false, 'created', 'DESC', 1);
	$consent_data = false;
	$consent_aggrement = $consent_date = '';
	if( count( $consent_record ) ){
		$consent_date = date( 'd-m-Y', strtotime( $consent_record->created ) );
		$consent_aggrement = unserialize( $consent_record->data );
		$consent_data = true;
	}
	
	$tbl_training = new wphrgdpr_manage_db(WPHRGDPR_TRAINING_TBL);
	$training_record = $tbl_training->wphrgdpr_get_by( array('user_id' => $employee->ID), '=', false, false, false, 'date', 'DESC' );
	
	$tbl_subject_access = new wphrgdpr_manage_db(WPHRGDPR_SUBJECT_ACCESS_TBL);
	$subject_access_record = $tbl_subject_access->wphrgdpr_get_by( array('user_id' => $employee->ID), '=', false, false, false, 'created', 'DESC' );
	
	include_once WPHRGDPR_PLUGIN_PATH . 'includes/admin/templates/wp-hr-user-data.php';	
}

/**
* Get Data Policy Record.
*
* @since 1.0.0	
*
* @param $consent_id int record id.
* @return object Return object of record
*/
function wphr_gpdr_consent_get_record( $consent_id ){
	$tbl_consent = new wphrgdpr_manage_db(WPHRGDPR_CONSENT_TBL);
	$consent_record = $tbl_consent->wphrgdpr_get_by(array('ID' => $consent_id), '=', true, false, false, 'created', 'DESC', 1);
	if( count( $consent_record ) ){
		return $consent_record;
	}
	return false;
}

/**
* Get SAR request data.
* 
* @since 1.0.0
* 
* @param $request_id int record id.
* @return object Return object of record
*/
function wphr_gpdr_get_sar_request( $request_id ){
	$tbl_consent = new wphrgdpr_manage_db(WPHRGDPR_SUBJECT_ACCESS_TBL);
	$sar_record = $tbl_consent->wphrgdpr_get_by(array('ID' => $request_id), '=', true, false, false, 'created', 'DESC', 1);
	if( count( $sar_record ) ){
		return $sar_record;
	}
	return false;
}

/**
* Check data is date or not?
*
* @since 1.0.0
*
* @param $value string/date Any value.
* @return boolean Return true if giving value is valid date
*/
function checkIsAValidDate($value){
	return strtotime($value);
}
?>