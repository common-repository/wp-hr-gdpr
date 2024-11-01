<?php
global $job_app_consent_form;
$form_contents = get_option("wphrgdpr_consent_contents", true);
$page_title=isset($atts['title'])?$atts['title']: $form_contents['consent_form_title'];
 $page_ids= get_option("wphrgdpr_page_ids");
?>
<div class="wphrgdpr_consent_main_div">
<?php if( !$job_app_consent_form ){ ?>
    <form method="post" id="wphrgdpr_consent_form">
		<div class="message success hide">
			<p><?php _e('Thank you for accepting data privacy policy.', 'wp-hr-gdpr'); ?></p>
		</div>
        <h4 class="wphrgdpr_consent_main_title"><strong><?php echo $page_title; ?></strong></h4>
<?php } ?>
        <?php
		echo wpautop($form_contents['content_before_consent_form']);
        $textbox_values = get_option("wphrgdpr_consent_quetions");
        if (!empty($textbox_values)) {
            foreach ($textbox_values as $key => $single_val) {
                ?>
                <div class="wphrgdpr_consent_inner_div">
                    <label class="wphrgdpr_consent_chk"><input type="checkbox" class="wphrgdpr_chkbox " name="consent_chk_<?php echo $key ?>">
                        <?php
                        echo esc_html($single_val);
                        ?>
                    </label>
                    <span class="help-block consent_chk_<?php echo $key ?>"></span>
                </div>
                <?php
            }
        }
        ?>

        <div class="wphrgdpr_consert_form_tbl col-md-12">
            <div class="row">
                <label class="wphrgdpr_consent_info">
                    <?php
                    	echo wpautop($form_contents['content_after_consent_form']);
                    ?>
                </label>
            </div>
            <div class="row">
                <label class="wphrgdpr_consent_chk">
                    <input type="checkbox" class="accept_privacy" required name="consent_chk_policy">&nbsp;
                    <?php
						echo $form_contents['consent_form_privacy_text'];
                    ?>
                </label>
                <span class="help-block consent_chk_policy"></span>
            </div>
<?php if( !$job_app_consent_form ){ ?>
            <div class="row">
                <div class="wphrgdpr_title">
                    <label class="wphrgdpr_consert_form_lbl"><?php _e("Name", "wphrgdpr") ?></label>
                </div>
                <div class="wphrgdpr_input_box">
                    <input type="text" name="consent_user_name" value="<?php echo $user_name ?>"  class="wphrgdpr_consert_form_name_lbl">
                    <span class="help-block consent_user_name"></span>
                </div>
            </div>
            <div class="row">
                <div class="wphrgdpr_title">
                    <label class="wphrgdpr_consert_form_lbl"><?php _e("Email", "wphrgdpr") ?></label>
                </div>
                <div class="wphrgdpr_input_box">
                    <input type="email" name="consent_email" class="wphrgdpr_consert_form_email_lbl">
                    <span class="help-block consent_email"></span>
                </div>
            </div>
            <div class="row">

                <div class="wphrgdpr_input__btn">
                    <input type="hidden" value="<?php echo get_the_permalink(); ?>" name="conset_page_url">
                    <input type="hidden" value="<?php echo wp_create_nonce( 'wphrgdpr_consent_page'); ?>" name="_wpnonce">
                    <input type="hidden" value="wphrgdpr_consent_form_save_front" name="action">
                    <input type="submit" name="consent_submit_btn" class="wphrgdpr_consert_form_submit_btn btn" value="<?php _e("I Agree","wphrgdpr") ?>">
                    <label class="loading" style="display: none"><?php _e("Please wait", "wphrgdpr") ?>&nbsp;<i class="fa fa-spinner fa-spin fa-pulse fa-fw"></i></label>

                </div>
            </div>
            
<?php } ?>            
        </div>
<?php if( !$job_app_consent_form ){ ?>		
    </form>
<?php } ?>            	
</div>