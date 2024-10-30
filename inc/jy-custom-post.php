<?php 
/**
 * Class jy_custom_post
 *
 * @category  WordPress_Plugin
 * @package   jemployee
 * @author    Codepassenger team
 */
class Jy_custom_post {
	protected static $instance = null;
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct(){
		add_action( 'admin_menu', array($this,'menu_list') );
		add_action( 'init', array($this,'generate_post_type'));
		add_action( 'admin_post_save_custom_data', array( $this, 'save_custom_data' ) );
		
	}
	
	public function menu_list(){
		add_menu_page(esc_html__('Jemployee','jemployee'), esc_html__('Jemployee','jemployee'), 'manage_options', 'job_yard',array($this,'custom_page'));
		$job_menu = 'edit.php?post_type=jy_job';
		$company_menu = 'edit.php?post_type=jy_company';
		$seeker_menu = 'edit.php?post_type=jy_seeker';
		$location = 'edit-tags.php?taxonomy=jy_location';
		$jy_category = 'edit-tags.php?taxonomy=jy_category';
		$jy_skill = 'edit-tags.php?taxonomy=jy_skill';
		add_submenu_page('job_yard', esc_html__('Job Seeker','jemployee'), esc_html__('Job Seeker','jemployee'), 'manage_options', $seeker_menu);
		add_submenu_page('job_yard', esc_html__('Company','jemployee'), esc_html__('Company','jemployee'), 'manage_options', $company_menu);
		add_submenu_page('job_yard', esc_html__('Job','jemployee'), esc_html__('Job','jemployee'), 'manage_options', $job_menu);
		add_submenu_page('job_yard', esc_html__('job Location','jemployee'), esc_html__('job Location','jemployee'), 'manage_options', $location);
		add_submenu_page('job_yard', esc_html__('Job Category','jemployee'), esc_html__('Job Category','jemployee'), 'manage_options', $jy_category);
		add_submenu_page('job_yard', esc_html__('Job Skill','jemployee'), esc_html__('Job Skill','jemployee'), 'manage_options', $jy_skill);
	}
	
	public function custom_page(){
		?>	
		<div class="wrap">
			<h2><?php esc_html_e('Jemployee Page Setting','jemployee'); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dashboard-form" method="post">
				<table class="form-table">
					<?php $jemployee_nonce = wp_create_nonce('jemployee_ajax_nonce'); ?>
					<input type="hidden" name="action" value="save_custom_data">
					<input type="hidden" name="jemployee_nonce" value="<?php echo esc_attr($jemployee_nonce); ?>" />			
					<tbody>
						<tr>
							<th><label for="blogname"><?php esc_html_e('Google Map Api Key','jemployee'); ?></label></th>
							<td><input type="text" value="<?php print get_option('jy_map_api_key'); ?>" name="jy_map_api_key" class="regular-text" ></td>
						</tr>
						<tr>
							<th><label for="blogname"><?php esc_html_e('Privacy Policy Page','jemployee'); ?></label></th>
							<td>
								<select name="jy_privacy_page">
									<?php 
										$page_list = Jy_helper::get_all_page();
										$jy_privacy_page = get_option('jy_privacy_page');
										foreach($page_list as $key => $p){
											$slec = ($jy_privacy_page == $key)?'selected':'';
											echo '<option '.$slec.' value="'.$key.'" >'.$p.'</option>';
										} 
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for="blogname"><?php esc_html_e('Terms Conditions Page','jemployee'); ?></label></th>
							<td>
								<select name="jy_terms_page">
									<?php 
										$jy_terms_page = get_option('jy_terms_page');
										foreach($page_list as $key => $p){
											$slec = ($jy_terms_page == $key)?'selected':'';
											echo '<option '.$slec.' value="'.$key.'" >'.$p.'</option>';
										} 
									?>
								</select>
							</td>
						</tr>
						<?php if(!get_option('jy_page_create',0)): ?>
							<tr>
								<th><label><?php esc_html_e('Create Jemployee Page','jemployee'); ?></label></th>
								<td>
									<label><input name="page_create" type="checkbox"><?php esc_html_e('Create Jemployee Page','jemployee'); ?></label>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e('Submit','jemployee'); ?></button>
				</p>
			</form>
		</div>
		<?php
	}
	
