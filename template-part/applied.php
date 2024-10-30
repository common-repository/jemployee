<?php 
	$current_user = wp_get_current_user();	
	$job_type = array(1=>'Full time',2=>'Part Time',3=>'Freelance',4=>'Temporary');
	$applied = Jy_helper::applied_result($current_user->ID);
	
?>
	<div class="alice-bg section-padding-bottom">
		<div class="container no-gliters">
			<div class="row no-gliters">
				<div class="col">
					<div class="dashboard-container">
						<div class="dashboard-content-wrapper">
							<div class="dashboard-bookmarked">
								<h4 class="bookmark-title"><span id="total_job"><?php print count($applied); ?></span> <?php esc_html_e("Job Applied","jemployee"); ?></h4>
								<div class="bookmark-area">
									<?php 
										$k = array_keys($applied);
										$k = !empty($k)?$k:array(0);
										$args = array(
											'post_type' => 'jy_job',
											'post_status' => 'publish',
											'posts_per_page'=> -1,
											'post__in'=> $k,
										);
										$query = new WP_Query( $args );
										if($query->have_posts()): 
											while($query->have_posts()): 
												$query->the_post();
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
												$job_type_v = $job_type[get_post_meta($j_id,'jy_job_type',true)];
									?>
									<div class="job-list" id="job_list_<?php print esc_attr($j_id); ?>">
										<div class="thumb">
											<a href="#">
												<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt='<?php esc_attr_e("img"); ?>'>
											</a>
										</div>
										<div class="body">
											<div class="content">
												<h4><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h4>
												<div class="info">
													<span class="company"><a href="<?php print get_the_permalink($company_id); ?>"><i data-feather="briefcase"></i><?php print get_the_title($company_id); ?></a></span>
													<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
													<span class="job-type <?php print Jy_helper::string_to_class($job_type_v); ?>"><a href="#"><i data-feather="clock"></i><?php print esc_html($job_type_v); ?></a></span>
												</div>
											</div>
											<div class="more">
												<a href="#" data-job_id="<?php print esc_attr($j_id); ?>" class="apply-remove"><i class="fas fa-times"></i></a>
												<p class="deadline"><?php esc_html_e("Deadline:","jemployee"); ?> <?php print esc_html($dead_line); ?></p>
											</div>
										</div>
									</div>
								<?php endwhile;wp_reset_postdata();endif; ?>
								</div>
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