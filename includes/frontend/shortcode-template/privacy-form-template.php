<?php
$page_title=isset($atts['title'])?$atts['title']:"";
$args = array('post_type' => 'wp_hr_privacy_policy', 'posts_per_page' => -1,
    'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'menu_order');
$posts = get_posts($args);
$privacy_answer = get_user_meta(get_current_user_id(), 'privacy_answer', true);
if ($posts) {
    ?>
    <div class="wphrgdpr_privacy_main_div">
       
            <h4 class="wphrgdpr_gdrp_title"><?php echo $page_title ?></h4>
            <?php
            foreach ($posts as $post) {
                $old_answer = get_post_meta($post->ID, 'model_answers', true);
                $old_answer_chk = get_post_meta($post->ID, 'model_answers_chk', true);
                $status_chk = get_post_meta($post->ID, 'model_answer_type', true);
                $status = get_post_meta($post->ID, 'field_required', true);
                $reqired_status = $content = "";
                 $value_checked= get_post_meta($post->ID,'value_checked',true)?get_post_meta($post->ID,'value_checked',true):array();
                 if(is_serialized($value_checked))
                    {
                        $value_checked=unserialize($value_checked);
                    }
                    else
                    {
                        $value_checked=$value_checked;
                    }
                    if(is_serialized($old_answer_chk))
                    {
                        $old_answer_chk1= unserialize($old_answer_chk);
                        if(is_serialized($old_answer_chk1))
                            $old_answer_chk=unserialize($old_answer_chk1);
                        else
                            $old_answer_chk=$old_answer_chk1;    
                    }
                    else
                        $old_answer_chk=$old_answer_chk;
                if ($status == 'required') {
                    $reqired_status = '<span class="wphrgdpr-required-class">*</span>';
                }
                $model_answers_text = get_post_meta($post->ID, 'model_answers_text', true);
                ?>
                <div class="wphrgdpr_single_privacy">
                    <div class="wphrgdpr_single_privacy_title">
                        <label><strong><?php echo esc_html($post->post_title) ?></strong></label>
                    </div>

                   <!-- <div class="wphrgdpr_single_privacy_desc">
                        <div class="wphrgdpr_desc">
                            <small>
                                
                            <?php /*$desc= str_replace('\n', '<br>', trim($post->post_content));
                                $desc= nl2br($desc);
                                echo $desc;*/
                            ?>
                            </small>
                        </div>
                    </div>
-->
                    <div class="wphrgdpr_single_privacy_selection">
                        <?php
                        if ($old_answer != "" && $status_chk == 'editor') {
                            if (isset($privacy_answer[$post->ID])) {
                                $content = $privacy_answer[$post->ID];
                            }
                            ?>
                        <label class="wphrgdpr_privacy_answer"><?php echo nl2br($old_answer) ?></label>
                            <span class="help-block  post_id_<?php echo $post->ID ?>"></span>
                            <?php
                        } elseif (!empty($old_answer_chk) && $status_chk == 'checkbox') {

                            echo '<label class="wphrgdpr_model_answers_text">' . esc_html($model_answers_text) . '</label>';
                            
                            if (is_array($old_answer_chk)) {
                                foreach ($old_answer_chk as $key => $answers) {
                                    
                                    if(key_exists($key, $value_checked))
                                    {
                                        
                                        echo '</span><label class="wphrgdpr_placeholder_answer"><span class="wphrgdpr_placeholder_answer_true">'. esc_html($answers) . '</label>';
                                    }
                                }
                            }
                            echo '<span class="help-block chk  post_id_' . esc_html($post->ID) . '"></span>';                        }
                        ?>
                    </div>
                </div><br>
                <?php
            }
        ?>
                <!-- 
        <input type="hidden" name="action" value="privacy_form_shortcode_save"  />
        <input type="hidden" value="<?php// echo get_the_permalink() ?>" name="redirect_url" >
       <input type="submit" class="wphrgdpr_button btn btn-default" id="wphrgdpr_submit_btn" name="wphrgdpr_submit_btn" value="<?php //_e("Submit", "wphrgdpr") ?>">
          <label class="loading" style="display: none"><?php// _e("Please wait","wphrgdpr") ?>&nbsp;<i class="fa fa-spinner fa-pulse fa-fw"></i></label>-->
    </div>
<?php
}
?>
