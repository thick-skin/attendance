<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Attendance_model extends CI_Model {


	public function takeAttendance($otp, $ongoing, $course_code, $datecheck, $date)
	{
		$data = array(
				'otp' => $otp,
				'ongoing' => $ongoing
			);

            $this->db->where('course_code', $course_code);
  #this makes sure that the times_held column is updated only when the attendance is started and not ended and also makes sure that the column is only updated once in a day
            if ($ongoing == 1 && $datecheck != $date) {
                $this->db->set('times_held', 'times_held + 1', FALSE);
                $this->db->set('date_lastheld', $date);
            }
			return $this->db->update('courses', $data);
	}

	public function regnoExists($str)
	{
		$this->db->where('reg_number', $str);

			$result = $this->db->get('students');

			if ($result->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
	}

	public function otpExists($str)
	{
		$this->db->where('otp', $str);

			$result = $this->db->get('courses');

			if ($result->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
	}

	public function checkIfMarked($course, $regno, $date)
	{
		// Validate
			$this->db->where('student_id', $regno);
			$this->db->where('course_id', $course);
			$this->db->where('date', $date);

			$result = $this->db->get('atlog');

			if ($result->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
	}

	public function markedBefore($course, $regno)
	{
		// Validate
			$this->db->where('student_id', $regno);
			$this->db->where('course_id', $course);

			$result = $this->db->get('atlog');

			if ($result->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
	}

	public function updateAttendance($course, $regno, $date)
	{
		$this->db->where('student_id', $regno);
		$this->db->where('course_id', $course);

		$this->db->set('times_attended', 'times_attended + 1', FALSE);
		$this->db->set('date', $date);
		
		return $this->db->update('atlog');
	}

	public function markAttendance($course, $regno, $date)
	{
		$data = array(
				'student_id' => $regno,
				'course_id' => $course,
				'times_attended' => 1,
				'date' => $date
			);

			//Insert user
			return $this->db->insert('atlog', $data);
	}

}