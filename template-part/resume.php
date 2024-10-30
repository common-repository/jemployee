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
	$social_list = array('facebook-f','twitter','google-plus','linkedin-in','pinterest-p','behance','dribbble','github');
	$terms = get_terms('jy_skill', array(
		'include' => $skill,
		'hide_empty' => false,
	) );
	$upload_cv = get_post_meta($post_id,'jy_upload_cv',true);
	$upload_cover = get_post_meta($post_id,'jy_upload_cover',true);
?>
<div class="alice-bg section-padding-bottom">
	<div class="container no-gliters">
		<div class="row no-gliters">
			<div class="col">
				<div class="dashboard-container">
					<div class="dashboard-content-wrapper">
						<div class="download-resume dashboard-section">
							<?php if($upload_cv) ?>
								<a download href="<?php print wp_get_attachment_url($upload_cv); ?>"><?php esc_html_e("Download CV","jemployee"); ?><i data-feather="download"></i></a>
							<?php if($upload_cover) ?>
								<a download href="<?php print wp_get_attachment_url($upload_cover); ?>"><?php esc_html_e("Download Cover Letter","jemployee"); ?><i data-feather="download"></i></a>
						</div>
						<div class="skill-and-profile dashboard-section">
							<div class="skill jy-skill">
								<div id="jy_skill_list">
									<label><?php esc_html_e("Skills:","jemployee"); ?></label>
									<?php
										$s_select = '';
										foreach($terms as $term){
											echo '<a href="#">'.esc_html($term->name).'</a>';
											$s_select .= '<option selected value="'.esc_attr($term->term_id).'">'.esc_html($term->name).'</option>';
										}
									?>
								</div>
							</div>
							<div class="social-profile">
								<label><?php esc_html_e("Social:","jemployee"); ?></label>
								<div class="social-view">
									<?php 
										foreach($social_list as $key=> $list): 
											$s_link = isset($social[$list])?$social[$list]:'';
											print '<a href="'.esc_url($s_link).'"><i class="fab fa-'.esc_attr($list).'"></i></a>'; 
										endforeach; 
									 ?>
								</div>
							</div>
						</div>
						<?php 
							///employee description
							$filed_list = array('category'=>'','location'=>'','status'=>array(1=>'Full time',2=>'Part Time'),'experience'=>'','salary'=>'','gender'=>array(1=>'Male',2=>'Female'),'age'=>'');
							$filed_list['location'] = get_post_meta($post_id,'location',true);
							$filed_list['experience'] = get_post_meta($post_id,'experience',true);
							$filed_list['salary'] = get_post_meta($post_id,'salary',true);
							$filed_list['age'] = get_post_meta($post_id,'age',true);
							$status = get_post_meta($post_id,'status',true);
							$gender = get_post_meta($post_id,'gender',true);
							$s_status = !empty($filed_list['status'][$status])?$filed_list['status'][$status]:'';
							
							$s_gender = !empty($filed_list['gender'][$gender])?$filed_list['gender'][$gender]:'';
							$filed_list['category'] = get_post_meta($post_id,'jy_category',true);
							$content = get_post_field('post_content', $post_id);
							$cat_data = Jy_helper::get_term($filed_list['category'],'jy_category');
						?>
						<div class="about-details details-section dashboard-section">
							<h4><i data-feather="align-left"></i><?php esc_html_e("About Me","jemployee"); ?></h4>
							<div class="information-and-contact">
								<p id="employee_about"><?php print $content; ?></p>
								<div class="information">
									<h4><?php esc_html_e("Information","jemployee"); ?></h4>
									<ul>
										<li id="emp_job_cat"><span><?php esc_html_e("Category:","jemployee"); ?></span> <?php print esc_html($cat_data->name); ?></li>
										<li id="emp_location"><span><?php esc_html_e("Location:","jemployee"); ?></span> <?php print esc_html($filed_list['location']); ?></li>
										<li id="emp_status"><span><?php esc_html_e("Status:","jemployee"); ?></span> <?php print esc_html($s_status); ?></li>
										<li id="emp_experience"><span><?php esc_html_e("Experience:","jemployee"); ?></span> <?php print esc_html($filed_list['experience']); ?></li>
										<li id="emp_salary"><span><?php esc_html_e("Salary:","jemployee"); ?></span> <?php print esc_html($filed_list['salary']); ?></li>
										<li id="emp_gender"><span><?php esc_html_e("Gender:","jemployee"); ?></span> <?php print esc_html($s_gender); ?> </li>
										<li id="emp_age"><span><?php esc_html_e("Age:","jemployee"); ?></span> <?php print esc_html($filed_list['age']); ?></li>
									</ul>
								</div>
							</div>
						</div>
						<?php 
							//education info
							$edu_data = get_post_meta($post_id,'employee_edu',true);
						?>
						<div class="edication-background details-section dashboard-section">
							<h4><i data-feather="book"></i><?php esc_html_e("Education Background","jemployee"); ?></h4>
							<div id="employee-edu">
								<?php if(!empty($edu_data)): foreach($edu_data as $item): ?>
									<div class="education-label">
										<span class="study-year"><?php print esc_html($item['edu_period']); ?></span>
										<h5><?php print esc_html($item['edu_title']); ?><span>@  <?php print esc_html($item['edu_institute']); ?></span></h5>
										<p><?php print esc_html($item['edu_description']); ?></p>
									</div>
								<?php endforeach;endif; ?>
							</div>
						</div>
						
						<?php 	
							//work info
							$exp_data = get_post_meta($post_id,'employee_exp',true);
						?>
						<div class="experience dashboard-section details-section">
							<h4><i data-feather="briefcase"></i><?php esc_html_e("Work Experiance","jemployee"); ?></h4>
							<div id="employee-exp">
								<?php if(!empty($exp_data)): foreach($exp_data as $item): ?>
									<div class="experience-section">
										<span class="study-year"><?php print esc_html($item['exp_period']); ?></span>
										<h5><?php print esc_html($item['exp_designation']); ?><span>@  <?php print esc_html($item['exp_company']); ?></span></h5>
										<p><?php print esc_html($item['exp_description']); ?></p>
									</div>
								<?php endforeach;endif; ?>
							</div>
						</div>
						
						<?php 
							$exp_data = get_post_meta($post_id,'employee_skill',true);
							$desc = '';
							if(isset($exp_data['skill_description'])){
								$desc = $exp_data['skill_description'];
								unset($exp_data['skill_description']);
							}
						?>
						<div class="professonal-skill dashboard-section details-section">
							<h4><i data-feather="feather"></i><?php esc_html_e("Professional Skill","jemployee"); ?></h4>
							<p class="skill_description"><?php print $desc; ?></p>
							<div class="progress-group skill-progress-group">
							<?php if(!empty($exp_data)): foreach($exp_data as $item): ?>
								<div class="progress-item">
									<div class="progress-head">
										<p class="progress-on"><?php print esc_html($item['skill_name']); ?></p>
									</div>
									<div class="progress-body">
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="<?php print esc_attr($item['skill_value']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
										</div>
										<p class="progress-to"><?php print esc_html($item['skill_value']); ?>%</p>
									</div>
								</div>
							   <?php endforeach;endif; ?>
							</div>
						</div>
						<?php 
							$exp_data = get_post_meta($post_id,'employee_sp_qa',true);
						?>
						<div class="special-qualification dashboard-section details-section">
							<h4><i data-feather="gift"></i><?php esc_html_e("Special Qualification","jemployee"); ?></h4>
							<ul class="sp-qa-data">
								<?php if(!empty($exp_data)):foreach($exp_data as $item): ?>
									<li><?php print $item; ?></li>
								<?php endforeach;endif; ?>
							</ul>
									
						</div>
						
						<?php 
						
							$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
							$default = array('');
							$exp_data = get_post_meta($post_id,'employee_portfolio',true);
						?>
						<div class="portfolio dashboard-section details-section">
							<h4><i data-feather="gift"></i><?php esc_html_e("Portfolio","jemployee"); ?></h4>
							<div class="portfolio-slider owl-carousel">
								<?php if(!empty($exp_data)):foreach($exp_data as $item): ?>
									<?php $img = wp_get_attachment_image_src($item['jy_portfolio_image'],array(240,160),true); ?>
										<div class="portfolio-item">
											<img src="<?php print esc_url(current($img)); ?>" class="img-fluid" alt='<?php esc_attr_e("img"); ?>'>
											<div class="overlay">
												<a href="<?php print esc_url($item['link']); ?>"><i data-feather="eye"></i></a>
												<a href="<?php print esc_url($item['link']); ?>"><i data-feather="link"></i></a>
											</div>
										</div>
								<?php endforeach;endif; ?>
							</div>
						</div>

						<div class="personal-information dashboard-section last-child details-section">
							<h4><i data-feather="user-plus"></i><?php esc_html_e("Personal Deatils","jemployee"); ?></h4>
							<ul class="jy-emp-full-info">
								<li><span><?php esc_html_e("Full Name:","jemployee"); ?></span> <?php print get_user_meta($current_user->ID,'first_name',true).' '.get_user_meta($current_user->ID,'last_name',true); ?></li>
								<li><span><?php esc_html_e("Father's Name:","jemployee"); ?></span> <?php print get_user_meta($current_user->ID,'jy_em_father_name',true); ?></li>
								<li><span><?php esc_html_e("Mother's Name:","jemployee"); ?></span><?php print get_user_meta($current_user->ID,'jy_em_mother_name',true); ?></li>
								<li><span><?php esc_html_e("Date of Birth:","jemployee"); ?></span> <?php print get_user_meta($current_user->ID,'jy_em_dob',true); ?></li>
								<li><span><?php esc_html_e("Nationality:","jemployee"); ?></span> <?php print get_user_meta($current_user->ID,'jy_em_nationality',true); ?> </li>
								<li><span><?php esc_html_e("Address:","jemployee"); ?></span> <?php print get_user_meta($current_user->ID,'jy_em_address',true); ?></li>
							</ul>
						</div>
					</div>
					<div class="dashboard-sidebar">
						<?php Jy_helper::get_menu($current_user); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>