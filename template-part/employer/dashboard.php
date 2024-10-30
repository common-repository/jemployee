<?php 
	$current_user = wp_get_current_user();
	$total_job = Jy_helper::get_employer_job_post($current_user->ID);
	$chart = Jy_helper::generate_profile_view_chart($total_job);
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
								<h3><?php print count($total_job); ?></h3>
								
								<span><?php esc_html_e("Total Job Posted","jemployee"); ?></span>
							</div>
							<div class="user-statistic">
								<i data-feather="briefcase"></i>
								<h3><?php print count(Jy_helper::get_total_application($total_job)); ?></h3>
								<span><?php esc_html_e("Application Submit","jemployee"); ?></span>
							</div>
							<div class="user-statistic">
								<i data-feather="heart"></i>
								<h3><?php print count(Jy_helper::get_total_shortlisted($total_job)); ?></h3>
								<span><?php esc_html_e("Call for interview","jemployee"); ?></span>
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
						generate_chart('view-chart',<?php print $chart['labels']; ?>,<?php print $chart['data']; ?>,'Total Job Posted');
					})
				</script>
			</div>
        </div>
    </div>
</div>