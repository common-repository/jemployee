<?php 
	$current_user = wp_get_current_user();	
	$job_type = array(1=>'Full time',2=>'Part Time',3=>'Freelance',4=>'Temporary');
	$job_list = Jy_helper::get_list_data(array('posts_per_page'=>-1,'author' => $current_user->ID,'post_type' => 'jy_job','post_status' =>'any'));
	$jb_select = isset($_POST['job_check'])?$_POST['job_check']:key($job_list);
	$act = $_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'].'?page_slug=applied&job_check='.$jb_select;
	$em_list = Jy_helper::get_applied_em_list($jb_select);

	?>
	<div class="alice-bg section-padding-bottom">
		<div class="container no-gliters">
			<div class="row no-gliters">
				<div class="col">
					<div class="dashboard-container">
						<div class="dashboard-content-wrapper">
							<form method="post" name="test" action="<?php print esc_url($act); ?>">
								<div class="listing-filter">
									<select onchange="this.form.submit()" name="job_check" class="selectpicker">
										<?php foreach($job_list as $key => $item): ?>
											<?php $selected = ($jb_select==$key)?'selected':''; ?>
											<option <?php print $selected; ?> value="<?php print $key; ?>"><?php print esc_html($item); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</form>
							<div class="manage-candidate-container">
								
								<table class="table">
									<thead>
										<tr>
											<th><?php esc_html_e("Job Title","jemployee");?></th>
											<th><?php esc_html_e("Shortlist","jemployee");?></th>
											<th class="action"><?php esc_html_e("Action","jemployee");?></th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(!empty($em_list)):
											foreach($em_list as $item):
											$em_id = get_user_meta($item->employee_id,'job_seeker_id',true);
											$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
											$user_img = get_user_meta($item->employee_id,'jy_em_image',true);
											if( $user_img ) {
												$image_thumb = wp_get_attachment_thumb_url( $user_img );
											}
											$filed_list = get_post_meta($em_id,'jy_category',true);
											$cat_data = Jy_helper::get_term($filed_list,'jy_category');
											$location = get_post_meta($em_id,'location',true);
											$upload_cv = get_post_meta($em_id,'jy_upload_cv',true);
										?>
										<tr class="candidates-list">
											<td class="title">
												<div class="thumb">
													<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt="">
												</div>
												<div class="body">
													<h5><a href="<?php print get_the_permalink($em_id); ?>"><?php print get_the_title($em_id); ?></a></h5>
													<div class="info">
														<span class="designation"><a href="#"><i data-feather="check-square"></i><?php print esc_html($cat_data->name); ?></a></span>
														<span class="location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
													</div>
												</div>
											</td>
											<td class="status">
												<?php if($item->shortlisted==1): ?>
													Shortlisted
												<?php else: ?>
													<a href="#" data-id="<?php print esc_attr($item->id); ?>"  title="Shortlisted" class="shortlisted"><?php esc_html_e("Add Shortlist","jemployee"); ?></a>
												<?php endif; ?>
											</td>
											<td class="action">
												<a href="<?php print wp_get_attachment_url($upload_cv); ?>" download class="download"><i data-feather="download"></i></a>
												<a href="#" class="inbox"><i data-feather="mail"></i></a>
												<a href="#" data-id="<?php print esc_attr($item->id); ?>"  class="remove_application" class="remove"><i data-feather="trash-2"></i></a>
											</td>
										</tr>
										<?php endforeach;endif; ?>
									</tbody>
								</table>
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