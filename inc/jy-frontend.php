<?php 


class Jy_FrontEnd {
	public $template_dir;
	public function __construct(){
		$this->template_dir = plugin_dir_url( __FILE__ ).'../';
		add_action( 'wp_enqueue_scripts', array( $this, 'script_load' ) );
		add_action( 'script_loader_tag', array( $this, 'add_id_to_script' ),10,2 );
		add_action( 'body_class', array( $this, 'jy_body_class' ),10,2 );
		add_action( 'wp_footer', array( $this, 'jy_login_red' ));
	}
	
	public function jy_body_class($classes){
		if(is_user_logged_in()){
			return array_merge( $classes, array( 'jemployee-body' ) );
		}
	}
	public function script_load(){
		global $post;
		$jy_map_key = get_option('jy_map_api_key');
		$template_name = get_page_template_slug( $post->ID );
		wp_enqueue_style('bootstrap',$this->template_dir. 'assets/css/bootstrap.min.css');  
		wp_enqueue_style('fontawesome',$this->template_dir. 'assets/css/fontawesome-all.min.css');  
		wp_enqueue_style('bootstrap-select',$this->template_dir. 'assets/css/bootstrap-select.min.css');  
		wp_enqueue_style('jquery-datetimepicker',$this->template_dir. 'assets/css/jquery.datetimepicker.min.css');  
		wp_enqueue_style('owl-carousel',$this->template_dir. 'assets/css/owl.carousel.min.css');  
		wp_enqueue_style('jquery-nstSlider',$this->template_dir. 'assets/css/jquery.nstSlider.min.css'); 
		wp_enqueue_style('jy-dashboard',$this->template_dir. 'assets/css/dashboard.css');
		wp_enqueue_style('jy-main',$this->template_dir. 'assets/css/main.css'); 
		wp_enqueue_style('select2-min',$this->template_dir. 'assets/css/select2.min.css');
		wp_enqueue_style('plyr',$this->template_dir. 'assets/css/plyr.css');
		wp_enqueue_style('toastr.min',$this->template_dir. 'assets/css/toastr.min.css');
		wp_enqueue_style('themify-icons',$this->template_dir. 'assets/css/themify-icons.css');
		
		if($template_name=='/template-part/job-list-map.php'){
			wp_enqueue_style('leaflet',$this->template_dir. 'assets/css/leaflet.css');
			wp_enqueue_style('MarkerCluster',$this->template_dir. 'assets/css/MarkerCluster.css');
			wp_enqueue_style('MarkerCluster-Default',$this->template_dir. 'assets/css/MarkerCluster.Default.css');
		}
		wp_enqueue_style('jy-custom',$this->template_dir. 'assets/css/jy-custom.css'); 		

		wp_enqueue_media();
		wp_enqueue_script( 'popper',plugins_url('../assets/js/popper.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'bootstrap',plugins_url('../assets/js/bootstrap.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'feather',plugins_url('../assets/js/feather.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'jquery-datetimepicker',plugins_url('../assets/js/jquery.datetimepicker.full.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'bootstrap-select',plugins_url('../assets/js/bootstrap-select.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'jquery-nstSlider',plugins_url('../assets/js/jquery.nstSlider.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'owl-carousel',plugins_url('../assets/js/owl.carousel.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'visible',plugins_url('../assets/js/visible.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'jquery-countTo',plugins_url('../assets/js/jquery.countTo.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'slick-min',plugins_url('../assets/js/slick.min.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'jquery-countTo',plugins_url('../assets/js/jquery.countTo.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'chart',plugins_url('../assets/js/chart.js',__FILE__),array('jquery'),false,false);
		
		wp_enqueue_script( 'plyr',plugins_url('../assets/js/plyr.js',__FILE__),array('jquery'),false,true);
		wp_enqueue_script( 'select2-full',plugins_url('../assets/js/select2.full.min.js',__FILE__),array('jquery'),false,true);
		if($template_name=='/template-part/job-list-map.php'){
			wp_enqueue_script( 'leaflet-src',plugins_url('../assets/js/leaflet-src.js',__FILE__),array('jquery'),false,true);
			wp_enqueue_script( 'leaflet.markercluster-src',plugins_url('../assets/js/leaflet.markercluster-src.js',__FILE__),array('jquery'),false,true);
		}
		wp_enqueue_script( 'jy-custom',plugins_url('../assets/js/custom.js',__FILE__),array('jquery'),false,true);
		
		wp_enqueue_script( 'google-map','//maps.googleapis.com/maps/api/js?key='.$jy_map_key.'&libraries=places,geometry&callback=initAutocomplete',array(),false,true);
		wp_enqueue_script( 'dashboard',plugins_url('../assets/js/dashboard.js',__FILE__),array('jquery'),false,false);
		wp_enqueue_script( 'toastr.min',plugins_url('../assets/js/toastr.min.js',__FILE__),array('jquery'),false,true);
		wp_register_script( 'jy-common',plugins_url('../assets/js/jy-common.js',__FILE__),array('jquery'),false,true);
		$translation_array = array(
			'url' => admin_url( 'admin-ajax.php' ),
			'jemployee_ajax_nonce' => wp_create_nonce('jemployee_ajax_nonce'),
			'jemployee_login' => is_user_logged_in()
		);
		wp_localize_script('jy-common', 'jemployee', $translation_array );
		wp_enqueue_script('jy-common');
	}
	
	function add_id_to_script($tag, $handle){
		if ( $handle == 'google-map'){
			return str_replace( ' src', ' async defer src', $tag );
		}
		return $tag;
	}
	
	public function jy_login_red(){
		if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
			return null;
		}
		?>
			<div class="modal fade" id="modal-login" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog jy-login-red" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<div class="content">
								<div class="row">
									<div class="offset-sm-3 col-sm-9">
										<div class="job-filter-wrapper">
											<a href="<?php echo wp_login_url(); ?>" class="jy-clear"><?php esc_html_e("Login as Employee","jemployee"); ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
}

new Jy_FrontEnd();