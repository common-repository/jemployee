<?php 
	$current_user = wp_get_current_user();
	$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
	$post_id = $_GET['job_id'];
	if(!Jy_helper::post_author_validation($post_id)){
		wp_redirect($_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']);
	}
	$skill = get_post_meta($post_id, 'jy_em_skill',true);
	$jy_job_category = get_post_meta($post_id, 'jy_job_category',true);
	$terms = get_term($jy_job_category,'jy_category');
	
	$job_type = array(1=>'Full time',2=>'Part Time',3=>'Freelance',4=>'Temporary');
	$experience = array(1=>'1+',2=>'2+',3=>'3+',4=>'4+',5=>'5+');
	$gender = array(1=>'Male',2=>'Female');
	$content_post = get_post($post_id);
	$mapLat = get_post_meta($post_id, 'mapLat',true);
	$mapLng = get_post_meta($post_id, 'mapLng',true);
	$content = $content_post->post_content;
?>
	<div class="alice-bg section-padding-bottom">
		<div class="container no-gliters">
			<div class="row no-gliters">
				<div class="col">
					<div class="dashboard-container">
						<div class="dashboard-content-wrapper">
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" name="jy_job_post" class="dashboard-form job-post-form">
								
								<input type="hidden" name="job_id" value="<?php print $post_id; ?>">
								<input type="hidden" name="action" value="jy_job_post">
								<input type="hidden" name="front_user" value="<?php print wp_create_nonce( 'front_user' ); ?>">
								<div class="dashboard-section basic-info-input">

									<h4><i data-feather="user-check"></i><?php esc_html_e("Edit  Job","jemployee"); ?></h4>

									<div class="form-group row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Title","jemployee"); ?></label>
										<div class="col-md-9">
											<input required type="text" name="jy_job_title" value="<?php print get_the_title($post_id); ?>" class="form-control" placeholder="Your job title here">
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Summery","jemployee"); ?></label>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<select name="jy_job_category" class="form-control jy-select" id="jy_job_category">
															<?php if($terms) echo '<option selected value="'.$terms->term_id.'">'.$terms->name.'</option>'; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_location" value="<?php print get_post_meta($post_id,'jy_job_location',true); ?>" type="text" class="form-control" placeholder="Job Location">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php print Jy_helper::dropdown('jy_job_type',$job_type,get_post_meta($post_id,'jy_job_type',true),array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php print Jy_helper::dropdown('jy_job_experience',$experience,get_post_meta($post_id,'jy_job_experience',true),array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
													<input name="jy_job_salary" value="<?php print get_post_meta($post_id,'jy_job_salary',true); ?>" type="text" class="form-control" placeholder="Salary Range">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
													  <?php print Jy_helper::dropdown('jy_job_gender',$gender,get_post_meta($post_id,'jy_job_gender',true),array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_vacancy" value="<?php print get_post_meta($post_id,'jy_job_vacancy',true); ?>" type="text" class="form-control" placeholder="Vacancy">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group datepicker">
														<input name="jy_job_date" value="<?php print get_post_meta($post_id,'jy_job_date',true); ?>" placeholder="Deadline " type="text" class="form-control datepicker">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Description","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor($content,'jy_job_description',array()); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Responsibilities","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor(get_post_meta($post_id,'jy_job_responsibilities',true),'jy_job_responsibilities',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Education","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor(get_post_meta($post_id,'jy_job_education',true),'jy_job_education',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Other Benefits","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor(get_post_meta($post_id,'jy_job_other_benefits',true),'jy_job_other_benefits',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Your Location","jemployee"); ?></label>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_country" value="<?php print get_post_meta($post_id,'jy_job_country',true) ?>" type="text" class="form-control" placeholder="Country">
													</div>
													<div class="form-group">
														<input name="jy_job_city" value="<?php print get_post_meta($post_id,'jy_job_city',true) ?>" type="text" class="form-control" placeholder="City">
													</div>
													<div class="form-group">
														<input name="jy_job_zip_code" value="<?php print get_post_meta($post_id,'jy_job_zip_code',true) ?>" type="text" class="form-control" placeholder="Zip Code">
													</div>
													<div class="form-group">
														<input type="text" name="jy_company_location" value="<?php print get_post_meta($post_id,'jy_company_location',true) ?>" class="form-control" placeholder="Your Location">
													</div>
												</div>
												<div class="col-md-6">
													<div class="set-location">
														<div id="map-area" class="contact-location">
															<div id="map1"></div>
															<input id="pac-input" class="controls custom-map" type="text" placeholder="Search Box">
														</div>
													</div>
													<input id="mapLat" name="mapLat" value="<?php print esc_attr($mapLat); ?>" type="hidden">
													<input id="mapLng" name="mapLng" value="<?php print esc_attr($mapLng); ?>" type="hidden">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label"></label>
										<div class="col-md-9">
											<button class="button"><?php esc_html_e("Update Job","jemployee"); ?></button>
										</div>
									</div>
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