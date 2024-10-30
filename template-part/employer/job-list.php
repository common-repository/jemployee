<?php 
	$current_user = wp_get_current_user();
	$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
	$user_img = get_user_meta($current_user->ID,'jy_em_image',true);
	if( $user_img ) {
		$image_thumb = wp_get_attachment_thumb_url( $user_img );
	}
	$job_type_arr = array(1=>'Full time',2=>'Part Time',3=>'Freelance',4=>'Temporary');
	$paged = isset($_GET['pd']) ? $_GET['pd'] : 1;
	$perpage = 10;
	$currentpage = $paged;
	$offset = ($paged>1)?$perpage*($paged-1):0;
	$query = new WP_Query( array( 'offset'=>$offset,'posts_per_page'=>$perpage,'author' => $current_user->ID,'post_type' => 'jy_job','post_status' =>'any'));
	$count_post = wp_count_posts('jy_job');
	$total = $count_post->publish+$count_post->pending;
	$edit_url = $_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']."?page_slug=edit-job&job_id=";
	$edit_url = esc_url($edit_url);
	?>
	<div class="alice-bg section-padding-bottom">
		<div class="container no-gliters">
			<div class="row no-gliters">
				<div class="col">
					<div class="dashboard-container">
						<div class="dashboard-content-wrapper">
							<div class="manage-job-container">
								<table class="table">
									<thead>
										<tr>
											<th><?php esc_html_e("Job Title","jemployee"); ?></th>
											<th><?php esc_html_e("Applications","jemployee"); ?></th>
											<th><?php esc_html_e("Deadline","jemployee"); ?></th>
											<th><?php esc_html_e("Status","jemployee"); ?></th>
											<th class="action"><?php esc_html_e("Action","jemployee"); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php if($query->have_posts()): while($query->have_posts()): $query->the_post(); ?>
											<?php 
												$id = get_the_ID();
												global $post;
												$status = ($post->post_status =='publish')?'active':'pending';
												$location = get_post_meta($id,'jy_job_location',true);
												$job_type  = get_post_meta($id,'jy_job_type',true);
												$dedline  = get_post_meta($id,'jy_job_date',true);
												$location = ($location!='')?$location:'---';
												$job_type = ($job_type!='')?$job_type_arr[$job_type]:'---';
												$dedline = ($dedline!='')?date('F d, Y',strtotime($dedline)):'---';
												$total_ap = Jy_helper::application_count_by_job($id);
											?>
											<tr class="job-items">
												<td class="title">
													<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
													<div class="info">
														<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
														<span class="job-type full-time"><a href="#"><i data-feather="clock"></i><?php print esc_html($job_type); ?></a></span>
													</div>
												</td>
												<td class="application"><a href="#"><?php print $total_ap; ?> Application(s)</a></td>
												<td class="deadline"><?php print esc_html($dedline); ?></td>
												<td class="status <?php print $status; ?>"><?php print ucfirst($status); ?></td>
												<td class="action">
													<a href="<?php the_permalink(); ?>" class="preview"><i data-feather="eye"></i></a>
													<a href="<?php print $edit_url.$id; ?>" class="edit"><i data-feather="edit"></i></a>
													<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
														<input name="action" type="hidden" value="delete_job_post">
														<input name="id" type="hidden" value="<?php print $id; ?>">
														<input type="hidden" name="front_user" value="<?php print wp_create_nonce( 'front_user' ); ?>">
														<button href="#" class="remove"><i data-feather="trash-2"></i></button>
													</form>
												</td>
											</tr>
										<?php endwhile;wp_reset_postdata();endif; ?>
									</tbody>
								</table>
								<div class="pagination-list text-center">
									
									<nav class="navigation pagination">
										<div class="nav-links">
											<?php print Jy_helper::pagination(array('total'=>$total,'currentpage'=>$currentpage,'page'=>'')); ?>
										</div>
									</nav>                
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