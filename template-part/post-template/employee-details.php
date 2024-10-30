<?php 
Jy_helper::employee_val();
get_header();

$image_thumb = plugins_url('../../assets/img/placeholder.png',__FILE__);
?>
	<!-- Company Details -->
	<?php if(have_posts()): while(have_posts()): the_post(); ?>
	<?php 
		$post_id = get_the_ID();
		$author_id  = get_the_author_meta( 'ID' ); 
		$email  = get_the_author_meta( 'email' ); 
		$skill = get_post_meta($post_id, 'jy_em_skill',true);
		$terms = get_terms('jy_skill', array(
			'include' => $skill,
			'hide_empty' => false,
		) );
		$social = get_post_meta($post_id, 'jy_em_social',true);
		$social_list = array('facebook-f','twitter','google-plus','linkedin-in','pinterest-p','behance','dribbble','github');
		$edu_data = get_post_meta($post_id,'employee_edu',true);
		$exp_data = get_post_meta($post_id,'employee_exp',true);
		$emp_skill_p = get_post_meta($post_id,'employee_skill',true);
		$desc = '';
		if(isset($emp_skill_p['skill_description'])){
			$desc = $emp_skill_p['skill_description'];
			unset($emp_skill_p['skill_description']);
		}
		
		$sp_q = get_post_meta($post_id,'employee_sp_qa',true);
		$portfolio = get_post_meta($post_id,'employee_portfolio',true);
		
		$location = get_post_meta($post_id,'location',true);
		$experience = get_post_meta($post_id,'experience',true);
		$salary = get_post_meta($post_id,'salary',true);
		$age = get_post_meta($post_id,'age',true);
		$status = get_post_meta($post_id,'status',true);
		$gender = get_post_meta($post_id,'gender',true);
		$emp_category = get_post_meta($post_id,'jy_category',true);
		$cat_data = Jy_helper::get_term($emp_category,'jy_category');
		$filed_list = array('status'=>array(1=>'Full time',2=>'Part Time'),'gender'=>array(1=>'Male',2=>'Female'),'age'=>'');
		$user_img = get_user_meta($author_id,'jy_em_image',true);
		if( $user_img ) {
			$image_thumb = wp_get_attachment_thumb_url( $user_img );
		}
		Jy_helper::employee_view_count($post_id);
	?>
    <div class="alice-bg padding-top-60 section-padding-bottom">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="candidate-details">
						<div class="title-and-info">
							<div class="title">
								<div class="thumb">
									<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt="">
								</div>
								<div class="title-body">
									<h4><?php the_title(); ?></h4>
									<div class="info">
										<span class="candidate-designation"><i data-feather="check-square"></i><?php esc_html_e("ios Developer","jemployee"); ?></span>
										<span class="candidate-location"><i data-feather="map-pin"></i><?php esc_html_e("Los Angeles","jemployee"); ?></span>
									</div>
								</div>
							</div>
							<div class="download-resume">
								<a href="#"><?php esc_html_e("Download CV ","jemployee"); ?><i data-feather="download"></i></a>
							</div>
						</div>
						<div class="skill-and-profile">
							<div class="skill">
								<label><?php esc_html_e("Skills:","jemployee"); ?></label>
								<?php
									foreach($terms as $term){
										echo '<a href="#">'.esc_html($term->name).'</a>';
									}
								?>
							</div>
							<div class="social-profile">
								<label><?php esc_html_e("Social:","jemployee"); ?></label>
								<?php 
									$view = '';
									foreach($social_list as $key=> $list){
										$link = isset($social[$list])?$social[$list]:"";
										$view .= '<a href="'.esc_url($link).'"><i class="fab fa-'.esc_attr($list).'"></i></a>';
									}
									print $view;
								?>
							</div>
						</div>
						<div class="details-information section-padding-60">
							<div class="row">
								<div class="col-xl-7 col-lg-8">
									<div class="about-details details-section">
										<h4><i data-feather="align-left"></i><?php esc_html_e("About Me","jemployee"); ?> </h4>
										<?php the_content(); ?>
										
									</div>
									<div class="edication-background details-section">
										<h4><i data-feather="book"></i><?php esc_html_e("Education Background","jemployee"); ?> </h4>
										<?php if(!empty($edu_data)): foreach($edu_data as $item): ?>
											<div class="education-label">
												<span class="study-year"><?php print $item['edu_period']; ?></span>
												<h5><?php print $item['edu_title']; ?><span>@  <?php print $item['edu_institute']; ?></span></h5>
												<p><?php print $item['edu_description']; ?></p>
											</div>
										<?php endforeach;endif; ?>
									</div>
									<div class="experience details-section">
										<h4><i data-feather="briefcase"></i><?php esc_html_e("Work Experiance","jemployee"); ?></h4>
										<?php if(!empty($exp_data)): foreach($exp_data as $item): ?>
											<div class="experience-section">
												<span class="study-year"><?php print esc_html($item['exp_period']); ?></span>
												<h5><?php print esc_html($item['exp_designation']); ?><span>@  <?php print esc_html($item['exp_company']); ?></span></h5>
												<p><?php print esc_html($item['exp_description']); ?></p>
											</div>
										<?php endforeach;endif; ?>
									</div>
									<div class="professonal-skill details-section">
										<h4><i data-feather="feather"></i><?php esc_html_e("Professional Skill","jemployee"); ?></h4>
										 <p><?php print esc_html($desc); ?></p>
										<div class="progress-group">
											<?php if(!empty($emp_skill_p)): foreach($emp_skill_p as $item): ?>
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
									<div class="special-qualification details-section">
										<h4><i data-feather="gift"></i><?php esc_html_e("Special Qualification","jemployee"); ?></h4>
										<ul class="sp-qa-data">
											<?php if(!empty($sp_q)):foreach($sp_q as $item): ?>
												<li><?php print $item; ?></li>
											<?php endforeach;endif; ?>
										</ul>
									</div>
									<div class="portfolio details-section">
										<h4><i data-feather="gift"></i><?php esc_html_e("Portfolio","jemployee"); ?></h4>
										
										<div class="portfolio-slider owl-carousel">
											<?php if(!empty($portfolio)):foreach($portfolio as $item): ?>
												<?php $img = wp_get_attachment_image_src($item['jy_portfolio_image'],array(240,160),true); ?>
													<div class="portfolio-item">
														<img src="<?php print esc_url(current($img)); ?>" class="img-fluid" alt="">
														<div class="overlay">
															<a href="<?php print esc_url($item['link']); ?>"><i data-feather="eye"></i></a>
															<a href="<?php print esc_url($item['link']); ?>"><i data-feather="link"></i></a>
														</div>
													</div>
											<?php endforeach;endif; ?>
										</div>
									</div>
								</div>
								<div class="col-xl-4 offset-xl-1 col-lg-4">
									<div class="information-and-contact">
										<div class="information">
											<h4><?php esc_html_e("Information","jemployee"); ?></h4>
											<ul>
												<li id="emp_job_cat"><span><?php esc_html_e("Category:","jemployee"); ?></span> <?php print esc_html($cat_data->name); ?></li>
												<li id="emp_location"><span><?php esc_html_e("Location:","jemployee"); ?></span> <?php print esc_html($location); ?></li>
												<li id="emp_status"><span><?php esc_html_e("Status:","jemployee"); ?></span> <?php print esc_html($filed_list['status'][$status]); ?></li>
												<li id="emp_experience"><span><?php esc_html_e("Experience:","jemployee"); ?></span> <?php print esc_html($experience); ?></li>
												<li id="emp_salary"><span><?php esc_html_e("Salary:","jemployee"); ?></span> <?php print esc_html($salary); ?></li>
												<li id="emp_gender"><span><?php esc_html_e("Gender:","jemployee"); ?></span> <?php print esc_html($filed_list['gender'][$gender]); ?> </li>
												<li id="emp_age"><span><?php esc_html_e("Age:","jemployee"); ?></span> <?php print esc_html($age); ?></li>
											</ul>
										</div>
										<div class="buttons">
											<a href="#" class="button contact-button" data-toggle="modal" data-target="#exampleModal"><?php esc_html_e("Contact Me","jemployee"); ?></a>
											<a href="#" class="button cover-download"><i data-feather="download"></i><?php esc_html_e("Cover Letter","jemployee"); ?></a>
											<a href="#" class="button contact-download"><i data-feather="download"></i><?php esc_html_e("Contact","jemployee"); ?></a>
										</div>
										<div class="modal fade contact-form-modal" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-body">
														<h4><i data-feather="edit"></i><?php esc_html_e("Contact Me","jemployee"); ?></h4>
														<form method="post" name="contact_with" action="#">
															<div class="form-group">
																<input name="name" type="text" class="form-control" placeholder="Your Name">
															</div>
															<div class="form-group">
																<input name="email" type="email" class="form-control" placeholder="Your Email">
															</div>
															<div class="form-group">
																	<textarea name="message" class="form-control" placeholder="Your Message"></textarea>
															</div>
															<input type="hidden" name="employee_email" value="<?php print esc_attr($email); ?>">
															<button id="company_query" class="button"><?php esc_html_e("Submit","jemployee"); ?></button>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-7 col-lg-8">
								<div class="personal-information details-section">
									<h4><i data-feather="user-plus"></i><?php esc_html_e("Personal Deatils","jemployee"); ?></h4>
									<ul class="jy-emp-full-info">
										<li><span><?php esc_html_e("Full Name:","jemployee"); ?></span> <?php print get_user_meta($author_id,'first_name',true).' '.get_user_meta($author_id,'last_name',true); ?></li>
										<li><span><?php esc_html_e("Father's Name:","jemployee"); ?></span> <?php print get_user_meta($author_id,'jy_em_father_name',true); ?></li>
										<li><span><?php esc_html_e("Mother's Name:","jemployee"); ?></span><?php print get_user_meta($author_id,'jy_em_mother_name',true); ?></li>
										<li><span><?php esc_html_e("Date of Birth:","jemployee"); ?></span> <?php print get_user_meta($author_id,'jy_em_dob',true); ?></li>
										<li><span><?php esc_html_e("Nationality:","jemployee"); ?></span> <?php print get_user_meta($author_id,'jy_em_nationality',true); ?> </li>
										<li><span><?php esc_html_e("Address:","jemployee"); ?></span> <?php print get_user_meta($author_id,'jy_em_address',true); ?></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<?php endwhile; endif; ?>
<?php
get_footer();