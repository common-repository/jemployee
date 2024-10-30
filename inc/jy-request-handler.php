<?php 
class Jy_Request_Handler {
	public function __construct(){
		add_action( 'admin_post_jy_employee_edit', array( $this, 'jy_employee_edit' ) );
		add_action( 'admin_post_jy_employer_edit', array( $this, 'jy_employer_edit' ) );
		add_action( 'admin_post_jy_delete_user', array( $this, 'jy_delete_user' ) );
		add_action( 'admin_post_jy_job_post', array( $this, 'jy_job_post' ) );
		add_action( 'admin_post_delete_job_post', array( $this, 'delete_job_post' ) );
		add_action( 'wp_ajax_jy_skill_list', array( $this, 'jy_skill_list' ) );
		add_action( 'wp_ajax_jy_skill_save', array( $this, 'jy_skill_save' ) );
		add_action( 'wp_ajax_jy_social_save', array( $this, 'jy_social_save' ) );
		add_action( 'wp_ajax_jy_employee_info', array( $this, 'jy_employee_info' ) );
		add_action( 'wp_ajax_jy_employee_edu', array( $this, 'jy_employee_edu' ) );
		add_action( 'wp_ajax_jy_employee_exp', array( $this, 'jy_employee_exp' ) );
		add_action( 'wp_ajax_jy_employee_personal_skill', array( $this, 'jy_employee_personal_skill' ) );
		add_action( 'wp_ajax_jy_employee_sp_qa', array( $this, 'jy_employee_sp_qa' ) );
		add_action( 'wp_ajax_jy_em_per_info', array( $this, 'jy_em_per_info' ) );
		add_action( 'wp_ajax_jy_em_portfolio', array( $this, 'jy_em_portfolio' ) );
		add_action( 'wp_ajax_jy_em_bookmark', array( $this, 'jy_em_bookmark' ) );
		add_action( 'wp_ajax_jy_em_apply', array( $this, 'jy_em_apply' ) );
		add_action( 'wp_ajax_jy_remove_bookmark', array( $this, 'jy_remove_bookmark' ) );
		add_action( 'wp_ajax_jy_remove_applied', array( $this, 'jy_remove_applied' ) );
		add_action( 'wp_ajax_jy_shortlisted', array( $this, 'jy_shortlisted' ) );
		add_action( 'wp_ajax_jy_application_reject', array( $this, 'jy_application_reject' ) );
		add_action( 'wp_ajax_jy_send_email', array( $this, 'jy_send_email' ) );
		add_action( 'wp_ajax_nopriv_jy_send_email', array( $this, 'jy_send_email' ) );
		add_action( 'wp_ajax_jy_email_job', array( $this, 'jy_email_job' ) );
		add_action( 'wp_ajax_nopriv_jy_email_job', array( $this, 'jy_email_job' ) );
		add_action( 'wp_ajax_jy_pdf_upload', array( $this, 'jy_pdf_upload' ) );	
	}
	
