<?php 

class Jy_Markup {
	
	public function __construct(){
		add_action('jy_edit_resume',array($this,'social_link'));
		add_action('jy_edit_description',array($this,'jy_edit_description_update'));
		add_action('jy_edit_description',array($this,'employee_eduction'));
		add_action('jy_edit_description',array($this,'work_exp'));
		add_action('jy_edit_description',array($this,'persional_skill'));
		add_action('jy_edit_description',array($this,'sp_qa'));
		add_action('jy_edit_description',array($this,'portfolio_info'));
		add_action('jy_edit_description',array($this,'personal_info'));
	}
	
	public function social_link($social){
		$social_list = array('facebook-f','twitter','google-plus','linkedin-in','pinterest-p','behance','dribbble','github');
		
		$view = '';
		$form = '';
		$user_id = get_current_user_id();
		$post_id = get_user_meta( $user_id, 'job_seeker_id',true);
		$data = get_post_meta($post_id, 'jy_em_social',true);
		foreach($social_list as $key=> $list){
			$link = isset($data[$list])?$data[$list]:'';
			$view .= '<a href="'.esc_url($link).'"><i class="fab fa-'.$list.'"></i></a>';
			$form .= '<div class="form-group row">';
				$form .= '<div class="offset-sm-3 col-sm-9">';
					$form .= '<div class="input-group">';
						$form .= '<div class="input-group-prepend">';
							$form .= '<div class="input-group-text"><i class="fab fa-'.$list.'"></i></div>';
						$form .= '</div>';
						$form .= '<input type="text" class="form-control" value="'.$link.'" name="social['.$list.']">';
					$form .= '</div>';
				$form .= '</div>';
			$form .= '</div>';
		}
		?>
			<div class="social-profile">
				<label><?php esc_html_e("Social:","jemployee"); ?></label>
				<div class="social-view">
					<?php print $view; ?>
				</div>
                    <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-button" data-toggle="modal" data-target="#modal-social">
					<i data-feather="edit-2"></i>
				</button>
                    <!-- Modal -->
				<div class="modal fade" id="modal-social" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="git-branch"></i><?php esc_html_e("Social Networks","jemployee"); ?></h4>
								</div>
								<div class="content">
									<form name="social_link" method="post">
										<?php print $form; ?>
										<div class="row">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_em_social" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
	
	public function  jy_edit_description_update($post_id){
		$filed_list = array('category'=>'','location'=>'','status'=>Jy_helper::job_type(),'experience'=>'','salary'=>'','gender'=>Jy_helper::gender(),'age'=>'');
		$filed_list['location'] = get_post_meta($post_id,'location',true);
		$filed_list['experience'] = get_post_meta($post_id,'experience',true);
		$filed_list['salary'] = get_post_meta($post_id,'salary',true);
		$filed_list['age'] = get_post_meta($post_id,'age',true);
		$status = get_post_meta($post_id,'status',true);
		$s_status = !empty($filed_list['status'][$status])?$filed_list['status'][$status]:'';
		$gender = get_post_meta($post_id,'gender',true);
		$s_gender = !empty($filed_list['gender'][$gender])?$filed_list['gender'][$gender]:'';
		$filed_list['category'] = get_post_meta($post_id,'jy_category',true);
		$content = get_post_field('post_content', $post_id);
		$cat_data = Jy_helper::get_term($filed_list['category'],'jy_category');
		?>
			<div class="about-details details-section dashboard-section">
				<h4><i data-feather="align-left"></i><?php esc_html_e("About Me","jemployee"); ?></h4>
				<div class="information-and-contact">
					<p id="employee_about"><?php print esc_html($content); ?></p>
					<div class="information">
						<h4><?php esc_html_e("Information","jemployee"); ?></h4>
						<ul>
							<li id="emp_job_cat"><span><?php esc_html_e("Category:","jemployee"); ?></span> <?php print esc_html($cat_data->name); ?></li>
							<li id="emp_location"><span><?php esc_html_e("Location:","jemployee"); ?></span> <?php print esc_html($filed_list['location']); ?></li>
							<li id="emp_status"><span><?php esc_html_e("Status:","jemployee"); ?></span> <?php print esc_html($s_status); ?></li>
							<li id="emp_experience"><span><?php esc_html_e("Experience:","jemployee"); ?></span> <?php print esc_html($filed_list['experience']); ?></li>
							<li id="emp_salary"><span><?php esc_html_e("Salary:","jemployee"); ?></span> <?php print esc_html($filed_list['salary']); ?></li>
							<li id="emp_gender"><span><?php esc_html_e("Gender:","jemployee"); ?></span> <?php print esc_html($s_gender); ?> </li>
							<li id="emp_age"><span><?php esc_html_e("Age:","jemployee"); ?></span> <?php print esc_html($filed_list['age']); ?></li>
						</ul>
					</div>
                </div>
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-about-me">
					<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
                <div class="modal fade" id="modal-about-me" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="align-left"></i><?php esc_html_e("About Me","jemployee"); ?></h4>
								</div>
								<div class="content">
									<form class="edit-resume-employee_info" name="employee_info" action="#">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Write Yourself","jemployee"); ?></label>
											<div class="col-sm-9">
												<textarea name="employee_description" class="form-control" placeholder="Write Yourself"><?php print esc_html($content); ?></textarea>
											</div>
										</div>
										<h4><i data-feather="align-left"></i><?php esc_html_e("Information","jemployee"); ?></h4>
										<?php foreach($filed_list as $key => $list): ?>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label"><?php print ucfirst($key); ?></label>
												<?php if(is_array($list)): ?>
													<div class="col-sm-9">
														<?php if($key=='status') $post_meta = $status;  ?>
														<?php if($key=='gender') $post_meta = $gender;  ?>
														<?php print Jy_helper::dropdown($key,$list,$post_meta,array('class'=>'form-control')); ?>
													</div>
												<?php elseif($key=='category'): ?>
													<div class="col-sm-9">
														<select name="jy_category" class="form-control jy-select" id="jy_job_category">
															<?php if($cat_data) echo '<option selected value="'.$cat_data->term_id.'">'.$cat_data->name.'</option>'; ?>
														</select>
													</div>
												<?php else: ?>
													<div class="col-sm-9">
														<input type="text" value="<?php print $list ?>" name="<?php print $key;  ?>" class="form-control" placeholder="<?php print ucfirst($key); ?>">
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
										<div class="row">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_employee_info" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
		<?php 
	}
	
	public function employee_eduction($post_id){
		$default = array(array('edu_title'=>'','edu_institute'=>'','edu_period'=>'','edu_description'=>''));
		$edu_data = get_post_meta($post_id,'employee_edu',true);
		
		?>
			<div class="edication-background details-section dashboard-section">
				<h4><i data-feather="book"></i><?php esc_html_e("Education Background","jemployee"); ?></h4>
				<div id="employee-edu">
					<?php if(!empty($edu_data)): foreach($edu_data as $item): ?>
						<div class="education-label">
							<span class="study-year"><?php print $item['edu_period']; ?></span>
							<h5><?php print $item['edu_title']; ?><span>@  <?php print $item['edu_institute']; ?></span></h5>
							<p><?php print $item['edu_description']; ?></p>
						</div>
					<?php endforeach;endif; ?>
				</div>
                  <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-education">
						<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
				<div class="modal fade modal-education" id="modal-education" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
									<div class="title">
										<h4><i data-feather="book"></i><?php esc_html_e("Education","jemployee"); ?></h4>
										<a href="#" class="add-more add-edu"><?php esc_html_e("+ Add Education","jemployee"); ?></a>
									</div>
								<div class="content">
									<form name="edu_info" class="edu-content" action="#">
									<?php $edu_data = wp_parse_args($edu_data,$default); ?>
										<?php foreach($edu_data as $item); ?>
										<div class="edu-block" class="input-block-wrap">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label edu-item"><?php esc_html_e("01","jemployee"); ?></label>
												<div class="col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Title","jemployee"); ?></div>
														</div>
													  <input name="edu_title" value="<?php print $item['edu_title']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-3 col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Institute","jemployee"); ?></div>
														</div>
														<input  type="text" value="<?php print $item['edu_institute']; ?>" name="edu_institute" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-9 offset-sm-3">
													<div class="input-group">
													  <div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Period","jemployee"); ?></div>
													  </div>
													  <input name="edu_period" value="<?php print esc_attr($item['edu_period']); ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-3 col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Description","jemployee"); ?></div>
														</div>
														<textarea name="edu_description" class="form-control"><?php print $item['edu_description']; ?></textarea>
													</div>
												</div>
											</div>
											<a class="edu-remove"><i class="fas fa-trash-alt"></i></a>
										</div>
										  
										<div data-id="1" class="row edu-last">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_employee_edu" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
				</div>
            </div>
		<?php 
	}
	
	public function work_exp($post_id){
		$default = array(array('exp_designation'=>'','exp_company'=>'','exp_period'=>'','exp_description'=>''));
		$exp_data = get_post_meta($post_id,'employee_exp',true);
		?>
			<div class="experience dashboard-section details-section">
                <h4><i data-feather="briefcase"></i><?php esc_html_e("Work Experiance","jemployee"); ?></h4>
				<div id="employee-exp">
					<?php if(!empty($exp_data)): foreach($exp_data as $item): ?>
						<div class="experience-section">
							<span class="study-year"><?php print $item['exp_period']; ?></span>
							<h5><?php print $item['exp_designation']; ?><span>@  <?php print $item['exp_company']; ?></span></h5>
							<p><?php print $item['exp_description']; ?></p>
						</div>
					<?php endforeach;endif; ?>
				</div>
                  <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-experience">
					<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
                <div class="modal fade modal-experience" id="modal-experience" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="briefcase"></i><?php esc_html_e("Experience","jemployee"); ?></h4>
									<a href="#" class="add-more add-exp"><?php esc_html_e("+ Add Experience","jemployee"); ?></a>
								</div>
								<div class="content">
									<form name="exp_info" method="post" action="#">
									<?php $exp_data = wp_parse_args($exp_data,$default); ?>
										<?php foreach($exp_data as $key => $item): ?>
										<div class="input-block-wrap exp-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label exp-item"><?php print ++$key; ?></label>
												 <div class="col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Designation","jemployee"); ?></div>
														</div>
														<input name="exp_designation" value="<?php print $item['exp_designation']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-3 col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Company","jemployee"); ?></div>
														</div>
														<input name="exp_company" value="<?php print $item['exp_company']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-9 offset-sm-3">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Period","jemployee"); ?></div>
														</div>
														<input name="exp_period" value="<?php print $item['exp_period']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-3 col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Description","jemployee"); ?></div>
														</div>
														<textarea name="exp_description" value="<?php print $item['exp_description']; ?>" class="form-control"></textarea>
													</div>
												</div>
											</div>
											<a class="exp-remove"><i class="fas fa-trash-alt"></i></a>
										</div>
										<?php endforeach; ?>
										<div data-id="<?php print $key; ?>" class="row exp-last">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_employee_exp" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
										
									</form>
								</div>
							</div>
						</div>
                    </div>
				</div>
            </div>
		<?php 
	}
	
	public function persional_skill($post_id){
		$default = array(array('skill_name'=>'','skill_value'=>''));
		$exp_data = get_post_meta($post_id,'employee_skill',true);
		$desc = '';
		if(isset($exp_data['skill_description'])){
			$desc = $exp_data['skill_description'];
			unset($exp_data['skill_description']);
		}
		?>
			<div class="professonal-skill dashboard-section details-section">
                <h4><i data-feather="feather"></i><?php esc_html_e("Professional Skill","jemployee"); ?></h4>
                <p class="skill_description"><?php print esc_html($desc); ?></p>
                <div class="progress-group skill-progress-group">
				<?php if(!empty($exp_data)): foreach($exp_data as $item): ?>
                    <div class="progress-item">
						<div class="progress-head">
							<p class="progress-on"><?php print esc_html($item['skill_name']); ?></p>
						</div>
						<div class="progress-body">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php print $item['skill_value']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
							</div>
							<p class="progress-to"><?php print esc_html($item['skill_value']); ?>%</p>
						</div>
                    </div>
                   <?php endforeach;endif; ?>
                </div>
                  <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-pro-skill">
					<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
				<div class="modal fade modal-pro-skill" id="modal-pro-skill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="feather"></i><?php esc_html_e("Professional Skill","jemployee"); ?></h4>
									<a href="#" class="add-more add-skill"><?php esc_html_e("+ Add Skill","jemployee"); ?></a>
								</div>
								<div class="content">
									<form name="jy_personal_skill" action="#">
										<div class="input-block-wrap">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("About Skill","jemployee"); ?></label>
												<div class="col-sm-9">
													<div class="input-group">
														<textarea name="skill_description" class="form-control"><?php print esc_html($desc); ?></textarea>
													</div>
												</div>
											</div>
										</div>
										<?php $exp_data = wp_parse_args($exp_data,$default); ?>
										<?php foreach($exp_data as $key => $item): ?>
										<div class="input-block-wrap personal-skill">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label "><?php esc_html_e("Skill","jemployee"); ?> <span class="personal-skill-item"><?php print ++$key ?></span></label>
												<div class="col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Skill Name","jemployee"); ?></div>
														</div>
														<input name="skill_name" value="<?php print $item['skill_name']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-3 col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php esc_html_e("Percentage","jemployee"); ?></div>
														</div>
														<input name="skill_value" value="<?php print $item['skill_value']; ?>" type="text" class="form-control" >
													</div>
												</div>
											</div>
											<a class="ps-remove"><i class="fas fa-trash-alt"></i></a>
										</div>
										<?php endforeach; ?>
										<div data-id="<?php print $key; ?>" class="row personal-skill-last">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_employee_personal_skill" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php 
	}
	
	public function sp_qa($post_id){
		$default = array('');
		$exp_data = get_post_meta($post_id,'employee_sp_qa',true);
		?>
			<div class="special-qualification dashboard-section details-section">
                <h4><i data-feather="gift"></i><?php esc_html_e("Special Qualification","jemployee"); ?></h4>
                <ul class="sp-qa-data">
					<?php if(!empty($exp_data)):foreach($exp_data as $item): ?>
						<li><?php print esc_html($item); ?></li>
					<?php endforeach;endif; ?>
                </ul>
                  <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-qualification">
					<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
                <div class="modal fade" id="modal-qualification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="align-left"></i><?php esc_html_e("Special Qualification","jemployee"); ?></h4>
									<a href="#" class="add-more add-sp-qa"><?php esc_html_e("+ Add Another","jemployee"); ?></a>
								</div>
								<div class="content">
									<form name="jy_sp_qa" action="#">
										<?php $exp_data = wp_parse_args($exp_data,$default); ?>
										<?php foreach($exp_data as $key => $item): ?>
											<div class="form-group row justify-content-center sp-qa">
												<div class="col-sm-9">
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text sp-qa-item"><?php print ++$key; ?></div>
														</div>
														<input value="<?php print $item; ?>" name="special_qualification" type="text" class="form-control" >
													</div>
												</div>
											</div>
										<?php endforeach; ?>
										<div data-id="<?php print $key; ?>" class="row sp-qa-last">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button id="save_employee_sq_qa" class="primary-bg"><?php esc_html_e("Save","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
		<?php 
	}
	
	public function portfolio_info($post_id){
		$image_thumb = plugins_url('../assets/img/placeholder.png',__FILE__);
		$default = array('');
		$default = array(array('title'=>'','jy_portfolio_image'=>'','link'=>''));
		$exp_data = get_post_meta($post_id,'employee_portfolio',true);
		?>
			<div class="portfolio dashboard-section details-section">
                <h4><i data-feather="gift"></i>Portfolio</h4>
                <div class="portfolio-slider owl-carousel">
					<?php if(!empty($exp_data)):foreach($exp_data as $item): ?>
						<?php $img = wp_get_attachment_image_src($item['jy_portfolio_image'],array(240,160),true); ?>
							<div class="portfolio-item">
								<img src="<?php print esc_url(current($img)); ?>" class="img-fluid" alt="">
								<div class="overlay">
									<a href="<?php print esc_url($item['link']); ?>"><i data-feather="eye"></i></a>
									<a href="<?php print esc_url($item['link']); ?>"><i data-feather="link"></i></a>
								</div>
							</div>
					<?php endforeach;endif; ?>
                </div>
                  <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-portfolio">
					<i data-feather="edit-2"></i>
                </button>
                  <!-- Modal -->
                <div class="modal fade modal-portfolio" id="modal-portfolio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="grid"></i><?php esc_html_e("Portfolio","jemployee"); ?></h4>
									<a href="#" class="add-more add-jy-p"><?php esc_html_e("+ Add Another","jemployee"); ?></a>
								</div>
								<div class="content">
									<form name="jy-portfolio" action="#">
										<?php $exp_data = wp_parse_args($exp_data,$default); ?>
										<?php if(!empty($exp_data)):foreach($exp_data as $key => $item): ?>
											<div class="input-block-wrap jy-portfolio">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Portfolio","jemployee"); ?> <span class="jy-p-item"><?php print ++$key; ?></span></label>
													<div class="col-sm-9">
														<div class="input-group">
															<div class="input-group-prepend">
																<div class="input-group-text"><?php esc_html_e("Title","jemployee"); ?></div>
															</div>
															<input name="title" value="<?php print $item['title']; ?>" type="text" class="form-control" >
														</div>
													</div>
												</div>
												<div class="form-group row">
													<div class="offset-sm-3 col-sm-9">
														<div class="input-group">
															<div class="input-group-prepend">
																<div class="input-group-text"><?php esc_html_e("Image","jemployee"); ?></div>
															</div>
															<?php $img = wp_get_attachment_image_src($item['jy_portfolio_image'],array(240,160),true); ?>
															<div class="upload-profile-photo">
																<div class="update-photo">
																	<img id="jy_protfolio_image<?php print $key; ?>_preview" class="image jy_protfolio_image_preview" src="<?php print esc_url(current($img)); ?>">
																</div>
																<button id="jy_protfolio_image<?php print $key; ?>_button" class="file-upload portfolio-img">         
																	<?php esc_html_e("Change Image","jemployee"); ?>
																</button>
																<input type="hidden" value="<?php print $item['jy_portfolio_image']; ?>" name="jy_portfolio_image" class="jy_protfolio_image" id="jy_protfolio_image<?php print $key; ?>">
															</div>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<div class="offset-sm-3 col-sm-9">
														<div class="input-group">
															<div class="input-group-prepend">
																<div class="input-group-text"><?php esc_html_e("Link","jemployee"); ?></div>
															</div>
															<input name="link" value="<?php print $item['link']; ?>" type="text" class="form-control">
														</div>
													</div>
												</div>
												<a class="portfolio-remove"><i class="fas fa-trash-alt"></i></a>
											</div>
										<?php endforeach;endif; ?>
										<div data-id="<?php print $key; ?>" class="row jy-p-last">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button class="primary-bg" id="save_employee_portfolio"><?php esc_html_e("Save Update","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
		<?php 
	}
	
	public function personal_info(){
		$user_id = get_current_user_id();
		?>
			<div class="personal-information dashboard-section last-child details-section">
                <h4><i data-feather="user-plus"></i><?php esc_html_e("Personal Deatils","jemployee"); ?></h4>
                <ul class="jy-emp-full-info">
                    <li><span><?php esc_html_e("Full Name:","jemployee"); ?></span> <?php print get_user_meta($user_id,'first_name',true).' '.get_user_meta($user_id,'last_name',true); ?></li>
                    <li><span><?php esc_html_e("Father's Name:","jemployee"); ?></span> <?php print get_user_meta($user_id,'jy_em_father_name',true); ?></li>
                    <li><span><?php esc_html_e("Mother's Name:","jemployee"); ?></span><?php print get_user_meta($user_id,'jy_em_mother_name',true); ?></li>
                    <li><span><?php esc_html_e("Date of Birth:","jemployee"); ?></span> <?php print get_user_meta($user_id,'jy_em_dob',true); ?></li>
                    <li><span><?php esc_html_e("Nationality:","jemployee"); ?></span> <?php print get_user_meta($user_id,'jy_em_nationality',true); ?> </li>
					<li><span><?php esc_html_e("Address:","jemployee"); ?></span> <?php print get_user_meta($user_id,'jy_em_address',true); ?></li>
                </ul>
                  <!-- Button trigger modal -->
				<button type="button" class="btn btn-primary edit-resume" data-toggle="modal" data-target="#modal-personal-details">
					<i data-feather="edit-2"></i>
				</button>
                  <!-- Modal -->
                <div class="modal fade" id="modal-personal-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<div class="title">
									<h4><i data-feather="user-plus"></i><?php esc_html_e("Personal Details","jemployee"); ?></h4>
								</div>
								<div class="content">
									<form name="jy_em_per_info" action="#">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("First Name","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="first_name" type="text" class="form-control"  placeholder="Micheal N. Taylor">
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Last Name","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="last_name" type="text" class="form-control"  placeholder="Micheal N. Taylor">
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Father’s Name","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="jy_em_father_name" type="text" class="form-control"  placeholder="Howard Armour">
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Mother’s Name","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="jy_em_mother_name" type="text" class="form-control"  placeholder="Megan Higbee">
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Date Of Birth","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="jy_em_dob" type="text" class="form-control"  placeholder="22/08/1992">
											</div>
										</div>
							  
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Nationality","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="jy_em_nationality" type="text" class="form-control"  placeholder="American">
											</div>
										</div>
                              
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label"><?php esc_html_e("Address","jemployee"); ?></label>
											<div class="col-sm-9">
												<input name="jy_em_address" type="text" class="form-control"  placeholder="2018 Nelm Street, Beltsville, VA 20705">
											</div>
										</div>
							  
										<div class="row">
											<div class="offset-sm-3 col-sm-9">
												<div class="buttons">
													<button class="primary-bg" id="save_employee_personal_info"><?php esc_html_e("Save Update","jemployee"); ?></button>
													<button data-dismiss="modal" aria-hidden="true"><?php esc_html_e("Cancel","jemployee"); ?></button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
		<?php 
	}
}

new Jy_Markup();