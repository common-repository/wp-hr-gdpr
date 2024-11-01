<?php
global $gdrppro_dpo;
$status = array('New', 'Initiated', 'Extended', 'In Progress', 'Completed');
?>
<form id="frm_access_request_register" method="post">
<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e("Subject Access Request Register", "wphrgdpr"); ?></h1>
    <hr class="wp-header-end">
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder subject_access_request_register_panel">
			<div class="section">
				<h2><?php _e('Summary', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field">
						<label for="id"><?php _e('Request Number', 'wp-hr-gdpr');?></label>
						<input type="text" name="ID" value="<?php echo $form_fields['ID']?>" readonly placeholder="<?php _e('It will auto generate'); ?>" />
					</div>
					<div class="form_field">
						<label for="name"><?php _e('Request From', 'wp-hr-gdpr');?></label>
						<input type="text" name="name" value="<?php echo $form_fields['name']?>" />
					</div>
					<div class="form_field">
						<label for="created"><?php _e('Date Received', 'wp-hr-gdpr');?></label>
						<input type="text" name="created" value="<?php echo $form_fields['created']?>" class="date_mask" placeholder="MM/DD/YYYY" />
					</div>
					<div class="form_field">
						<label for="status"><?php _e('Status', 'wp-hr-gdpr');?></label>
						<select name="status">
							<?php
							foreach( $status as $status_value ){
								$selected = '';
								if( $status_value ==  $form_fields['status'] ){
									$selected = 'selected';	
								}
								echo sprintf( '<option %s value="%s">%s</option>', $selected, $status_value, __( $status_value, 'wp-hr-gdpr' ) );
							}
							?>
						</select>
					</div>
					<div class="form_field dpo_manager_handler">
						<label for="dpo_manager"><?php _e('Data Protection Office Handling this request', 'wp-hr-gdpr');?></label>
							<?php
							$dpo_manager = wp_dropdown_users( array( 'name' => 'dpo_manager', 'role' => $gdrppro_dpo->wphr_gdpr_get_dpo_role(), 'show_option_none' => __('Select DPO', 'wp-hr-gdpr'), 'echo' => 0, 'selected' => $form_fields['dpo_manager'] ) );
							if( $dpo_manager ){
								echo $dpo_manager;
							}else{
								echo '<select><option>'. __('No DPO Found', 'wp-hr-gdpr') .'</option></select>';
							}
							?>
					</div>
					<div class="form_field appointed_date_handler">
						<label for="appointed_date"><?php _e('Appointed Date', 'wp-hr-gdpr');?></label>
						<input type="text" name="appointed_date" value="<?php echo $form_fields['appointed_date']?>"  class="date_mask" placeholder="MM/DD/YYYY"/>
					</div>
					<div class="form_field">
						<label for="appointed_date"><?php _e('Link to SAR Form', 'wp-hr-gdpr');?></label>
						<?php
						if( $visible_request_form ){
						?>
						<a class="view_details" href="#"><?php _e( 'View Subject Access Request', 'wp-hr-gdpr' ); ?></a>
						<?php
						}else{
							echo sprintf('<a href="#">%s</a>', __('Not available','wp-hr-gdpr') );
						}
						?>
					</div>
					<div class="form_field expiry_date_handler">
						<label for="expiry_date"><?php _e('Expiry Date', 'wp-hr-gdpr');?></label>
						<input type="text" name="expiry_date" value="<?php echo $form_fields['expiry_date']?>"  class="date_mask" placeholder="MM/DD/YYYY"/>
						<span class="description">(<?php _e( 'max 30 days from receipt', 'wp-hr-gdpr' ); ?>)</span>
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Contact Details', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="address"><?php _e('Address', 'wp-hr-gdpr');?></label>
						<input type="text" name="address" value="<?php echo $form_fields['address']?>" />
					</div>
					<div class="form_field full_width">
						<label for="telephone"><?php _e('Phone', 'wp-hr-gdpr');?></label>
						<input type="text" name="telephone" value="<?php echo $form_fields['telephone']?>" />
					</div>
					<div class="form_field full_width">
						<label for="email"><?php _e('Email', 'wp-hr-gdpr');?></label>
						<input type="text" name="email" value="<?php echo $form_fields['email']?>" />
					</div>
					<div class="form_field">
						<label for="idenitity_proof_1"><?php _e('ID 1', 'wp-hr-gdpr');?></label>
						<input type="text" name="idenitity_proof_1" value="<?php echo $form_fields['idenitity_proof_1']?>" />
					</div>
					<div class="form_field">
						<label for="idenitity_proof_1_verified"><?php _e('ID Verified', 'wp-hr-gdpr');?>
							<input type="checkbox" id="idenitity_proof_1_verified" name="idenitity_proof_1_verified" <?php echo ( $form_fields['idenitity_proof_1_verified'] ? 'checked' : '' )?> value="1" />
						</label>
					</div>
					<div class="form_field">
						<label for="idenitity_proof_2"><?php _e('ID 2', 'wp-hr-gdpr');?></label>
						<input type="text" name="idenitity_proof_2" value="<?php echo $form_fields['idenitity_proof_2']?>" />
					</div>
					<div class="form_field">
						<label for="idenitity_proof_2_verified"><?php _e('ID Verified', 'wp-hr-gdpr');?>
							<input type="checkbox" id="idenitity_proof_2_verified" name="idenitity_proof_2_verified" value="1" <?php echo ( $form_fields['idenitity_proof_2_verified'] ? 'checked' : '' )?> />
						</label>
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Extension Needed', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="extention_reason"><?php _e('Why an extension needed?', 'wp-hr-gdpr');?></label>
						<textarea name="extention_reason"><?php echo $form_fields['extention_reason']?></textarea>
					</div>
					<div class="form_field full_width">
						<label for="reviced_expiry_date"><?php _e('Revised Expiry Date', 'wp-hr-gdpr');?></label>
						<input type="text" name="reviced_expiry_date" value="<?php echo $form_fields['reviced_expiry_date']?>" class="date_mask" placeholder="MM/DD/YYYY" />
						<span class="description">(<?php _e('Max two further months', 'wp-hr-gdpr')?>)</span>
					</div>
					<div class="form_field full_width">
						<label for="notified_date"><?php _e('Data Subject Notified on', 'wp-hr-gdpr');?></label>
						<input type="text" name="notified_date" value="<?php echo $form_fields['notified_date']?>" class="date_mask" placeholder="MM/DD/YYYY" />
						<span class="description">(<?php _e('Must be withing 30 days of initial application date', 'wp-hr-gdpr')?>)</span>
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Additional information Requested from Data Subject', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="additional_info"><?php _e('What additional information do you need from the Data Subject', 'wp-hr-gdpr');?></label>
						<textarea name="additional_info"><?php echo $form_fields['additional_info']?></textarea>
					</div>
					<div class="form_field">
						<label for="additional_info_date"><?php _e('Request Date', 'wp-hr-gdpr');?></label>
						<input type="text" name="additional_info_date" value="<?php echo $form_fields['additional_info_date']?>" class="date_mask" placeholder="MM/DD/YYYY" />
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Unfounded, Excessive or Repetitive Requests', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="unfounded_request"><?php _e('Explain why you believe the request is unfounded, excessive or repetitive ', 'wp-hr-gdpr');?></label>
						<textarea name="unfounded_request"><?php echo $form_fields['unfounded_request']?></textarea>
					</div>
					<div class="form_field full_width">
						<label for="declined_request_reason"><?php _e('You can choose to decline the request or change a reasonable fee, please explain which option you have to chosen and why:', 'wp-hr-gdpr');?></label>
						<textarea name="declined_request_reason"><?php echo $form_fields['declined_request_reason']?></textarea>
					</div>
					<div class="form_field">
						<label for="unfounded_request_date"><?php _e('Date this information was notified to the Data Subject', 'wp-hr-gdpr');?></label>
						<input type="text" name="unfounded_request_date" value="<?php echo $form_fields['unfounded_request_date']?>" class="date_mask" placeholder="MM/DD/YYYY" />
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Third Party Data', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="third_party_data_request"><?php _e('If you need to obtain third party data please record what and why and when this was requested, etc:', 'wp-hr-gdpr');?></label>
						<textarea name="third_party_data_request"><?php echo $form_fields['third_party_data_request']?></textarea>
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Completion Notes', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="requested_data_type"><?php _e('Was the information supplier to the Data Subject in any way edited, restricted or partial? If so, explain why:', 'wp-hr-gdpr');?></label>
						<textarea name="requested_data_type"><?php echo $form_fields['requested_data_type']?></textarea>
					</div>
					<div class="form_field full_width">
						<label for="requested_form_data_characteristic"><?php _e('How was the information applied to the Data Subject?', 'wp-hr-gdpr');?></label>
						<textarea name="requested_form_data_characteristic"><?php echo $form_fields['requested_form_data_characteristic']?></textarea>
					</div>
					<div class="form_field">
						<label for="requested_form_completion_date"><?php _e('When was the requested completed?', 'wp-hr-gdpr');?></label>
						<input type="text" name="requested_form_completion_date" value="<?php echo $form_fields['requested_form_completion_date']?>" class="date_mask" placeholder="MM/DD/YYYY" />
					</div>
				</div>
			</div>
			<div class="section">
				<h2><?php _e('Other Notes', 'wp-hr-gdpr'); ?></h2>
				<div class="form_set">
					<div class="form_field full_width">
						<label for="other_notes"><?php _e('Please note any other relevant information here:', 'wp-hr-gdpr');?></label>
						<textarea name="other_notes"><?php echo $form_fields['other_notes']?></textarea>
					</div>
				</div>
			</div>
			<div>
				<input type="hidden" value="<?php echo wp_create_nonce('subject_access_request_register'); ?>" name="_wpnonce">
				<input type="submit" class="button button-primary" value="<?php echo $_GET['request_no'] ? __('Update Access Request', 'wp-hr-gdpr') : __('Add New Access Request', 'wp-hr-gdpr'); ?>" />
			</div>
		</div>
	</div>
</div>
</form>
<div class="hide" id="wphr-gdpr-sar-request">
	<table cellspacing="0">
		<?php
		foreach( $sar_form_fields as $key => $field ){
			echo sprintf( '<tr><th>%s</th><td>%s</td></tr>', $field, $form_fields[ $key ] ? $form_fields[ $key ] : '-' );
		}
		?>
	</table>
</div>