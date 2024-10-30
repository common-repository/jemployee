<?php 
	$current_user = wp_get_current_user();
	$image_thumb = plugins_url('../../assets/img/placeholder.png',__FILE__);
	$employer_identity = get_user_meta($current_user->ID,'job_employer_id',true);
	$user_img = get_post_meta($employer_identity,'jy_em_image',true);
	if( $user_img ) {
		$image_thumb = wp_get_attachment_thumb_url( $user_img );
	}
	$content_post = get_post($employer_identity);
?>
<div class="alice-bg section-padding-bottom">
	<div class="container no-gliters">
		<div class="row no-gliters">
			<div class="col">
				<div class="dashboard-container">
					<div class="dashboard-content-wrapper">
						
						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dashboard-form" method="post">
							<?php $front_user = wp_create_nonce( 'front_user' ); ?>
							<input type="hidden" name="action" value="jy_employer_edit">
							<input type="hidden" name="front_user" value="<?php echo $front_user ?>" />			
							<div class="dashboard-section upload-profile-photo">
								<div class="update-photo">
									<img id="jy_em_image_preview" class="image" src="<?php print esc_url($image_thumb); ?>">
								</div>
								<button id="jy_em_image_button" class="file-upload image_upload_button">            
									<?php esc_html_e("Change Avatar","jemployee"); ?>
								</button>
								<input type="hidden" name="jy_em_image" id="jy_em_image">
							</div>
							<div class="dashboard-section basic-info-input">
								<h4><i data-feather="user-check"></i><?php esc_html_e("Basic Info","jemployee"); ?></h4>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Company Name","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="company_name" type="text" value="<?php print $content_post->post_title; ?>" class="form-control" placeholder="Company Name">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Username","jemployee"); ?></label>
									<div class="col-sm-9">
										<input readonly type="text" value="<?php print $current_user->nickname; ?>" class="form-control" placeholder="@username">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Email Address","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="email_address" value="<?php print $current_user->user_email; ?>" type="text" class="form-control" placeholder="email@example.com">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Phone","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="phone" value="<?php  print get_post_meta($employer_identity,'jy_em_phone',true); ?>" type="text" class="form-control" placeholder="+55 123 4563 4643">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("WebSite","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="website" value="<?php  print get_post_meta($employer_identity,'jy_em_website',true); ?>" type="text" class="form-control" placeholder="www.tet.com">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Company Emaployee","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="total_employee" value="<?php  print get_post_meta($employer_identity,'jy_total_employee',true); ?>" type="text" class="form-control" placeholder="20-25">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Address","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="address" value="<?php  print get_post_meta($employer_identity,'jy_em_address',true); ?>" type="text" class="form-control" placeholder="Washington D.C">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("About Company","jemployee"); ?></label>
									<div class="col-sm-9">
										<?php Jy_helper::_editor($content_post->post_content,'description',array()); ?>
									</div>
								</div>
							</div>
							
							<div class="dashboard-section media-inputs">
								<h4><i data-feather="image"></i><?php esc_html_e("Photo Video","jemployee"); ?></h4>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Intro Video","jemployee"); ?></label>
									<div class="col-sm-9">
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text"><?php esc_html_e("Link","jemployee"); ?></div>
											</div>
											<input name="intro_video" value="<?php print get_post_meta($employer_identity,'jy_em_intro_video',true); ?>" type="text" class="form-control" placeholder="https://www.youtube.com/watch?v=ZRkdyjJ_489M">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Gallery","jemployee"); ?></label>
									<div class="col-sm-9">
										<div class="input-group image-upload-input">
											<div class="input-group-prepend">
												<div class="input-group-text jy_gallery_image">
													<div class="multie-image-list upload-images" id="jy_p_gallery_list">
														<?php $gallery = get_post_meta($employer_identity,'jy_p_gallery',true);
															if(!empty($gallery)):
																$markup = '';
																foreach($gallery as $key => $item):
																	$image = wp_get_attachment_thumb_url( $item );
																	$indx = $key;
																	if(!empty($image)):
																		$markup .= '<div class="img"  id="image-remove-'.$indx.'">'; 
																			$markup .= '<image class="preview-image" src="'.$image.'" >';      
																			$markup .= '<span data-image-pos-id="'.$indx.'" class="dashicons dashicons-dismiss ti-close image-remove"></span>';      
																			$markup .= '<input type="hidden" name="jy_p_gallery['.$indx.']" value="'.$indx.'" class="preview-image" >';
																		$markup .= '</div>';
																	endif;
																endforeach;
																print $markup;
															endif;
														?>
													</div>
												</div>
											</div>
											<div class="active">
												<div class="upload-images">
													<div id="jy_p_gallery_button" class="pic add-gallery-image">
														<span class="fas fa-plus"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-section social-inputs">
								<h4><i data-feather="cast"></i><?php esc_html_e("Social Networks","jemployee"); ?></h4>
								<?php 
									$default = array('facebook'=>'','twitter'=>'','google'=>'');
									$social_link = get_post_meta($employer_identity,'jy_social_link',true);
									$social_link = wp_parse_args($social_link,$default);
								?>
								
								<div class="form-group row">
									<div class="offset-sm-3 col-sm-9">
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-facebook-f"></i></div>
											</div>
											<input value="<?php print esc_attr($social_link['facebook']); ?>" name="social_link[facebook]" type="text" class="form-control" placeholder="facebook.com/username">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="offset-sm-3 col-sm-9">
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-twitter"></i></div>
											</div>
											<input type="text" value="<?php print $social_link['twitter']; ?>" name="social_link[twitter]"  class="form-control" placeholder="twitter.com/username">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="offset-sm-3 col-sm-9">
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-google-plus"></i></div>
											</div>
											<input type="text" value="<?php print $social_link['google']; ?>" name="social_link[google]"  class="form-control" placeholder="google.com/username">
										</div>
									</div>
								</div>
								
							</div>
							
							<div class="dashboard-section basic-info-input">
								<h4><i data-feather="lock"></i><?php esc_html_e("Change Password","jemployee"); ?></h4>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Current Password","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="c_pass" type="password" class="form-control" placeholder="Current Password">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("New Password","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="new_pass" type="password" class="form-control" placeholder="New Password">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Retype Password","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="conf_pass" type="password" class="form-control" placeholder="Retype Password">
									</div>
								</div>
								<button class="file-upload"><?php esc_html_e("Update","jemployee"); ?></button>
							</div>
						</form>
					</div>
		  
					<div class="dashboard-sidebar">
						<?php Jy_helper::get_menu($current_user); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>