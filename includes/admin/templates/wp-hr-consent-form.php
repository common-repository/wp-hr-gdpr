<?php
	$form_contents = get_option("wphrgdpr_consent_contents", true);
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e("Consent Form", "wphrgdpr"); ?></h1>
    <hr class="wp-header-end">
    <div id="poststuff">

        <form method="post">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content" style="position: relative;">
<div id="postbox-container-1" class="postbox-container big-container">
                <div class="meta-box-sortables ui-sortable wphrgdpr_consent_form_div">
                    <div  class="postbox wphrgdpr_consent_form_div_postbox">
                        <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Consent Form Title", "wphrgdpr") ?></h2>
                        <div class="inside wphrgdpr_consent_form_div_inside" id="titlediv">
							<input type="text" name="consent_form_title" value="<?php echo $form_contents['consent_form_title']; ?>" id="title" />
						</div>
					</div>
                    <div  class="postbox wphrgdpr_consent_form_div_postbox">
                        <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Content Before Consent Form", "wphrgdpr") ?></h2>
                        <div class="inside wphrgdpr_consent_form_div_inside">
							<?php
							wp_editor( $form_contents['content_before_consent_form'], 'content_before_consent_form', array('teeny' => true, 'media_buttons' => false, 'textarea_rows' => 5));
							?>
						</div>
					</div>
                    <div  class="postbox wphrgdpr_consent_form_div_postbox">
                        <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Content After Consent Form", "wphrgdpr") ?></h2>
                        <div class="inside wphrgdpr_consent_form_div_inside">
							<?php
							wp_editor( $form_contents['content_after_consent_form'], 'content_after_consent_form', array('teeny' => true, 'media_buttons' => false, 'textarea_rows' => 5));
							?>
						</div>
					</div>
                    <div  class="postbox wphrgdpr_consent_form_div_postbox">
                        <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Consent Form Final Confirmation Statement / Agreement", "wphrgdpr") ?></h2>
                        <div class="inside wphrgdpr_consent_form_div_inside">
							<?php
							wp_editor( $form_contents['consent_form_privacy_text'], 'consent_form_privacy_text', array('teeny' => true, 'media_buttons' => false, 'textarea_rows' => 5));
							?>
						</div>
					</div>
                    <div  class="postbox wphrgdpr_consent_form_div_postbox">
                        <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Consent Form Fields", "wphrgdpr") ?></h2>
                        <div class="inside wphrgdpr_consent_form_div_inside">
                                <div class="inside wphrgdpr_consent_form_div_add">
                                    <?php
                                    $textbox_values = get_option("wphrgdpr_consent_quetions");
                                    if (!empty($textbox_values)) {
                                        foreach ($textbox_values as $key => $single_val) {
                                            ?>
                                            <div class="row wphrgdpr_consent_form_div_inside_txt">
                                                <textarea rows="6" class="wphrgdpr_consent_form_txtarea" name="consent_form_textarea[<?php echo $key ?>]"><?php echo $single_val ?></textarea>
                                                <?php
                                                if ($key > 0) {
                                                    echo '<label class="wphrgdpr_close_consent">X</label>';
                                                }
                                                ?>

                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="row wphrgdpr_consent_form_div_inside_txt">
                                            <textarea rows="6" class="wphrgdpr_consent_form_txtarea" name="consent_form_textarea[]"></textarea>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="add_new_consent_form">
                                    <input type="hidden" value="<?php echo wp_create_nonce( 'wphrgdpr_consent_form_submit'); ?>" name="_wpnonce">
                                    <button type="submit" name="wphrgdpr_consent_form_save_btn" class="button button-primary"><?php _e("Save", "wphrgdpr") ?></button>
                                    &nbsp;&nbsp;
                                    <button type="button" class="button button-primary add_new_textarea"><?php _e("Add New", "wphrgdpr") ?></button>
                                </div>
                        </div>
                    </div>
  
                </div>
</div>
            </div>
        </div>
		</form>
    </div>
</div>

