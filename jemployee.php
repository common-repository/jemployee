<?php 
/*
Plugin Name: Jemployee Job Board Manager
Plugin URI: http://themerail.com/wp/theme/jemployee/
Description: Job Manager Plugin for WordPress
Author: CodePassenger
Author URI: https://codepassenger.com/
Text Domain: jemployee
Domain Path: /languages/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Version: 1.0
*/
define("JEMPLOYEE_PREFIX", "jy__");
define("JEMPLOYEE_SETTING",'jy_settings');

class Jemployee {
	
	protected static $instance = null;
	protected $plugin_path;
	protected $plugin_settings_page;
	protected $plugin_prefix;
    /**
     * [instance description]
     * @return [object] 
     */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct(){
		$this->file_include();
		add_action('init',array($this,'jy_language'));
	}
	
	public function file_include(){
		require 'inc/jy-helper.php';
		require 'inc/jy-custom-post.php';
		if(!is_admin()){
			require 'inc/jy-frontend.php';
			require 'template-part/jy-markup.php';
		}
		require 'inc/jy-admin.php';
		require 'inc/jy-request-handler.php';
		require 'inc/jy-install.php';
		
	}
	
	public function jy_language() {
		$loaded = load_plugin_textdomain( 'jemployee', false, '/languages/' );

		if ( ! $loaded ) {
			$loaded = load_muplugin_textdomain( 'jemployee', '/languages/' );
		}

		if ( ! $loaded ) {
			$loaded = load_theme_textdomain( 'jemployee', get_stylesheet_directory() . '/languages/' );
		}

		if ( ! $loaded ) {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'jemployee' );
			$mofile = dirname( __FILE__ ) . '/languages/jemployee-' . $locale . '.mo';
			load_textdomain( 'jemployee', $mofile );
		}
	}
}
Jemployee::instance();
register_activation_hook( __FILE__, array('Jy_Install','active'));