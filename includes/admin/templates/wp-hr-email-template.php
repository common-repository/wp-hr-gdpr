<?php
$editTemplate = isset($_REQUEST['editTemplate']) ? $_REQUEST['editTemplate'] : 1;
if ($editTemplate) {
    $email_templates_data_old = get_option("wphrgdpr_email_templates_data");
    $email_templates_label_old = get_option("wphrgdpr_email_templates_label");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e("Email Template", "wphrgdpr"); ?></h1>
        <hr class="wp-header-end">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content" style="position: relative;">
                    <div id="postbox-container-1" class="postbox-container">
                        <div class="meta-box-sortables ui-sortable">
                            <div  class="postbox wphrgdpr_consent_form_div_postbox">
                                <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Email Template list", "wphrgdpr") ?></h2>
                                <div class="inside wphrgdpr_consent_form_div_inside">
                                    <table  class="wp-list-table widefat fixed " style="margin-top: 15px;">
                                        <tr>
                                            <th>
                                                <strong><?php _e('Email List', "wphrgdpr"); ?></strong>
                                            </th>
                                            <th>
                                                <strong><?php _e("Change Content", 'wp-hr-gdpr') ?></strong>
                                            </th>
                                        </tr>
                                        <?php
                                        $email_templates = get_option("wphrgdpr_email_templates_label");
                                        if ($email_templates) {
                                            foreach ($email_templates as $key => $email_subject) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $email_subject ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo admin_url('admin.php?page=wphrgdpr_email_template&editTemplate=') . $key ?>" data-id="<?php echo $key ?>"><?php _e("Edit", "wphrgdpr") ?></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-2" class="postbox-container big-container">
                        <div class="meta-box-sortables ui-sortable wphrgdpr_consent_form_div">
                            <div  class="postbox wphrgdpr_consent_form_div_postbox">
                                <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Getting Started</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle wphrgdpr_consent_form_div_title"><?php _e("Email Template", "wphrgdpr") ?></h2>
                                <div class="inside wphrgdpr_consent_form_div_inside">
                                    <form method="post">
                                        <table style="width: 100%">
                                            <tr>
                                                <td><h4><strong><?php _e("Email subject ", "wphrgdpr") ?>:</strong></h4></td>
                                                <td><input style="width: 100%;height: 40px;" type="text" value="<?php echo $email_templates_label_old[$editTemplate] ?>" name="wphrgdpr_email_title"></td>
                                            </tr>
                                            <tr>
                                                <td><h4><strong><?php _e("Email body ", "wphrgdpr") ?>:</strong></h4></td>
                                                <td>
                                                    <?php
                                                    $settings = array(
                                                        'editor_height' => 250, // In pixels, takes precedence and has no default value
                                                        'textarea_rows' => 10, // Has no visible effect if editor_height is set, default is 20
                                                    );
                                                    $editor_id = 'wphrgdpr_email_desc';
                                                    wp_editor(stripslashes($email_templates_data_old[$editTemplate]), $editor_id, $settings);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" value="<?php echo $editTemplate ?>" name="wphrgdpr_email_id">
                                                    <input type="hidden" value="<?php echo wp_create_nonce('wphrgdpr_email_template_submit'); ?>" name="_wpnonce">
                                                    <input type="submit" name="wphrgdpr_email_save_btn" class="button-primary button-large " value="<?php _e("Save", "eventmana-child") ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>    
</div>
