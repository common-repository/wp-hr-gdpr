<?php
namespace WPHR\HR_MANAGER\HRM\Emails;

use WPHR\HR_MANAGER\Email;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Data Protection Form
 */
class Data_Protection_Form extends Email {

    use Hooker;

    function __construct() {
        $this->id             = 'data-protection-form';
        $this->title          = __( 'Data Protection Form', 'wphr' );
        $this->description    = __( 'Send a copy of the Consent Form information to the person submitting the form and the Data Protection Officer(s).', 'wphr' );

        $this->subject        = __( 'Copy of Data Protection Form Submission', 'wphr');
        $this->heading        = __( 'Data Protection Form Submission', 'wphr');

        $this->find = [
            'copy_of_form_content'    	=> '{copy_of_form_content}',
            'privacy_notice_link' 		=> '{privacy_notice_link}',
            'sar_form_link'   => '{sar_form_link}',
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
     *
     * @param int $consent_id
     *
     * @return boolean
     */
    public function trigger( $consent_id = null ) {
		global $gdrppro_dpo;
        $consent_data = wphr_gpdr_consent_get_record( $consent_id );

        if ( ! $consent_data ) {
            return;
        }
		$html = '';
        $this->heading     = $this->get_option( 'heading', $this->heading );
        $this->subject     = $this->get_option( 'subject', $this->subject );
		
 		$page_ids = get_option( 'wphrgdpr_page_ids' );
		$user_email = '';
		if( $consent_data ){
			$user_email = $consent_data->email;
			$consent_aggrement = unserialize( $consent_data->data );	
			if( $consent_aggrement ){
				$html .= '<table cellspacing="0" align="top">';
				foreach( $consent_aggrement as $key => $field ){
					$html .= sprintf( '<tr><td valign="top"><input disabled type="checkbox" %s /></td><td>%s</td></tr>', $field ? 'checked' : '', $key );	
				}	
				$html .= '</table>';
			}
		}

        $this->replace = [
            'copy_of_form_content'  => $html,
            'privacy_notice_link' 	=> $page_ids['privacy_form'],
            'sar_form_link'   		=> $page_ids['subject_access_summary'],
        ];

        $subject     = $this->get_subject();
        $content     = $this->get_content();
        $headers     = $this->get_headers();
        $attachments = $this->get_attachments();
        $recipients  = [];

        $dop_officers = get_users( [ 'role' => $gdrppro_dpo->wphr_gdpr_get_dpo_role() ] );

        if ( ! $dop_officers ) {
            return;
        }

        foreach ( $dop_officers as $hr ) {
            $recipients[] = $hr->user_email;
        }
        $recipients = apply_filters( 'wphr_consent_form_notification_recipients', $recipients, $request );
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
