
<div class="info-form-wrap">
    <div class="row" data-selected="{{ data.user_id }}">
		<?php wphr_html_form_input( array(
            'label'    => __( 'Employee', 'wp-hr-gdpr' ),
            'name'     => 'user_id',
			'value'		=> '{{ data.user_id }}',
            'type'     => 'select',
            'required' => true,
            'options'  => wphr_hr_get_employees_dropdown_raw()
        ) ); ?>	
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Course name', 'wp-hr-gdpr' ),
            'name'     => 'course_name',
            'value'    => '{{ data.course_name }}',
            'required' => true,
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Date', 'wp-hr-gdpr' ),
            'name'     => 'training_date',
            'value'    	  => '{{ data.training_date }}',
            'required' => true,
            'class'    => 'wphr-date-field',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Note', 'wp-hr-gdpr' ),
            'name'        => 'note',
            'value'    	  => '{{ data.note }}',
            'placeholder' => __( 'Optional comment', 'wphr' ),
            'type'        => 'textarea',
            'custom_attr' => array( 'rows' => 4, 'cols' => 25 )
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr_training' ); ?>
    <input type="hidden" name="id" value="{{ data.id }}">
    <input type="hidden" name="action" value="wphr-hr-manage-training">
</div>
