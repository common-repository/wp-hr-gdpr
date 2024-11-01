<form id="frm_subject_reuqest" action="" method="post" enctype='multipart/form-data'>
	<?php
	if( isset( $_GET['status'] ) ){
		$message = '';
		switch( $_GET['status'] ){
			case 'unvalid_form':
				$message = __('Please fill all the form fields.', 'wp-hr-gdpr');
				break;
			case 'failed':
				$message = __('Please try again with accurate data. There is some issue raised during processing on your data.', 'wp-hr-gdpr');
				break;
			case 'success':
				$message = __('Your request is successfully submitted.', 'wp-hr-gdpr');
				break;
		}
		echo sprintf( '<div class="message %s"><p>%s</p></div>', $_GET['status'], $message );
	}
	?>
<div class="subject_request_form_handler">
	<p><?php _e( 'A Subject Access Request is a request for information we hold on you.  You do not have to use this form and can contact us by any means you choose (such as a letter or email) to request information, however by using the form below you will help to to more efficiently dealy with your request.', 'wp-hr-gdpr' ); ?></p> 
	<p><?php echo sprintf( __( 'Alternative contact details can be found on our Privacy Policy Page <a href="%s">here</a>.', 'wp-hr-gdpr' ), '#' ); ?></p> 
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('1.0 Your Name', 'wp-hr-gdpr'); ?></lable>
			<p>(<?php _e('Please also provide any other names under which you have been employed, for example a maiden name', 'wp-hr-gdpr'); ?>)</p>
		</div>
		<div class="inputset">
			<input type="text" name="employee_name" value="<?php echo $user_name; ?>" />
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('2.0 Your Status', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('Are you an employee, ex-employee or job applicant?', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<select name="user_position">
				<option value="employee"><?php _e( 'Employee', 'wp-hr-gdpr' );?></option>
				<option value="ex-employee"><?php _e( 'Ex-employee', 'wp-hr-gdpr' );?></option>
				<option value="job-applicant"><?php _e( 'Job applicant', 'wp-hr-gdpr' );?></option>
			</select>
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('3.0 Where Located', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('Company location and employing department (if applicable).', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="location" value="" />
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('4.0 When Employed', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e( 'Relevant employment dates (as applicable):', 'wp-hr-gdpr' ); ?></p>
		</div>
		<div class="inputset">&nbsp;</div>
	</div>
	<div class="subject_request_formset listing_dates">
		<div class="lableset padding-left-15">
			<p><?php _e( '(a) Commencement date', 'wp-hr-gdpr' ); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="employed_date" class="date_mask" placeholder="MM/DD/YYYY"  />
		</div>
	</div>
	<div class="subject_request_formset listing_dates">
		<div class="lableset padding-left-15">
			<p><?php _e( '(b) Termination date', 'wp-hr-gdpr' ); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="termination_date" class="date_mask" placeholder="MM/DD/YYYY"  />
		</div>
	</div>
	<div class="subject_request_formset listing_dates">
		<div class="lableset padding-left-15">
			<p><?php _e( '(c) Date of job application', 'wp-hr-gdpr' ); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="job_application_date" class="date_mask" placeholder="MM/DD/YYYY"  />
		</div>
	</div>
	<div class="subject_request_formset listing_dates margin-bottom-40">
		<div class="lableset padding-left-15">
			<p><?php _e( '(d) Date of job interview', 'wp-hr-gdpr' ); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="job_interview_date" class="date_mask" placeholder="MM/DD/YYYY"  />
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('5.0 Data Requested', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e( 'Please specify precisely the personal data that you are requesting and give as much information as possible to enable the personal data to be located, including:', 'wp-hr-gdpr' ); ?></p>
			<ul>
				<li><?php _e( '(a) The specific documents or files you wish to see.', 'wp-hr-gdpr' ); ?></li>
				<li><?php _e( '(b) The names of any individuals or departments whom you believe may hold the personal data you have requested.', 'wp-hr-gdpr' ); ?></li>
				<li><?php _e( '(c) Any other information which will assist us in searching for the personal data you have requested.', 'wp-hr-gdpr' ); ?></li>
			</ul>
		</div>
		<div class="inputset">
			<textarea name="data"></textarea>
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('6.0 Address', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('Address where we can contact you and to which you would like the personal data to be sent.', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<textarea name="address"></textarea>
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('7.0 Telephone Number', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('A number we can contact you on if we need to talk to you.', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="telephone"  />
		</div>
	</div>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('8.0 Email Address', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('An email address where we can contact you.', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<input type="text" name="email"  />
		</div>
	</div>
	<?php if( !get_current_user_id() ){ ?>
	<div class="subject_request_formset">
		<div class="lableset">
			<lable><?php _e('9.0 Evidence of your Identity', 'wp-hr-gdpr'); ?></lable>
			<p><?php _e('Please supply two documents as proof of your identity and address (for example, a driving licence, passport or identity card as evidence of identity and a recent bill from a utility company or bank or credit card statement as evidence of address).', 'wp-hr-gdpr'); ?></p>
		</div>
		<div class="inputset">
			<div class="upload_file_handler">
				<span><?php _e( '1) Identity proof', 'wp-hr-gdpr' ); ?></span> <input type="file" name="idenitity_proof_1" />
			</div>
			<div class="upload_file_handler">
				<span><?php _e( '2) Address proof', 'wp-hr-gdpr' ); ?></span> <input type="file" name="idenitity_proof_2" />
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="subject_request_formset">
		<p><?php _e( 'By submitting this form, I accept that I am making a request under the General Data Protection Regulations to receive a copy of specified personal data that the Company holds about me.  By submitting this form, I confirm that I am the data subject named above and that you can contact me if necessary if you wish to obtain further identifying information before agreeing to my request.', 'wp-hr-gdpr' ); ?></p>
		<p><?php _e( 'I acknowledge that you may also need to contact me to obtain any further information that you require to enable you to comply with my request.', 'wp-hr-gdpr' ); ?></p>
		<p><?php _e( 'I understand that it may take up to 30 days from receipt of this form, or from receipt of any further information you request from me which is required to enable you to comply with my request, before a reply to my request is provided to me.', 'wp-hr-gdpr' )?></p>
		<p>
			<?php wp_nonce_field( 'subject_access_request' ); ?>
			<input type="hidden" name="action" value="subject_access_request" />
			<input type="submit" value="Submit">
		</p>
	</div>
</div>
</form>