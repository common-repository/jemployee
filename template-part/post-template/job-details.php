<?php 
get_header();
$image_thumb = plugins_url('../../assets/img/placeholder.png',__FILE__);
?>
	<!-- Company Details -->
	<?php if(have_posts()): while(have_posts()): the_post(); ?>
	<?php 
		$post_id = get_the_ID();
		$author_id  = get_the_author_meta( 'ID' ); 
		$email  = get_the_author_meta( 'email' ); 
		$company_id = get_user_meta($author_id,'job_employer_id',true);
		
		$jy_job_category = get_post_meta($post_id, 'jy_job_category',true);
		$location = get_post_meta($post_id, 'jy_job_location',true);
		$terms = get_term($jy_job_category,'jy_category');
		$dead_line = get_post_meta($post_id,'jy_job_date',true);
		$logo = get_post_meta($company_id,'jy_em_image',true);
		$dead_line = ($dead_line!='')?date('F d, Y',strtotime($dead_line)):'---';
		$experience = array(0=>esc_html__('Select Experiance','jemployee'),1=>'1+',2=>'2+',3=>'3+',4=>'4+',5=>'5+');
		$gender = array(1=>esc_html__('Male','jemployee'),2=>esc_html__('Female','jemployee'));
		$job_type = array(1=>esc_html__('Full time','jemployee'),2=>esc_html__('Part Time','jemployee'),3=>esc_html__('Freelance','jemployee'),4=>esc_html__('Temporary','jemployee'));
		$experience_v = $experience[get_post_meta($post_id, 'jy_job_experience',true)];
		$gender_v = $gender[get_post_meta($post_id, 'jy_job_gender',true)];
		$job_type_v = $job_type[get_post_meta($post_id,'jy_job_type',true)];
		if( $logo ) {
			$image_thumb = wp_get_attachment_thumb_url( $logo );
		}
		$of_location = ($location!='')?$location:'---';
	?>
    <div class="alice-bg padding-top-60 section-padding-bottom">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="job-listing-details">
						<div class="job-title-and-info">
							<div class="title">
								<div class="thumb">
									<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt="">
								</div>
								<div class="title-body">
									<h4><?php the_title(); ?></h4>
									<div class="info">
										<span class="company"><a href="#"><i data-feather="briefcase"></i><?php print get_the_title($company_id); ?></a></span>
										<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($of_location); ?></a></span>
										<span class="job-type <?php print $job_type_v; ?>"><a href="#"><i data-feather="clock"></i><?php print esc_html($job_type_v); ?></a></span>
									</div>
								</div>
							</div>
							<div class="buttons">
								<a data-job_id="<?php print esc_attr($post_id); ?>" class="save jy-bookmark" href="#"><i data-feather="heart"></i><?php esc_html_e("Save Job","jemployee"); ?></a>
								<a data-job_id="<?php print esc_attr($post_id); ?>" class="apply jy-online-apply" href="#"><?php esc_html_e("Apply Online","jemployee"); ?></a>
							</div>
						</div>
						<div class="details-information section-padding-60">
							<div class="row">
								<div class="col-xl-7 col-lg-8">
									<div class="description details-section">
										<h4><i data-feather="align-left"></i><?php esc_html_e("Job Description","jemployee"); ?></h4>
										
										<?php the_content(); ?>
									</div>
									<div class="responsibilities details-section">
										<h4><i data-feather="zap"></i><?php esc_html_e("Responsibilities","jemployee"); ?></h4>
										<?php print get_post_meta($post_id,'jy_job_responsibilities',true); ?>
									</div>
									<div class="edication-and-experience details-section">
										<h4><i data-feather="book"></i><?php esc_html_e("Education + Experience","jemployee"); ?></h4>
										<?php print get_post_meta($post_id,'jy_job_education',true); ?>
									</div>
									<div class="other-benifit details-section">
										<h4><i data-feather="gift"></i>Other Benefits</h4>
										<?php print get_post_meta($post_id,'jy_job_other_benefits',true); ?>
									</div>
									<div class="job-apply-buttons">
										<a href="#" data-job_id="<?php print $post_id; ?>"  class="apply jy-online-apply"><?php esc_html_e("Apply Online","jemployee"); ?></a>
										<a href="#" data-toggle="modal" data-target="#exampleModal" class="email"><i data-feather="mail"></i><?php esc_html_e("Email Job","jemployee"); ?></a>
									</div>
									<div class="modal fade contact-form-modal" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-body">
													<h4><?php esc_html_e("Email Job","jemployee"); ?></h4>
													<form name="email-job" method="post" action="#">
														<div class="form-group">
															<input name="name" type="text" class="form-control" placeholder="Friend Name">
														</div>
														<div class="form-group">
															<input name="email" type="email" class="form-control" placeholder="Friend Email">
														</div>
														<div class="form-group">
															<textarea name="message" class="form-control" placeholder="Your Message"></textarea>
														</div>
														<button id="email_job" class="button"><?php esc_html_e("Submit","jemployee"); ?></button>
														<input name="post_id" type="hidden" value="<?php print $post_id; ?>">
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-4 offset-xl-1 col-lg-4">
									<div class="information-and-share">
										<div class="job-summary">
											<h4><?php esc_html_e("Job Summary","jemployee"); ?></h4>
											<ul>
												<li><span><?php esc_html_e("Published on:","jemployee"); ?></span> <?php print esc_html(get_the_date('F d, Y')); ?></li>
												<li><span><?php esc_html_e("Vacancy:","jemployee"); ?></span> <?php print  esc_html(get_post_meta($post_id, 'jy_job_vacancy',true)); ?></li>
												<li><span><?php esc_html_e("Employment Status:","jemployee"); ?></span> <?php print esc_html($job_type_v); ?></li>
												<li><span><?php esc_html_e("Experience:","jemployee"); ?></span> <?php print esc_html($experience_v); ?>  year(s)</li>
												<li><span><?php esc_html_e("Job Location:","jemployee"); ?></span> <?php print  esc_html($of_location); ?></li>
												<li><span><?php esc_html_e("Salary:","jemployee"); ?></span> <?php print  esc_html(get_post_meta($post_id, 'jy_job_salary',true)); ?></li>
												<li><span><?php esc_html_e("Gender:","jemployee"); ?></span> <?php print esc_html($gender_v); ?></li>
												<li><span><?php esc_html_e("Application Deadline:","jemployee"); ?></span> <?php print esc_html($dead_line); ?></li>
											</ul>
										</div>
										<?php Jy_helper::job_share(); ?>
										<div class="buttons">
											<a href="#" class="button print"><i data-feather="printer"></i><?php esc_html_e("Print Job","jemployee"); ?></a>
											<a href="#" class="button report"><i data-feather="flag"></i><?php esc_html_e("Report Job","jemployee"); ?></a>
										</div>
										<div class="job-location">
											<h4><?php esc_html_e("Job Location","jemployee"); ?></h4>
											<div id="map-area">
												<div class="cp-map" id="location" data-lat="40.713355" data-lng="-74.005535" data-zoom="10"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
							
							$company_info = get_post($company_id);
							 $website =  get_post_meta($company_info->ID,'jy_em_website',true); 
						?>
						<div class="row">
							<div class="col-xl-7 col-lg-8">
								<div class="company-information details-section">
									<h4><i data-feather="briefcase"></i><?php esc_html_e("About the Company","jemployee"); ?></h4>
									<ul>
										<li><span><?php esc_html_e("Company Name:","jemployee"); ?></span> <?php print esc_html($company_info->post_title); ?></li>
										<li><span><?php esc_html_e("Address:","jemployee"); ?></span> <?php print esc_html(get_post_meta($company_info->ID,'jy_em_address',true)); ?></li>
										<li><span><?php esc_html_e("Website:","jemployee"); ?></span> <a href="<?php print esc_url($website); ?>"><?php print esc_html($website); ?></a></li>
										<li><span><?php esc_html_e("Company Profile:","jemployee"); ?></span></li>
										<li><?php print esc_html($company_info->post_content); ?></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	
	<div class="section-padding-bottom alice-bg">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section-header section-header-2 section-header-with-right-content">
						<h2><?php esc_html_e("Simillar Jobs","jemployee"); ?></h2>
						<a href="#" class="header-right"><?php esc_html_e("+ Browse All Jobs","jemployee"); ?></a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<?php 
						$args = array(
							'post_type' => 'jy_job',
							'post_status' => 'publish',
							'posts_per_page'=> 3,
							'post__not_in'=> array($post_id),
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => 'jy_job_category',
									'value' => $jy_job_category
								),
								array(
									'key' => 'jy_job_date',
									'value' => date('Y-m-d',current_time( 'timestamp')),
									'compare' => '>=',
								)
							)
						);
						$job_list = new WP_Query($args);
						if($job_list->have_posts()): 
							while($job_list->have_posts()): 
								$job_list->the_post();
								$image_thumb = plugins_url('../../assets/img/placeholder.png',__FILE__);
								$j_id = get_the_ID();
								$location = get_post_meta($j_id, 'jy_job_location',true);
								$dead_line = get_post_meta($j_id,'jy_job_date',true);
								$dead_line = ($dead_line!='')?date('F d, Y',strtotime($dead_line)):'---';
								$author_id  = get_the_author_meta( 'ID' ); 
								$company_id = get_user_meta($author_id,'job_employer_id',true);
								$logo = get_post_meta($company_id,'jy_em_image',true);
								if( $logo ) {
									$image_thumb = wp_get_attachment_thumb_url( $logo );
								}
					?>
						<div class="job-list">
							<div class="thumb">
								<a href="<?php get_the_permalink($company_id); ?>">
									<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt="">
								</a>
							</div>
							<div class="body">
								<div class="content">
									<h4><a href="<?php print get_the_permalink(); ?>"><?php print get_the_title(); ?></a></h4>
									<div class="info">
										<span class="company"><a href="<?php print get_the_permalink($company_id); ?>"><i data-feather="briefcase"></i><?php print get_the_title($company_id); ?></a></span>
										<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
										<span class="job-type full-time"><a href="#"><i data-feather="clock"></i><?php print $job_type[get_post_meta($j_id,'jy_job_type',true)] ?></a></span>
									</div>
								</div>
								<div class="more">
									<div class="buttons">
										<a data-job_id="<?php print $j_id; ?>" href="#" class="button jy-online-apply"><?php esc_html_e("Apply Now","jemployee"); ?></a>
										<a data-job_id="<?php print $j_id; ?>" href="#" class="favourite jy-bookmark"><i data-feather="heart"></i></a>
									</div>
									<p class="deadline"><?php esc_html_e("Deadline:","jemployee"); ?> <?php print esc_html($dead_line); ?></p>
								</div>
							</div>
						</div>
						<?php endwhile;wp_reset_postdata();endif; ?>
				  </div>
			</div>
		</div>
    </div>
	<?php endwhile;endif; ?>
    <!-- Company Details End -->
<?php
get_footer();