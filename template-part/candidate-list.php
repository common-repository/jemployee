<?php 
get_header();
?>
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="container">
        <div class="row">
			<div class="col-md-6">
				<div class="breadcrumb-area">
					<h1><?php esc_html_e("Candidate","jobyerd"); ?></h1>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#"><?php esc_html_e("Home","jobyerd"); ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php esc_html_e("Candidate Listing","jobyerd"); ?></li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col-md-6">

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
		'post_type' 		=> 'jy_seeker',
		
	);
	$url = get_the_permalink();
	$p_id = isset($_GET['pd'])?$_GET['pd']:1;
	$link = $url."?pd=$p_id";
	$gender = Jy_helper::gender();
	
	$get_link = $filter_job_category = $filter_job_g = '';
	if(isset($_GET['filter_job_g']) && !empty($_GET['filter_job_g'])){
		$args['meta_query'][] =  array('key'=> 'gender','value' => $_GET['filter_job_g']);
		$filter_job_g = $_GET['filter_job_g'];
		$get_link .= '&filter_job_g='.$_GET['filter_job_g'];
	}

	if(isset($_POST['filter_job_category']) && !empty($_POST['filter_job_category'])){
		$args['meta_query'][] =  array('key'=> 'jy_category','value' => $_POST['filter_job_category']);
		$get_link .= '&filter_job_category='.$_REQUEST['filter_job_category'];
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
				<div class="candidate-container">
					<div class="filtered-candidate-wrapper">
						<div class="candidate-view-controller-wrapper">
							<div class="candidate-view-controller">
								<div class="controller list active">
									<i data-feather="menu"></i>
								</div>
								<div class="controller grid">
									<i data-feather="grid"></i>
								</div>
							
							</div>
							<div class="showing-number">
								<span><?php  printf('Showing %d of %d Employee',$count,$q->found_posts); ?></span>
							</div>
						</div>
						<div class="candidate-filter-result">
							<?php 
								if($q->have_posts()): 
								while($q->have_posts()): 
									$q->the_post();
									$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
									$j_id = get_the_ID();
									$location = get_post_meta($j_id, 'location',true);
									$jy_category = get_post_meta($j_id, 'jy_category',true);
									$author_id  = get_the_author_meta( 'ID' ); 
									$img = get_user_meta($author_id,'jy_em_image',true);
									if( $img ) {
										$image_thumb = wp_get_attachment_thumb_url( $img );
									}
									$cterm = Jy_helper::get_term($jy_category,'jy_category');
							?>
							<div class="candidate">
								<div class="thumb">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid" alt='<?php esc_attr_e("img"); ?>'>
									</a>
								</div>
								<div class="body">
									<div class="content">
										<h4><a href="#"><?php the_title(); ?> </a></h4>
										<div class="info">
											<span class="work-post"><a href=""><i data-feather="check-square"></i><?php print esc_html($cterm->name); ?></a></span>
											<span class="location"><a href="#"><i data-feather="map-pin"></i><?php print esc_html($location); ?></a></span>
										</div>
									</div>
									<div class="more">
										<div class="button-area">
											<a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e("View Resume","jobyerd"); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php endwhile;wp_reset_postdata();endif; ?>
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
								$markup .= (1<$currentpage)?'<a class="prev page-numbers" href="'.$link.($currentpage-1).'"><span class="screen-reader-text">'.esc_html__("Prev page","jobyerd").'</span><span aria-hidden="true">'.$prev.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$prev.'</span>';
								$markup .= '<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">'.$currentpage.' of <span class="total-pages">'.esc_html($total_page).'</span></span></span>';
								$markup .= ($currentpage < $total_page)?'<a class="next page-numbers" href="'.$link.($currentpage+1).'"><span class="screen-reader-text">'.esc_html__("Next page","jobyerd").'</span><span aria-hidden="true">'.$next.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$next.'</span>';
							}
							$markup .= '</div>';
							print $markup
						?>
					</div>
					<div class="job-filter-wrapper">
						<form action="<?php print esc_attr($link); ?>" method="post">
							<div class="job-filter-dropdown category">
								<select onchange="this.form.submit()" name="filter_job_category" class="selectpicker">
									<option value="" selected><?php esc_html_e("Category","jobyerd"); ?></option>
									<?php foreach($term as $key => $item): ?>
										<?php $st = ($key==$_POST['filter_job_category'])?'selected':''; ?>
										<option <?php print esc_attr($st); ?> value="<?php print esc_html($key); ?>"><?php print esc_html($item); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</form>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 

get_footer();