	public function jy_pdf_upload(){
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$id = $_POST['id'];
		$slug = $_POST['slug'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		if($slug=='upload_cv'){
			update_post_meta($post_id,'jy_upload_cv',$id);
		}elseif($slug=='upload_cover'){
			update_post_meta($post_id,'jy_upload_cover',$id);
		}
		echo 1;
		exit;
	}
	
	public function jy_employee_edit(){
		
		$id = get_current_user_id();
		$post_data = $_POST;
		
		$update = array( 
					'ID' => $id, 
					'first_name' => $post_data['first_name'], 
					'last_name' => $post_data['last_name'] ,
					'description' => $post_data['description'] ,
					'user_email' => $post_data['email_address'] 
				);
		if(isset($post_data['c_pass'])){
			$user = get_user_by('id', $id);
			if ( $user && wp_check_password( $post_data['c_pass'], $user->data->user_pass, $user->ID)){
				if($post_data['new_pass'] == $post_data['conf_pass']){
					wp_set_password($post_data['new_pass'],$id);
				}
			}
		}
		update_user_meta($id,'jy_em_address',$post_data['address']);
		update_user_meta($id,'jy_em_phone',$post_data['phone']);
		update_user_meta($id,'jy_em_expertise',$post_data['expertise']);
		if(!empty($post_data['jy_em_image'])){
			update_user_meta($id,'jy_em_image',$post_data['jy_em_image']);
		}
		wp_update_user($update);
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function jy_employer_edit(){
		
		$id = get_current_user_id();
		$employer_identity = get_user_meta($id,'job_employer_id',true);
		$post_data = $_POST;
		
		$update = array( 
			'ID' => $id, 
			'description' => $post_data['description'],
			'user_email' => $post_data['email_address'] 
		);
		update_user_meta($id,'jy_em_company_name',$post_data['company_name']);
		
		
		if(isset($post_data['c_pass'])){
			$user = get_user_by('id', $id);
			if ( $user && wp_check_password( $post_data['c_pass'], $user->data->user_pass, $user->ID)){
				if($post_data['new_pass'] == $post_data['conf_pass']){
					wp_set_password($post_data['new_pass'],$id);
				}
			}
		}
		
		
		
		$my_post = array(
			  'ID'           => $employer_identity,
			  'post_title'   => $post_data['company_name'],
			  'post_content' => $post_data['description'],
		);
		if($post_data['jy_em_image']>0){
			update_post_meta($employer_identity,'jy_em_image',$post_data['jy_em_image']);
		}
		update_post_meta($employer_identity,'jy_em_address',$post_data['address']);
		update_post_meta($employer_identity,'jy_em_phone',$post_data['phone']);
		update_post_meta($employer_identity,'jy_em_intro_video',$post_data['intro_video']);
		update_post_meta($employer_identity,'jy_social_link',$post_data['social_link']);
		if(isset($post_data['jy_p_gallery'])){
			update_post_meta($employer_identity,'jy_p_gallery',$post_data['jy_p_gallery']);
		}
		update_post_meta($employer_identity,'jy_em_website',$post_data['website']);
		update_post_meta($employer_identity,'jy_total_employee',$post_data['total_employee']);
		wp_update_user($update);
		wp_update_post($my_post);
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function jy_skill_list(){
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$search = $_POST['search'];
		$post_type = $_POST['post_type'];
		$taxonomies = get_terms( array(
			'taxonomy' => $post_type,
			'hide_empty' => false,
			'search' => $search
		) );
		$arr = array();
		$i=0;
		if (!empty($taxonomies) ){
			foreach ( $taxonomies as $term ) {
				$arr[$i]['id'] = $term->term_id;
				$arr[$i]['text'] = $term->name;
				$i++;
			}
		}
		echo json_encode($arr);
		exit;
	}
	
	
	
	public function jy_skill_save(){
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$ids = $_POST['ids'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$save = update_post_meta($post_id, 'jy_em_skill',$ids);
		$terms = get_terms( 'jy_skill', array(
			'include' => $ids,
			'hide_empty' => false,
		) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			$list = '<label>Skills:</label>';
			foreach ( $terms as $term ){
				$list .= '<a href="#">'.$term->name.'</a>';
			}
		}
		echo $list;
		exit;
	}
	
	public function jy_social_save(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$arr = array();
		foreach($data['social'] as $list){
			$name = str_replace(array('social[',']'),'',$list['name']);
			$arr[$name] = $list['value'];
		}
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$save = update_post_meta($post_id, 'jy_em_social',$arr);
		echo 1;
		exit;
	}
	
	public function jy_employee_info(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$employee_info = $data['employee_info'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		foreach($employee_info as $item){
			if($item['name']=='employee_description'){
				$my_post = array(
					'ID'       		=> $post_id,
					'post_content' 	=> $item['value'],
				);
				wp_update_post( $my_post );
			}else{
				update_post_meta($post_id,$item['name'],$item['value']);
			}
		}
		echo 1;
		exit;
	}
	
	public function jy_employee_edu(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$employee_edu = $data['employee_edu'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$edu_arr = array();
		
		$i = 0;
		foreach($employee_edu as $key => $item){
			$edu_arr[$i][$item['name']] = $item['value'];
			if(($key+1)%4==0 && $key>=3){
				$i++;
			}
		}
		if(!empty($edu_arr)){
			update_post_meta($post_id,'employee_edu',$edu_arr);
		}
		$markup ='';
		foreach($edu_arr as $value){
			$markup .='<div class="education-label">';
				$markup .='<span class="study-year">'.$value['edu_period'].'</span>';
				$markup .='<h5>'.$value['edu_title'].'<span>@ '.$value['edu_institute'].'</span></h5>';
				$markup .='<p>'.$value['edu_description'].'</p>';
			$markup .='</div>';
		}
		echo $markup;
		exit;
	}
	
	public function jy_employee_exp(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$employee_exp = $data['employee_exp'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$edu_arr = array();
		
		$i = 0;
		foreach($employee_exp as $key => $item){
			$edu_arr[$i][$item['name']] = $item['value'];
			if(($key+1)%4==0 && $key>=3){
				$i++;
			}
		}
		if(!empty($edu_arr)){
			update_post_meta($post_id,'employee_exp',$edu_arr);
		}
		$markup ='';
		foreach($edu_arr as $value){
			$markup .='<div class="experience-section">';
				$markup .='<span class="service-year">'.$value['exp_period'].'</span>';
				$markup .='<h5>'.$value['exp_designation'].'<span>@ '.$value['exp_company'].'</span></h5>';
				$markup .='<p>'.$value['exp_description'].'</p>';
			$markup .='</div>';
		}
		echo $markup;
		exit;
	}
	
	public function jy_employee_personal_skill (){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$skill_arr = array();
		$response['status'] = false;
		$emp_personal_skill = $data['emp_personal_skill'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$i = 0;
		foreach($emp_personal_skill as $key => $item){
			if($item['name']=='skill_description'){
				$skill_arr['skill_description'] = $item['value'];
				continue;
			}
			$skill_arr[$i][$item['name']] = $item['value'];
			if(($key)%2==0 && $key>=2){
				$i++;
			}
		}
		if(!empty($skill_arr)){
			$response['status'] = true;
			update_post_meta($post_id,'employee_skill',$skill_arr);
		}
		$response['skill_description'] = $skill_arr['skill_description'];
		unset($skill_arr['skill_description']);
		$markup ='';
		foreach($skill_arr as $value){
			$markup .='<div class="progress-item">';
				$markup .='<div class="progress-head"><p class="progress-on">'.$value['skill_name'].'</p></div>';
				$markup .='<div class="progress-body"><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'.$value['skill_value'].'" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div></div><p class="progress-to">'.$value['skill_value'].'</p></div>';
			$markup .='</div>';
		}
		$response['markup'] = $markup;
		wp_send_json($response);
		exit;
	}
	
	public function jy_employee_sp_qa(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$sp_qa_arr = array();
		$jy_sp_qa = $data['jy_sp_qa'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta($user_id, 'job_seeker_id',true);
		$markup = '';
		$i = 0;
		foreach($jy_sp_qa as $key => $item){
			$sp_qa_arr[$i] = $item['value'];
			$i++;
			$markup .= '<li>'.$item['value'].'</li>';
		}
		if(!empty($sp_qa_arr)){
			update_post_meta($post_id,'employee_sp_qa',$sp_qa_arr);
		}
		echo $markup;
		exit;
	}
	
	public function jy_em_per_info(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$sp_qa_arr = array();
		$jy_em_per_info = $data['jy_em_per_info'];
		$user_id = get_current_user_id();
		$arr = array();
		foreach($jy_em_per_info as $item){
			$arr[$item['name']] = $item['value'];
			update_user_meta($user_id,$item['name'],$item['value']);
		}
		$markup = '';
		$markup .= '<li><span>'.esc_html__("Full Name:","jemployee").'</span>'.$arr['first_name'].'</li>';
		$markup .= '<li><span>'.esc_html__("Father's Name:","jemployee").'</span>'.$arr['jy_em_father_name'].'</li>';
		$markup .= '<li><span>'.esc_html__("Mother's Name:","jemployee").'</span>'.$arr['jy_em_mother_name'].'</li>';
		$markup .= '<li><span>'.esc_html__("Date of Birth:","jemployee").'</span>'.$arr['jy_em_dob'].'</li>';
		$markup .= '<li><span>'.esc_html__("Nationality:","jemployee").'</span>'.$arr['jy_em_nationality'].'</li>';
		$markup .= '<li><span>'.esc_html__("Address:","jemployee").'</span>'.$arr['jy_em_address'].'</li>';
		echo $markup;exit;
	}
	
	public function jy_em_portfolio(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$sp_qa_arr = array();
		$jy_em_portfolio = $data['jy_em_portfolio'];
		$user_id = get_current_user_id();
		$post_id = get_user_meta($user_id, 'job_seeker_id',true);
		$edu_arr = array();
		$i = 0;
		foreach($jy_em_portfolio as $key => $item){
			$edu_arr[$i][$item['name']] = $item['value'];
			if(($key+1)%3==0 && $key>=2){
				$i++;
			}
		}
		if(!empty($edu_arr)){
			update_post_meta($post_id,'employee_portfolio',$edu_arr);
		}
		$markup = '';
		foreach($edu_arr as $item){
			$img = wp_get_attachment_image_src($item['jy_portfolio_image'],array(240,160),true);
			$markup .= '<div class="portfolio-item">';
				$markup .= '<img src="'.esc_url(current($img)).'" class="img-fluid" alt="">';
				$markup .= '<div class="overlay">';
					$markup .= '<a href="'.esc_url($item['link']).'"><i data-feather="eye"></i></a>';
					$markup .= '<a href="'.esc_url($item['link']).'"><i data-feather="link"></i></a>';
				$markup .= '</div>';
			$markup .= '</div>';
		}
		echo $markup;
		exit;
	}
	
	public function jy_job_post(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['front_user'], 'front_user')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			wp_redirect($_SERVER['HTTP_REFERER']);
		}
		$removeKeys = array('action','front_user','jy_job_title','jy_job_description','my-radio');
		$user_id = get_current_user_id();
		if(empty($data['jy_job_title']) || empty($data['jy_job_description'])){
			wp_redirect($_SERVER['HTTP_REFERER']);
		}
		$save_post = array('post_author'=>$user_id,'post_title'=>$data['jy_job_title'],'post_content'=>$data['jy_job_description'],'post_type'=>'jy_job');
		$filter_arr = array_diff_key($data, array_flip($removeKeys));
		$save_post['meta_input'] = $filter_arr;
		if(isset($data['job_id'])){
			$val = Jy_helper::post_author_validation($data['job_id']);
			if(!$val){
				wp_redirect($_SERVER['HTTP_REFERER']);
			}
			$save_post['ID'] = $data['job_id'];
		}else{
			$save_post['post_status'] = 'pending';
		}
		wp_insert_post($save_post);
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function delete_job_post(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['front_user'], 'front_user')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			wp_redirect($_SERVER['HTTP_REFERER']);
		}
		$user_id = get_current_user_id();
		$post_tmp = get_post($data['id']);
		$post_tmp->post_author;
		if($user_id==$post_tmp->post_author){
			wp_delete_post($data['id']);
		}
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function jy_em_bookmark(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if($user_role!= 'jy_employee'){
			print esc_html__('User Must Login as a Employee','jemployee');
			exit;
		}
		$valid = $this->bookmark_exist($data['job_id'],$current_user->ID);
		if($valid){
			print esc_html__('Already Bookmarked','jemployee');
			exit;
		}
		global $wpdb;
		$table = $wpdb->prefix.'jy_bookmark';
		$save = array('job_id' => $data['job_id'], 'employee_id' => $current_user->ID,'bookmark_date'=>date('Y-m-d'));
		$wpdb->insert($table,$save);
		$my_id = $wpdb->insert_id;
		if($my_id){
			print esc_html__('Bookmarked','jemployee');
		}else{
			print esc_html__('Failed','jemployee');
		}
		exit;
	}
	
	public function jy_em_apply(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			print esc_html__('Sorry, your nonce did not verify.','jemployee');
			exit;
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if($user_role!= 'jy_employee'){
			print esc_html__('User Must Login as a Employee','jemployee');
			exit;
		}
		$valid = $this->already_apply($data['job_id'],$current_user->ID);
		if($valid){
			print esc_html__('Already Apply','jemployee');
			exit;
		}
		global $wpdb;
		$table = $wpdb->prefix.'jy_apply';
		$save = array('job_id' => $data['job_id'], 'employee_id' => $current_user->ID,'apply_date'=>date('Y-m-d'));
		$wpdb->insert($table,$save);
		$my_id = $wpdb->insert_id;
		if($my_id){
			print esc_html__('Applyed','jemployee');
		}else{
			print esc_html__('Failed','jemployee');
		}
		exit;
	}
	
	public function bookmark_exist($job_id,$employee_id){
		global $wpdb;
		$table = $wpdb->prefix.'jy_bookmark';
		return $mylink = $wpdb->get_row( "SELECT id FROM $table WHERE job_id = $job_id and employee_id=$employee_id", ARRAY_A );
	}
	
	public function already_apply($job_id,$employee_id){
		global $wpdb;
		$table = $wpdb->prefix.'jy_apply';
		return $wpdb->get_row( "SELECT id FROM $table WHERE job_id = $job_id and employee_id=$employee_id", ARRAY_A );
	}
	
	public function jy_remove_bookmark(){
		$data = $_POST;
		$res = array('status'=>false);
		$valid = true;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if(($user_role!= 'jy_employee') && $valid){
			$msg = esc_html__('Unauthorized User','jemployee');
		}
		if($valid){
			global $wpdb;
			$table = $wpdb->prefix.'jy_bookmark';
			$delet_d = array('job_id' => $data['job_id'], 'employee_id' => $current_user->ID);
			$my_id = $wpdb->delete($table, $delet_d );
			if($my_id){
				$msg =  esc_html__('Bookmarked Delete','jemployee');
				$res['status'] = true;
			}else{
				$msg =  esc_html__('Delete Failed','jemployee');
			}
		}
		$res['msg'] = $msg;
		echo json_encode($res);
		exit;
	}
	
	public function jy_remove_applied(){
		$data = $_POST;
		$res = array('status'=>false);
		$valid = true;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if(($user_role!= 'jy_employee') && $valid){
			$msg = esc_html__('Unauthorized User','jemployee');
		}
		if($valid){
			global $wpdb;
			$table = $wpdb->prefix.'jy_apply';
			$delet_d = array('job_id' => $data['job_id'], 'employee_id' => $current_user->ID);
			$my_id = $wpdb->delete($table, $delet_d );
			if($my_id){
				$msg =  esc_html__('Delete','jemployee');
				$res['status'] = true;
			}else{
				$msg =  esc_html__('Delete Failed','jemployee');
			}
		}
		$res['msg'] = $msg;
		echo json_encode($res);
		exit;
	}
	
	public function jy_shortlisted(){
		$data = $_POST;
		$res = array('status'=>false);
		$valid = true;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if(($user_role!= 'jy_employer') && $valid){
			$msg = esc_html__('Unauthorized User','jemployee');
		}
		if($valid){
			global $wpdb;
			$table = $wpdb->prefix.'jy_apply';
			$my_id = $wpdb->update($table,array('shortlisted'=>1),array( 'id' =>$data['id']));
			if($my_id){
				$res['status'] = true;
			}
		}
		echo json_encode($res);
		exit;
	}
	
	public function jy_application_reject(){
		$data = $_POST;
		$res = array('status'=>false);
		$valid = true;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if(($user_role!= 'jy_employer') && $valid){
			$msg = esc_html__('Unauthorized User','jemployee');
		}
		if($valid){
			global $wpdb;
			$table = $wpdb->prefix.'jy_apply';
			$my_id = $wpdb->update($table,array('application_de'=>1),array( 'id' =>$data['id']));
			if($my_id){
				$res['status'] = true;
			}
		}
		echo json_encode($res);
		exit;
	}
	
	public function jy_send_email(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		$value = array();
		if($data['job_id']){
			foreach($data['job_id'] as $item){
				$value[$item->name] = $item->value;
			}
		}
		$name = $value['name'];
		$email = $value['email'];
		$headers[] = "From: $name $email";
		$res = wp_mail($value['employee_email'],'Query Email',$value['message'],$email);
		echo json_encode($res);
		exit;
	}
	
	public function jy_email_job(){
		$data = $_POST['from_data'];
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			$msg =  esc_html__('Sorry, your nonce did not verify.','jemployee');
		}
		if(!empty($data)){
			foreach($data as $item){
				$value[$item->name] = $item->value;
			}
			$res = wp_mail($value['email'],'New Job',get_the_permalink($item->post_id));
		}
		if($res){
			return true;
		}
		return false;
		exit;
	}
	
	public function jy_delete_user(){
		$data = $_POST;
		if(!isset($data['user_pass']) || !isset($data['jy_delete_per'])){
			wp_redirect($_SERVER['HTTP_REFERER']);
		}
		if ( is_user_logged_in()) {
			if(wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
				require_once(ABSPATH.'wp-admin/includes/user.php' );
				$current_user = wp_get_current_user();
				$post_id = get_user_meta($current_user->ID,'job_seeker_id',true);
				$check = wp_check_password($_POST['user_pass'], $user->data->user_pass, $user->ID );
				if(!$check){
					wp_redirect($_SERVER['HTTP_REFERER']);
				}
				wp_delete_post($post_id);
				wp_delete_user($current_user->ID);
				wp_logout();
			}
		}
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
}
new Jy_Request_Handler();