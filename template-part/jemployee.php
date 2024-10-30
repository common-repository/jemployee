<?php 
if(is_user_logged_in()){
	$current_user = wp_get_current_user();
	$user_role = current($current_user->roles);
	if($user_role!= 'jy_employee' && $user_role!= 'jy_employer'){
		wp_redirect(get_dashboard_url());
	}
}else{
	wp_redirect(wp_login_url());
}
$page_slug = isset($_GET['page_slug'])?$_GET['page_slug']:'dashboard';
get_header();
?>
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="container">
        <div class="row">
			<div class="col-md-6">
				<div class="breadcrumb-area">
					<h1><?php print Jy_helper::remove_hp_($page_slug); ?></h1>
				</div>
			</div>
			<div class="col-md-6">
				
			</div>
        </div>
    </div>
</div>
	<?php 
		$select_file = Jy_helper::get_page($page_slug);
		$load_template = ($user_role=='jy_employer')?'employer/'.$select_file:$select_file;
		Jy_helper::load_template($load_template); 
	?>
<?php 

get_footer();