<?php 
	$current_user = wp_get_current_user();
	$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
	$user_img = get_user_meta($current_user->ID,'jy_em_image',true);
	if( $user_img ) {
		$image_thumb = wp_get_attachment_thumb_url( $user_img );
	}
?>
<div class="alice-bg section-padding-bottom">
	<div class="container no-gliters">
		<div class="row no-gliters">
			<div class="col">
				<div class="dashboard-container">
					<div class="dashboard-content-wrapper">
						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dashboard-form" method="post">
							<?php $front_user = wp_create_nonce( 'front_user' ); ?>
							<input type="hidden" name="action" value="jy_employee_edit">
							<input type="hidden" name="front_user" value="<?php echo esc_attr($front_user); ?>" />			
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
									<label class="col-sm-3 col-form-label"><?php esc_html_e("First Name","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="first_name" type="text" value="<?php print $current_user->user_firstname; ?>" class="form-control" placeholder="First Name">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Last Name","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="last_name" value="<?php print esc_html($current_user->user_lastname); ?>" type="text" class="form-control" placeholder="Last Name">
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
										<input name="email_address" value="<?php print esc_html($current_user->user_email); ?>" type="text" class="form-control" placeholder="email@example.com">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Phone","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="phone" value="<?php  print get_user_meta($current_user->ID,'jy_em_phone',true); ?>" type="text" class="form-control" placeholder="+55 123 4563 4643">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Address","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="address" value="<?php print get_user_meta($current_user->ID,'jy_em_address',true); ?>" type="text" class="form-control" placeholder="Washington D.C">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("Indestry Expertise","jemployee"); ?></label>
									<div class="col-sm-9">
										<input name="expertise" value="<?php  print get_user_meta($current_user->ID,'jy_em_expertise',true); ?>" type="text" class="form-control" placeholder="UI & UX Designer">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label"><?php esc_html_e("About Me","jemployee"); ?></label>
									<div class="col-sm-9">
										<textarea name="description" class="form-control" placeholder="Introduce Yourself"><?php print esc_html($current_user->description); ?></textarea>
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