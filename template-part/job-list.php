<?php 
get_header();
?>
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="container">
        <div class="row">
			<div class="col-md-6">
				<div class="breadcrumb-area">
					<h1><?php esc_html_e("Job Listing","jemployee"); ?></h1>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#"><?php esc_html_e("Home","jemployee"); ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php esc_html_e("Job Listing","jemployee"); ?></li>
						</ol>
					</nav>
				</div>
			</div>
        </div>
    </div>
</div>
<?php 
	$paged = isset($_GET['pd']) ? $_GET['pd'] : 1;
	$perpage = 10;
	$currentpage = $paged;
	$offset = ($paged>1)?$perpage*($paged-1):0;
	$args = array(
		'offset'=>$offset,
		'posts_per_page' 	=>$perpage,
		'post_type' 		=> 'jy_job',
		'meta_query' 		=> array(
			'relation' 		=> 'AND',
			array(
				'key' 	=> 'jy_job_date',
				'value' => date('Y-m-d',current_time( 'timestamp')),
				'compare' => '>=',
			)
		)
	);
	$url = get_the_permalink();
	$p_id = isset($_GET['pd'])?$_GET['pd']:1;
	$link = $url."?pd=$p_id";
	$job_type = 	Jy_helper::job_type();
	$experience = 	Jy_helper::experience();
	$date_s = 		Jy_helper::date_data();
	$gender = 		Jy_helper::gender();
	
	$get_link = $filter_job_type = $filter_job_g = $filter_job_exp= $filter_job_date = '';
	if(isset($_GET['filter_job_type']) && !empty($_GET['filter_job_type'])){
		$args['meta_query'][] =  array('key'=> 'jy_job_type','value' => $_GET['filter_job_type']);
		$filter_job_type = $_GET['filter_job_type'];
		$get_link .= '&filter_job_type='.$_GET['filter_job_type'];
	}
	if(isset($_GET['filter_job_g']) && !empty($_GET['filter_job_g'])){
		$args['meta_query'][] =  array('key'=> 'jy_job_gender','value' => $_GET['filter_job_g']);
		$filter_job_g = $_GET['filter_job_g'];
		$get_link .= '&filter_job_g='.$_GET['filter_job_g'];
	}
	if(isset($_GET['filter_job_exp']) && !empty($_GET['filter_job_exp'])){
		$args['meta_query'][] =  array('key'=> 'jy_job_experience','value' => $_GET['filter_job_exp']);
		$filter_job_exp = $_GET['filter_job_exp'];
		$get_link .= '&filter_job_exp='.$_GET['filter_job_exp'];
	}
	if(isset($_GET['filter_job_date']) && !empty($_GET['filter_job_date'])){
		$args['date_query'] = array(array('after'=>$_GET['filter_job_date'],'inclusive' => true));
		$filter_job_date = $_GET['filter_job_date'];
		$get_link .= '&filter_job_date='.$_GET['filter_job_date'];
	}
	if(isset($_REQUEST['filter_job_category']) && !empty($_REQUEST['filter_job_category'])){
		$args['meta_query'][] =  array('key'=> 'jy_job_category','value' => $_REQUEST['filter_job_category']);
		$get_link .= '&filter_job_category='.$_REQUEST['filter_job_category'];
	}
	if(isset($_REQUEST['mx_s']) || isset($_REQUEST['mi_s'])){
		$args['meta_query'][] =  array('key'=> 'jy_job_salary','compare'=>'BETWEEN','type'=>'NUMERIC','value' => array($_REQUEST['mx_s'],$_REQUEST['mi_s']));
		$max = $_REQUEST['mx_s'];
		$min = $_REQUEST['mi_s'];
		$get_link .= '&mx_s='.$max.'&mi_s='.$min;
	}
	$term = Jy_helper::get_cat_list('jy_category');
	$q =  new WP_Query( $args );
	$total = $q->found_posts;
	$count = $q->post_count;
 ?>
