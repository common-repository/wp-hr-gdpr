$ = jQuery;
$(document).ready(function () {
	$('#frm_access_request_register .date_mask').datepicker({
                dateFormat: 'mm/dd/yy',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+5',
            });
	
    if (parseInt($('input[name=wphrgdpr_selection]:checked').val()) === 1)
    {
        $(".wphrgdpr_editor_selection").show();
        $(".wphrgdpr_chk_selection").hide();
    } else if (parseInt($('input[name=wphrgdpr_selection]:checked').val()) === 2)
    {
        $(".wphrgdpr_editor_selection").hide();
        $(".wphrgdpr_chk_selection").show();
    }

    $('.wphrgdpr_editor_chk').click(function () {
        var select_value;
        if ($(this).prop("checked") == true)
        {
            select_value = $(this).val();
            if (select_value == 1)
            {
                $(".wphrgdpr_editor_selection").show();
                $(".wphrgdpr_chk_selection").hide();
            } else if (select_value == 2)
            {
                $(".wphrgdpr_editor_selection").hide();
                $(".wphrgdpr_chk_selection").show();
            }
        }
    });
    $('#addNewField').click(function () {

        $("#wphrgdpr_txtboxs").append('<div class="wphrgdpr_txtboxs_inner"><div class="wphrgdpr_txtboxs_inner_textbox"><label><input type="checkbox" name="value_checked" value="1"></label><input type="text" class="chk_textbox input-text" name="chk_model_answer[]" placeholder="' + admin_veriables.checkbox_msg + '">&nbsp;<label class="wphrgdpr_close">X</label></div></div>');
        $(this).closest(".chk_textbox").focus();
        subSessionCnt = 0;
        $('input[name^="value_checked"]').each(function () {
            $(this).attr('name', 'value_checked[' + (subSessionCnt) + ']');
            subSessionCnt++
        });

    });
    $("body").on("click", '.wphrgdpr_close', function () {
        $(this).parent().remove();
        subSessionCnt = 0;
        $('input[name^="value_checked"]').each(function () {
            $(this).attr('name', 'value_checked[' + (subSessionCnt) + ']');
            subSessionCnt++
        });
    });
    $('.add_new_textarea').click(function () {

        $(".wphrgdpr_consent_form_div_add").append('<div class="wphrgdpr_consent_form_div_inside_txt"><textarea rows="6" class="wphrgdpr_consent_form_txtarea" name="consent_form_textarea"></textarea><label class="wphrgdpr_close_consent">X</label></div>');
        $(this).closest(".wphrgdpr_consent_form_txtarea").focus();
        subSessionCnt = 0;
        $('textarea[name^="consent_form_textarea"]').each(function () {
            $(this).attr('name', 'consent_form_textarea[' + (subSessionCnt) + ']');
            subSessionCnt++
        });

    });
    $("body").on("click", '.wphrgdpr_close_consent', function () {
        $(this).parent().remove();
        subSessionCnt = 0;
        $('textarea[name^="consent_form_textarea"]').each(function () {
            $(this).attr('name', 'consent_form_textarea[' + (subSessionCnt) + ']');
            subSessionCnt++
        });
    });
});
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}
jQuery(function () {

   if(jQuery("#the-list").length)
   {
     
       jQuery(".post-type-wp_hr_privacy_policy #the-list").sortable({
           
            stop: function(event, ui) {
                  var post_ids=[];
                    var i=1;
                jQuery('input[name^="post"]').each(function(item,index){
                    value=jQuery(this).attr('value');
                    if(jQuery.isNumeric(value))
                    {
                        post_ids[i++]=value;
                    }
                        
                        
                });
                var data_value={"action":"wphrgdpr_post_sortable_handle","post_ids":post_ids}
               $.ajax({
                    type: 'POST',
                    url: admin_veriables.ajax_url,
                    data:data_value,
                    success: function (data) {
                    }
                });
            }
            
        });
      
        $( "#the-list" ).disableSelection();
   }
   
       
    
    
    jQuery(".button-link").click(function (e) {

        if (jQuery(this).parent().hasClass("closed"))
        {
            jQuery(this).parent().removeClass("closed");
            jQuery(this).attr("aria-expanded", "true");
        } else
        {
            jQuery(this).parent().addClass("closed");
            jQuery(this).attr("aria-expanded", "false");
        }
    });
	
	
	$('.consent-aggrement-panel a.link_consent_form, .view_consent_form_link').click(function(e){
		e.preventDefault();
		var consent_data = '';
		if( $('.view_consent_form_link').length ){
			consent_data = $(this).data('data')
		}
        $.wphrPopup({
            title: wpHr.popup.view_consent_aggrement,
            id: 'wphr-gdpr-consent-modal',
            onReady: function() {
                var modal = this;
				var html = '';
				if( $('#wphr-gdpr-request-aggrement-content').length ){
					html = $('#wphr-gdpr-request-aggrement-content').html();	
				}else{
					html = '<table cellspacing="0" align="top">';
					data = consent_data;
					$.each( data, function(i, item) {
						var checked = ( data[ i ] ? 'checked' : '' );
						html += '<tr><td valign="top"><input disabled type="checkbox" '+ checked +' /></td><td>'+ i +'</td></tr>';
					});
					html += '</table>';
				}
				$('footer', modal).hide();
				$( '.content', modal ).html( html );
            },
            onSubmit: function(modal) {
				modal.closeModal();
            }
        });
	});
	
	$('#frm_access_request_register .view_details').click(function(e){
		e.preventDefault();
        $.wphrPopup({
            title: wpHr.popup.view_sar_request,
            id: 'wphr-gdpr-sar-modal',
            onReady: function() {
                var modal = this;
				html = $('#wphr-gdpr-sar-request').html();
				$('footer', modal).hide();
				$( '.content', modal ).html( html );
            },
            onSubmit: function(modal) {
				modal.closeModal();
            }
        });
	});
	
	/**
	* Add new training record
	*/
	$('.new_training_record_link').click(function(e){
		e.preventDefault();
        $.wphrPopup({
            title: wpHr.popup.trainig_record,
            id: 'wphr-gdpr-training-add',
            content: wphr.template('wphr-gdpr-training-add')({data:null}).trim(),
            extraClass: 'smaller',
            onReady: function() {
				$('select[id="user_id"] option').first().val('');
				//$('.wphr-date-field').removeAttr('id');
				$('.wphr-date-field').datepicker({
					dateFormat: 'mm/dd/yy',
					changeMonth: true,
					changeYear: true,
                	yearRange: '-100:+5',
				});
            },
            onSubmit: function(modal) {
				//$('.wphr-date-field').attr('id', $('.wphr-date-field').attr('name'));
                wp.ajax.send( {
                    data: this.serialize(),
                    success: function(res) {
                        modal.closeModal();
						window.location.reload();
                    },
                    error: function(error) {
                        modal.showError( error );
                    }
                });
            }
        }); //popup	
	});
	
	/**
	* Confirm for delete training record
	*/
	$('.dataprotectiontraining .delete a').click(function( e ){
		if( confirm( wpHr.confirm ) ){
			return true;	
		}
		return false;	
	});
	
	/**
	* Edit training record
	*/
	$('.dataprotectiontraining .edit a').click(function( e ){
		e.preventDefault();
		var self = $(this);
		console.log( self.data('data') );
        $.wphrPopup({
            title: wpHr.popup.trainig_record,
            id: 'wphr-gdpr-training-add',
            content: wphr.template('wphr-gdpr-training-add')(self.data('data')).trim(),
            extraClass: 'smaller',
            onReady: function() {
				$('select[id="user_id"]').val( self.data('data').user_id );
				$('.wphr-date-field').datepicker({
					dateFormat: 'mm/dd/yy',
					changeMonth: true,
					changeYear: true,
				                	yearRange: '-100:+5',
				});
            },
            onSubmit: function(modal) {
                wp.ajax.send( {
                    data: this.serialize(),
                    success: function(res) {
                        modal.closeModal();
						window.location.reload();
                    },
                    error: function(error) {
                        modal.showError( error );
                    }
                });
            }
        });
	});
});