<div class="data-protection-tab-wrap">
	<!-- Privacy Notice -->
		<div class="postbox leads-actions">
		    <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
		    <h3 class="hndle"><span><?php _e( 'Privacy Notice (Data Protection Policy)', 'wp-hr-gdpr' ); ?></span></h3>
		    <div class="inside">
				<p><?php _e('You can read our Privacy notice on this page.', 'wp-hr-gdpr')?> <a target="_blank" href="<?php echo $privacy_notice; ?>"><i class="fa fa-external-link-square"></i></a></p>
		    </div>
		</div>
	<!-- Concent Form -->
		<div class="postbox leads-actions consent-aggrement-panel">
		    <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
		    <h3 class="hndle"><span><?php _e( 'Your Consent', 'wp-hr-gdpr' ); ?></span></h3>
		    <div class="inside">
				<?php if( !$consent_data ){ ?>
				<p><?php _e('You can complete the consent form to allow us to process your data here.', 'wp-hr-gdpr'); ?> <a target="_blank" href="<?php echo $consent_form; ?>"><i class="fa fa-external-link-square"></i></a></p>
				<?php }else{ ?>
				<p><?php echo sprintf( __('Your consented to us processing your data on %s', 'wp-hr-gdpr'), $consent_date ); ?></p>
				<p><?php _e('You can view the consent agreement here.', 'wp-hr-gdpr' ); ?>  <a class="link_consent_form" href="#"><i class="fa fa-external-link-square"></i></a></p>
				<p><?php _e('You may withdraw your consent at any time. Please notify our Data Protection Officer.', 'wp-hr-gdpr'); ?></p>
				<?php } ?>
		    </div>
		</div>
	<!-- Data Protection Training -->
		<div class="postbox leads-actions">
		    <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
		    <h3 class="hndle"><span><?php _e( 'Data Protection Training', 'wp-hr-gdpr' ); ?></span></h3>
		    <div class="inside">
				<?php
				if( count( $training_record ) ){
					echo '<p>';
					_e('Our record indicate that you received the following data protection training:', 'wp-hr-gdpr');
					echo '</p>';
					echo '<table class="wp-list-table widefat fixed striped ">';
					echo sprintf( '<thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead>', __('Course Date', 'wp-hr-gdpr'), __('Course Name', 'wp-hr-gdpr'), __('Notes', 'wp-hr-gdpr') );
					echo '<tbody>';
					foreach( $training_record as $row ){
						$date = date( 'Y-m-d', strtotime( $row->date ) );
						echo sprintf( '<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $date, $row->course_name, $row->note ? $row->note : '-'  );	
					}
					echo '</tbody>';
					echo '</table>';
				}else{
					echo '<p>';
					_e('No records found!', 'wp-hr-gdpr');
					echo '</p>';
				}
				?>
		    </div>
		</div>
	<!-- Data Protection Training -->
		<div class="postbox leads-actions">
		    <div class="handlediv" title="<?php _e( 'Click to toggle', 'wphr' ); ?>"><br></div>
		    <h3 class="hndle"><span><?php _e( 'Subject Access Request', 'wpwphrgdprhr' ); ?></span></h3>
		    <div class="inside">
				<?php
				if( count( $subject_access_record ) ){
					echo '<p>';
					_e('You have submitted the following Subject Access Requests.', 'wp-hr-gdpr');
					echo '</p>';
					echo '<table class="wp-list-table widefat fixed striped ">';
					echo sprintf( '<thead><tr><th>%s</th><th>%s</th></tr></thead>', __('Date of Submission', 'wp-hr-gdpr'), __('Date of Receipt', 'wp-hr-gdpr') );
					echo '<tbody>';
					foreach( $subject_access_record as $row ){
						$date = date( 'Y-m-d', strtotime( $row->created ) );
						$requested_form_completion_date = strtotime( $row->requested_form_completion_date );
						if( $requested_form_completion_date > 0 ){
							$requested_form_completion_date = date( 'Y-m-d', $requested_form_completion_date );	
						}else{
							$requested_form_completion_date = '-';
						}
						echo sprintf( '<tr><td>%s</td><td>%s</td></tr>', $date, $requested_form_completion_date );	
					}
					echo '</tbody>';
					echo '</table>';
				}else{
					echo '<p>';
					_e('You are entitled to request to see the data help on you by us. Please us the Subject Access Request from here to make this request.', 'wp-hr-gdpr');
					echo sprintf( ' <a target="_blank" href="%s"><i class="fa fa-external-link-square"></i></a>', $subject_access_summary);
					echo '</p>';
				}
				?>
		    </div>
		</div>
</div>

<div class="hide" id="wphr-gdpr-request-aggrement-content">
	<table cellspacing="0" align="top">
		<?php
		foreach( $consent_aggrement as $key => $field ){
			echo sprintf( '<tr><td valign="top"><input disabled type="checkbox" %s /></td><td>%s</td></tr>', $field ? 'checked' : '', $key );
		}
		?>
	</table>
</div>