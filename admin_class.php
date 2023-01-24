<?php

session_start();
ini_set('display_errors', 0);
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");
require("PHPMailer/OAuth.php");
require("PHPMailer/Exception.php");
error_reporting(E_ALL);

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include './includes/db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){ 
		// extract($_POST);
		$stringuser = $_POST['username'];
		$stringpass = $_POST['password'];
        if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $stringuser))
          {
				$ip = $_SERVER['REMOTE_ADDR']; 
				$datalog = 'description = "Login with username = '.$_POST['username'].' and password ='.$_POST['password'].' " ';
				$datalog .= ", username = '$username' ";
				$datalog .= ", login_faculty_id = '9999'";
				$datalog .= ", login_type = '9999' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);

			// return 2;
        	return $ip;
		}else {
			$username = $this->db->real_escape_string($_POST['username']);
		}

		if (preg_match('/[\'^£%&*()}{#~?><>,|=_+¬-]/', $stringpass))
		{
				$ip = $_SERVER['REMOTE_ADDR']; 
				$datalog = 'description = "Login with username = '.$_POST['username'].' and password ='.$_POST['password'].' " ';
				$datalog .= ", username = '$username' ";
				$datalog .= ", login_faculty_id = '9999'";
				$datalog .= ", login_type = '9999' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);

			// return 2;
        	return $ip;
		}else {
			$password = $this->db->real_escape_string($_POST['password']);
		} 
    	
    	$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
    
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
        		$ip = $_SERVER['REMOTE_ADDR'];
            	$login_faculty_id = $_SESSION['login_id'];
				$login_type = $_SESSION['login_role'];
        		$username = $_SESSION['login_name'];
				$datalog = 'description = "Login with username = '.$_POST['username'].' and password ='.$_POST['password'].' " ';
				$datalog .= ", username = '$username' ";
				$datalog .= ", login_faculty_id = '$login_faculty_id'";
				$datalog .= ", login_type = '$login_type' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);
				return 1;
		}else{
        		$ip = $_SERVER['REMOTE_ADDR']; 
    			$login_faculty_id = $_SESSION['login_id'];
				$login_type = $_SESSION['login_role'];
				$datalog = 'description = "Login with username = '.$_POST['username'].' and password ='.$_POST['password'].' " ';
				$datalog .= ", username = '$username' ";
				$datalog .= ", login_faculty_id = '9999'";
				$datalog .= ", login_type = '9999' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);
			return 3;
		}
	}

	function logout(){
		$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_id'];
		$login_type = $_SESSION['login_role'];
		$username = $_SESSION['login_name'];
		$datalog = 'description = "LogOut"';
		$datalog .= ", username = '$username' ";
		$datalog .= ", login_faculty_id = '$login_faculty_id'";
		$datalog .= ", login_type = '$login_type' ";
		$datalog .= ", ip_address = '$ip'";

		$save = $this->db->query("INSERT INTO logs set ".$datalog);

		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login");
	}


	function save_user(){ 
		extract($_POST);
    	$username = trim($_POST['username']);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", email = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", department = '$department' ";
		$data .= ", role = '$role' ";

		if(!empty($_FILES["file"]["name"])){
			$file_name=$_FILES["file"]["name"];
			$ext=pathinfo($file_name,PATHINFO_EXTENSION);
			$newFileName=$username.'_'.time().".".$ext;
			move_uploaded_file($file_tmp=$_FILES["file"]["tmp_name"],"./assets/uploads/".$newFileName);
			$data .= ", image = '$newFileName' ";
		}

		$chk = $this->db->query("Select * from tbl_users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$add = $this->db->query("INSERT INTO tbl_users set ".$data);
		}else{
			$save = $this->db->query("UPDATE tbl_users set ".$data." where id = ".$id);
		}

		if($add){
			$email_subject = "Message from Ticket system";
			$email_message = 'Welcome to Ticket sysytem';

			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer\PHPMailer\PHPMailer();

			try {
				//Server settings
				//$mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;     //Enable verbose debug output
				
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host = "ssl://smtp.gmail.com";               //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                      //Enable SMTP authentication
				$mail->Username   = 'rajisha.abhay@gmail.com';                      //SMTP username
				$mail->Password   = 'abhay@mani';                            //SMTP password
				$mail->SMTPSecure = 'SSL';
				$mail->Port       = 465;                                       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS`
			
				//Recipients
				$mail->setFrom('rajisha.abhay@gmail.com');
				$mail->addAddress('storedacc21@gmail.com');                      //Add a recipient
		
				//Content
				$mail->isHTML(true);                                         //Set email format to HTML
				$mail->Subject = $email_subject;
				$mail->Body    = $email_message;
		
			  if($mail->send()){
					$msg = "success";
				}
				else{ 
					$msg =  "Failed";
				}
				
			} catch (Exception $e) {
				$msg =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}

		}

		if($save || ($msg == "success")){
			$ip = $_SERVER['REMOTE_ADDR']; 
			$login_faculty_id = $_SESSION['login_id'];
			$login_type = $_SESSION['login_role'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "save_user"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";
			$save = $this->db->query("INSERT INTO tbl_logs set ".$datalog);
			return 1;
		}
	}

	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tbl_users set status = 1 where id = ".$id);
		if($delete) {
			$ip = $_SERVER['REMOTE_ADDR']; 
			$login_faculty_id = $_SESSION['login_id'];
			$login_type = $_SESSION['login_role'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "delete_user"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}

	
	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "save_settings"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);

			return 1;
				}
	}

	
	function save_course(){
		extract($_POST);
		$data = " course = '$course' ";
		$data .= ", description = '$description' ";
		$check = $this->db->query("SELECT * FROM courses where course = '$course' ".(!empty($id) ? ' and id!=$id ' : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
			if(empty($id)){
				$save = $this->db->query("INSERT INTO courses set $data");
			}else{
				$save = $this->db->query("UPDATE courses set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_course(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_subject(){
		extract($_POST);  
		$data = " subject = '$subject' ";
		$data .= ", description = '$description' ";
    if($secondlanguage == '1'){
		$data .= ", second_language = '1' ";}
    else { $data .= ", second_language = '0' "; }

		$check = $this->db->query("SELECT * FROM subjects where subject = '$subject' ".(!empty($id) ? ' and id!=$id ' : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
			if(empty($id)){
				

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Save '.$subject.' subject"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);

			$save = $this->db->query("INSERT INTO subjects set $data");
			}else{
				$save = $this->db->query("UPDATE subjects set $data where id = $id");

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Update '.$subject.' subject"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		if($save){
			return 1;
		}
	}
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("UPDATE subjects set status = '1' where id = ".$id);
		if($delete){
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "delete subject id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}
	function save_class(){
		extract($_POST);
		$data = " course_id = '$course_id' ";
		$data .= ", level = '$level' ";
		$data .= ", section = '$section' ";
		$data2 = " course_id = '$course_id' ";
		$data2 .= "and level = '$level' ";
		$data2 .= "and section = '$section' ";

		$check = $this->db->query("SELECT * FROM class where $data2 ".(!empty($id) ? ' and id!=$id ' : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
			if(empty($id)){
				
				$save = $this->db->query("INSERT INTO class set $data");

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Save '.$level.' '.$section.' class"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);

			}else{
				$save = $this->db->query("UPDATE class set $data where id = $id");
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Update '.$level.' '.$section. ' class"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		if($save){
			return 1;
		}
	}
	function delete_class(){
		extract($_POST);
		$delete = $this->db->query("UPDATE class set status = 0 where id = ".$id);
		if($delete){
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Delete class id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}
	function save_faculty(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM faculty where id_no ='$id_no' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO faculty set $data");
			$nid=$this->db->insert_id;

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = trim($_SESSION['login_name']);
			$datalog = 'description = "Save faculty '.$name.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
		}else{
			$save = $this->db->query("UPDATE faculty set $data where id = $id");

						$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername =  trim($_SESSION['login_name']);
			$datalog = 'description = "Update faculty '.$name.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
		}

		if($save){
			$user = " name = '$name' ";
			$user .= ", username = '".trim($email)."' ";
			$user .= ", password = '".(md5($id_no))."' ";
			$user .= ", type = 3 ";
			if(empty($id)){
			$user .= ", faculty_id = $nid ";
			$save = $this->db->query("INSERT INTO users set $user");
			}else{
			$save = $this->db->query("UPDATE users set $user where faculty_id = $id");
			}
			return 1;
		}
	}
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("UPDATE faculty set status = '1' where id = ".$id);
		if($delete){
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Delete faculty id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		// echo "SELECT * FROM students where id_no ='$id_no' ".(!empty($id) ? " and id != {$id} " : '');
		$check = $this->db->query("SELECT * FROM students where id_no ='$id_no' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}

		if(empty($id)){
			$save = $this->db->query("INSERT INTO students set $data");
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Save student"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
		}else{
			// echo "Select * from students where id = $id";
			$qry = $this->db->query("Select * from students where id = $id");
		$pre_data_array = array();
		while($row = $qry->fetch_assoc()){
			$pre_data_array = $row;
		}

		$pre_data = json_encode($pre_data_array);

		 $array_final = preg_replace('/"([a-zA-Z_]+[a-zA-Z0-9_]*)":/','$1:',$pre_data);

		 $pre_data = $replaced = str_replace('"', "'", $array_final);
			
			$save = $this->db->query("UPDATE students set $data where id = $id");

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Update student id '.$id.' and data from '.$pre_data.' to '.$data.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
		}

		if($save){
			
			return 1;
		}
	}
	function delete_student(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM students where id = ".$id);
		if($delete){
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Delete student id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}
	function save_class_subject(){
		extract($_POST);
		$data = "";
		$data2 = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
					$data2 .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
					$data2 .= "and $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM class_subject where $data2 ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO class_subject set $data");

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Save class subject"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
		}else{
			$save = $this->db->query("UPDATE class_subject set $data where id = ".$id);

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "update class subject '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$savelog = $this->db->query("INSERT INTO logs set ".$datalog);
		}

		if($save){
			return 1;
		}
	}
	function delete_class_subject(){
		extract($_POST);
		$delete = $this->db->query("UPDATE class_subject set status = '1' where id = ".$id);
		if($delete){
			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "delete class subject '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);
			return 1;
		}
	}
	function get_class_list(){
		extract($_POST);
		$data = array();

		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$class_subject_id'");
		if(isset($att_id)){
			$record = $this->db->query("SELECT * FROM attendance_record where attendance_id='$att_id' ");
		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
			}
		}
		}
		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_attendance(){
		extract($_POST);
		// print_r($_POST);
		foreach($student_id as $k => $v) {
			$stud_id[] = $k;
		}

		$json = json_encode($stud_id);
		$json =  str_replace("[","(",$json);
		$json =  str_replace("]",")",$json);

		$getstud = $this->db->query("SELECT s.id FROM `students` s INNER JOIN class_subject cs ON cs.class_id = s.class_id WHERE s.id NOT IN ".$json." and cs.id = ".$class_subject_id);


		$stud = array();

		while($row = $getstud->fetch_assoc()){
			$stud[] = $row;
		}

		
// print_r($student_id);
		$data  = " class_subject_id = '$class_subject_id' ";
		$data .= ", section = '$section' ";
		$data .= ", doc = '$doc' ";
		$data2  = " class_subject_id = '$class_subject_id' ";
		$data2 .= "and section = '$section' ";
		$data2 .= "and doc = '$doc' ";
		//echo "SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : '');
		$check = $this->db->query("SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
				
				$save = $this->db->query("INSERT INTO attendance_list set $data ");
			if($save){
				$id = $this->db->insert_id;

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Add attendance list id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);

				foreach ($stud as $key => $value) {

					$data = " attendance_id = '$id' ";
					$data .= ", student_id = '".$value['id']."'";
					$data .= ", type = '3' ";
					// echo "INSERT INTO attendance_record set $data";
					$this->db->query("INSERT INTO attendance_record set $data ");
				}

				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= ", student_id = '$k' ";
					$data .= ", type = '$type[$k]' ";
						  $this->db->query("INSERT INTO attendance_record set $data ");
				}
			}
		}else{
			$save = $this->db->query("UPDATE attendance_list set $data where id=$id ");
			if($save){

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Update attendance list id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";

			$save = $this->db->query("INSERT INTO logs set ".$datalog);

				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= "and student_id = '$k' ";
						  $this->db->query("UPDATE attendance_record set type = '$type[$k]' where $data ");
				}
			}
		}

		if($save){
			return 1;
		}
	}

	function get_att_record(){
		extract($_POST); 

		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where  cs.id = '$class_subject_id'");

		$record = $this->db->query("SELECT ar.*,a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id where a.class_subject_id='$class_subject_id' and a.section = '$section' and a.doc = '$doc' ORDER BY `ar`.`student_id` ASC");
		$data = array();

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
		}
		}
		else{
			$data['record'] = 'No record Found';
		}

		$qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `class`, s.subject, co.course FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN subjects s on s.id = cs.subject_id  INNER JOIN courses co on co.id = c.course_id WHERE cs.id = {$class_subject_id} ");
		foreach($qry->fetch_array() as $k => $v){
			$data['details'][$k] =$v; 
		}
		$data['details']['doc'] =date('M d, Y',strtotime($doc)); 

		return json_encode($data);
	}
	function get_att_report(){
		extract($_POST);
		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$class_subject_id'");

		$record = $this->db->query("SELECT ar.*,a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id where a.class_subject_id='$class_subject_id' and a.doc >= '$doc_start' AND a.doc <= '$doc_end' ");
		$data = array();
		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}
		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][$row['student_id']][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
		}
		}
		else{
			$data['record'] = 'No record Found';
		}

		$noc = $this->db->query("SELECT * FROM attendance_list where class_subject_id='$class_subject_id' and doc >= '$doc_start' AND doc <= '$doc_end'");
				$data['details']['noc'] = $noc->num_rows;


				$qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `class`, s.subject, co.course FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN subjects s on s.id = cs.subject_id  INNER JOIN courses co on co.id = c.course_id WHERE cs.id = {$class_subject_id} ");
				foreach($qry->fetch_array() as $k => $v){
					$data['details'][$k] =$v; 
				}

		$data['details']['doc'] =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		return json_encode($data);
	}

	function get_att_report_class(){
		extract($_POST); 
		$data = array();

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id' GROUP BY s.id");

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		$getthead = $this->db->query("SELECT s.id, s.subject FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where cs.class_id = '$class_id' and a.doc = '$doc' GROUP BY ar.attendance_id ORDER BY `a`.`id` DESC");

		while($row = $getthead->fetch_assoc()){
			$data['thead'][] = $row;
		}

		$getclas = $this->db->query("SELECT concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id'");

		$class = $getclas->fetch_assoc();

		$table = ''; $i=1;
		$date =date('M d, Y',strtotime($doc)); 
		$table = '<table width="100%"> <tr class="text-center"><td width="50%"><p>Class: <b class="class">'.$class['class'].'</b></p></td>
				<td width="50%"><p>Date: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';

		$table .= "<table class='table table-bordered table-hover att-list '><thead>
		<tr><th class='text-center' width='5%'>#</th>
				<th width='20%' class='text-center'>Student</th>";
		$subject = array();
		foreach($data['thead'] as $key => $value){
			$table .= "<th class='text-center'>". $value['subject']. "</th>";
			array_push($subject, $value['id']);
		}
		$subject = array_unique($subject);
		
			$table .= "</tr></thead><tbody>";

		foreach($data['data'] as $key => $value){
			$table .= "<tr class='text-center'><td class='text-center'>".$i."</td><td class='text-center' >". $value['name']. "</td>";
			$student_id = $value['id'];
			$data = array();
			foreach ($subject as $key => $value) {
				echo $sub = $value;
		// echo "SELECT ar.*,s.id as sbjid, s.subject, a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where  a.doc = '$doc' and ar.student_id = '$student_id' and s.id = ".$sub;
		$strecord = $this->db->query("SELECT ar.*,s.id as sbjid, s.subject, a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where  a.doc = '$doc' and ar.student_id = '$student_id' and s.id = ".$sub);
            
		if($strecord->num_rows > '0')
		{
			$data = array(); $data1 = array();
			while($row = $strecord->fetch_assoc()){
				$data[$row['attendance_id']] = $row['type'];
			}
			print_r($data);
			
			$status = '';
			foreach ($data as $key => $value) {
				if($value['type'] == '0'){
					$status = '<td><p  style="color:red;">Absent1</p></td>';
					} elseif($value['type'] == '1'){
						$status = '<td><p  style="color:Green;">Present1</p></td>';
					} elseif($value['type'] == '2'){
						$status = '<td><p style="color:Orange;">Late1</p></td>';
					} elseif($value['type'] == '3') {
						$status = '<td>-</td>';
					}
				$table .= $status;
			}

			}  else {
			$status = '<td><p  style="color:red;">Absent</p></td>';
			$table .= $status;
        	
        	}
            
            }

			$table .= "</tr>";

			$i++;
		} 
			$table .= "</tbody>";
			return $table;
	}

	function get_att_report_student(){
		extract($_POST); 
		$data = array();

		$getstudent = $this->db->query("SELECT concat(name,' (',id_no,')' ) as st_name FROM `students` WHERE id='$student_id'");

		$student = $getstudent->fetch_assoc();

		$record = $this->db->query("SELECT ar.*,a.class_subject_id, a.doc, s.subject as subject FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where ar.student_id='$student_id' and a.doc >= '$doc_start' AND a.doc <= '$doc_end' ORDER BY a.doc ASC");

		while($row = $record->fetch_assoc()){
			$data['record'][] = $row;
		}

		$table = ''; $i=1; $status="";
		$date = date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end));
		$table = '<table width="100%"> <tr class="text-center"><td width="50%"><p>Student: <b class="class">'.$student['st_name'].'</b></p></td>
				<td width="50%"><p>Month: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';

		$table .= "<table class='table table-bordered table-hover att-list '><thead>
		<tr class='text-center'><th  width='5%'>#</th>
				<th width='40%' >Date</th><th>Subject</th><th>Status</th></thead>";

				foreach ($data['record'] as $key => $value) {
					if($value['type'] == '0'){
					$status = '<td><p  style="color:red;">Absent</p></td>';
					} elseif($value['type'] == '1'){
						$status = '<td><p  style="color:Green;">Present</p></td>';
					} elseif($value['type'] == '2'){
						$status = '<td><p style="color:Orange;">Late</p></td>';
					} elseif($value['type'] == '3') {
						$status = '<td>-</td>';
					}
					$table .= "<tr class='text-center'><td>".$i."</td><td>".$value['doc']."</td><td>".$value['subject']."</td>".$status."</tr>";

					$i++;
				}


		$table .= "</tbody>";
			return $table;

	}

	function get_att_report_daterange(){
		extract($_POST); 

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where cs.id = '$class_id' ");

		$record = $this->db->query("SELECT a.*,cs.class_id, ar.type, ar.student_id FROM attendance_list a INNER JOIN class_subject cs on cs.id = a.class_subject_id INNER JOIN attendance_record ar on ar.attendance_id = a.id where cs.class_id='$class_id' and a.doc >= '$doc_start' AND a.doc <=  '$doc_end'; ");
		$data = array();

		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][$row['student_id']][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
		}
		}

		$noc = $this->db->query("SELECT a.*,cs.class_id FROM attendance_list a INNER JOIN class_subject cs on cs.id = a.class_subject_id where cs.class_id='$class_id' and a.doc >= '$doc_start' AND a.doc <= '$doc_end' GROUP BY a.doc");
				$data['details']['noc'] = $noc->num_rows;


				$qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `class`, s.subject, co.course FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN subjects s on s.id = cs.subject_id  INNER JOIN courses co on co.id = c.course_id WHERE cs.id = {$class_id} ");
				foreach($qry->fetch_array() as $k => $v){
					$data['details'][$k] =$v; 
				}

		$data['details']['doc'] =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		return json_encode($data);
	}


	function get_att_report_count_periods(){
		extract($_POST);
		$get = $this->db->query("SELECT * FROM students where class_id = '$class_id'");

		$record = $this->db->query("SELECT ar.*, s.name FROM `attendance_record` ar INNER JOIN students s ON s.id = ar.student_id WHERE (attendance_id IN(SELECT id FROM `attendance_list` WHERE doc >= '$doc_start' AND doc <= '$doc_end')) AND (student_id IN (SELECT id FROM students WHERE class_id = '$class_id'));");
		$data = array();
		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}
		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][$row['student_id']][] = $row;
		}
		}

		$qry = $this->db->query("SELECT concat(level,'-',section) as 'class' FROM  class WHERE id = $class_id");

				foreach($qry->fetch_array() as $k => $v){
					$data['details'][$k] =$v; 
				}

		$data['details']['doc'] =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		return json_encode($data);
	}
	
	function get_att_report_total(){
		extract($_POST);

		$data = array();
		$getattlist = $this->db->query("SELECT DISTINCT  al.doc FROM `attendance_list` al INNER JOIN class_subject cs ON cs.id = al.class_subject_id WHERE (al.doc >= '$doc_start' AND al.doc <= '$doc_end') and cs.class_id = '$class_id' ORDER BY al.doc ASC");

		if($getattlist->num_rows > 0){
			while($row = $getattlist->fetch_assoc()){
				$data['attlist'][] = $row;
			}
		}

		$get = $this->db->query("SELECT * FROM students where class_id = '$class_id'");
		while($row = $get->fetch_assoc()){
			$data['student'][] = $row;
		}

		$table = ''; $i=1; $tr=""; $thead = ''; $dates = array(); 
		$status = '';

		$getclas = $this->db->query("SELECT c.level, concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id'");

		while($row = $getclas->fetch_assoc()){
			$level = $row['level'];
			$class = $row['class'];
		}

		$date =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		$table = '<table width="100%"> <tr class="text-center"><td width="50%" id="class_name"><p>Class: <b class="class">'.$class.'</b></p></td>
				<td width="50%"><p>Date: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';


		$table .= '<table class="table table-bordered table-hover att-list">';

		$thead = '<thead><tr class="text-center"><td>#</td><td>Student</td>';

		$tr = '<tbody>';

	foreach ($data['student'] as $key => $value) {

		$totalpresent = 0;
		$totalhalf = 0;
		$totalabsent = 0;

		$name = $value['name'];
		$stud_id = $value['id'];

		$tr .= '<tr class="text-center"><td>'.$i.'</td><td>'.$name.'</td>';
		foreach ($data['attlist'] as $key => $value) {

			$doc = $value['doc'];
			$day = date('d-m-Y',strtotime($doc));

			array_push($dates, $day);
// echo "SELECT COUNT(ar.student_id) as count FROM `attendance_record` ar INNER JOIN attendance_list al ON al.id = ar.attendance_id WHERE student_id = '$stud_id' and al.doc = '$doc' AND (ar.type = '1' OR ar.type = '2')  AND al.class_subject_id = '$class_id'";
			$count = $this->db->query("SELECT COUNT(ar.student_id) as count FROM `attendance_record` ar INNER JOIN attendance_list al ON al.id = ar.attendance_id WHERE student_id = '$stud_id' and al.doc = '$doc' AND (ar.type = '1' OR ar.type = '2')");

			$count = $count->fetch_assoc(); 

			if($level <=5){
				if($count['count'] >= '2') {
					$totalpresent++;
					$status = '<td><p style="color:Green">P</p></td>';
				}elseif($count['count'] == '1') {
					$totalhalf++ ;
					$status = '<td><p style="color:Orange">H</p></td>';
				}else {
					$totalabsent++;
					$status = '<td><p style="color:Red">A</p></td>';
				}
			} else {
				if($count['count'] >= '3') {
					$totalpresent++;
					$status = '<td><p style="color:Green">P</p></td>';
				}elseif($count['count'] == '2') {
					$totalhalf++ ;
					$status = '<td><p style="color:Orange">H</p></td>';
				}else {
					$totalabsent++;
					$status = '<td><p style="color:Red">A</p></td>';
				}
			}
			$tr .= $status;
		}
		$tr .= '<td><span style="color:Green">P-'.$totalpresent.'</span>, <span style="color:Orange">H-'.$totalhalf.'</span>, <span style="color:Red">A-'.$totalabsent.'</span></td>';
		$tr .= '</tr>';
		$i++;
	}
	$dates = array_unique($dates);
		foreach ($dates as $key => $value) {
				$thead .= '<td>'.$value.'</td>'; 
		}
			


	$thead .= '<td>Count</td></tr></thead>';
	$table .= $thead;
	$table .= $tr;
	$table .= '</tbody></table>';

	return $table;
	}

	function delete_attendance(){
		extract($_POST);		

		$data  = " class_subject_id = '$class_subject_id' ";
		$data .= ", doc = '$doc' ";
		$data2  = " class_subject_id = '$class_subject_id' ";
		$data2 .= "and doc = '$doc' ";
		// echo "(SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : '' );
		$check = $this->db->query("SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;

		if($check > 0){
			// echo "SELECT id FROM attendance_list where $data2 ";
			$id_list = $this->db->query("SELECT id FROM attendance_list where $data2 ");
			$id = $id_list->fetch_assoc(); 
			$id = $id['id'];
			$delete = $this->db->query("DELETE FROM attendance_list where id = ".$id);
			$delete_record = $this->db->query("DELETE FROM attendance_record where attendance_id = ".$id);

			$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
			$login_type = $_SESSION['login_type'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Delete attendance list id '.$id.'"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";


			$save = $this->db->query("INSERT INTO logs set ".$datalog);

			return 1;	
		}else{
			return 2;
			exit;
		}
	}

	function save_assignment(){
		extract($_POST);

		$data = " class_subject_id = '$class_subject_id' ";
		$data .= ", topic = '$topic' ";
		$data .= ", activities = '$activities' ";
		$data .= ", participation = '$participation' ";
		$data .= ", hwork = '$hwork' ";
		$data .= ", status = '0' ";
		// echo "SELECT * FROM assignments where class_subject_id = '$class_subject_id' ".(!empty($id) ? ' and id!=$id ' : '');
		// $check = $this->db->query("SELECT * FROM assignments where class_subject_id = '$class_subject_id' ".(!empty($id) ? ' and id!=$id ' : ''))->num_rows;
		// if($check > 0){
		// 	return 2;
		// 	exit;
		// }

			if(empty($id)){
				$save = $this->db->query("INSERT INTO assignments set $data");
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
				$login_type = $_SESSION['login_type'];
				$loginusername = $_SESSION['login_name'];
				$datalog = 'description = "Save Assignment"';
				$datalog .= ", username = '$loginusername' ";
				$datalog .= ", login_faculty_id = '$login_faculty_id'";
				$datalog .= ", login_type = '$login_type' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);
			}else{
				$save = $this->db->query("UPDATE assignments set $data where id = $id");
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
				$login_type = $_SESSION['login_type'];
				$loginusername = $_SESSION['login_name'];
				$datalog = 'description = "update Assignment id '.$id.'"';
				$datalog .= ", username = '$loginusername' ";
				$datalog .= ", login_faculty_id = '$login_faculty_id'";
				$datalog .= ", login_type = '$login_type' ";
				$datalog .= ", ip_address = '$ip'";
				$save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		if($save)
			return 1;
		}

	function get_class_student_list(){
		extract($_POST);
		$data = array();

		$get = $this->db->query("SELECT * FROM `students` WHERE class_id ='$class_id'");
		if(isset($att_id)){
			$record = $this->db->query("SELECT * FROM attendance_record where attendance_id='$att_id' ");
		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
			}
		}
		}
		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}
		return json_encode($data);
	}


	function save_activity_attendance(){
		extract($_POST);
		$data  = " activity_class_id = '$activity_class_id' ";
		$data .= ", doc = '$doc' ";
		$data2  = " activity_class_id = '$activity_class_id' ";
		$data2 .= "and doc = '$doc' ";
		// echo "SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and attendance_id != {$id} " : '');
		$check = $this->db->query("SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
				// echo "INSERT INTO attendance_list set $data ";
				$save = $this->db->query("INSERT INTO attendance_list set $data ");
				
			if($save){
				$id = $this->db->insert_id;
				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= ", student_id = '$k' ";
					$data .= ", type = '$type[$k]' ";
						  $this->db->query("INSERT INTO attendance_record set $data ");

						  
				}
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Save activity attendance id '.$id.'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		}else{
			$save = $this->db->query("UPDATE attendance_list set $data where id=$id ");
			if($save){
				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= "and student_id = '$k' ";
						  $this->db->query("UPDATE attendance_record set type = '$type[$k]' where $data ");
				}
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Update activity attendance id '.$id.'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		}

		if($save){
			return 1;
		}
	}

	function get_activity_att_record(){
		extract($_POST); 

		$get = $this->db->query("SELECT * from students WHERE class_id = '$activity_class_id'");

		$record = $this->db->query("SELECT ar.*,a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id where a.activity_class_id='$activity_class_id' and a.doc = '$doc' ORDER BY `ar`.`student_id` ASC");
		$data = array();

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
		}
		}
		else{
			$data['record'] = 'No record Found';
		}

		$qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `class`, co.course FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN courses co on co.id = c.course_id WHERE c.id = {$activity_class_id} ");
		// $qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `clas` FROM  class WHERE id = '$activity_class_id'");
		foreach($qry->fetch_array() as $k => $v){
			$data['details'][$k] =$v; 
		}
		$data['details']['subject'] = 'Activity';
		$data['details']['doc'] =date('M d, Y',strtotime($doc)); 

		return json_encode($data);
	}

	function get_activity_report_total(){
		extract($_POST);
		// echo "SELECT DISTINCT  al.doc FROM `attendance_list` al INNER JOIN class_subject cs ON cs.id = al.activity_class_id WHERE (al.doc >= '$doc_start' AND al.doc <= '$doc_end') and cs.class_id = '$activity_class_id' ORDER BY al.doc ASC";
		$data = array();
		$getattlist = $this->db->query("SELECT DISTINCT doc FROM `attendance_list` WHERE (doc >= '$doc_start' AND doc <= '$doc_end') and activity_class_id = '$activity_class_id' ORDER BY doc ASC");

		if($getattlist->num_rows > 0){
			while($row = $getattlist->fetch_assoc()){
				$data['attlist'][] = $row;
			}
		}

		$get = $this->db->query("SELECT * FROM students where class_id = '$activity_class_id'");
		while($row = $get->fetch_assoc()){
			$data['student'][] = $row;
		}

		$table = ''; $i=1; $tr=""; $thead = ''; $dates = array(); 
		$status = '';

		$getclas = $this->db->query("SELECT c.level, concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$activity_class_id'");

		while($row = $getclas->fetch_assoc()){
			$level = $row['level'];
			$class = $row['class'];
		}

		$date =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		$table = '<table width="100%"> <tr class="text-center"><td width="50%" id="class_name"><p>Class: <b class="class">'.$class.'</b></p></td>
				<td width="50%"><p>Date: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';


		$table .= '<table class="table table-bordered table-hover att-list">';

		$thead = '<thead><tr class="text-center"><td>#</td><td>Student</td>';

		$tr = '<tbody>';

	foreach ($data['student'] as $key => $value) {

		$totalpresent = 0;
		$totallate = 0;
		$totalabsent = 0;

		$name = $value['name'];
		$stud_id = $value['id'];

		$tr .= '<tr class="text-center"><td>'.$i.'</td><td>'.$name.'</td>';
		foreach ($data['attlist'] as $key => $value) {

			$doc = $value['doc'];
			$day = date('d-m-Y',strtotime($doc));

			array_push($dates, $day);

			$type = $this->db->query("SELECT ar.* FROM `attendance_record` ar INNER JOIN attendance_list al ON al.id = ar.attendance_id WHERE student_id = '$stud_id' and al.doc = '$doc' AND al.activity_class_id = '$activity_class_id'");

			$type = $type->fetch_assoc(); 

				if($type['type'] == '1'){
					$totalpresent++;
					$status = '<td><p style="color:Green">P</p></td>';
				}elseif($type['type'] == '2') {
					$totallate++;
					$status = '<td><p style="color:Orange">L</p></td>';
				}else {
					$totalabsent++;
					$status = '<td><p style="color:Red">A</p></td>';
				}
			
			$tr .= $status;
		}
		$tr .= '<td><span style="color:Green">P-'.$totalpresent.'</span>, <span style="color:orange"> L-'.$totallate.'</span>, <span style="color:Red"> A-'.$totalabsent.'</span></td>';
		$tr .= '</tr>';
		$i++;
	}
    
	$dates = array_unique($dates);
		foreach ($dates as $key => $value) {
				$thead .= '<td>'.$value.'</td>'; 
		}
			


	$thead .= '<td>Count</td></tr></thead>';
	$table .= $thead;
	$table .= $tr;
	$table .= '</tbody></table>';

	return $table;
	}

	public function get_mark_list(){
		extract($_POST);

		$data = array();

		$table = ''; $i=1; $tr=""; $thead = ''; $dates = array(); 
		$status = '';

		extract($_POST);
		$data = array();

		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$class_subject_id' order by s.id");

		$qry = $this->db->query("SELECT co.course, concat(c.level,'-',c.section) as `class`, s.subject, f.name FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN subjects s on s.id = cs.subject_id  INNER JOIN courses co on co.id = c.course_id INNER JOIN faculty f on cs.faculty_id = f.id WHERE cs.id = {$class_subject_id} ");
			foreach($qry->fetch_array() as $k => $v){
				$data['details'][] =$v; 	
			}
			$class =  implode(" | ",array_unique($data['details']));

		$record = $this->db->query("SELECT * FROM marks where class_subject_id='$class_subject_id'  order by student_id");

		if($record->num_rows > 0){
			// while($row = $record->fetch_assoc()){
			// 	$written_outoff = $row[written_outoff];
			// 	$obj_outoff = $row[obj_outoff];
			// }

			$written_outoff_1 = $this->db->query("SELECT written_outoff FROM marks where class_subject_id='$class_subject_id' ");
			$written_outoff = $written_outoff_1->fetch_assoc(); 

			$obj_outoff_1 = $this->db->query("SELECT obj_outoff FROM marks where class_subject_id='$class_subject_id' ");
			$obj_outoff = $obj_outoff_1->fetch_assoc();
			
			$assesment_name_1 = $this->db->query("SELECT assesment_name_1 FROM marks where class_subject_id='$class_subject_id' ");
			$as_name_1 = $assesment_name_1->fetch_assoc();

			$assesment_name_2 = $this->db->query("SELECT assesment_name_2 FROM marks where class_subject_id='$class_subject_id' ");
			$as_name_2 = $assesment_name_2->fetch_assoc();

			$assesment_name_3 = $this->db->query("SELECT assesment_name_3 FROM marks where class_subject_id='$class_subject_id' ");
			$as_name_3 = $assesment_name_3->fetch_assoc();

			$assesment_name_4 = $this->db->query("SELECT assesment_name_4 FROM marks where class_subject_id='$class_subject_id' ");
			$as_name_4 = $assesment_name_4->fetch_assoc();

			$table = '<div class="row" style="padding:5px;">
			<div class="col-sm-4">
			Class: <b class="class">'.$class.'</b>
			</div>
			<label for="" class="mt-2">Written Exam Max. Mark</label>
			<div class="col-sm-2">
				<input typt="text" name="written_outoff" class="form-control" value="'.$written_outoff['written_outoff'].'" placeholder="required" required>
			</div>
			<label for="" class="mt-2">Obj / MCQ Max. Mark</label>
			<div class="col-sm-2">
				<input typt="text" name="obj_outoff" class="form-control" value="'.$obj_outoff['obj_outoff'].'" placeholder="required" required>
			</div>
			</div>';

			

			$table .= '<table class="table table-bordered table-hover mark-list" id="table_mark">';

			$thead = '<thead><tr class="text-center"><td rowspan="2"><b>#</b></td><td rowspan="2"><b>Student</b></td><td rowspan="2"><b>Written Exam</b></td><td rowspan="2"><b>Obj / MCQ</b></td><td colspan="5"><b>Assessment (Max Mark for each assessment : 10)</b></td><td rowspan="2"><b>Unit Test</b></td><td rowspan="2"><b>Total</b></td><td rowspan="2"><b>Percentage</b></td></tr>
			<tr class="text-center"><td ><b><textarea rows="2" cols="15" name="assesment_name1" required>'.$as_name_1['assesment_name_1'].' </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name2" required>'.$as_name_2['assesment_name_2'].' </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name3" required>'.$as_name_3['assesment_name_3'].' </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name4" required>'.$as_name_4['assesment_name_4'].' </textarea></b></td><td ><b>Total</b></td></tr>
			</thead>';

			$tr = '<tbody>';

			while($row = $record->fetch_assoc()){
				$name_list = $this->db->query("SELECT name FROM students where id= $row[student_id]");
				$name = $name_list->fetch_assoc();

				$tr .= '<tr class="text-center"><td>'.$i.'</td><td>'.$name['name'].' <input type="hidden" class="form-control" name="std_id['.$row[student_id].']" value="'.$row[student_id].'"> </td><td><input type="text" class="form-control text-center" name="written['.$row[student_id].']" value="'.$row[written].'"></td><td><input type="text" class="form-control text-center" name="obj['.$row[student_id].']" value="'.$row[obj].'"></td><td><input type="text" class="form-control text-center" name="assesment1['.$row[student_id].']" value="'.$row[assesment_1].'"></td><td><input type="text" class="form-control text-center" name="assesment2['.$row[student_id].']" value="'.$row[assesment_2].'"></td><td><input type="text" class="form-control text-center" name="assesment3['.$row[student_id].']" value="'.$row[assesment_3].'"></td><td><input type="text" class="form-control text-center" name="assesment4['.$row[student_id].']" value="'.$row[assesment_4].'"></td><td><p>'.$row[total].'</p></td><td><input type="text" class="form-control text-center" name="unit_test['.$row[student_id].']" value="'.$row[unit_test].'"></td><td><p>'.$row[subtotal].'</p></td><td><p>'.$row[percentage].'%</p></td></tr>';
				$i++;
			}
			
		} else {

		while($row = $get->fetch_assoc()){
			$data['student'][] = $row;
		}
		$table = '<div class="row" style="padding:5px;">
			<div class="col-sm-4">
			Class: <b class="class">'.$class.'</b>
			</div>
			<label for="" class="mt-2">Written Exam Max. Mark <span style="color:red">*</span></label>
			<div class="col-sm-2">
				<input typt="text" name="written_outoff" class="form-control" value="" style="border:1px solid red" placeholder="required" required>
			</div>
			<label for="" class="mt-2">Obj / MCQ Max. Mark <span style="color:red">*</span></label>
			<div class="col-sm-2">
				<input typt="text" name="obj_outoff" class="form-control required" value="" style="border:1px solid red" placeholder="required" required>
			</div> </div>';

		


		$table .= '<table class="table table-bordered table-hover mark-list" id="table_mark">';

		$thead = '<thead><tr class="text-center"><td rowspan="2"><b>#</b></td><td rowspan="2"><b>Student</b></td><td rowspan="2"><b>Written Exam</b></td><td rowspan="2"><b>Obj / MCQ</b></td><td colspan="4"><b>Assessment  (Max Mark for each assessment : 10)</b></td><td rowspan="2"><b>Unit Test</b></td></tr>

		<tr class="text-center"><td ><b><textarea rows="2" cols="15" name="assesment_name1" required> </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name2" required> </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name3" required> </textarea></b></td><td ><b><textarea rows="2" cols="15" name="assesment_name4" required> </textarea></b></td></tr>
		</thead>';

		$tr = '<tbody>';
		

	foreach ($data['student'] as $key => $value) {
		$name = $value['name'];
		$stud_id = $value['id'];

		$tr .= '<tr class="text-center" id="'.$stud_id.'"><td>'.$i.'</td><td>'.$name.' <input type="hidden" class="form-control" name="std_id['.$stud_id.']" value="'.$stud_id.'"> </td><td><input type="text" class="form-control text-center" name="written['.$stud_id.']" value="0" ></td><td><input type="text" class="form-control text-center" name="obj['.$stud_id.']" value="0"></td><td><input type="text" class="form-control text-center" name="assesment1['.$stud_id.']" value="0"></td><td><input type="text" class="form-control text-center" name="assesment2['.$stud_id.']" value="0"></td><td><input type="text" class="form-control text-center" name="assesment3['.$stud_id.']" value="0"></td><td><input type="text" class="form-control text-center" name="assesment4['.$stud_id.']" value="0"></td><td><input type="text" class="form-control text-center" name="unit_test['.$stud_id.']" value="0"></td></tr>';


		$i++;

	}
	
}
	$table .= $thead;
	$table .= $tr;
	$table .= '</tbody></table>';
	return $table;
	}


	function get_exam_class_list(){
		extract($_POST);
		$data = array();

		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$exam_class_id'");
		if(isset($att_id)){
			$record = $this->db->query("SELECT * FROM attendance_record where attendance_id='$att_id' ");
		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
			}
		}
		}
		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}
		return json_encode($data);
	}	

	public function save_mark(){
		extract($_POST);
		// print_r($_POST);
		foreach($std_id as $k => $v) {

			// echo "SELECT * FROM marks where class_subject_id = '$class_subject_id' and  student_id = '$std_id[$v]'";
			$record = $this->db->query("SELECT * FROM marks where class_subject_id = '$class_subject_id' and  student_id = '$std_id[$v]'");
			if($record->num_rows > 0){

				$outoff = $written_outoff + $obj_outoff + 20;

				$total = ($assesment1[$v] + $assesment2[$v] + $assesment3[$v] + $assesment4[$v])/4;
				$subtotal = $written[$v] + $obj[$v] + $total + $unit_test[$v];
				$percentage = ($subtotal / $outoff) * 100;

				$total = number_format((float)$total, 2, '.', '');
				$subtotal = number_format((float)$subtotal, 2, '.', '');
				$percentage = number_format((float)$percentage, 0, '.', '');

				$data = " class_subject_id = '$class_subject_id' ";
				$data .= ", written_outoff = '$written_outoff' ";
				$data .= ", obj_outoff = '$obj_outoff' ";
				$data .= ", student_id = '$std_id[$v]' ";
				$data .= ", written = '$written[$v]' ";
				$data .= ", obj = '$obj[$v]' ";
				$data .= ", assesment_name_1 = '$assesment_name1' ";
				$data .= ", assesment_name_2 = '$assesment_name2' ";
				$data .= ", assesment_name_3 = '$assesment_name3' ";
				$data .= ", assesment_name_4 = '$assesment_name4' ";
				$data .= ", assesment_1 = '$assesment1[$v]' ";
				$data .= ", assesment_2 = '$assesment2[$v]' ";
				$data .= ", assesment_3 = '$assesment3[$v]' ";
				$data .= ", assesment_4 = '$assesment4[$v]' ";
				$data .= ", total = '$total' ";
				$data .= ", unit_test = '$unit_test[$v]' ";
				$data .= ", subtotal = '$subtotal' ";
				$data .= ", percentage = '$percentage' ";
				// echo "UPDATE marks set $data where class_subject_id = '$class_subject_id' and  student_id = '$std_id[$v]'";
				$save = $this->db->query("UPDATE marks set $data where class_subject_id = '$class_subject_id' and  student_id = '$std_id[$v]'");
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Update Mark where class_subject_id = '.$class_subject_id.' and  student_id = '.$std_id[$v].'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			} else {

				$outoff = $written_outoff + $obj_outoff + 20;

				$total = ($assesment1[$v] + $assesment2[$v] + $assesment3[$v] + $assesment4[$v])/4;
				$subtotal = $written[$v] + $obj[$v] + $total + $unit_test[$v];
				$percentage = ($subtotal / $outoff) * 100;

				$total = number_format((float)$total, 2, '.', '');
				$subtotal = number_format((float)$subtotal, 2, '.', '');
				$percentage = number_format((float)$percentage, 0, '.', '');

				$data = " class_subject_id = '$class_subject_id' ";
				$data .= ", written_outoff = '$written_outoff' ";
				$data .= ", obj_outoff = '$obj_outoff' ";
				$data .= ", student_id = '$std_id[$v]' ";
				$data .= ", written = '$written[$v]' ";
				$data .= ", obj = '$obj[$v]' ";
				$data .= ", assesment_name_1 = '$assesment_name1' ";
				$data .= ", assesment_name_2 = '$assesment_name2' ";
				$data .= ", assesment_name_3 = '$assesment_name3' ";
				$data .= ", assesment_name_4 = '$assesment_name4' ";
				$data .= ", assesment_1 = '$assesment1[$v]' ";
				$data .= ", assesment_2 = '$assesment2[$v]' ";
				$data .= ", assesment_3 = '$assesment3[$v]' ";
				$data .= ", assesment_4 = '$assesment4[$v]' ";
				$data .= ", total = '$total' ";
				$data .= ", unit_test = '$unit_test[$v]' ";
				$data .= ", subtotal = '$subtotal' ";
				$data .= ", percentage = '$percentage' ";
				// echo "INSERT INTO marks set $data ";
				$save = $this->db->query("INSERT INTO marks set $data ");
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Save Mark where class_subject_id = '.$class_subject_id.' and student_id = '.$std_id[$v].'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		}
					if($save){
						return 1;
					} else {
						return 2;
					}		
	}

	function save_exam_attendance(){
		extract($_POST);
		$data  = " exam_class_id = '$exam_class_id' ";
		$data .= ", doc = '$doc' ";
		$data2  = " exam_class_id = '$exam_class_id' ";
		$data2 .= "and doc = '$doc' ";
		// echo "SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and attendance_id != {$id} " : '');
		$check = $this->db->query("SELECT * FROM attendance_list where $data2 ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
				// echo "INSERT INTO attendance_list set $data ";
				$save = $this->db->query("INSERT INTO attendance_list set $data ");
			if($save){
				$id = $this->db->insert_id;
				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= ", student_id = '$k' ";
					$data .= ", type = '$type[$k]' ";
						  $this->db->query("INSERT INTO attendance_record set $data ");
				}
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Save exam attendance id = '.$id.'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		}else{
			$save = $this->db->query("UPDATE attendance_list set $data where id=$id ");
			if($save){
				foreach($student_id as $k => $v) {
					$data = " attendance_id = '$id' ";
					$data .= "and student_id = '$k' ";
						  $this->db->query("UPDATE attendance_record set type = '$type[$k]' where $data ");
				}
				$ip = $_SERVER['REMOTE_ADDR']; $login_faculty_id = $_SESSION['login_faculty_id'];
						  $login_type = $_SESSION['login_type'];
						  $loginusername = $_SESSION['login_name'];
						  $datalog = 'description = "Update exam attendance id = '.$id.'"';
						  $datalog .= ", username = '$loginusername' ";
						  $datalog .= ", login_faculty_id = '$login_faculty_id'";
						  $datalog .= ", login_type = '$login_type' ";
						  $datalog .= ", ip_address = '$ip'";
						  $save = $this->db->query("INSERT INTO logs set ".$datalog);
			}
		}

		if($save){
			return 1;
		}
	}

	function get_exam_att_record(){
		extract($_POST); 

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$exam_class_id'");

		$record = $this->db->query("SELECT ar.*,a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id where a.exam_class_id='$exam_class_id' and a.doc = '$doc' ORDER BY `ar`.`student_id` ASC");
		$data = array();

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		if($record->num_rows > 0){
			while($row = $record->fetch_assoc()){
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
		}
		}
		else{
			$data['record'] = 'No record Found';
		}

		$qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `class`, co.course FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN courses co on co.id = c.course_id WHERE c.id = {$exam_class_id} ");
		// $qry = $this->db->query("SELECT concat(c.level,'-',c.section) as `clas` FROM  class WHERE id = '$exam_class_id'");
		foreach($qry->fetch_array() as $k => $v){
			$data['details'][$k] =$v; 
		}
		$data['details']['subject'] = 'Exam';
		$data['details']['doc'] =date('M d, Y',strtotime($doc)); 
	// print_r($data);
		return json_encode($data);
	}

	public function get_exam_report_total(){
		extract($_POST);
		// echo "SELECT DISTINCT  al.doc FROM `attendance_list` al INNER JOIN class_subject cs ON cs.id = al.exam_class_id WHERE (al.doc >= '$doc_start' AND al.doc <= '$doc_end') and cs.class_id = '$exam_class_id' ORDER BY al.doc ASC";
		$data = array();
		$getattlist = $this->db->query("SELECT DISTINCT doc FROM `attendance_list` WHERE (doc >= '$doc_start' AND doc <= '$doc_end') and exam_class_id = '$exam_class_id' ORDER BY doc ASC");

		if($getattlist->num_rows > 0){
			while($row = $getattlist->fetch_assoc()){
				$data['attlist'][] = $row;
			}
		}

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$exam_class_id'");
		while($row = $get->fetch_assoc()){
			$data['student'][] = $row;
		}

		$table = ''; $i=1; $tr=""; $thead = ''; $dates = array(); 
		$status = '';

		$getclas = $this->db->query("SELECT c.level, concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$exam_class_id'");

		while($row = $getclas->fetch_assoc()){
			$level = $row['level'];
			$class = $row['class'];
		}

		$date =date('M d, Y',strtotime($doc_start)).' - '.date('M d, Y',strtotime($doc_end)); 

		$table = '<table width="100%"> <tr class="text-center"><td width="50%" id="class_name"><p>Class: <b class="class">'.$class.'</b></p></td>
				<td width="50%"><p>Date: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';


		$table .= '<table class="table table-bordered table-hover att-list">';

		$thead = '<thead><tr class="text-center"><td>#</td><td>Student</td>';

		$tr = '<tbody>';

		foreach ($data['student'] as $key => $value) {

		$totalpresent = 0;
		$totallate = 0;
		$totalabsent = 0;

		$name = $value['name'];
		$stud_id = $value['id'];

		$tr .= '<tr class="text-center"><td>'.$i.'</td><td>'.$name.'</td>';
		foreach ($data['attlist'] as $key => $value) {

			$doc = $value['doc'];
			$day = date('d-m-Y',strtotime($doc));

			array_push($dates, $day);

			$type = $this->db->query("SELECT ar.* FROM `attendance_record` ar INNER JOIN attendance_list al ON al.id = ar.attendance_id WHERE student_id = '$stud_id' and al.doc = '$doc' AND al.exam_class_id = '$exam_class_id'");

			$type = $type->fetch_assoc(); 

				if($type['type'] == '1'){
					$totalpresent++;
					$status = '<td><p style="color:Green">P</p></td>';
				}elseif($type['type'] == '2') {
					$totallate++;
					$status = '<td><p style="color:Orange">L</p></td>';
				}else {
					$totalabsent++;
					$status = '<td><p style="color:Red">A</p></td>';
				}
			
			$tr .= $status;
			}
			$tr .= '<td><span style="color:Green">P-'.$totalpresent.'</span>, <span style="color:orange"> L-'.$totallate.'</span>, <span style="color:Red"> A-'.$totalabsent.'</span></td>';
			$tr .= '</tr>';
			$i++;
		}
    
		$dates = array_unique($dates);
		foreach ($dates as $key => $value) {
				$thead .= '<td>'.$value.'</td>'; 
		}
			


		$thead .= '<td>Count</td></tr></thead>';
		$table .= $thead;
		$table .= $tr;
		$table .= '</tbody></table>';

		return $table;
	}

	public function get_exam_att_report_class(){
		extract($_POST); 
		$data = array();

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id' GROUP BY s.id");

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		$getthead = $this->db->query("SELECT s.id, s.subject FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.exam_class_id INNER JOIN subjects s on s.id = cs.subject_id where cs.class_id = '$class_id' and a.doc = '$doc' GROUP BY ar.attendance_id ORDER BY `a`.`id` DESC");

		while($row = $getthead->fetch_assoc()){
			$data['thead'][] = $row;
		}

		$getclas = $this->db->query("SELECT concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id'");

		$class = $getclas->fetch_assoc();

		$table = ''; $i=1;
		$date =date('M d, Y',strtotime($doc)); 
		$table = '<table width="100%"> <tr class="text-center"><td width="50%"><p>Class: <b class="class">'.$class['class'].'</b></p></td>
				<td width="50%"><p>Date: <b class="doc">'.$date.'</b></p>
				</td> </tr> </table>';

		$table .= "<table class='table table-bordered table-hover att-list '><thead>
		<tr><th class='text-center' width='5%'>#</th>
				<th width='20%' class='text-center'>Student</th>";
		$subject = array();
		foreach($data['thead'] as $key => $value){
			$table .= "<th class='text-center'>". $value['subject']. "</th>";
			array_push($subject, $value['id']);
		}
		
			$table .= "</tr></thead><tbody>";

		foreach($data['data'] as $key => $value){
			$table .= "<tr class='text-center'><td class='text-center'>".$i."</td><td class='text-center' >". $value['name']. "</td>";
			$student_id = $value['id'];
			$data = array();
			foreach ($subject as $key => $value) {
				$sub = $value;

		$strecord = $this->db->query("SELECT ar.*,s.id as sbjid, s.subject, a.exam_class_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id INNER JOIN class_subject cs on cs.id = a.exam_class_id INNER JOIN subjects s on s.id = cs.subject_id where  a.doc = '$doc' and ar.student_id = '$student_id' and s.id = ".$sub);
            
		if($strecord->num_rows > '0')
		{
			$data = array();
			while($row = $strecord->fetch_assoc()){
				$data['strecord'][$row['subject']] = $row;
			}
			$status = '';
			foreach ($data['strecord'] as $key => $value) {
				if($value['type'] == '0'){
					$status = '<td><p  style="color:red;">Absent</p></td>';
					} elseif($value['type'] == '1'){
						$status = '<td><p  style="color:Green;">Present</p></td>';
					} elseif($value['type'] == '2'){
						$status = '<td><p style="color:Orange;">Late</p></td>';
					} elseif($value['type'] == '3') {
						$status = '<td>-</td>';
					}
				$table .= $status;
			}

			}  else {
			$status = '<td><p  style="color:red;">Absent</p></td>';
			$table .= $status;
        	
        	}
            
            }

			$table .= "</tr>";

			$i++;
		} 
			$table .= "</tbody>";
			return $table;
	}



	public function get_mark_report(){
		extract($_POST);

		$data = array();

		$table = ''; $i=1; $tr=""; $thead = ''; $dates = array(); 
		$status = '';

		extract($_POST);
		$data = array();

		$get = $this->db->query("SELECT s.*, cs.subject_id, su.second_language FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id INNER JOIN subjects su ON cs.subject_id = su.id where (IF(su.second_language = '1', s.second_language = cs.subject_id OR s.second_language_two = cs.subject_id OR s.second_language_three = cs.subject_id OR s.second_language_four = cs.subject_id,'1')) and cs.id = '$class_subject_id' order by s.id");

		$qry = $this->db->query("SELECT co.course, concat(c.level,'-',c.section) as `class`, s.subject, f.name FROM class_subject  cs INNER JOIN class c on c.id = cs.class_id INNER JOIN subjects s on s.id = cs.subject_id  INNER JOIN courses co on co.id = c.course_id INNER JOIN faculty f on cs.faculty_id = f.id WHERE cs.id = {$class_subject_id} ");
			foreach($qry->fetch_array() as $k => $v){
				$data['details'][] =$v; 	
			}
			$class =  implode(" | ",array_unique($data['details']));

		$record = $this->db->query("SELECT * FROM marks where class_subject_id='$class_subject_id'  order by student_id");

		if($record->num_rows > 0){

			$written_outoff_1 = $this->db->query("SELECT written_outoff FROM marks where class_subject_id='$class_subject_id' ");
			$written_outoff = $written_outoff_1->fetch_assoc(); 

			$obj_outoff_1 = $this->db->query("SELECT obj_outoff FROM marks where class_subject_id='$class_subject_id' ");
			$obj_outoff = $obj_outoff_1->fetch_assoc();
        	$subtotal = $written_outoff['written_outoff'] + $obj_outoff['obj_outoff'];

			$table = '<div class="row" style="padding:5px;">
			<div class="col-sm-12">
			<label for="" class="mt-2"> Class: <b class="class">'.$class.'</b>  </label>
			</div>
            
			</div>';

			

			$table .= '<table class="table table-bordered table-hover mark-list" id="table_mark">';

			$thead = '<thead><tr class="text-center"><td ><b>#</b></td><td><b>Student</b></td><td><b>Written Exam ('.$written_outoff['written_outoff'].')</b></td><td><b>Obj / MCQ ('.$obj_outoff['obj_outoff'].')</b></td>
			<td ><b>Total ('.$subtotal.')</b></td><td ><b>Total (100)</b></td></tr>	</thead>';

			$tr = '<tbody>';

			while($row = $record->fetch_assoc()){
				$name_list = $this->db->query("SELECT name FROM students where id= $row[student_id]");
				$name = $name_list->fetch_assoc();
				// $total = (($row[written] + $row[obj]) / ($row['written_outoff'] + $row['obj_outoff']))* 100;
				$total = (($row[written] + $row[obj])* 100) / ($row['written_outoff'] + $row['obj_outoff']);
				$tr .= '<tr class="text-center"><td>'.$i.'</td><td>'.$name['name'].' <input type="hidden" class="form-control" name="std_id['.$row[student_id].']" value="'.$row[student_id].'"> </td>
                <td>'.$row[written].'</td>
                <td>'.$row[obj].'</td>
                 <td>'.($row[written] + $row[obj]).'</td>
               	<td>'.ceil($total).'</td></tr>';
				$i++;
			}
			
		} else {
			$tr = 'No record found';
		}
	
		$table .= $thead;
		$table .= $tr;
		$table .= '</tbody></table>';
		return $table;
	}


	public function get_mark_total(){
		extract($_POST); 
		$data = array();

		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id' GROUP BY s.id");

		while($row = $get->fetch_assoc()){
			$data['data'][] = $row;
		}

		$getthead = $this->db->query("SELECT s.id, s.subject FROM marks m INNER JOIN class_subject cs on cs.id = m.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where cs.class_id = '$class_id'  group by m.class_subject_id");

		while($row = $getthead->fetch_assoc()){
			$data['thead'][] = $row;
		}

		$getclas = $this->db->query("SELECT concat(c.level,'-',c.section) as `class` FROM `class` c inner join class_subject cs on cs.class_id = c.id where c.id = '$class_id'");

		$class = $getclas->fetch_assoc();

		$table = ''; $i=1;

		$table = '<table width="100%"> <tr class="text-center"><td width="50%"><p>Class: <b class="class">'.$class['class'].'</b></p></td>
				 </tr> </table>';

		$table .= "<table class='table table-bordered table-hover mark-list '><thead>
		<tr><th class='text-center' width='5%'>#</th>
				<th width='20%' class='text-center'>Student</th>";
		$subject = array();
		foreach($data['thead'] as $key => $value){
			$table .= "<th class='text-center'>". $value['subject']. "</th>";
			array_push($subject, $value['id']);
		}
		
			$table .= "</tr></thead><tbody>";

		foreach($data['data'] as $key => $value){
			$table .= "<tr class='text-center'><td class='text-center'>".$i."</td><td class='text-center' >". $value['name']. "</td>";
			$student_id = $value['id'];
			$data = array();
			foreach ($subject as $key => $value) {
				$sub = $value;
		
		$strecord = $this->db->query("SELECT m.*,s.id as sbjid, s.subject, m.class_subject_id FROM marks m INNER JOIN class_subject cs on cs.id = m.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where m.student_id = '$student_id' and s.id = ".$sub);
            
		if($strecord->num_rows > '0')
		{
			$data = array();
			while($row = $strecord->fetch_assoc()){
				$data['strecord'][$row['subject']] = $row;
			}

			$status = '';
			foreach ($data['strecord'] as $key => $value) {
            
            $total = (($value[written] + $value[obj])* 100) / ($value['written_outoff'] + $value['obj_outoff']);

					$status = '<td>'.ceil($total).'</td>';

				$table .= $status;
			}

			}  else {
        	$status = '<td>   </td>';
			$table .= $status;
        	
        	}
            
            }

			$table .= "</tr>";

			$i++;
		} 
			$table .= "</tbody>";
			return $table;
	}

	public function get_mark_student(){
		extract($_POST); 
		$data = array();

		$getstudent = $this->db->query("SELECT concat(s.name,' (',s.id_no,')' ) as st_name, concat(c.level,' - ',c.section) as class_id FROM `students` s inner join class c on s.class_id = c.id WHERE s.id='$student_id'");

		$student = $getstudent->fetch_assoc();
// echo "SELECT m.*, s.subject as subject FROM marks m INNER JOIN class_subject cs on cs.id = m.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where m.student_id='$student_id'  ORDER BY m.order_by ASC";
		$record = $this->db->query("SELECT m.*, s.subject as subject FROM marks m INNER JOIN class_subject cs on cs.id = m.class_subject_id INNER JOIN subjects s on s.id = cs.subject_id where m.student_id='$student_id'  ORDER BY s.order_by ASC");

		while($row = $record->fetch_assoc()){
			$data['record'][] = $row;
		}

		$table = ''; $i=1; $status="";
		
		$table = '<table class="center" width="100%"> <tr class="text-center"><td width="70%"><p>Name of the Child : <b class="class">'.$student['st_name'].'</b></p></td> <td><p >Class : <b class="class">'.$student['class_id'].'</b></td></tr></table> <br>';

		$table .= "<table class='table table-bordered table-hover mark-list center'><thead>
		<tr class='text-center'><th  width='5%'>#</th>
				<th width='55%' >Subject</th><th  width='40%' >Marks In Percentage</th></thead>";

				foreach ($data['record'] as $key => $value) {
					
					$total = (($value[written] + $value[obj])* 100) / ($value['written_outoff'] + $value['obj_outoff']);
					$table .= "<tr class='text-center'><td>".$i."</td><td>".$value['subject']."</td><td>".ceil($total)."</td></tr>";

					$i++;
				}


		$table .= "</tbody>";
			return $table;
	}

	public function get_student_cls(){
		extract($_POST); 
		$data = [];
		$get = $this->db->query("SELECT s.* FROM students s where s.class_id = '$class_id'");

		while($row = $get->fetch_assoc()){
			$data[$row['id']] = $row['name'];
		}
		// print_r($data);
		// return $data;
		echo json_encode($data);
	}
// -------------------------------------------------------
// 				Question paper generation
// -------------------------------------------------------
	public function save_chapter(){
		extract($_POST);

		$data = " title = '$title' ";
		$data .= ", cls_sub_id  = '$id'";
		$data .= ", description = '$content' ";
		$data .= ", status  = '$status'";

		if(empty($cid)){
			$save = $this->db->query("INSERT INTO chapters set ".$data);	
			$cid = $this->db->insert_id;	
		}else{
			$save = $this->db->query("UPDATE chapters set ".$data." where id = ".$cid);
		}
		if($save){
			$ip = $_SERVER['REMOTE_ADDR']; 
			$login_faculty_id = $_SESSION['login_id'];
			$login_type = $_SESSION['login_role'];
			$loginusername = $_SESSION['login_name'];
			$datalog = 'description = "Ticket add / update"';
			$datalog .= ", username = '$loginusername' ";
			$datalog .= ", login_faculty_id = '$login_faculty_id'";
			$datalog .= ", login_type = '$login_type' ";
			$datalog .= ", ip_address = '$ip'";
			$save = $this->db->query("INSERT INTO tbl_logs set ".$datalog);

			return $id;
		}
	}


}

