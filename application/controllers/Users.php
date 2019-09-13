<?php   
	class Users extends CI_Controller	{
		function __construct(){
	 	parent::__construct();
	 	
#Start: the following lines of code enables lecturer to be able to end attendance if they are logged out by the system(session expires) and they re-login. If session expires, the lecturer will have to login again to end the attendance, because if otp_set session expires it means the lecturers login session has expired too.
	 	$n = $this->session->userdata('course_code');
						$infos = $this->users_model->check_ongoing($n);
						if ($infos) {
							foreach ($infos as $info) {
								if ($info['ongoing'] == 1) {
										$setotp = array(
											'otp_set' => TRUE
										);
									$this->session->set_userdata($setotp);
									}else{
										$this->session->unset_userdata('otp_set');
									}
							}
						}
////////////////////// End;
	 }

		public function addcourses()
		{/// To show the page when upload courses is clicked
			$data['title'] = 'Add courses';
			
			$this->load->view('templates/header', $data);
			$this->load->view('users/courses', $data);
			$this->load->view('templates/footer');
		}

		public function register()
		{// To show the page when User registration is clicked 
			$data['title'] = 'Register';
			$data['courses'] = $this->users_model->get_timetable();
			
			$this->load->view('templates/header', $data);
			$this->load->view('users/register', $data);
			$this->load->view('templates/footer');
		}

		public function regStudent()
		{//To validate and register students
			/// For ajax validation
			$data = array('status' => false, 'messages' => array());
			
			$this->form_validation->set_rules('fullname', 'Fullname', 'required');
			$this->form_validation->set_rules('regno', 'RegNo', 'required|is_unique[students.reg_number]',
				array(
                'is_unique'     => 'This %s already exists.'
        	)
		);
			$this->form_validation->set_rules('hiddengender', 'Gender', 'required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			
		if ($this->form_validation->run() === FALSE) {
			foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
		}else{
			$data['status'] = true;
			
			$this->users_model->registerStudent();
		}
		echo json_encode($data);
		}

		public function regLecturer()
		{//To validate and register lecturers
			
			$data = array('status' => false, 'messages' => array());
			
			$this->form_validation->set_rules('lecturername', 'Fullname', 'required');
			$this->form_validation->set_rules('course', 'Course', 'callback_course_check|is_unique[lecturers.course_code]',
				array(
                'is_unique'     => 'This %s has already been registered under a lecturer. Please refresh the page'
        	));
			$this->form_validation->set_rules('pword','Password','required|min_length[6]');
			$this->form_validation->set_rules('confirmpassword','Confirm Password','required|min_length[6]|matches[pword]');
			
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			
		if ($this->form_validation->run() === FALSE) {
			foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
		}else{
			$data['status'] = true;
			
			$this->users_model->registerLecturer();
			///The line directly below updates the lecturer's name in courses table in the database  
			$this->users_model->lecturerUpdate();
		}
		echo json_encode($data);
		}

		public function changePwd()
		{//To validate and register lecturers
			
			$data = array('status' => false, 'messages' => array());
			
			$this->form_validation->set_rules('oldpwd', 'Old Password', 'required|callback_checkPwd');
			$this->form_validation->set_rules('newpwd','New Password','required|min_length[6]');
			$this->form_validation->set_rules('confirmnewpwd','Confirm Password','required|min_length[6]|matches[newpwd]');
			
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			
		if ($this->form_validation->run() === FALSE) {
			foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
		}else{
			$data['status'] = true;
			
			$this->users_model->changePwd();
		}
		echo json_encode($data);
		}

		public function checkPwd($str)
        {///Callback function for password change
        	$result = $this->users_model->checkPwd($str);
                if (!$result)
                {
                        $this->form_validation->set_message('checkPwd', 'Wrong old password!');
                        return FALSE;
                }
                else
                {
                        return TRUE;
                }
        }

		public function logLecturerIn()
		{
			$data = array('success' => false, 'messages' => array());
			$data['error'] = false;
// The button clicked is saved in the variable to enable the system determine who is logging in(admin or lecturer)
			$btnClicked = $this->input->post('btnClicked');
		// Admin login details hardcoded into the system
			$admin = "admin";
			$passwrd = "password";
			
			$this->form_validation->set_rules('uname', 'Username/Coursecode', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_error_delimiters('<p class="text-danger bg-danger">', '</p>');
		
			if ($this->form_validation->run() === FALSE) {
				foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
			}else{
				if ($btnClicked == "logAdminIn") {
					$name = $this->input->post('uname');
					$password = $this->input->post('password');

					// Login Admin
				if ($name == $admin && $password == $passwrd) {
					// Create session
					$user_data = array(
						'usr_id' => 1,
						'usernme' => $name,
						'log_in' => true
					);

					$this->session->set_userdata($user_data);

					$data['success'] = true;

				} else {
					$data['error'] = true;
				}
				}

				if ($btnClicked == "logLecturerIn") {
					
					$name = $this->input->post('uname');
					$password = $this->input->post('password');

					// Login Lecturer
					$Lectures = $this->users_model->loginLecturer($name, $password);

					if ($Lectures) {
						// Create session
						foreach ($Lectures as $Lecture) {
							
						$lecturer_data = array(
							'lecturer_id' => $Lecture['id'],
							'lecturer_name' => $Lecture['lecturer_name'],
							'course_code' => $Lecture['course_code'],
							'lecturer_in' => true
						);

						}
						$this->session->set_userdata($lecturer_data);

					$data['success'] = true;

					} else {
						$data['error'] = true;
					}
				
				}
				
			}
			echo json_encode($data);
		}

		public function logout()
		{
			if ($this->session->userdata('lecturer_in')) {
//Start: makes sure that attendance is ended as lecturer logs out
				$course_code = $this->session->userdata('course_code');
				$otp = 0;
				$ongoing = 0;
				
				$this->attendance_model->takeAttendance($otp, $ongoing, $course_code);
				$this->session->unset_userdata('otp_set');
///////////End;
			// Unset lecturer user data
				$this->session->unset_userdata('lecturer_id');
				$this->session->unset_userdata('lecturer_name');
				$this->session->unset_userdata('course_code');
				$this->session->unset_userdata('lecturer_in');

				$this->session->sess_destroy();
				redirect('home');
			}elseif ($this->session->userdata('log_in')) {
			  	// Unset admin user data
				$this->session->unset_userdata('usr_id');
				$this->session->unset_userdata('usernme');
				$this->session->unset_userdata('log_in');

				$this->session->sess_destroy();	
				redirect('home');
			}else{
				redirect('about');
			}
		}

		public function dashboard()
		{
			if(!$this->session->userdata('lecturer_in'))
	 		redirect('home');
			$data['title'] = "Dashboard";
			
			$courses = $this->users_model->get_timetable();
			foreach ($courses as $course) {
				if ($course['course_code'] == $this->session->userdata('course_code')) {
					$course_id = $course['id'];
					
					$data2 = array(
					'times_held' => $course['times_held'],
					'information' => $course['information'],
					'date_lastheld' => $course['date_lastheld']
					);
				}
			}
			$data['details'] = $this->users_model->course_atlog($course_id);
			$data{'students'} = $this->users_model->get_students();

			$this->load->view('templates/header', $data);
			$this->load->view('users/dashboard', $data2);
			$this->load->view('templates/footer');
		}

		public function updateInfo()
		{
			$info = $this->input->post('info');
			$infos = $this->users_model->updateInfo($info);
			if ($infos) {
				redirect('users/dashboard');
			}
		}

		public function course_check($str)
        {///Callback function for lecturer registration
                if ($str == 'Select-Course')
                {
                        $this->form_validation->set_message('course_check', 'Please select a course');
                        return FALSE;
                }
                else
                {
                        return TRUE;
                }
        }

		public function courses()
		{//To add courses to database. Client side validation is done so no need for server side
			for ($count=0; $count < count($_POST['code']); $count++) { 
				$code = $_POST['code'][$count];
				$title = $_POST['title'][$count];
				$date = $_POST['date'][$count];
				$venue = $_POST['venue'][$count];

			$this->users_model->create_courses($code, $title, $date, $venue);
			}
		}

		public function editCourse()
		{
			$courseId = $this->uri->segment(3);
			$result = $this->users_model->editCourse($courseId);
			if ($result) {
				//Set message
				$this->session->set_flashdata('editSuccess', 'Successfully edited!!');
				redirect('users/timetable');
			}
		}

		public function timetable()
		{
			$data['title'] = 'Timetable';

			$data['courses'] = $this->users_model->get_timetable();
			
			$this->load->view('templates/header', $data);
			$this->load->view('users/timetable', $data);
			$this->load->view('templates/footer');
		}

		public function students()
		{
			$data['title'] = 'Students';

			$data['students'] = $this->users_model->get_students();
			
			$this->load->view('templates/header', $data);
			$this->load->view('users/students', $data);
			$this->load->view('templates/footer');
		}

		public function atlog()
		{
			$studentid = $this->uri->segment(4);

			$data['reg_number'] = $this->uri->segment(4);

			$data['courses'] = $this->users_model->get_timetable();
			$data['atlogs'] = $this->users_model->get_atlog($studentid);
		//The line below loads the result from the query into the page without refreshing the entire page
			$this->load->view('users/atlog', $data);
		}
	
	}

 ?>