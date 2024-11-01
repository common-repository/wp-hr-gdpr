<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle all DPO Requests
 *
 * @class wphrgdpr_dpo
 * @since 1.0.0
 * @author prism
 */
class wphrgdpr_dpo {
 
    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wphr_user_profile_role', array( $this, 'role' ) );
        add_action( 'wphr_update_user', array( $this, 'update_user' ), 10, 2 );
		
		add_filter( 'wphr_hr_get_caps_for_role', array( $this, 'wphr_dpo_capability' ), 10, 2 );
    }
 
 	/*
	* Filter DPO role slug
	*/
	public function wphr_gdpr_get_dpo_role(){
		return apply_filters( 'wphr_gdpr_get_dpo_role', 'dpo_manager' );
	}
	
	/*
	* Update user
	*/
    function update_user( $user_id, $post ) {

        // HR role we want the user to have
        $new_hr_manager_role    = isset( $post['dpo_manager'] ) ? sanitize_text_field( $post['dpo_manager'] ) : false;

        // Bail if current user cannot promote the passing user
        if ( ! current_user_can( 'promote_user', $user_id ) ) {
            return;
        }

        // Set the new HR role
        $user = get_user_by( 'id', $user_id );

        if ( $new_hr_manager_role ) {
            $user->add_role( $new_hr_manager_role );
        } else {
            $user->remove_role( $this->wphr_gdpr_get_dpo_role() );
        }
    }
	
    /**
    * include admin assests file
    */
 	public function dpo_role(){
		remove_role( $this->wphr_gdpr_get_dpo_role() );
	    add_role (
			$this->wphr_gdpr_get_dpo_role(),
			__( 'Data Protection Officer', 'wp-hr-gdpr' ),
			wphr_hr_get_caps_for_role( $this->wphr_gdpr_get_dpo_role() )
		);
 	}
	
    /**
     * include admin assests file
     */
    public function role( $profileuser ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $checked = in_array( $this->wphr_gdpr_get_dpo_role(), $profileuser->roles ) ? 'checked' : '';
        ?>
        <label for="wphr-dpo-manager">
            <input type="checkbox" id="wphr-dpo-manager" <?php echo $checked; ?> name="dpo_manager" value="<?php echo $this->wphr_gdpr_get_dpo_role(); ?>">
            <span class="description"><?php _e( 'Data Protection Officer', 'wphr' ); ?></span>
        </label>
        <?php
    }
	
	/**
	* wphr_dpo_capability.
	* Add capability for DPO role.
	*
	* @since 1.0.0.
	* @param $caps array list of role capability.
	* @param $role string name of select user role.
	*
	* @return array Give list of capability of user role.
	*/
	function wphr_dpo_capability( $caps, $role ){
		if( $role ==  $this->wphr_gdpr_get_dpo_role() ){
			$caps = [
                'read'                     => true,

                // Upload file
                'upload_files'             => true,
				
				// Notice Pages
                'wphr_list_notice'        => true,
                'wphr_create_notice'      => true,
                'wphr_view_notice'        => true,
                'wphr_edit_notice'        => true,
                'wphr_delete_notice'      => true,
				
				// Consent Pages
                'wphr_list_consent'        => true,
                'wphr_create_consent'      => true,
                'wphr_view_consent'        => true,
                'wphr_edit_consent'        => true,
                'wphr_delete_consent'      => true,
				
                'wphr_view_consent_summary'      => true,
				
				// Access request pages
                'wphr_list_access_request'        => true,
                'wphr_create_access_request'      => true,
                'wphr_view_access_request'        => true,
                'wphr_edit_access_request'        => true,
                'wphr_delete_access_request'      => true,
				
				// Data training pages
                'wphr_list_data_training'        => true,
                'wphr_create_data_training'      => true,
                'wphr_view_data_training'        => true,
                'wphr_edit_data_training'        => true,
                'wphr_delete_data_training'      => true,
			];
		}
		return $caps;
	}

    /**
     * Set dpo_manager role for admin user
     *
     * @since 1.0
     *
     * @return void
     */
    public function set_role() {
        $admins = get_users( array( 'role' => 'administrator' ) );

        if ( $admins ) {
            foreach ($admins as $user) {
                $user->add_role( $this->wphr_gdpr_get_dpo_role() );
            }
        }
    }
	
}
$GLOBALS['gdrppro_dpo'] = new wphrgdpr_dpo();