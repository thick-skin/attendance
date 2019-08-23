<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Attendance extends CI_Controller {
	 function __construct(){
	 	parent::__construct();
	 	date_default_timezone_set('Africa/Lagos');// to make sure date/time is correct
	 	if(!$this->session->userdata('lecturer_in'))
	 		redirect('home');//makes sure lecturer is logged in
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
	 }

	public function startAttendance()
	{// To create OTP and start session for attendance
		$course_code = $this->session->userdata('course_code');
		$otp = rand(345678,765432);
		$ongoing = 1;
			// save otp in database
		$this->attendance_model->takeAttendance($otp, $ongoing, $course_code);

		$att = array(
			'otp_set' => TRUE
		);
		$this->session->set_userdata($att);

		redirect('users/timetable');
	}

	public function endAttendance()
	{// TO destroy otp and unset session
		$course_code = $this->session->userdata('course_code');
		$otp = 0;
		$ongoing = 0;

		$this->attendance_model->takeAttendance($otp, $ongoing, $course_code);
		$this->session->unset_userdata('otp_set');

		redirect('users/timetable');
	}

	public function markAttendance()
	{// Validation and updating students attendance records
		$data = array('success' => false, 'messages' => array());
		$data['error'] = false;

		$this->form_validation->set_rules('regno', 'Reg Number', 'required|callback_regExists');
		$this->form_validation->set_rules('otp', 'OTP', 'required|callback_otpExists');
		$this->form_validation->set_error_delimiters('<p class="text-danger bg-danger">', '</p>');
		
		if ($this->form_validation->run() === FALSE) {
			foreach ($_POST as $key => $value) {
				$data['messages'][$key] = form_error($key);
			}
		}else{
			# check if date field of table for the course has already been updated for the reg no for that day.
				$course = $this->input->post('hiddenCourseId');
				$regno = $this->input->post('regno');
				$date =  date("d.m.y");

				$result = $this->attendance_model->checkIfMarked($course, $regno, $date);

				if ($result) {
					$data['error'] = true;
				} else {
					//check if user already exists for the course in session
					$result = $this->attendance_model->markedBefore($course, $regno);
					if ($result) {
						# update if user exists
						$this->attendance_model->updateAttendance($course, $regno, $date);
					}else{
						# add user if user doesn't exist
						$this->attendance_model->markAttendance($course, $regno, $date);
					}
					$data['success'] = true;
				}
		}
		echo json_encode($data);
	}

	public function regExists($str)
        {// Callback function to check if the reg no was registered
        	$result = $this->attendance_model->regnoExists($str);
        	if ($result)
        	{
        		return TRUE;
        	}
        	else
        	{
        		$this->form_validation->set_message('regExists', 'Reg No not registered');
        		return FALSE;
        	}
        }

        public function otpExists($str)
        {// Callback fun to check if the correct otp for the course in session was input
        	$result = $this->attendance_model->otpExists($str);
        	if ($result)
        	{
        		return TRUE;
        	}
        	else
        	{
        		$this->form_validation->set_message('otpExists', 'Incorrect OTP!');
        		return FALSE;
        	}
        }

}