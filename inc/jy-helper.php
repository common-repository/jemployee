<?php 

class Jy_helper {
	public static function load_template($template){
		if ( $overridden_template = locate_template( $template ) ) {
			load_template( $overridden_template );
		} else {
			load_template( dirname(__DIR__) . '/template-part/'.$template );
		}
	}
	
	public static function get_page($slug){
		switch($slug){
			case 'edit-profile': 
				return 'edit-profile.php';
			break;
			case 'edit-resume': 
				return 'edit-resume.php';
			break;
			case 'post-job': 
				return 'post-job.php';
			break;
			case 'job-list': 
				return 'job-list.php';
			break;
			case 'edit-job': 
				return 'edit-job.php';
			break;
			case 'bookmark': 
				return 'bookmark.php';
			break;
			case 'applied': 
				return 'applied.php';
			break;
			case 'resume': 
				return 'resume.php';
			break;
			default:
				return 'dashboard.php';
		}
	}
	
	public static function get_menu($current_user){
		$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
		$user_img = get_user_meta($current_user->ID,'jy_em_image',true);
		if( $user_img ) {
			$image_thumb = wp_get_attachment_thumb_url( $user_img );
		}
		$user_info = get_userdata($current_user->ID);
		$page_url = get_the_permalink(get_option('jy_jobyar_page')).'?page_slug=';
		$user_role = current($current_user->roles);
		if($user_role == 'jy_employer'){
			$employer_identity = get_user_meta($current_user->ID,'job_employer_id',true);
			$user_img = get_post_meta($employer_identity,'jy_em_image',true);
			if( $user_img ) {
				$image_thumb = wp_get_attachment_thumb_url( $user_img );
			}
		}
		$page_slug = 'dashboard';
		if(isset($_GET['page_slug'])){
			$page_slug = $_GET['page_slug'];
		}
		?>
			<div class="user-info">
				<div class="thumb">
					<img src="<?php print esc_url($image_thumb); ?>" class="img-fluid"  alt='<?php esc_attr_e("img"); ?>'>
				</div>
				<div class="user-body">
					<h5><?php print $user_info->first_name;?></h5>
					<span>@<?php print $user_info->user_login ?></span>
				</div>
			</div>
			<div class="dashboard-menu">
                <ul>
                    <li class="<?php if($page_slug=='dashboard') print 'active'; ?>"><i class="fas fa-home"></i><a href="<?php print esc_url($page_url.'dashboard'); ?>"><?php esc_html_e("Dashboard","jemployee"); ?></a></li>
                    <li class="<?php if($page_slug=='edit-profile') print 'active'; ?>"> <i class="fas fa-user"></i><a href="<?php print esc_url($page_url.'edit-profile'); ?>"><?php esc_html_e("Edit Profile","jemployee"); ?></a></li>
                    <?php if($user_role == 'jy_employer'): ?>
						<li class="<?php if($page_slug=='post-job') print 'active'; ?>"><i class="fas fa-plus-square"></i><a href="<?php print esc_url($page_url.'post-job'); ?>"><?php esc_html_e("Post Job","jemployee"); ?></a></li>
						<li class="<?php if($page_slug=='job-list') print 'active'; ?>"><i class="fas fa-briefcase"></i><a href="<?php print esc_url($page_url.'job-list'); ?>"><?php esc_html_e("Manage Job","jemployee"); ?></a></li>
					<?php endif; ?>
					<?php if($user_role == 'jy_employee'): ?>
						<li class="<?php if($page_slug=='edit-resume') print 'active'; ?>"><i class="fas fa-edit"></i><a href="<?php print esc_url($page_url.'edit-resume'); ?>"><?php esc_html_e("Edit Resume","jemployee"); ?></a></li>
						<li class="<?php if($page_slug=='resume') print 'active'; ?>"><i class="fas fa-file-alt"></i><a href="<?php print esc_url($page_url.'resume'); ?>"><?php esc_html_e("Resume","jemployee"); ?></a></li>
						<li class="<?php if($page_slug=='bookmark') print 'active'; ?>"><i class="fas fa-heart"></i><a href="<?php print esc_url($page_url.'bookmark'); ?>"><?php esc_html_e("BookMark","jemployee"); ?></a></li>
					<?php endif; ?>
					<li class="<?php if($page_slug=='applied') print 'active'; ?>"><i class="fas fa-check-square"></i><a href="<?php print esc_url($page_url.'applied'); ?>"><?php esc_html_e("Applied Job","jemployee"); ?></a></li>
			   </ul>
                <ul class="delete">
                    <li><i class="fas fa-power-off"></i><a href="<?php echo esc_url(wp_logout_url()); ?>"><?php esc_html_e("Logout","jemployee"); ?></a></li>
                    <li><i class="fas fa-trash-alt"></i><a href="#" data-toggle="modal" data-target="#modal-delete"><?php esc_html_e("Delete Profile","jemployee"); ?></a></li>
                    <!-- Modal -->
                    <div class="modal fade modal-delete" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-body">
									<h4><i data-feather="trash-2"></i><?php esc_html_e("Delete Account","jemployee"); ?></h4>
									<p><?php esc_html_e("Are you sure! You want to delete your profile. This can't be undone!","jemployee"); ?></p>
									<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
										<?php $jemployee_nonce = wp_create_nonce('jemployee_ajax_nonce'); ?>
										<input type="hidden" name="action" value="jy_delete_user">
										<input type="hidden" name="jemployee_nonce" value="<?php echo esc_attr($jemployee_nonce); ?>" />
										<div class="form-group">
											<input name="user_pass" required type="password" class="form-control" placeholder="Enter password">
										</div>
										<div class="buttons">
											<button class="delete-button"><?php esc_html_e("Delete User","jemployee"); ?></button>
											<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
										</div>
										<div class="form-group form-check">
											<input name="jy_delete_per" type="checkbox" class="form-check-input" checked="">
											<label class="form-check-label" for="exampleCheck1"><?php esc_html_e("You accepts our","jemployee"); ?> <a href="<?php print get_permalink(get_option('jy_terms_page')); ?>"><?php esc_html_e("Terms and Conditions","jemployee"); ?></a> <?php esc_html_e("and","jemployee"); ?> <a href="<?php print get_permalink(get_option('jy_privacy_page')); ?>"><?php esc_html_e("Privacy Policy","jemployee"); ?></a></label>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </ul>
            </div>
		<?php 
		
	}
	
