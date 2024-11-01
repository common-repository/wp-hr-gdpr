<?php
/**
 * WPHRGDPR_GDRP setup
 *
 * @package WP_HR GDPR Lite
 * @since    1.0.0
 */
defined('ABSPATH') || exit;

/**
 * WPHR_GDPR_USER_TRAINING Class.
 *
 * @class WPHR_GDPR_USER_TRAINING
 * @author prism
 */
class WPHR_GDPR_USER_TRAINING{

    /**
     * WPHR_GDPR_USER_PROFILE Constructor.
     */	
	function __construct(){
		add_filter( 'wp_ajax_wphr-hr-manage-training', array( $this, 'employee_training_controller' ) );
	}

	/**
	* Save "Data Processing Training".
	* 
	* @return int new added record id. 
	*/	
	function employee_training_controller(){
		$tbl_training = new wphrgdpr_manage_db(WPHRGDPR_TRAINING_TBL);
		$id = (int) $_POST['id'];
		$data['user_id'] = $_POST['user_id'];
		$data['course_name'] = $_POST['course_name'];
		$data['date'] = date( 'Y-m-d', strtotime( $_POST['training_date'] ) );
		$data['note'] = $_POST['note'];
		if( $id ){
			$record_no = $tbl_training->update( $data, array( 'id' => $_POST['id'] ) );
		}else{
			$record_no = $tbl_training->insert( $data );
		}
		if( $record_no !== false ){
			wp_send_json_success( array('id' => $record_no, 'title' => $data['course_name'] ) );	
		}else{
			wp_send_json_error();	
		}
		die();
	}
	
}
new WPHR_GDPR_USER_TRAINING();	
?>