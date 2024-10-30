$ = jQuery.noConflict();
$(document).ready(function(){
	var file_frame = [];
	$.fn.uploadMediaFile = function( button, preview_media,multiple=false ,rt=false) {
		var button_id = button.attr('id');
		var field_id = button_id.replace( '_button', '' );
		var preview_id = button_id.replace( '_button', '_preview' );
		// If the media frame already exists, reopen it.
		if ( file_frame[button_id] ) {
		  file_frame[button_id].open();
		  return;
		}

		// Create the media frame.
		file_frame[button_id] = wp.media.frames.file_frame = wp.media({
		  title: $( this ).data( 'uploader_title'),
		  button: {
			text: $( this ).data( 'uploader_button_text' ),
		  },
		  multiple: multiple
		});

		// When an image is selected, run a callback.
		file_frame[button_id].on( 'select', function() {
			if(multiple){
				attachment = file_frame[button_id].state().get('selection').map(function(attachment){
					attachment.toJSON();
					return attachment;
				});
				var appenID = field_id+'_list';
				for (i = 0; i < attachment.length; ++i) {
					var main = attachment[i].attributes;
					$("#"+appenID).append($.fn.ppp_add(main,field_id));
				}
			}else{
				attachment = file_frame[button_id].state().get('selection').first().toJSON();
				$("#"+field_id).val(attachment.id);
				if( preview_media ) {
					$("#"+preview_id).show();
					$("#"+preview_id).attr('src',attachment.sizes.thumbnail.url);
				}
			}
			
		});

		// Finally, open the modal
		file_frame[button_id].open();
	}
	
	$.fn.ppp_add = function(value,id){
		var markup = '';
		markup += '<div class="single-image img"  id="image-remove-'+value.id+'">'; 
			markup += '<image class="preview-image" src="'+value.url+'" >';      
			markup += '<span data-image-pos-id="'+value.id+'" class="dashicons dashicons-dismiss ti-close image-remove"></span>';      
			
			markup += '<input type="hidden" name="'+id+'['+value.id+']" value="'+value.id+'" class="preview-image" >';
		markup += '</li>';
		return markup;
	}
	
	$('.image_upload_button').click(function(e) {
		e.preventDefault();
		$.fn.uploadMediaFile( $(this), true);
	});
	
	
	
	$(document).on('click','.portfolio-img',function(e){
		e.preventDefault();
		$.fn.uploadMediaFile( $(this), true);
	});
	
	$(document).on('click','.add-gallery-image',function(e){
		e.preventDefault();
		$.fn.uploadMediaFile( $(this), true,true);
	});
	
	$(document).on('click','#jy_upload_cv',function(e){
		e.preventDefault();
		jy_pdf_upload('upload_cv');
	});
	$(document).on('click','#jy_upload_cover',function(e){
		e.preventDefault();
		jy_pdf_upload('upload_cover');
	});
	
	function jy_pdf_upload(slug=''){
		var frame;
		if ( frame ) {
			frame.open();
			return;
		}
		frame = wp.media({
			title: 'Please Select Only Pdf File',
			button: {
				text: 'Select'
			},
			multiple: false 
		});
		frame.on( 'select',function(){
			var attachment =  frame.state().get('selection').first().toJSON();
			if(attachment){
				if(attachment.mime!='application/pdf'){
					alert('Only Pdf');
					return false;
				}
				var data = {
					'action': 'jy_pdf_upload',
					'id': attachment.id,
					'slug': slug,
					'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
				};
				$.post( jemployee.url, data, function( res ) { 
					console.log('success');
				});
			}
			
		});
		frame.open();
	}
	
	$(document).on('click','.image-remove',function(){
		var id = $(this).data('image-pos-id');
		$('#image-remove-'+id).remove();
	});
	
	$('.jy-select').select2();
	send_request('#jy-skill','jy_skill_list',{'post_type':'jy_skill','type':2});	
	send_request('#jy_job_category','jy_skill_list',{'post_type':'jy_category','type':2});	
	function send_request(id,action,pa){
		$(id).select2({
			ajax: {
				type: 'POST',
				url: jemployee.url,
				dataType: 'json',
				data: function (params) {
					var queryParameters = {
						search : params.term,
						action : action,
						post_type : pa.post_type,
						s_type : pa.type,
						jemployee_nonce :  jemployee.jemployee_ajax_nonce
					}
					return queryParameters;
				},
				processResults: function (data) {
					return {
						results: data
					};
				}
			},
			placeholder: "Select a Option",
			minimumInputLength: 3
		});
	}
	
	$(document).on('click','#jy_skill_save',function(e){
		e.preventDefault();
		var ids = $('#jy-skill').val();
		if(ids!=null){
			var data = {
				'action': 'jy_skill_save',
				'ids': ids,
				'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
			};
			$.post( jemployee.url, data, function( res ) { 
				if(res){
					toastr.success('Update Successfully');
					$('#jy_skill_list').html(res);
				}
			});
		}
	});
	
	$(document).on('click','#save_em_social',function(e){
		e.preventDefault();
		const social = $('form[name="social_link"]').serializeArray();
		var data = {
			'action': 'jy_social_save',
			'social': social,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ) { 
			if(res){
				toastr.success('Update Successfully');
			}
		});
	});
	
	$(document).on('click','#save_employee_info',function(e){
		e.preventDefault();
		var empnew = [];
		const employee_info = $('form[name="employee_info"]').serializeArray();
		for(i=0;employee_info.length>i;i++){
			empnew[employee_info[i]['name']] = employee_info[i]['value'];
		}
		var data = {
			'action': 'jy_employee_info',
			'employee_info': employee_info,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){ 
			if(res){
				toastr.success('Update Successfully');
				var a = $('#jy_job_category').select2('data');
				$("#employee_about").text(empnew['employee_description']);
				$("#emp_job_cat").html('<span>Category:</span>'+a[0].text);
				$("#emp_location").html('<span>Category:</span>'+empnew['location']);
				$("#emp_status").html('<span>Category:</span>'+$('select[name="status"] option:selected').text());
				$("#emp_experience").html('<span>Experience:</span>'+empnew['experience']);
				$("#emp_salary").html('<span>Salary:</span>'+empnew['salary']);
				$("#emp_gender").html('<span>Gender:</span>'+$('select[name="gender"] option:selected').text());
				$("#emp_age").html('<span>Age:</span>'+empnew['age']);
			}
		});
	});
	
	$(document).on('click','.add-edu',function(e){
		e.preventDefault();
		var id = $('.edu-last').data('id');
		var a = $(".edu-block:first").clone();
		a.find('.edu-item').html(++id);
		clear_field(a);
		a.insertBefore('.edu-last');
		$('.edu-last').data('id',id);
	});
	
	$(document).on('click','.add-exp',function(e){
		e.preventDefault();
		var id = $('.exp-last').data('id');
		var a = $(".exp-block:first").clone();
		a.find('.exp-item').html(++id);
		clear_field(a);
		a.insertBefore('.exp-last');
		$('.exp-last').data('id',id);
	});
	
	$(document).on('click','.add-skill',function(e){
		e.preventDefault();
		var id = $('.personal-skill-last').data('id');
		var a = $(".personal-skill:first").clone();
		a.find('.personal-skill-item').html(++id);
		clear_field(a);
		a.insertBefore('.personal-skill-last');
		$('.personal-skill-last').data('id',id);
	});
	
	$(document).on('click','.add-sp-qa',function(e){
		e.preventDefault();
		var id = $('.sp-qa-last').data('id');
		var a = $(".sp-qa:first").clone();
		a.find('.sp-qa-item').html(++id);
		clear_field(a);
		a.insertBefore('.sp-qa-last');
		$('.sp-qa-last').data('id',id);
	});
	
	$(document).on('click','.add-jy-p',function(e){
		e.preventDefault();
		var id = $('.jy-p-last').data('id');
		var a = $(".jy-portfolio:first").clone();
		a.find('.jy-p-item').html(++id);
		var clid = 'jy_protfolio_image'+id+'_button';
		a.find('.portfolio-img').attr("id",clid);
		a.find('.jy_protfolio_image_preview').attr("id",'jy_protfolio_image'+id+'_preview');
		a.find('.jy_protfolio_image').attr("id",'jy_protfolio_image'+id);
		clear_field(a);
		a.insertBefore('.jy-p-last');
		$('.jy-p-last').data('id',id);
	});
	
	
	
	function clear_field(a){
		a.find('input').val(''); 
		a.find('textarea').val(''); 
	}
	
	
	$(document).on('click','#save_employee_edu',function(e){
		e.preventDefault();
		const employee_edu = $('form[name="edu_info"]').serializeArray();
		var data = {
			'action': 'jy_employee_edu',
			'employee_edu': employee_edu,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
				$('#employee-edu').html(res);
			}
		});
	});
	
	$(document).on('click','#save_employee_exp',function(e){
		e.preventDefault();
		const employee_exp = $('form[name="exp_info"]').serializeArray();
		var data = {
			'action': 'jy_employee_exp',
			'employee_exp': employee_exp,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
				$('#employee-exp').html(res);
			}
		});
	});
	
	$(document).on('click','#save_employee_personal_skill',function(e){
		e.preventDefault();
		const emp_personal_skill = $('form[name="jy_personal_skill"]').serializeArray();
		var data = {
			'action': 'jy_employee_personal_skill',
			'emp_personal_skill': emp_personal_skill,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res.status){
				toastr.success('Update Successfully');
				$('.skill_description').html(res.skill_description);
				$('.skill-progress-group').html(res.markup);
			}
		});
	});
	
	$(document).on('click','#save_employee_sq_qa',function(e){
		e.preventDefault();
		const jy_sp_qa = $('form[name="jy_sp_qa"]').serializeArray();
		var data = {
			'action': 'jy_employee_sp_qa',
			'jy_sp_qa': jy_sp_qa,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
				$('.sp-qa-data').html(res);
			}
		});
	});
	
	
	$(document).on('click','#save_employee_personal_info',function(e){
		e.preventDefault();
		const jy_em_per_info = $('form[name="jy_em_per_info"]').serializeArray();
		var data = {
			'action': 'jy_em_per_info',
			'jy_em_per_info': jy_em_per_info,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
				$('.jy-emp-full-info').html(res);
			}
		});
	});
	
	$(document).on('click','#save_employee_portfolio',function(e){
		e.preventDefault();
		const jy_em_portfolio = $('form[name="jy-portfolio"]').serializeArray();
		var data = {
			'action': 'jy_em_portfolio',
			'jy_em_portfolio': jy_em_portfolio,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
				$('.portfolio-slider').html(res);
			}
		});
	});
	
	jy_remove('.edu-remove','.edu-block');
	jy_remove('.exp-remove','.exp-block');
	jy_remove('.ps-remove','.personal-skill');
	jy_remove('.portfolio-remove','.jy-portfolio');
	function jy_remove(clas,sg){
		$(document).on('click',clas,function(e){
			e.preventDefault();
			var size = $(sg).size();
			if(size>1){
				$(this).parent().remove();
			}
		});
	}
	
	$('.datepicker').datetimepicker({
		timepicker:false,
		format:'Y-m-d'
	});
	
	$('#post_job').click(function(e){
		var check = $('.accept_condition').prop("checked");
		if(!check){
			e.preventDefault();
		}
	});
	
	$(document).on('click','#company_query',function(e){
		e.preventDefault();
		const from_data = $('form[name="contact_with"]').serializeArray();
		var data = {
			'action': 'jy_send_email',
			'job_id': from_data,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Update Successfully');
			}
		});
	});
	
	$('.jy-bookmark').click(function(e){
		e.preventDefault();
		const job_id = $(this).data('job_id');
		if(!jemployee.jemployee_login){
			$('#modal-login').modal('show');
		}
		var data = {
			'action': 'jy_em_bookmark',
			'job_id': job_id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				toastr.success('Bookmark Successfully');
			}
		});
	});
	
	$('.jy-online-apply').click(function(e){
		e.preventDefault();
		const job_id = $(this).data('job_id');
		if(!jemployee.jemployee_login){
			$('#modal-login').modal('show');
		}
		var data = {
			'action': 'jy_em_apply',
			'job_id': job_id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			if(res){
				if(res == 'Applyed'){
					toastr.success(res);
				}else{
					toastr.warning(res);
				}
				
			}
		});
	});
	
	$('.bookmark-remove').click(function(e){
		e.preventDefault();
		const job_id = $(this).data('job_id');
		if(!jemployee.jemployee_login){
			return false;
		}
		var data = {
			'action': 'jy_remove_bookmark',
			'job_id': job_id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			var response = JSON.parse(res);
			if(response.status){
				toastr.success('Bookmark Remove');
				var a = $('#total_job').text();
				$('#total_job').text(a-1);
				$('#job_list_'+job_id).remove();
			}
		});
	});
	
	$('.apply-remove').click(function(e){
		e.preventDefault();
		const job_id = $(this).data('job_id');
		if(!jemployee.jemployee_login){
			return false;
		}
		var data = {
			'action': 'jy_remove_applied',
			'job_id': job_id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			var response = JSON.parse(res);
			if(response.status){
				toastr.success('Cancle Apply');
				var a = $('#total_job').text();
				$('#total_job').text(a-1);
				$('#job_list_'+job_id).remove();
			}
		});
	});
	
	$('.shortlisted').click(function(e){
		e.preventDefault();
		var a = $(this);
		const id = $(this).data('id');
		var data = {
			'action': 'jy_shortlisted',
			'id': id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			var response = JSON.parse(res);
			if(response.status){
				toastr.success('Shortlisted');
				var st = 'Shortlisted';
				a.html(st);
			}
		});
	});
	
	
	$('.remove_application').click(function(e){
		e.preventDefault();
		var a = $(this);
		const id = $(this).data('id');
		var data = {
			'action': 'jy_application_reject',
			'id': id,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			var response = JSON.parse(res);
			if(response.status){
				toastr.success('Cancle Application');
				a.parent().parent().remove();
			}
		});
	});
	
	///Send email with job 
	$('#email_job').click(function(e){
		e.preventDefault();
		var a = $(this);
		const from_data = $('form[name="email-job"]').serializeArray();
		var data = {
			'action': 'jy_email_job',
			'from_data': from_data,
			'jemployee_nonce' :  jemployee.jemployee_ajax_nonce
		};
		$.post( jemployee.url, data, function( res ){
			var response = JSON.parse(res);
			toastr.success('Email Send');
		});
	});
	
	// if(document.getElementById('cp-map') != null){
		// var mapLat = parseFloat($(this).data('lat'));
		// var mapLng = parseFloat($(this).data('lng'));
		// var mapZoom = parseInt($(this).data('zoom'));
		// var mapType = $(this).data('maptype');
		
		// var mapOptions = {
			// center: {
				// lat: mapLat,
				// lng: mapLng
			// },
			// zoom: mapZoom,
			// mapTypeId: mapType,
			// scrollwheel: false,
		// };
		// var map = new google.maps.Map(this, mapOptions);
	// }
});