	public static function dropdown($name,$list,$selected,$args=array()){
		$defaults = array('class'=>'','id'=>'');
		$args = wp_parse_args( $args, $defaults );
		$markup = '';
		$id = !empty($args['id'])?'id = "'.$args['id'].'"':'';
		if(!empty($list)){
			$markup = '<select '.$id .' class="'.$args['class'].'"  name="'.$name.'">';
			foreach($list as $key=> $item){
				$std = ($key==$selected)?"selected":"";
				$markup .= '<option '.$std.' value="'.$key.'">'.$item.'</option>';
			}
			$markup .= '</select>';
		}
		return $markup;
	}
	
	public static function get_term($id,$cat_name){
		$ob = array('name'=>'');
		$term = get_term_by('id', $id, $cat_name);
		if($term){
			return  $term;
		}
		return (object)$ob;
	}
	
	public static function _editor($cont,$id,$setting){
		$defalut = array(
			'media_buttons'=>false,
			'textarea_rows'=>4,
			'quicktags'=>false,
			'tinymce'=> array(
				'toolbar1'=> 'formatselect,bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,bullist,numlist,undo,redo',
			)
		);
		$setting = wp_parse_args($setting,$defalut);
		wp_editor( $cont, $id ,$setting);
	}
	
	public static function pagination($args=array()) {
		extract($args);
        $total_page = ceil($total/10);
        if($currentpage>$total_page){
            $currentpage = $total_page;
        }
        $next = '<i class="fas fa-angle-right"></i>';
        $prev = '<i class="fas fa-angle-left"></i>';
        $markup = '';
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];
		$slug = isset($_GET['page_slug'])?$_GET['page_slug']:'';
		$p_id = isset($_GET['pd'])?$_GET['pd']:'';
        $link = $url."?page_slug=$slug&pd=";
		$link = esc_url($link);
        $markup .= '<div class="nav-links">';
        if($total_page>0){
            $markup .= (1<$currentpage)?'<a class="prev page-numbers" href="'.$link.($currentpage-1).'"><span class="screen-reader-text">Prev page</span><span aria-hidden="true">'.$prev.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$prev.'</span>';
            $markup .= '<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">'.$currentpage.' of <span class="total-pages">'.$total_page.'</span></span></span>';
            $markup .= ($currentpage < $total_page)?'<a class="next page-numbers" href="'.$link.($currentpage+1).'"><span class="screen-reader-text">Next page</span><span aria-hidden="true">'.$next.'</span></a>':'<span class="tablenav-pages-navspan" aria-hidden="true">'.$next.'</span>';
        }
        $markup .= '</div>';
        return $markup;
	}
	
	public static function post_author_validation($id){
		$user_id = get_current_user_id();
		$post_tmp = get_post($id);
		$post_tmp->post_author;
		if($post_tmp->post_author==$user_id){
			return true;
		}
		return true;
	}
	
	public static function bookmar_result($employee_id){
		global $wpdb;
		$table = $wpdb->prefix.'jy_bookmark';
		return $wpdb->get_results( "SELECT job_id FROM $table WHERE employee_id=$employee_id", OBJECT_K );
	}
	
	public static function applied_result($employee_id){
		global $wpdb;
		$table = $wpdb->prefix.'jy_apply';
		return $wpdb->get_results( "SELECT job_id FROM $table WHERE employee_id=$employee_id", OBJECT_K );
	}
	
	public static function string_to_class($str){
		$str = str_replace(' ','-',$str);
		return strtolower($str);
	}
	
	public static function remove_hp_($str){
		$str = str_replace('-',' ',$str);
		return ucfirst($str);
	}
	
	public static function get_list_data($array){
		$list = array();
		$query = new WP_Query($array);
		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post(); 
				$list[get_the_ID()] = get_the_title();
			}	
		}
		return $list;
	}
	
	public static function get_applied_em_list($job_id){
		if(!empty($job_id)){
			global $wpdb;
			$table = $wpdb->prefix.'jy_apply';
			return $wpdb->get_results( "SELECT * FROM $table WHERE application_de = 0 and job_id=$job_id", OBJECT_K );
		}
		return array();
	}
	
	public static function application_count_by_job($job_id){
		if(!empty($job_id)){
			global $wpdb;
			$table = $wpdb->prefix.'jy_apply';
			$data =  $wpdb->get_results( "SELECT count(id) as total FROM $table WHERE job_id=$job_id", OBJECT );
			$data = current($data);
			return $data->total;
		}
		return 0;
	}
	
	
	
	public static function get_cat_list($cat_name){
		$data = array();
		$terms = get_terms( array(
			'taxonomy' => $cat_name,
			'hide_empty' => false,
		) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			foreach ( $terms as $term ) {
				$data[$term->term_id] = $term->name;
			}
		}
		return $data;
	}
	
	public static function employee_val(){
		$valid = true;
		if(!is_user_logged_in()){
			$valid = false;
		}
		$id = get_the_ID();
		$author_id  =  get_post_field('post_author',$id);
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if(($user_role == 'jy_employee') && $valid){
			if($current_user->ID!=$author_id){
				$valid = false;
			}
		}elseif(($user_role == 'jy_employer') && $valid){
			$valid = false;
			$ids = self::get_employer_job_post($current_user->ID);
			$ids = array_keys($ids);
			$valid = self::auth_to_see_emp($ids,$author_id);
		}elseif(($user_role == 'administrator') && $valid){
			$valid = true;
		}else{
			$valid = false;
		}
		if(!$valid){
			wp_redirect(get_dashboard_url());
			exit;
		}
	}
	
	public static function get_employer_job_post($author_id){
		$id_arr = array();
		$args = array(
			'author '=> $author_id,
			'post_type' => 'jy_job',
			'post_status' => 'publish',
			'posts_per_page'=> -1,
		);
		$query = new WP_Query( $args );
		if($query->have_posts()): while($query->have_posts()): $query->the_post();
			$id_arr[get_the_ID()] = get_the_date('m-Y');
		endwhile;wp_reset_postdata();endif;
		return $id_arr;
	}
	
	public static function auth_to_see_emp($ids,$emid){
		if(!empty($ids)){
			global $wpdb;
			$ids = implode(',',$ids);
			$table = $wpdb->prefix.'jy_apply';
			$data =  $wpdb->get_results( "SELECT id  FROM $table WHERE job_id in (".$ids.") and employee_id=$emid", OBJECT );
			if(!empty($data)){
				return true;
			}
			return false;
		}
		return 0;
	}
	public static function get_total_application($ids){
		if(!empty($ids)){
			global $wpdb;
			$ids = implode(',',$ids);
			$table = $wpdb->prefix.'jy_apply';
			$data =  $wpdb->get_results( "SELECT id  FROM $table WHERE job_id in (".$ids.")", OBJECT );
			if(!empty($data)){
				return $data;
			}
			return 0;
		}
		return 0;
	}
	
	public static function get_total_shortlisted($ids){
		if(!empty($ids)){
			global $wpdb;
			$ids = implode(',',$ids);
			$table = $wpdb->prefix.'jy_apply';
			$data =  $wpdb->get_results( "SELECT id  FROM $table WHERE job_id in (".$ids.") and shortlisted=1", OBJECT );
			if(!empty($data)){
				return $data;
			}
			return 0;
		}
		return 0;
	}
	
	
	public static function employee_view_count($id){
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if($user_role == 'jy_employer'){
			$view = get_post_meta($id,'employee_view',true);
			$date = date('m-Y');
			if(!empty($view)){
				if(!array_key_exists($current_user->ID,$view)){
					$view[$current_user->ID] = $date;
					update_post_meta($id,'employee_view',$view);
				}
			}else{
				$view = array();
				$view[$current_user->ID] = $date;
				update_post_meta($id,'employee_view',$view);
			}
		}
	}
	
	public static function generate_profile_view_chart($view){
		$new = array();
		$labels = $data = '';
		if(!empty($view)){
			foreach($view as $key => $item){
				if(isset($new[$item])){
					$new[$item] = $new[$item]+1;
				}else{
					$new[$item] = 1;
				}
			}
			foreach($new as $key => $item){
				$labels .= '"'.date('M-y',strtotime('01-'.$key)).'",';
				$data .= $item.',';
			}
			$labels = trim($labels,',');
			$data = trim($data,',');
			return array('labels'=>'['.$labels.']','data'=>'['.$data.']');
		}
		return array('labels'=>'["no Data"]','data'=>'[0]');
	}
	
	public static function job_share(){
		global $post;
		// Get current page URL 
		$crunchifyURL = get_permalink();
	 
		// Get current page title
		$crunchifyTitle = str_replace( ' ', '%20', get_the_title());
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=Crunchify';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
		$googleURL = 'https://plus.google.com/share?url='.$crunchifyURL;
		$linkedin = 'http://www.linkedin.com/shareArticle?mini=true&url='.$crunchifyURL;
		$pinterest = 'http://pinterest.com/pin/create/button/?url='.$crunchifyURL;
		?>
		<div class="share-job-post">
			<span class="share"><i class="fas fa-share-alt"></i><?php esc_html_e("Share:","jemployee"); ?></span>
			<a href="<?php print esc_url($facebookURL); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
			<a href="<?php print esc_url($twitterURL); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
			<a href="<?php print esc_url($googleURL); ?>" target="_blank"><i class="fab fa-google-plus-g"></i></a>
			<a href="<?php print esc_url($pinterest); ?>" target="_blank"><i class="fab fa-pinterest-p"></i></a>
			<a href="<?php print esc_url($linkedin); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
		</div>
		<?php
	}
	
	public static function get_meta_value_by_key($meta_value){
		global $wpdb;
		$re = true;
		$value = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' and meta_value =  %s LIMIT 1" , $meta_value) );
		if($value){
			if(isset($_GET['action']) && $_GET['action']=='edit'){
				$re = ($_GET['post']==$value)?true:false;
			}
		}
		return $re;
	}
	
	public static function job_type(){
		return array(1=>esc_html__('Full time','jemployee'),2=>esc_html__('Part Time','jemployee'),3=>esc_html__('Freelance','jemployee'),4=>esc_html__('Temporary','jemployee'));
	}
	
	public static function experience(){
		return array(1=>esc_html__('Fresh','jemployee'),2=>esc_html__('2 Year','jemployee'),3=>esc_html__('3 Year','jemployee'),4=>esc_html__('4 Year','jemployee'),5=>esc_html__('Avobe 5 Years','jemployee'));
	}
	
	public static function date_data(){
		return array(date('Y-m-d')=>'Last hour',date('Y-m-d', strtotime('-1 days'))=>'Last 24 hour',date('Y-m-d', strtotime('-7 days'))=>'Last 7 days',date('Y-m-d', strtotime('-14 days'))=>'Last 14 days',date('Y-m-d', strtotime('-1 months'))=>'Last 30 days');
	}
	public static function gender(){
		return  array(1=>esc_html__('Male','jemployee'),2=>esc_html__('Female','jemployee'));
	}
	
	public static function get_all_page(){
		$pages = get_pages();
		$page_list = array();
		foreach ( $pages as $page ) {
			$page_list[$page->ID] = $page->post_title;
		}
		return $page_list;
	}
}