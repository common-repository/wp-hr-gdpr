<table style="padding:10px 0 0 0">
    <?php
    $status_chk = get_post_meta(get_the_ID(), 'model_answer_type', true);
    ?>
    <td>
        <input type="radio" name="wphrgdpr_selection" class="wphrgdpr_editor_chk" value="1" id="wphrgdpr_selection_editor" <?php if ($status_chk == 'editor' || $status_chk == "") echo "checked"; ?> >
        <label for="wphrgdpr_selection_editor">
            <?php _e("Editor", "wphrgdpr"); ?>
        </label>
    </td>
    <td>
        <input type="radio" name="wphrgdpr_selection" class="wphrgdpr_editor_chk" value="2" id="wphrgdpr_seletion_chk" <?php if ($status_chk == 'checkbox') echo "checked"; ?> >
        <label for="wphrgdpr_selection_chk">
            <?php _e("Checklist", "wphrgdpr"); ?>
        </label>
    </td>
</table>
<div class="wphrgdpr_editor_selection" style="display: none">
    <?php
    $old_answer_defalt = get_post_meta(get_the_ID(), 'model_default_answers', true);
    $old_answer_defalt_new_line=nl2br($old_answer_defalt);
    if ($old_answer_defalt != '') {
        echo '<lable  class="wphrgdpr_default_answer_title"><strong>' . __("Default Suggested Text", "") . '</strong></lable>';
        echo "<lable class='wphrgdpr_default_answer'>" . esc_html($old_answer_defalt_new_line) . "</lable>";
    }
	echo sprintf( '<div class="wphrgdpr_heading">%s</div>', __('Your Policy:', 'wp-hr-gdpr') );
    ?>
    <?php
    $old_answer = get_post_meta(get_the_ID(), 'model_answers', true);
    $editor_id = 'single_model_answer';
    $settings = array(
        'media_buttons' => false,
        'quicktags' => array('buttons' => 'strong,em,del,ul,ol,li,close'),
        'editor_height' => 250, // In pixels, takes precedence and has no default value
        'textarea_rows' => 10, // Has no visible effect if editor_height is set, default is 20
    );
    if ($old_answer != "") {
        wp_editor($old_answer, $editor_id, $settings);
    } else {
        wp_editor('', $editor_id, $settings);
    }
    ?>
</div>
<div class="wphrgdpr_chk_selection" style="display: none">
    <?php
    $old_answer_chk_default = get_post_meta(get_the_ID(), 'model_answers_chk', true);
    if (is_serialized($old_answer_chk_default)) {
        $old_answer_chk_default1 = unserialize($old_answer_chk_default);
        if (is_serialized($old_answer_chk_default1))
            $old_answer_chk_default = unserialize($old_answer_chk_default1);
        else
            $old_answer_chk_default = $old_answer_chk_default1;
    } else
        $old_answer_chk_default = $old_answer_chk_default;
    if ($old_answer_chk_default)
        $old_answer_chk_default = array_filter($old_answer_chk_default);
    if (!empty($old_answer_chk_default) && is_array($old_answer_chk_default)) {
        echo '<lable  class="wphrgdpr_default_answer_title"><strong>' . __("Default Checklist", "") . '</strong></lable><div class="wphrgdpr_txtboxs_inner_textbox_default"><ul>';
        foreach ($old_answer_chk_default as $key => $answer) {
            ?>
            <li ><?php echo esc_html($answer) ?></li>
            <?php
        }
        echo "</ul></div>";
    }
    ?>
    <div class="wphrgdpr_suggestion_text">
        <?php
        $model_answers_text = get_post_meta(get_the_ID(), 'model_answers_text', true);
        ?>
      <!--  <label class="wphrgdpr_txtboxs_inner_textbox_title"><strong><?php // _e("Suggested Text / Place Holder", "wphrgdpr")            ?></strong></label><br><br>
        -->
    </div>
    <div id="wphrgdpr_txtboxs">
        <?php
        $old_answer_chk = get_post_meta(get_the_ID(), 'model_answers_chk', true);
        $value_checked = get_post_meta(get_the_ID(), 'value_checked', true);
        if (is_serialized($value_checked)) {
            $value_checked = unserialize($value_checked);
        } else {
            $value_checked = $value_checked;
        }
        if (is_serialized($old_answer_chk)) {
            $old_answer_chk1 = unserialize($old_answer_chk);
            if (is_serialized($old_answer_chk1))
                $old_answer_chk = unserialize($old_answer_chk1);
            else
                $old_answer_chk = $old_answer_chk1;
        } else
            $old_answer_chk = $old_answer_chk;
        if ($old_answer_chk)
            $old_answer_chk = array_filter($old_answer_chk);
        if (!empty($old_answer_chk) && is_array($old_answer_chk)) {
            foreach ($old_answer_chk as $key => $answer) {
                ?>
                <div class="wphrgdpr_txtboxs_inner">
                    <div class="wphrgdpr_txtboxs_inner_textbox">
                        <label><input type="checkbox" name="value_checked[<?php echo $key ?>]" value="1" <?php if (isset($value_checked[$key]) && $value_checked[$key] == 1) echo "checked"; ?> ></label><input type="text" placeholder="<?php _e("Checkbox Options", "wphrgdpr"); ?>" class="chk_textbox input-text" name="chk_model_answer[]" value="<?php echo $answer ?>">
                        <?php
                        if ($key > 0) {
                            echo '<label class="wphrgdpr_close">X</label>';
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="wphrgdpr_txtboxs_inner">
                <div class="wphrgdpr_txtboxs_inner_textbox">
                    <label><input type="checkbox" name="value_checked" value="1"></label>&nbsp;<input type="text" placeholder="<?php _e("Checkbox Options", "wphrgdpr"); ?>" class="chk_textbox input-text" name="chk_model_answer[]">
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <input type="button" class="button button-primary" id="addNewField" value="<?php _e("Add New", "wphrgdpr") ?>">
</div>