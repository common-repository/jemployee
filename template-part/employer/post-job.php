<?php 
	$current_user = wp_get_current_user();	
	$job_type = array(1=>esc_html__('Full time','jemployee'),2=>esc_html__('Part Time','jemployee'),3=>esc_html__('Freelance','jemployee'),4=>esc_html__('Temporary','jemployee'));
	$experience = array(0=>esc_html__('Select Experiance','jemployee'),1=>'1+',2=>'2+',3=>'3+',4=>'4+',5=>'5+');
	$gender = array(1=>esc_html__('Male','jemployee'),2=>esc_html__('Female','jemployee'));
?>
	<div class="alice-bg section-padding-bottom">
		<div class="container no-gliters">
			<div class="row no-gliters">
				<div class="col">
					<div class="dashboard-container">
						<div class="dashboard-content-wrapper">
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" name="jy_job_post" class="dashboard-form job-post-form">
								
								<input type="hidden" name="action" value="jy_job_post">
								<input type="hidden" name="front_user" value="<?php print wp_create_nonce( 'front_user' ); ?>">
								<div class="dashboard-section basic-info-input">
									<h4><i data-feather="user-check"></i><?php esc_html_e("Post A Job","jemployee"); ?></h4>
									<div class="form-group row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Title","jemployee"); ?></label>
										<div class="col-md-9">
											<input required type="text" name="jy_job_title" class="form-control" placeholder="Your job title here">
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Summery","jemployee"); ?></label>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<select name="jy_job_category" class="form-control jy-select" id="jy_job_category">
															
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_location" type="text" class="form-control" placeholder="Job Location">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php print Jy_helper::dropdown('jy_job_type',$job_type,'',array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php print Jy_helper::dropdown('jy_job_experience',$experience,'',array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
													<input name="jy_job_salary" type="text" class="form-control" placeholder="Salary Range">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
													  <?php print Jy_helper::dropdown('jy_job_gender',$gender,'',array('class'=>'form-control')); ?>
														<i class="fa fa-caret-down"></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_vacancy" type="text" class="form-control" placeholder="Vacancy">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group datepicker">
														<input name="jy_job_date" placeholder="Deadline " type="text" class="form-control datepicker">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Job Description","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor('','jy_job_description',array()); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Responsibilities","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor('','jy_job_responsibilities',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Education","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor('','jy_job_education',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Other Benefits","jemployee"); ?></label>
										<div class="col-md-9 mb-20">
											<?php Jy_helper::_editor('','jy_job_other_benefits',array('tinymce'=> array('toolbar1'=>'bold,italic,bullist,numlist,undo,redo'))); ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label"><?php esc_html_e("Company Location","jemployee"); ?></label>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<input name="jy_job_country" type="text" class="form-control" placeholder="Country">
													</div>
													<div class="form-group">
														<input name="jy_job_city" type="text" class="form-control" placeholder="City">
													</div>
													<div class="form-group">
														<input name="jy_job_zip_code" type="text" class="form-control" placeholder="Zip Code">
													</div>
													<div class="form-group">
														<input type="text" name="jy_job_location" class="form-control" placeholder="Location">
													</div>
												</div>
												<div class="col-md-6">
													<div class="set-location">
														<div id="map-area" class="contact-location">
															<div id="map1"></div>
															<input id="pac-input" class="controls custom-map" type="text" placeholder="Search Box">
														</div>
													</div>
													<input id="mapLat" name="mapLat" value="" type="hidden">
													<input id="mapLng" name="mapLng" value="" type="hidden">
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="row">
										<div class="col-md-9 offset-md-3">
											<div class="form-group terms">
												<input class="custom-radio accept_condition" type="checkbox" name="accept_condition" id="radio-4" name="termsandcondition">
												<label for="radio-4">
													<span class="dot"></span> <?php esc_html_e("You accepts our","jemployee"); ?> <a href="<?php print get_permalink(get_option('jy_terms_page')); ?>"><?php esc_html_e("Terms and Conditions","jemployee"); ?></a> <?php esc_html_e("and","jemployee"); ?> <a href="<?php print get_permalink(get_option('jy_privacy_page')); ?>"><?php esc_html_e("Privacy Policy","jemployee"); ?></a>
												</label>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label"></label>
										<div class="col-md-9">
											<button id="post_job" class="button"><?php esc_html_e("Post Your Job","jemployee"); ?></button>
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