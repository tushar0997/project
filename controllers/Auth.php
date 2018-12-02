<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends CI_Controller {

	 function __construct() {
        parent::__construct();
        $this->load->model('general_model');
        $this->load->library('google');
    }

	public function index(){

		
		$data['google_login_url']=$this->google->get_login_url();
		//print_r($data['google_login_url']);
		$this->load->view('home_view',$data);

	}

	public function oauth2callback(){
		$google_data=$this->google->validate();
	
		//echo '<pre>'; print_r($google_data); echo '</pre>';

		$insert = $this->general_model->SaveForm("g_user",$google_data);
		
		// 
		$user=$this->general_model->GetAllInfo('g_user','id',array('email'=>$google_data['email']));
		//print_r($user); exit();
		if(count($user)>0){
			$session_data=array(
				'name'=>$google_data['name'],
				'email'=>$google_data['email'],
				'profile_pic'=>$google_data['profile_pic'],
				'link'=>$google_data['link'],
				'sess_logged_in'=>1
				);
			$this->session->set_userdata($session_data);
			
		}
		else{
			
			$session_data=array(
				'name'=>$google_data['name'],
				'email'=>$google_data['email'],
				'profile_pic'=>$google_data['profile_pic'],
				'link'=>$google_data['link'],
				'sess_logged_in'=>1
				);
			$this->session->set_userdata($session_data);
		}

		//echo  $this->session->userdata('email');
		
		$this->load->view('home_view');

	}

	

	public function logout(){
		session_destroy();
		unset($_SESSION['access_token']);
		$session_data=array(
				'sess_logged_in'=>0

				);
		$this->session->set_userdata($session_data);
		redirect(base_url());
	}
}