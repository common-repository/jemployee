<?php 
class Jy_Admin {
	protected $templates;
	public function __construct(){
		$this->custom_page();
		add_filter( 'template_include',array($this,'_post_type_template'));
		add_filter( 'theme_page_templates', array($this,'add_custom_page_tamplate'), 20);
		add_filter( 'wp_insert_post_data', array( $this, 'register_project_templates' ) );
		add_filter( 'ajax_query_attachments_args', array( $this, 'show_current_user_attachments' ),10,1 );
		add_action( 'register_form', array($this,'jy_user_register_form') );
		add_action( 'user_register', array($this,'update_user_data') );
		add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );
		add_filter( 'login_redirect', array( $this, 'jy_login_redirect' ), 10, 3 );
		add_action('manage_jy_job_posts_columns',array($this,'add_post_columns'));
		add_action('manage_jy_company_posts_columns',array($this,'add_post_columns'));
		add_action('manage_jy_seeker_posts_columns',array($this,'add_post_columns'));
	}
	
	public function add_post_columns($columns){
		$new_columns = array(
			'author' => esc_html__('Author', 'jemployee'),
		);
	    return array_merge($columns, $new_columns);
	}
	
	function jy_login_redirect( $redirect_to, $request, $user ) {
		if (!empty($user->roles)){
			$user_role = current($user->roles);
			if ($user_role == 'jy_employee' || $user_role == 'jy_employer') {
				
				$p_data = get_option('jy_page_create',0);
				if($p_data){
					$redirect_to = get_the_permalink(get_option('jy_jobyar_page'));
				}
			}
		}
		return $redirect_to;
	}
	
	public function add_custom_page_tamplate($pages){
		$pages = array_merge( $pages, $this->templates );
		return $pages;
	}
	
	public function custom_page(){
		$a = Jy_helper::get_meta_value_by_key('/template-part/jemployee.php');
		if($a){
			$this->templates['/template-part/jemployee.php'] = esc_html__('Jemployee','jemployee');
		}
		$this->templates['/template-part/job-list.php'] = esc_html__('Job Listing','jemployee');
		$this->templates['/template-part/job-list-map.php'] = esc_html__('Job Listing Map','jemployee');
		$this->templates['/template-part/candidate-list.php'] = esc_html__('Candidate Listing','jemployee');
	}
	function show_current_user_attachments( $query = array() ) {
		$current_user = wp_get_current_user();
		$user_role = current($current_user->roles);
		if($user_role == 'jy_employer' || $user_role == 'jy_employee' ){
			$query['author'] = $current_user->ID;
		}
		return $query;
	}
	
	public function jy_user_register_form() {

		global $wp_roles;

		echo '<select name="role" class="input">';
		foreach ( $wp_roles->roles as $key => $value ) {
		   if ( in_array( $key, [ 'jy_employee', 'jy_employer'] )) {
			  echo '<option value="'.$key.'">'.$value['name'].'</option>';
		   }
		}
		echo '</select>';
	}
	
	public function register_project_templates($atts){
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list. 
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		} 

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;
	}
	
	public function _post_type_template($single_template){
		
		global $post;
		$template_dir = plugin_dir_path( __FILE__ ).'../';
		if(is_single()){
			if ($post->post_type == 'jy_company'){
				$single_template = $template_dir . 'template-part/post-template/company.php';
			}elseif($post->post_type == 'jy_job'){
				$single_template = $template_dir . 'template-part/post-template/job-details.php';
			}
			elseif($post->post_type == 'jy_seeker'){
				$single_template = $template_dir . 'template-part/post-template/employee-details.php';
			}
		}elseif(is_page()){
			if ( ! isset( $this->templates[get_post_meta($post->ID, '_wp_page_template', true )] ) ) {
				return $single_template;
			}
			$single_template = $template_dir. get_post_meta( $post->ID, '_wp_page_template', true);
		}
		return $single_template;
	}
	
	public function update_user_data($user_id){
		$role = isset($_POST['role'])?$_POST['role']:'jy_employee';
		$user_data = wp_update_user( array( 'ID' => $user_id, 'role' =>  $role));
		if($role == 'jy_employee'){
			$author_obj = get_user_by('id', $user_id);
			$my_post = array(
				'post_title'    => $author_obj->data->user_login,
				'post_type'     => 'jy_seeker',
				'post_status'   => 'publish',
				'post_author'   => $user_id,
			);
			$post_id = wp_insert_post( $my_post );
			add_user_meta( $user_id, 'job_seeker_id', $post_id);
		}elseif($role == 'jy_employer'){
			$author_obj = get_user_by('id', $user_id);
			$my_post = array(
				'post_title'    => esc_html__('Company Name','jemployee'),
				'post_type'     => 'jy_company',
				'post_status'   => 'publish',
				'post_author'   => $user_id,
			);
			$post_id = wp_insert_post( $my_post );
			add_user_meta( $user_id, 'job_employer_id', $post_id);
		}
	}
	
	public function add_display_post_states( $post_states, $post ) {
		$slug = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( '/template-part/jemployee.php' === $slug ) {
			$post_states['job_yard_page'] = esc_html__( 'Jemployee Page', 'jemployee' );
		}
		return $post_states;
	}	
}
new Jy_Admin();