	public function  save_custom_data(){
		$data = $_POST;
		if(! wp_verify_nonce( $_POST['jemployee_nonce'], 'jemployee_ajax_nonce')){
			wp_redirect($_SERVER['HTTP_REFERER']);
		}
		update_option('jy_map_api_key',$_POST['jy_map_api_key']);
		update_option('jy_privacy_page',$_POST['jy_privacy_page']);
		update_option('jy_terms_page',$_POST['jy_terms_page']);
		if(isset($_POST['page_create'])){
			$post_id = $this->set_page();
			update_option('jy_page_create',1);
			update_option('jy_jobyar_page',$post_id);
		}
		wp_redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function set_page(){
		$my_post = array(
			'post_title'    => esc_html__('Jemployee','jemployee'),
			'post_type'     => 'page',
			'post_status'   => 'publish'
		);
		$my_post['meta_input'] = array('_wp_page_template' => '/template-part/jemployee.php');
		return $post_id = wp_insert_post( $my_post );
	}
	
	public function generate_post_type(){
		$this->create_post_type('jy_seeker',esc_html__('Job Seeker','jemployee'),array('menu_icon'=>'dashicons-admin-users','show_in_menu'=>false));
		$this->create_post_type('jy_company',esc_html__('Company','jemployee'),array('menu_icon'=>'dashicons-groups','show_in_menu'=>false));
		$this->create_post_type('jy_job',esc_html__('Job','jemployee'),array('menu_icon'=>'dashicons-groups','show_in_menu'=>false));
		
		$this->create_taxonomy('jy_location','',esc_html__('job Location','jemployee'),array('meta_box_cb'=>false));
		$this->create_taxonomy('jy_category','',esc_html__('Job Category','jemployee'),array('meta_box_cb'=>false));
		$this->create_taxonomy('jy_skill','',esc_html__('Job Skill','jemployee'),array('meta_box_cb'=>false));
	}
	
	private function create_post_type($post,$name,$args=array()){
		if(empty($post)){
			return '';
		}
		
		$name = (!empty($name))?$name:$post;
		
		$defaults = array(
			'public'             	=> true,
			'publicly_queryable' 	=> true,
			'show_in_menu'       	=> true,
			'show_in_admin_bar'   	=> true,
			'can_export'          	=> true,
			'has_archive'        	=> true,
			'hierarchical'       	=> false,
			'menu_position'         => 30,
			'menu_icon'             => 'dashicons-groups',
			'supports'           	=> array( 'title','editor','thumbnail')
		);
		
		$args = wp_parse_args( $args, $defaults );
		$labels = array( 
			'name'                	=> $name,
			'singular_name'       	=> $name,
			'menu_name'           	=> $name,
			'parent_item_colon'   	=> sprintf(esc_html__( 'Parent %s:', 'jemployee' ),$name),
			'all_items'           	=> sprintf(esc_html__( 'All %s', 'jemployee' ),$name),
			'view_item'           	=> sprintf(esc_html__( 'View %s', 'jemployee' ),$name),
			'add_new_item'        	=> sprintf(esc_html__( 'Add New %s', 'jemployee' ),$name),
			'add_new'             	=> sprintf(esc_html__( 'New %s', 'jemployee' ),$name),
			'edit_item'           	=> sprintf(esc_html__( 'Edit %s', 'jemployee' ),$name),
			'update_item'         	=> sprintf(esc_html__( 'Update %s', 'jemployee' ),$name),
			'search_items'        	=> sprintf(esc_html__( 'Search %s', 'jemployee' ),$name),
			'not_found'           	=> sprintf(esc_html__( 'No %s found', 'jemployee' ),$name),
			'not_found_in_trash'  	=> sprintf(esc_html__( 'No %s found in Trash', 'jemployee' ),$name)
		);
		$args['labels'] = $labels;
		register_post_type($post,$args);
	}
	
	public function create_taxonomy($taxonomy,$postname,$name,$args=array()){
		$defaults = array(
			'hierarchical'      => true,
			'show_ui'           => true,
			'query_var'         => true,
			'public'            => true
		);
		$args = wp_parse_args( $args, $defaults );
		$labels = array(
			'name'              => esc_html($name),
			'singular_name'     => esc_html($name),
			'search_items'      => sprintf(esc_html__( 'Search %s:', 'jemployee' ),$name),
			'all_items'      	=> sprintf(esc_html__( 'All %s:', 'jemployee' ),$name),
			'parent_item'      	=> sprintf(esc_html__( 'Parent  %s:', 'jemployee' ),$name),
			'parent_item_colon' => sprintf(esc_html__( 'Parent  %s:', 'jemployee' ),$name),
			'edit_item'     	=> sprintf(esc_html__( 'Edit  %s:', 'jemployee' ),$name),
			'update_item'     	=> sprintf(esc_html__( 'Update %s:', 'jemployee' ),$name),
			'add_new_item'      => sprintf(esc_html__( 'Add New %s:', 'jemployee' ),$name),
			'new_item_name'     => sprintf(esc_html__( 'New  %s Name:', 'jemployee' ),$name),
			'menu_name'      	=> esc_html($name),
		);
		$args['labels'] = $labels;
		register_taxonomy( $taxonomy, $postname, $args );
	}
}

Jy_custom_post::get_instance();