<div class="alice-bg section-padding-bottom">
   <div class="container">
		<div class="row no-gutters">
			<div class="col">
				<div class="job-listing-container">
					<div class="filtered-job-listing-wrapper">
						<div class="job-view-controller-wrapper">
							<div class="job-view-controller">
								<div class="controller list active">
								  <i data-feather="menu"></i>
								</div>
								<div class="controller grid">
								  <i data-feather="grid"></i>
								</div>
							</div>
							<div class="showing-number">
								<span><?php  printf('Showing %d of %d Jobs',$count,$q->found_posts); ?></span>
							</div>
						</div>
						<div class="job-filter-result">
							<?php 
								$sallery = array(0);
								if($q->have_posts()): 
								
								while($q->have_posts()): 
									$q->the_post();
									$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
									$j_id = get_the_ID();
									$location = get_post_meta($j_id, 'jy_job_location',true);
									$dead_line = get_post_meta($j_id,'jy_job_date',true);
									$dead_line = ($dead_line!='')?date('F d, Y',strtotime($dead_line)):'---';
									$author_id  = get_the_author_meta( 'ID' ); 
									$company_id = get_user_meta($author_id,'job_employer_id',true);
									$logo = get_post_meta($company_id,'jy_em_image',true);
									$sallery[] = get_post_meta($j_id,'jy_job_salary',true);
									$job_type_v = get_post_meta($j_id,'jy_job_type',true);
									$job_type_v = isset($job_type[$job_type_v])?$job_type[$job_type_v]:'';
									if( $logo ) {
										$image_thumb = wp_get_attachment_thumb_url( $logo );
									}
							?>
							<div class="job-list">
								<div class="thumb">
								  <a href="<?php esc_url(the_permalink()); ?>">
									<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt='<?php esc_attr_e("img"); ?>'>
								  </a>
								</div>
								<div class="body">
									<div class="content">
										<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a></h4>
										<div class="info">
											<span class="company"><a href="<?php print get_the_permalink($company_id); ?>"><i data-feather="briefcase"></i><?php print get_the_title($company_id); ?></a></span>
											<span class="office-location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
											<span class="job-type temporary"><a href="#"><i data-feather="clock"></i><?php print esc_html($job_type_v); ?></a></span>
										</div>
									</div>
									<div class="more">
										<div class="buttons">
											<a data-job_id="<?php print esc_attr($j_id); ?>" href="#" class="button jy-online-apply"><?php esc_html_e("Apply Now","jemployee"); ?></a>
											<a data-job_id="<?php print esc_attr($j_id); ?>" href="#" class="favourite jy-bookmark"><i data-feather="heart"></i></a>
										</div>
										<p class="deadline"><?php esc_html_e("Deadline: ","jemployee"); ?><?php print esc_html($dead_line); ?></p>
									</div>
								</div>
							</div>
						  
							<?php endwhile;wp_reset_postdata();endif; ?>
						 
						  <?php 
								if(!isset($_REQUEST['mx_s'])){
									$max = max($sallery);
									$max = !empty($max)?$max:0;
								}
								if(!isset($_REQUEST['mi_s'])){
									$min = min($sallery);
									$min = !empty($min)?$min:0;
								}
								
							?>
						</div>
						<?php 
							$total_page = ceil($total/10);
							if($currentpage>$total_page){
								$currentpage = $total_page;
							}
							$next = '<i class="fas fa-angle-right"></i>';
							$prev = '<i class="fas fa-angle-left"></i>';
							$markup = '';
							$link = $link.$get_link;
							$link = esc_url($link);
							$markup .= '<div class="nav-links">';
							if($total_page>0){
								$markup .= (1<$currentpage)?'<a class="prev page-numbers" href="'.$link.($currentpage-1).'"><span class="screen-reader-text">Prev page</span><span aria-hidden="true">'.$prev.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$prev.'</span>';
								$markup .= '<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">'.$currentpage.' of <span class="total-pages">'.$total_page.'</span></span></span>';
								$markup .= ($currentpage < $total_page)?'<a class="next page-numbers" href="'.$link.($currentpage+1).'"><span class="screen-reader-text">Next page</span><span aria-hidden="true">'.$next.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$next.'</span>';
							}
							$markup .= '</div>';
							print $markup
						?>
					</div>
					<div class="job-filter-wrapper">
						
						<form action="<?php print esc_attr($link); ?>" method="post">
							<div class="job-filter-dropdown category">
								<select onchange="this.form.submit()" name="filter_job_category" class="selectpicker">
										<option value="" selected><?php esc_html_e("Category","jemployee"); ?></option>
									<?php foreach($term as $key => $item): ?>
										<?php $st = ($key==$_POST['filter_job_category'])?'selected':''; ?>
										<option <?php print esc_html($st); ?> value="<?php print esc_html($key); ?>"><?php print esc_html($item); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</form>
						<div data-id="job-type" class="job-filter job-type">
							<h4 class="option-title"><?php esc_html_e("Job Type","jemployee"); ?></h4>
							<ul>
								<?php
									$remove = '&#038;filter_job_type='.$filter_job_type;
									$m_link = str_replace($remove,'',$link);
									foreach($job_type as $key => $item): 
										$dlink = '&filter_job_type='.$key;
										$cl = Jy_helper::string_to_class($item);
								?>
									<li class="<?php print esc_attr($cl); ?>"><i data-feather="clock"></i><a href="<?php print esc_url($m_link.$dlink); ?>" data-attr="Full Time"><?php print esc_html($item); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div data-id="experience" class="job-filter experience">
							<h4 class="option-title"><?php esc_html_e("Experience","jemployee"); ?></h4>
							<ul>
								<?php
									$remove = '&#038;filter_job_exp='.$filter_job_exp;
									$m_link = str_replace($remove,'',$link);
									foreach($experience as $key=>$item): 
									$dlink = '&filter_job_exp='.$key;
								?>
								<li><a href="<?php print esc_url($m_link.$dlink); ?>" data-attr="Fresh"><?php print esc_html($item); ?></a></li>
								
								<?php endforeach; ?>
							</ul>
						</div>
						<form method="post" action="<?php print $link; ?>">
							<div class="job-filter">
								<h4 class="option-title"><?php esc_html_e("Salary Range","jemployee"); ?></h4>
								<div class="price-range-slider">
									<div class="nstSlider sallery_change" data-range_min="<?php print $min; ?>" data-range_max="<?php print $max; ?>" 
										data-cur_min="<?php print $min; ?>"    data-cur_max="<?php print $max; ?>">
										<div class="bar"></div>
										<div class="leftGrip"></div>
										<div class="rightGrip"></div>
										<div class="grip-label">
											<span class="leftLabel"></span>
											<span class="rightLabel"></span>
										</div>
										<input type="hidden" class="mx_s" value="<?php print $max; ?>" name="mx_s">
										<input type="hidden" class="mi_s" value="<?php print $min; ?>" name="mi_s">
										<button type="submit"><?php esc_attr_e('Search','jemployee'); ?></button>
									</div>
								</div>
							</div>
						</form>
				
						<div data-id="post" class="job-filter post">
							<h4 class="option-title"><?php esc_html_e("Date Posted","jemployee"); ?></h4>
							<ul>
								<?php 
									$remove = '&#038;filter_job_date='.$filter_job_date;
									$m_link = str_replace($remove,'',$link);
									foreach($date_s as $key => $item): 
									$dlink = '&filter_job_date='.$key;
								?>
									<li><a href="<?php print esc_url($m_link.$dlink); ?>"><?php print esc_html($item); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
				
						<div data-id="gender" class="job-filter gender">
							<h4 class="option-title"><?php esc_html_e("Gender","jemployee"); ?></h4>
							<ul>
							<?php 
								$remove = '&#038;filter_job_g='.$filter_job_g;
								$m_link = str_replace($remove,'',$link);
								foreach($gender as $key=>$item): 
								$dlink = '&filter_job_g='.$key;
							?>
								<li><a href="<?php  print esc_url($m_link.$dlink); ?>" data-attr="Male"><?php print esc_html($item); ?></a></li>
							<?php endforeach; ?>
							</ul>
						</div>
						<a class="jy-clear" href="<?php print $url; ?>"><?php esc_html_e('Clear','jemployee'); ?></a>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<?php 

get_footer();