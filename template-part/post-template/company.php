<?php 
get_header();
$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
?>
	<!-- Company Details -->
	<?php if(have_posts()): while(have_posts()): the_post(); ?>
	<?php 
		$post_id = get_the_ID();
		$author_id  = get_the_author_meta( 'ID' );
		
		$email  = get_the_author_meta( 'email' ); 
		$phone = get_post_meta($post_id,'jy_em_phone',true);
		$total_employee = get_post_meta($post_id,'jy_total_employee',true);
		$website = get_post_meta($post_id,'jy_em_website',true);
		$location = get_post_meta($post_id,'jy_em_address',true);
		$logo = get_post_meta($post_id,'jy_em_image',true);
		$intor_video = get_post_meta($post_id,'jy_em_intro_video',true);
		$jy_p_gallery = get_post_meta($post_id,'jy_p_gallery',true);
		$social_link = get_post_meta($post_id,'jy_social_link',true);
		$url_arr = parse_url( $intor_video, PHP_URL_QUERY );
		parse_str( $url_arr, $my_array_of_vars );
		if( $logo ) {
			$image_thumb = wp_get_attachment_thumb_url( $logo );
		}
		$of_location = ($location!='')?$location:'---';
		
		$args = array(
			'author '=> $author_id,
			'post_type' => 'jy_job',
			'post_status' => 'publish',
			'posts_per_page'=> 3,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'jy_job_date',
					'value' => date('Y-m-d',current_time( 'timestamp')),
					'compare' => '>=',
				)
			)
		);
		$query = new WP_Query( $args );
	?>
    <div class="alice-bg padding-top-60 section-padding-bottom">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="company-details">
						<div class="title-and-info">
							<div class="title">
								<div class="thumb">
									<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt="">
								</div>
								<div class="title-body">
									<h4><?php the_title(); ?></h4>
									<div class="info">
										<span class="company-type"><i data-feather="briefcase"></i><?php esc_html_e("Software Firm","jemployee"); ?></span>
										<span class="office-location"><i data-feather="map-pin"></i><?php print esc_html($location); ?></span>
									</div>
								</div>
							</div>
							<div class="download-resume">
								<a href="#"><?php print esc_html($query->post_count); ?> <?php esc_html_e("Open Positions","jemployee"); ?></a>
							</div>
						</div>
			  
						<div class="details-information padding-top-60">
							<div class="row">
								<div class="col-xl-7 col-lg-8">
									<div class="about-details details-section">
										<h4><i data-feather="align-left"></i><?php esc_html_e("About Us","jemployee"); ?></h4>
										<?php the_content(); ?>
									</div>
									<?php if(isset($my_array_of_vars['v'])): ?>
									<div class="intor-video details-section">
										<h4><i data-feather="video"></i><?php esc_html_e("Intro Video","jemployee"); ?></h4>
										<div class="video-area">
											<div data-type="youtube" data-video-id="<?php print esc_attr($my_array_of_vars['v']); ?>"></div>
										</div>
									</div>
									<?php endif; ?>
									<?php if(!empty($jy_p_gallery)): ?>
										<div class="portfolio details-section">
											<h4><i data-feather="grid"></i><?php esc_html_e("Image Gallery","jemployee"); ?></h4>
											<div class="portfolio-slider owl-carousel">
												<?php foreach($jy_p_gallery as $img_link): ?>
													<div class="portfolio-item">
														<img src="<?php print esc_url(wp_get_attachment_thumb_url( $img_link )); ?>" class="img-fluid" alt="">
														<div class="overlay">
															<a href="#"><i data-feather="eye"></i></a>
															<a href="#"><i data-feather="link"></i></a>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									<?php endif; ?>
					
									<div class="open-job details-section">
										<h4><i data-feather="check-circle"></i><?php esc_html_e("Open Job","jemployee"); ?></h4>
										<?php 
											
											if($query->have_posts()): 
												$job_type = array(1=>'Full time',2=>'Part Time',3=>'Freelance',4=>'Temporary');
												while($query->have_posts()): 
													$query->the_post();
													$job_id = get_the_ID();
													$job_type_id = get_post_meta($job_id,'jy_job_type',true);
													$location = get_post_meta($job_id,'jy_job_location',true);
													$deadline = get_post_meta($job_id,'jy_job_date',true);
													$deadline = ($deadline!='')?date('M d, Y',strtotime($deadline)):'---';
										?>
												<div class="job-list">
													<div class="body">
														<div class="content">
															<h4><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h4>
															<div class="info">
																<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
																<span class="job-type temporary"><a href="#"><i data-feather="clock"></i><?php print esc_html($job_type[$job_type_id]); ?></a></span>
															</div>
														</div>
														<div class="more">
															<div class="buttons">
																<a data-job_id="<?php print esc_attr($job_id); ?>" href="#" class="button jy-online-apply"><?php esc_html_e("Apply Now","jemployee"); ?></a>
																<a data-job_id="<?php print esc_attr($job_id); ?>" href="#" class="favourite jy-bookmark"><i data-feather="heart"></i></a>
															</div>
															<p class="deadline"><?php esc_html_e("Deadline: ","jemployee"); ?><?php print esc_html($deadline); ?></p>
														</div>
													</div>
												</div>
										
											<?php endwhile;endif; wp_reset_postdata(); ?>
									</div>
								</div>
								<div class="col-xl-4 offset-xl-1 col-lg-4">
									<div class="information-and-contact">
										<div class="information">
											<h4><?php esc_html_e("Information","jemployee");?></h4>
											<ul>
												<li><span><?php esc_html_e("Location:","jemployee");?></span> <?php print esc_html($of_location); ?></li>
												<li><span><?php esc_html_e("Hotline:","jemployee");?></span> <?php print esc_html($phone); ?></li>
												<li><span><?php esc_html_e("Email:","jemployee");?></span> <?php print esc_html($email); ?></li>
												<li><span><?php esc_html_e("Company Size:","jemployee");?></span> <?php print esc_html($total_employee); ?></li>
												<li><span><?php esc_html_e("Website:","jemployee");?></span> <a href="<?php print esc_url($website); ?>"><?php print esc_html($website); ?></a></li>
											</ul>
										</div>
										<div class="buttons">
											<a href="#" class="button contact-button" data-toggle="modal" data-target="#exampleModal"><?php esc_html_e("Contact Us","jemployee"); ?></a>
										</div>
										  <!-- Modal -->
										<div class="modal fade contact-form-modal" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-body">
														<h4><i data-feather="edit"></i><?php esc_html_e("Contact Us","jemployee"); ?></h4>
														<form name="complany_contact_form" method="post">
															<div class="form-group">
																<input name="name" required type="text" class="form-control" placeholder="Your Name">
															</div>
															<div class="form-group">
																<input name="email" required type="email" class="form-control" placeholder="Your Email">
															</div>
															<div class="form-group">
																<textarea name="message" required class="form-control" placeholder="Your Message"></textarea>
															</div>
															<button id="company_query" class="button"><?php esc_html_e("Submit","jemployee"); ?></button>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!----div class="job-location">
										<h4><?php //esc_html_e("Our Location","jemployee"); ?></h4>
										<div id="map-area">
											<div class="cp-map" id="cp-map" data-lat="40.713355" data-lng="-74.005535" data-zoom="10"></div>
										</div>
									</div!--->
									<div class="share-job-post">
										<?php 
											$social_link = wp_parse_args($social_link,array('facebook'=>'','twitter'=>'','google'=>''));
										?>
										<span class="share"><?php esc_html_e("Social Profile:","jemployee"); ?></span>
										<a href="<?php print esc_url($social_link['facebook']); ?>"><i class="fab fa-facebook-f"></i></a>
										<a href="<?php print esc_url($social_link['twitter']); ?>"><i class="fab fa-twitter"></i></a>
										<a href="<?php print esc_url($social_link['google']); ?>"><i class="fab fa-google-plus-g"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<?php endwhile;endif; ?>
    <!-- Company Details End -->
<?php
get_footer();