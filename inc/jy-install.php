<?php 
class Jy_Install {
	
	public static function active(){
		self::jy_role();
		self::create_table();
	}
	

	public static function jy_role(){
		if( !get_role('jy_employee')){
			add_role( 'jy_employee', 'Employee', array( 'read' => true,'upload_files'=>true, 'edit_posts' => true ) );
		}
		if( !get_role('jy_employer')){
			add_role( 'jy_employer', 'Employer', array( 'read' => true,'upload_files'=>true, 'edit_posts' => true ) );
		}
	}
	
	public static function create_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . "jy_apply";
		$jy_bookmark = $wpdb->prefix . "jy_bookmark";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id INT(11) NOT NULL AUTO_INCREMENT,
			job_id INT(11) NOT NULL,
			employee_id INT(11) NOT NULL,
			shortlisted TINYINT(4) NOT NULL DEFAULT '0',
			application_de TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Application Delete by Employer',
			time INT(11) NOT NULL,
			flag INT(11) NOT NULL COMMENT 'goal=1,owgoal=2,fouls=3,corners=4,offsides=5,rcard=6,ycard=7,replace=8',
			apply_date DATE NOT NULL,
			PRIMARY KEY (id),
			INDEX employee_id (employee_id)
		) $charset_collate;";
		
		$sql2 = "CREATE TABLE IF NOT EXISTS $jy_bookmark (
			id INT(11) NOT NULL AUTO_INCREMENT,
			job_id INT(11) NOT NULL,
			employee_id INT(11) NOT NULL,
			bookmark_date DATE NOT NULL,
			PRIMARY KEY (id),
			INDEX employee_id (employee_id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql);
		dbDelta( $sql2);
	}
}
