<?php 
	$current_user = wp_get_current_user();
	$p_id = get_user_meta($current_user->ID,'job_seeker_id',true);
	$view = get_post_meta($p_id,'employee_view',true);
	$view_count = !empty($view)?count($view):0;

	
	$chart = Jy_helper::generate_profile_view_chart($view);
	
?>
<div class="alice-bg section-padding-bottom">
    <div class="container no-gliters">
        <div class="row no-gliters">
			<div class="col">
				<div class="dashboard-container">
					<div class="dashboard-content-wrapper">
						<div class="dashboard-section user-statistic-block">
							<div class="user-statistic">
								<i data-feather="pie-chart"></i>
								<h3><?php print esc_html($view_count); ?></h3>
								<span><?php esc_html_e("Companies Viewed","jobyerd"); ?></span>
							</div>
							<div class="user-statistic">
								<i data-feather="briefcase"></i>
								<h3><?php print count(Jy_helper::applied_result($current_user->ID)); ?></h3>
								<span><?php esc_html_e("Applied Jobs","jobyerd"); ?></span>
							</div>
							<div class="user-statistic">
								<i data-feather="heart"></i>
								<h3><?php print count(Jy_helper::bookmar_result($current_user->ID)); ?></h3>
								<span><?php esc_html_e("Favourite Jobs","jobyerd"); ?></span>
							</div>
						</div>
						<div class="dashboard-section dashboard-view-chart">
							<canvas id="view-chart" width="400" height="200"></canvas>
						</div>
						
					</div>
					<div class="dashboard-sidebar">
						<?php Jy_helper::get_menu($current_user); ?>
					</div>
				</div>
				<script>
					$(document).ready(function(){
						generate_chart('view-chart',<?php print $chart['labels']; ?>,<?php print $chart['data']; ?>,'Companies Viewed');
					})
				</script>
			</div>
        </div>
    </div>
</div>