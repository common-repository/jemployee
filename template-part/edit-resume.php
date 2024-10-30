<?php 
	$current_user = wp_get_current_user();
	$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
	$user_img = get_user_meta($current_user->ID,'jy_em_image',true);
	if( $user_img ) {
		$image_thumb = wp_get_attachment_thumb_url( $user_img );
	}
	$post_id =  get_user_meta( $current_user->ID, 'job_seeker_id',true);
	$skill = get_post_meta($post_id, 'jy_em_skill',true);
	$social = get_post_meta($post_id, 'jy_em_social',true);
	$terms = array();
	if($skill){
		$terms = get_terms('jy_skill', array(
			'include' => $skill,
			'hide_empty' => false,
		) );
	}
	
?>
<div class="alice-bg section-padding-bottom">
	<div class="container no-gliters">
		<div class="row no-gliters">
			<div class="col">
				<div class="dashboard-container">
					<div class="dashboard-content-wrapper">
						<div class="download-resume dashboard-section">
							<button id="jy_upload_cv" class="update-file">
								<input type="file"><?php esc_html_e("Update CV","jemployee"); ?> <i data-feather="edit-2"></i>
							</button>
							<button id="jy_upload_cover" class="update-file">
								<input type="file"><?php esc_html_e("Update Cover Letter","jemployee"); ?> <i data-feather="edit-2"></i>
							</button>
							<span><?php esc_html_e("Upload PDF File","jemployee"); ?></span>
						</div>
						<div class="skill-and-profile dashboard-section">
							<div class="skill jy-skill">
								<div id="jy_skill_list">
									<label><?php esc_html_e("Skills: ","jemployee"); ?></label>
									<?php
										$s_select = '';
										foreach($terms as $term){
											echo '<a href="#">'.esc_html($term->name).'</a>';
											$s_select .= '<option selected value="'.esc_attr($term->term_id).'">'.esc_html($term->name).'</option>';
										}
									?>
								</div>
								<!-- Button trigger modal -->
								<button type="button" class="btn btn-primary edit-button" data-toggle="modal" data-target="#modal-skill">
									<i data-feather="edit-2"></i>
								</button>
								<!-- Modal -->
								<div class="modal fade" id="modal-skill" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-body">
												<div class="title">
													<h4><i data-feather="git-branch"></i><?php esc_html_e("MY SKILL","jemployee"); ?></h4>
												</div>
												<div class="content">
													<form action="#">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Type Skills","jemployee"); ?></label>
															<div class="col-sm-9">
																<div class="input-group">
																	<select name="jy_skill" multiple="multiple" id="jy-skill" class="form-control jy-select">
																		<?php print esc_html($s_select); ?>

																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="offset-sm-3 col-sm-9">
																<div class="buttons">
																	<button id="jy_skill_save" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
																	<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php do_action('jy_edit_resume',$social); ?>
						</div>
						<?php do_action('jy_edit_description',$post_id); ?>
					</div>
					<div class="dashboard-sidebar">
						<?php Jy_helper::get_menu($current_user); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>