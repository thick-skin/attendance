<?php 
class Pages extends CI_Controller{
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

	public function view($page = 'home'){
		if (!file_exists(APPPATH.'views/pages/'.$page.'.php')) {
			show_404();
		}
		$data['title'] = ucfirst($page);
		if ($this->session->userdata('lecturer_in')) {
			$data['username'] = $this->session->userdata('lecturer_name');
		}elseif ($this->session->userdata('log_in')) {
			$data['username'] = 'Admin';	
		}else{
			$data['username'] = 'Guest';
		}

		$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer');
	}
}