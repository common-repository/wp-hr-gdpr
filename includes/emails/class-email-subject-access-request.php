<?php
namespace WPHR\HR_MANAGER\HRM\Emails;

use WPHR\HR_MANAGER\Email;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Subject Access Request
 */
class Subject_Access_Request extends Email {

    use Hooker;

    function __construct() {
        $this->id             = 'subject-access-request';
        $this->title          = __( 'Subject Access Request', 'wphr' );
        $this->description    = __( 'Copy the Subject Access Request form details by email to person submitting the Form and the Data Protection Officer(s).', 'wphr' );

        $this->subject        = __( 'Subject Access Request', 'wphr');
        $this->heading        = __( 'New Subject Access Request Submitted', 'wphr');

        $this->find = [
            'sar_content'    	=> '{sar_content}',
        ];

        $this->action( 'wphr_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    function get_args() {
        return [
            'email_heading' => $this->heading,
            'email_body'    => wpautop( $this->get_option( 'body' ) ),
        ];
    }

    /**
     * Trigger sending email
     *
     * @since 1.0.0
     * @since 1.2.0 Send single email to multiple recipients.
     *              Add `wphr_new_leave_request_notification_recipients` filter
     *
     * @param int $request_id
     *
     * @return boolean
     */
    public function trigger( $request_id = null ) {
		global $gdrppro_dpo;
		
        $request = wphr_gpdr_get_sar_request( $request_id );
		
        if ( ! $request ) {
            return;
        }
		$form_fields = array( 
						'name' => __('Name','wp-hr-gdpr'), 
						'email' => __('Email','wp-hr-gdpr'), 
						'telephone' => __('Phone', 'wp-hr-gdpr'), 
						'address' => __('Address', 'wp-hr-gdpr'), 
						'user_position' => __('Status', 'wp-hr-gdpr'), 
						'location' => __('Company location and employing department', 'wp-hr-gdpr'), 
						'employed_date' => __('Employed date', 'wp-hr-gdpr'), 
						'data' => __('Personal information', 'wp-hr-gdpr') 
					);
		if( !$request->user_id ){
			$form_fields['idenitity_proof_1'] = __('idenitity_proof_1', 'wp-hr-gdpr');
			$form_fields['idenitity_proof_2'] = __('idenitity_proof_2', 'wp-hr-gdpr');
		}

		$user_email = $request->email;
        $this->heading     = $this->get_option( 'heading', $this->heading );
        $this->subject     = $this->get_option( 'subject', $this->subject );
		
		$sar_content = '<table cellspacing="0" align="top">';
		foreach( $form_fields as $key => $field ){
			$sar_content .= sprintf( '<tr><td valign="top">%s</td><td>%s</td></tr>', $field, $request->$key );	
		}	
		
        $this->replace = [
            'sar_content'    => $sar_content,
        ];

        $subject     = $this->get_subject();
        $content     = $this->get_content();
        $headers     = $this->get_headers();
        $attachments = $this->get_attachments();
        $recipients  = [];

        $managers = get_users( [ 'role' => $gdrppro_dpo->wphr_gdpr_get_dpo_role() ] );

        if ( ! $managers ) {
            return;
        }

        foreach ( $managers as $hr ) {
            $recipients[] = $hr->user_email;
        }

        $recipients = apply_filters( 'wphr_new_sar_request_notification_recipients', $recipients, $request );
		$this->send( $user_email, $subject, $content, $headers, $attachments );
        return $this->send( $recipients, $subject, $content, $headers, $attachments );
    }

    /**
     * get_content_html function.
     *
     * @access public
     * @return string
     */
    function get_content_html() {
        $message = $this->get_template_content( WPHR_INCLUDES . '/email/email-body.php', $this->get_args() );

        return $this->format_string( $message );
    }

    /**
     * get_content_plain function.
     *
     * @access public
     * @return string
     */
    function get_content_plain() {
        $message = $this->get_template_content( WPHR_INCLUDES . '/email/email-body.php', $this->get_args() );

        return $message;
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = [
            [
                'title'       => __( 'Subject', 'wphr' ),
                'id'          => 'subject',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'wphr' ), $this->subject ),
                'placeholder' => '',
                'default'     => $this->subject,
                'desc_tip'    => true
            ],
            [
                'title'       => __( 'Email Heading', 'wphr' ),
                'id'          => 'heading',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'wphr' ), $this->heading ),
                'placeholder' => '',
                'default'     => $this->heading,
                'desc_tip'    => true
            ],
            [
                'title'             => __( 'Email Body', 'wphr' ),
                'type'              => 'wysiwyg',
                'id'                => 'body',
                'description'       => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'wphr' ), $this->heading ),
                'placeholder'       => '',
                'default'           => '',
                'desc_tip'          => true,
                'custom_attributes' => [
                    'rows' => 5,
                    'cols' => 45
                ]
            ],
            [
                'type' => $this->id . '_help_texts'
            ]
        ];
    }

    /**
     * Template tags
     *
     * @return void
     */
    function replace_keys() {
        ?>
        <tr valign="top" class="single_select_page">
            <th scope="row" class="titledesc"><?php _e( 'Template Tags', 'wphr' ); ?></th>
            <td class="forminp">
                <em><?php _e( 'You may use these template tags inside subject, heading, body and those will be replaced by original values', 'wphr' ); ?></em>:
                <?php echo '<code>' . implode( '</code>, <code>', $this->find ) . '</code>'; ?>
            </td>
        </tr>
        <?php
    }
}
