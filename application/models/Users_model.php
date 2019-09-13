<?php 
class Users_model extends CI_Model{
		public function __construct(){
		$this->load->database();	
		}

		public function create_courses($code, $title, $date, $venue)
		{
			$data = array(
				'course_code' => $code,
				'course_title' => $title,
				'date_time' => $date,
				'venue' => $venue
			);

			return $this->db->insert('courses', $data);
		}

		public function registerStudent()
		{
			$data = array(
				'fullname' => $this->input->post('fullname'),
				'reg_number' => $this->input->post('regno'),
				'gender' => $this->input->post('hiddengender')
			);

			//Insert user
			return $this->db->insert('students', $data);
		}

		public function registerLecturer()
		{
			$data = array(
				'lecturer_name' => $this->input->post('lecturername'),
				'course_code' => $this->input->post('hidden'),
				'password' => $this->input->post('pword')
			);

			//Insert user
			return $this->db->insert('lecturers', $data);
		}

		public function lecturerUpdate()
		{
			//Update lecturer name in courses table
			$data2 = array(
				'lecturer_name' => $this->input->post('lecturername') 
			);

            $this->db->where('course_code', $this->input->post('hidden'));
			return $this->db->update('courses', $data2);
		}

		public function loginLecturer($name, $password)
		{
		// Validate
			$this->db->where('course_code', $name);
			$this->db->where('password', $password);

			$result = $this->db->get('lecturers');

			if ($result->num_rows() > 0) {
				return $result->result_array();
			} else {
				return false;
			}
			
		}

		public function check_ongoing($name)
		{
			$name = $name;
			$this->db->where('course_code', $name);
			$query = $this->db->get('courses');
			return $query->result_array();
		}

		public function get_timetable()
		{
			$this->db->order_by('id');
			$query = $this->db->get('courses');
			return $query->result_array();
		}

		public function updateInfo($info)
		{
			//Update info in courses table
			$data2 = array(
				'information' => $info
			);

            $this->db->where('course_code', $this->session->userdata('course_code'));
			return $this->db->update('courses', $data2);
		}

		public function checkPwd($str)
		{
			// Validate
			$this->db->where('id', $this->session->userdata('lecturer_id'));
			$this->db->where('password', $str);

			$result = $this->db->get('lecturers');

			if ($result->num_rows() > 0) {
				return $result->result_array();
			} else {
				return false;
			}
		}

		public function changePwd()
		{
			//Update info in courses table
			$data = array(
				'password' => $this->input->post('newpwd')
			);

            $this->db->where('id', $this->session->userdata('lecturer_id'));
			return $this->db->update('lecturers', $data);
		}

		public function editCourse($courseId)
		{
			//Update courses table
			$data = array(
				'date_time' => $this->input->post('date'),
				'venue' => $this->input->post('venue')
			);
			$this->db->where('id', $courseId);
			return $this->db->update('courses', $data);
		}

		public function get_students()
		{
			$this->db->order_by('reg_number');
			$query = $this->db->get('students');
			return $query->result_array();
		}

		public function course_atlog($course_id)
	{
		$course_id = $course_id;
		$this->db->where('course_id', $course_id);
		$this->db->order_by('times_attended', 'desc');
		$query = $this->db->get('atlog');
		return $query->result_array();
	}

		public function get_atlog($studentid)
	{
		$studentid = $studentid;
		$this->db->where('student_id', $studentid);
		$this->db->order_by('percentage', 'desc');
		$query = $this->db->get('atlog');
		return $query->result_array();
	}
}
 ?>