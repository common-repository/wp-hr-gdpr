(function($){
$(document).ready(function () {
    /*
    $('#wphrgdpr_submit_btn').click(function (e) {

        e.preventDefault();
        var btn = $(this);
        var post_ids;
        $('.help-block').empty();
        $('.loading').show();
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: frontend_veriables.ajax_url,
            data: $('#wphrgdpr_privacy_form').serialize(),
            success: function (data) {
                $('.loading').hide();
                btn.prop('disabled', false);
                records = $.parseJSON(data);
                if (records.type == 'error')
                {
                    post_ids = records.data;
                    for (i = 0; i < post_ids.length; i++)
                    {
                        $('.post_id_' + post_ids[i]).html("");
                        $('.post_id_' + post_ids[i]).html(frontend_veriables.required_field);
                        $('.post_id_' + post_ids[i]).focus();
                        $('span.help-block.chk.post_id_' + post_ids[i]).html(frontend_veriables.required_field_chk);
                    }
                }
                if (records.type == 'redirect')
                {
                     $('.loading').show();
                     $('.loading').html("");
                     $('.loading').html(frontend_veriables.policy_submit+ ' ' +'<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                    window.location = records.data;
                }
            }
        });
    });
    $(".wphrgdpr_single_privacy input").click(function () {
        $(this).closest('.help-block').html("");
    });
    $(".wphrgdpr_single_privacy_selection > textarea").change(function () {

        $(this).closest('.help-block').html("");
    });
    
    
    */
    
    $('.wphrgdpr_consert_form_submit_btn').click(function (e) {

        e.preventDefault();
        
        var btn = $(this);
        $('.help-block').empty();
        $('.loading').show();
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: frontend_veriables.ajax_url,
            data: $('#wphrgdpr_consent_form').serialize(),
            success: function (data) {
                $('.loading').hide();
                btn.prop('disabled', false);
                records = $.parseJSON(data);
                if (records.type == 'error' && records.reset_form==false)
                {
                    error_classes = records.data;
                    for (i = 0; i < error_classes.length; i++)
                    {
                        if(error_classes[i]=='consent_chk_policy')
                        {
                            $('.' + error_classes[i]).html("");
                            $('.' + error_classes[i]).html(frontend_veriables.required_field_chk_one);
                            $('.' + error_classes[i]).focus();
                        }
                        if (error_classes[i] == 'consent_email')
                        {
                            $('.' + error_classes[i]).html("");
                            $('.' + error_classes[i]).html(frontend_veriables.email_err_msg);
                        }
                        if (error_classes[i] == 'consent_user_name')
                        {
                            $('.' + error_classes[i]).html("");
                            $('.' + error_classes[i]).html(frontend_veriables.name_err_msg);
                        }
                    }
                }
                if (records.type == 'redirect')
                {
                     $('.loading').show();
                     $('.loading').html("");
                     $('.loading').css('color','#00ff00');
					 $('#wphrgdpr_consent_form .message').removeClass('hide');
					 $('#wphrgdpr_consent_form')[0].reset();
		 			 var body = $("html, body");
					 var offset = $('#wphrgdpr_consent_form .message').offset().top - 200
		 			 body.stop().animate({scrollTop:offset}, 500, 'swing');
                     //$('.loading').html(frontend_veriables.consent_submit+ ' ' +'<i style="color:#000" class="fa fa-spinner fa-spin fa-pulse fa-fw"></i>');
                     
                    //window.location = records.data;
                }
                if (records.type == 'error' && records.reset_form==true)
                {
                    
                     $('.loading').show();
                     $('.loading').html("");
                     $('.loading').css('color','#ff0000');
                     $('.loading').html(records.data);
					 $('.wphrgdpr_consert_form_submit_btn').attr('disabled', 'disabled');
                }

            }
        });
    })
    $(".wphrgdpr_consent_main_div input").change(function () {
        $(this).next().empty();
    });
    $(".wphrgdpr_consent_main_div .wphrgdpr_consent_chk").click(function () {
        //$(this).next().empty();
    });
	
	$('#frm_subject_reuqest  .date_mask').datepicker({
                dateFormat: 'mm/dd/yy',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+5',
            });
	$('#frm_subject_reuqest').submit(function(e){
		jQuery(this).find('div.message').slideUp();
		jQuery(this).find('input, textarea').removeClass('required');
	   	jQuery(this).find('input, textarea').each(function(){
			if( jQuery(this).val() == '' ){
				jQuery(this).addClass('required');
			}
		});
		if( $('#frm_subject_reuqest .required').length ){
			var body = $("html, body");
			var offset = $('#frm_subject_reuqest .required').first().offset().top - 200
			body.stop().animate({scrollTop:offset}, 500, 'swing');
	  	  return false;
		}
		return true;
	});
	$('#frm_subject_reuqest input, #frm_subject_reuqest textarea').focusout(function(e){
		if( !jQuery(this).hasClass('hasDatepicker') && jQuery(this).val() == '' ){
			jQuery(this).addClass('required');
		}	
	});
	$('#frm_subject_reuqest input, #frm_subject_reuqest textarea').focusin(function(e){
		jQuery('#frm_subject_reuqest .message').slideUp();
		jQuery(this).removeClass('required');
	});
	$('.consent-aggrement-panel a.link_consent_form').click(function(e){
		e.preventDefault();
        $.wphrPopup({
            title: wpHr.popup.view_consent_aggrement,
            id: 'wphr-gdpr-consent-modal',
            onReady: function() {
                var modal = this;
				html = $('#wphr-gdpr-request-aggrement-content').html();
				$('footer', modal).hide();
				$( '.content', modal ).html( html );
            },
            onSubmit: function(modal) {
				modal.closeModal();
            }
        });
	});
});	
})(jQuery);