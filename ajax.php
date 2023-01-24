<?php
	ob_start();
	$action = $_GET['action'];
	include 'admin_class.php';
	$crud = new Action();

	if($action == 'login'){
		$login = $crud->login();
		if($login)
			echo $login;
	}
	if($action == 'logout'){
		$logout = $crud->logout();
		if($logout)
			echo $logout;
	}

	if($action == 'save_user'){
		$save = $crud->save_user();
		if($save)
			echo $save;
	}
	if($action == 'delete_user'){
		$delete = $crud->delete_user();
		if($delete)
			echo $delete;
	}
	if($action == "save_settings"){
		$save = $crud->save_settings();
		if($save)
			echo $save;
	}
	if($action == "save_settings"){
		$save = $crud->save_settings();
		if($save)
			echo $save;
	}
	if($action == "save_course"){
		$save = $crud->save_course();
		if($save)
			echo $save;
	}
	
	if($action == "delete_course"){
		$delete = $crud->delete_course();
		if($delete)
			echo $delete;
	}
	if($action == "save_subject"){
		$save = $crud->save_subject();
		if($save)
			echo $save;
	}
	if($action == "delete_subject"){
		$save = $crud->delete_subject();
		if($save)
			echo $save;
	}
	
	if($action == "save_class"){
		$save = $crud->save_class();
		if($save)
			echo $save;
	}
	if($action == "delete_class"){
		$save = $crud->delete_class();
		if($save)
			echo $save;
	}
	if($action == "save_faculty"){
		$save = $crud->save_faculty();
		if($save)
			echo $save;
	}
	if($action == "delete_faculty"){
		$save = $crud->delete_faculty();
		if($save)
			echo $save;
	}
	
	if($action == "save_student"){
		$save = $crud->save_student();
		if($save)
			echo $save;
	}
	if($action == "delete_student"){
		$save = $crud->delete_student();
		if($save)
			echo $save;
	}
	if($action == "save_class_subject"){
		$save = $crud->save_class_subject();
		if($save)
			echo $save;
	}
	if($action == "delete_class_subject"){
		$save = $crud->delete_class_subject();
		if($save)
			echo $save;
	}
	if($action == "get_class_list"){
		$get = $crud->get_class_list();
		if($get)
			echo $get;
	}
	if($action == "save_attendance"){
		$save = $crud->save_attendance();
		if($save)
			echo $save;
	}
	if($action == "delete_attendance"){
		$save = $crud->delete_attendance();
		if($save)
			echo $save;
	}
	if($action == "get_att_record"){
		$get = $crud->get_att_record();
		if($get)
			echo $get;
	}
	if($action == "get_att_report"){
		$get = $crud->get_att_report();
		if($get)
			echo $get;
	}
	if($action == "get_att_report_class"){
		$get = $crud->get_att_report_class();
		if($get)
			echo $get;
	}
	if($action == "get_att_report_student"){
		$get = $crud->get_att_report_student();
		if($get)
			echo $get;
	}
	if($action == "get_att_report_daterange"){
		$get = $crud->get_att_report_daterange();
		if($get)
			echo $get;
	}
	
	if($action == "get_att_report_total"){
		$get = $crud->get_att_report_total();
		if($get)
			echo $get;
	}
	
	if($action == "get_class_student_list"){
		$get = $crud->get_class_student_list();
		if($get)
			echo $get;
	}
	
	if($action == "save_activity_attendance"){
		$get = $crud->save_activity_attendance();
		if($get)
			echo $get;
	}
	
	if($action == "get_activity_att_record"){
		$get = $crud->get_activity_att_record();
		if($get)
			echo $get;
	}
	
	if($action == "get_activity_report_total"){
		$get = $crud->get_activity_report_total();
		if($get)
			echo $get;
	}
	
	if($action == "save_assignment"){
		$get = $crud->save_assignment();
		if($get)
			echo $get;
	}
	
	if($action == "get_mark_list"){
		$get = $crud->get_mark_list();
		if($get)
			echo $get;
	}
	
	if($action == "save_mark"){
		$get = $crud->save_mark();
		if($get)
			echo $get;
	}
	
	if($action == "get_exam_class_list"){
		$get = $crud->get_exam_class_list();
		if($get)
			echo $get;
	}
	
	if($action == "save_exam_attendance"){
		$get = $crud->save_exam_attendance();
		if($get)
			echo $get;
	}
	
	if($action == "get_exam_att_record"){
		$get = $crud->get_exam_att_record();
		if($get)
			echo $get;
	}
	
	if($action == "get_exam_report_total"){
		$get = $crud->get_exam_report_total();
		if($get)
			echo $get;
	}
	
	if($action == "get_exam_att_report_class"){
		$get = $crud->get_exam_att_report_class();
		if($get)
			echo $get;
	}
	
	if($action == "get_mark_report"){
		$get = $crud->get_mark_report();
		if($get)
			echo $get;
	}
	
	if($action == "get_mark_total"){
		$get = $crud->get_mark_total();
		if($get)
			echo $get;
	}
	
	if($action == "get_mark_student"){
		$get = $crud->get_mark_student();
		if($get)
			echo $get;
	}
	
	if($action == "get_student_cls"){
		$get = $crud->get_student_cls();
		if($get)
			echo $get;
	}

	// Question Paper Generation
	if($action == "save_chapter"){
		$save = $crud->save_chapter();
		if($save)
			echo $save;
	}
	ob_end_flush();
	?>
	
