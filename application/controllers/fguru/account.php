<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('muser','',TRUE);
		$this->load->model('mmenu','',TRUE);
		
		if($this->session->userdata('level') == '' || $this->session->userdata('kd_ta') == '')
		{
			redirect('login/logout');
		}
	}

	function index()
	{
		$data = array(	  
			'akun'			=> 'class="active"',
			'main'			=> 'fguru/akun',
			'type'			=> 'a',
			'menu'			=> $this->mmenu->getTeamTeaching(),
		);
			
		$this->load->view('fguru/template',$data);
	}
	
	function submit_account()
	{
		if($this->muser->cekUserEdit() > 0)
		{
			echo "Maaf Username Telah Digunakan";
		}
		elseif($this->muser->cekUserPass() > 0)
		{
			echo "Maaf Username dan Password Tidak Cocok";
		}
		else
		{
			$kueri = $this->muser->updateMember();
			if($this->db->affected_rows() > 0)
			{
				echo "OK";
			}
			else
			{
				echo "Maaf Gagal Menyimpan Member Baru";
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
