<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nilai extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('mnilai','',TRUE);
		$this->load->model('mrumus','',TRUE);
		$this->load->model('mmenu','',TRUE);
		
		if($this->session->userdata('level') == '' || $this->session->userdata('kd_ta') == '')
		{
			redirect('login/logout');
		}
	}

	function index($id=NULL)
	{
		if($id == "")
		{
			$data = array(
				'main'		=> 'fguru/nilai',
				'nilai'		=> 'class="active"',
				'type'		=> 'a',
				'mapels'	=> $this->mnilai->getMapells(),
				'menu'		=> $this->mmenu->getTeamTeaching()
			);
			$this->load->view('fguru/template',$data);
		}
		else
		{
			$mapel = $this->mnilai->getMapel($id);
			redirect('fguru/nilai/daftar/'.$id.'/'.$mapel);	
		}
	}
	
	function submit_nilai()
	{
		$kunci = $this->input->post('mapel',TRUE);
		redirect('fguru/nilai/nilai/'.$kunci);	
	}
	
	function daftar($id,$jenis=NULL,$kls=NULL,$kes=NULL,$jns=1,$short_by='b.nama',$short_order='asc')
	{
		$sAkses = $this->mnilai->getAspek($id);		

		$this->load->library('arey');

		if($id == "" || $id == 0)
		{
			redirect('fguru/nilai');		
		}
		$this->load->helper('shorting');
		$nilai = $this->mnilai->getMenu($id);		

		$query = $this->mnilai->getSiswaKelas($kls,$short_by,$short_order);
		$tingkatane = $this->mnilai->getTingkatane(1,$id);
		if($tingkatane == 0)
		{
			$this->message->set('information','Aspek Nilai Belum Dibuat');
		}
		$j_keterampilan = 1;
		if($jns == 2)
		{
			$j_keterampilan = $this->mnilai->getTingkatane(2,$id);			
			if($j_keterampilan == 0)
			{
				$this->message->set('information','Aspek Nilai Keterampilan Belum Dibuat');		
			}
		}
		$klassss = $this->mnilai->getKelas($id);
		if($klassss == 0)
		{
			redirect('nilai/pilih');
		}
				
		$data = array(
			'kueri' 		=> $query->result(),
			'jum_kueri'		=> $query->num_rows(),
			'main'			=> 'fguru/daftar_nilai',
			'nilai'			=> 'class="active"',
			'sort_by' 		=> $short_by,
			'sort_order' 	=> $short_order,
			'type'			=> 'b',
			'daftar'		=> 'class="active"',
			'urs'			=> 3,
			'ref'			=> 'refresh',
			'id'			=> $id,
			'ref'			=> 'refresh/'.$id,
			'klas'			=> $klassss,
			'kls'			=> $kls,
			'menu'			=> $this->mmenu->getTeamTeaching(),
			'mnu'			=> $nilai,
			'jenis'			=> $jenis,
			'tingkat'		=> 1,
			'kes'			=> $kes,
			'jenise'		=> $this->arey->getJenis(),
			'jns'			=> $jns,
			'tingkatane'	=> $tingkatane,
			'j_keterampilan'=> $j_keterampilan,
			'isi'			=> 'daftar',
			'penget'		=> $this->arey->getPengetahuan()
		);

		$this->load->view('fguru/template',$data);	
	}
	
	function daftar_uts($id,$jenis=NULL,$kls=NULL,$kes=NULL,$short_by='b.nama',$short_order='asc')
	{
		if($id == "" || $id == 0)
		{
			redirect('fguru/nilai');		
		}
		$this->load->helper('shorting');
		$this->load->library('arey');

		$nilai = $this->mnilai->getMenu($id);

		$query = $this->mnilai->getSiswaKelas($kls,$short_by,$short_order);
		$klassss = $this->mrumus->getKelas($id);
		if($klassss == 0)
		{
			redirect('nilai/pilih');
		}
				
		$data = array(
			'kueri' 		=> $query->result(),
			'jum_kueri'		=> $query->num_rows(),
			'main'			=> 'fguru/daftar_nilai',
			'nilai'			=> 'class="active"',
			'sort_by' 		=> $short_by,
			'sort_order' 	=> $short_order,
			'type'			=> 'b',
			'daftart'		=> 'class="active"',
			'urs'			=> 3,
			'ref'			=> 'refresh',
			'id'			=> $id,
			'ref'			=> 'refresh/'.$id,
			'klas'			=> $klassss,			
			'kls'			=> $kls,
			'menu'			=> $this->mmenu->getTeamTeaching(),
			'mnu'			=> $nilai,
			'jenis'			=> $jenis,
			'tingkat'		=> 2,
			'kes'			=> $kes,
			'jns'			=> 1,
			'tingkatane'	=> '',
			'isi'			=> 'tes',
			'penget'		=> ''
		);

		$this->load->view('fguru/template',$data);	
	}
	
	function daftar_uas($id,$jenis=NULL,$kls=NULL,$short_by='b.nama',$short_order='asc')
	{
		if($id == "" || $id == 0)
		{
			redirect('fguru/nilai');		
		}
		$this->load->helper('shorting');
		$this->load->library('arey');
		
		$nilai = $this->mnilai->getMenu($id);		

		$query = $this->mnilai->getSiswaKelas($kls,$short_by,$short_order);
		$klassss = $this->mrumus->getKelas($id);
		if($klassss == 0)
		{
			redirect('nilai/pilih');
		}
				
		$data = array(
			'kueri' 		=> $query->result(),
			'jum_kueri'		=> $query->num_rows(),
			'main'			=> 'fguru/daftar_nilai',
			'nilai'			=> 'class="active"',
			'sort_by' 		=> $short_by,
			'sort_order' 	=> $short_order,
			'type'			=> 'b',
			'daftars'		=> 'class="active"',
			'urs'			=> 3,
			'ref'			=> 'refresh',
			'id'			=> $id,
			'ref'			=> 'refresh/'.$id,
			'klas'			=> $klassss,			
			'kls'			=> $kls,
			'menu'			=> $this->mmenu->getTeamTeaching(),
			'mnu'			=> $nilai,
			'jenis'			=> $jenis,
			'tingkat'		=> 3,
			'kes'			=> "",
			'jns'			=> 1,
			'tingkatane'	=> '',
			'isi'			=> 'tes',
			'penget'		=> ''
		);
		$this->load->view('fguru/template',$data);	
	}	
	
	function submit_kategori($alamat,$id,$jenis)
	{
		$kelas = $this->input->post('kelas',TRUE);
		$ke = $this->input->post('ke',TRUE);
		$jenise = $this->input->post('jenise',TRUE);
		if($kelas == "")
		{
			redirect('fguru/nilai/'.$alamat.'/'.$id.'/'.$jenis);
		}
		else
		{
			redirect('fguru/nilai/'.$alamat.'/'.$id.'/'.$jenis.'/'.$kelas.'/'.($ke + 1).'/'.$jenise);
		}	
	}
	
	function submit_kategoris($id)
	{
		$kelas = $this->input->post('kelas',TRUE);
		if($kelas == "")
		{
			redirect('fguru/nilai/daftar_ulangan/'.$id);
		}
		else
		{
			redirect('fguru/nilai/daftar_ulangan/'.$id.'/'.$kelas);
		}	
	}
	
	function input_nilai($nip,$id,$kelas,$stat,$biji,$tingkat,$kode,$ket=NULL,$pengetahuan=1)
	{
		$data = array();

		$this->mnilai->addNilai($nip,$id,$kelas,$stat,$biji,$tingkat,$kode,$ket,$pengetahuan);
		if($this->db->affected_rows() > 0)
		{
			$data = array(
				'statuse'		=> 'ok',
				'kode'			=> $this->db->insert_id(),
				'awal'			=> $nip."_".$id."_".$kelas."__".$tingkat,
				'newK'			=> $nip."_".$id."_".$kelas."_".$this->db->insert_id()."_".$tingkat,
				'k2'			=> $nip."_".$id."_".$kelas."__".$tingkat."_2",
				'k3'			=> $nip."_".$id."_".$kelas."__".$tingkat."_3"
			);			
		}
		else
		{
			$data = array(
				'status'		=> 'gagal'
			);
		}

		echo json_encode($data);
	}
	
	function cek_nilai($nip,$id,$kelas,$tingkat,$ket=NULL)
	{
		$kueri = $this->mnilai->cekNilai($nip,$id,$kelas,$tingkat,$ket);
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			$hasil = array(
				'status'	=> 'ok',
				'id'		=> $data->id_nilai,
				'nilai'		=> $data->nilai
			);			
		}
		else
		{
			$hasil = array(
				'status'	=> 'gagal'
			);		
		}
		echo json_encode($hasil);
	}
	
	function input_nilaiu($nip,$id,$kelas,$biji)
	{
		$this->mnilai->addNilai($nip,$id,$kelas,0,$biji,1);
		if($this->db->affected_rows() > 0)
		{
			echo "ok";
		}
		else
		{
			echo "gagal";
		}
	}
	
	function update_nilai($id,$biji,$pengetahuan)
	{
		$this->mnilai->updateNilai($id,$biji,$pengetahuan);
		if($this->db->affected_rows() > 0)
		{
			echo "ok";
		}
		else
		{
			echo "gagal";
		}
	}
	
	function upload($id,$kls,$tingkat,$ket)
	{
		$config['upload_path'] = './temp/';
		$config['allowed_types'] = 'csv|xls';
		$config['max_size']	= '50000';	
		$config['remove_spaces'] = TRUE;			
				
		$this->load->library('upload', $config);
		$uplod = $this->upload->do_upload("uploadfile");
		
		$file = str_replace(" ","_",$_FILES['uploadfile']['name']);
			
		if ( !$uplod )
		{		    	
			echo "gagal";
		}
		else
		{
			$filename = './temp/'.$file;
		
			$this->load->library('excel');
			$data = $this->excel->reader($filename);
			$no = 0;
			foreach($data as $dt_excel)
			{
				if($no > 0)
				{
					if($this->mrumus->cekNilais($dt_excel['A'],$id,$kls,$tingkat,$ket) == 0)
					{
						$this->mrumus->addNilai($dt_excel['A'],$id,$kls,$dt_excel['C'],$tingkat,$ket);
					}
					else
					{	
						$this->mrumus->updateNilais($dt_excel['A'],$id,$kls,$dt_excel['C'],$tingkat,$ket);
					}
				}
				$no++;
			}
			if($this->db->affected_rows() > 0)
			{
				echo "ok";
			}
			else
			{
				echo "gagals";
			}
			unlink($filename);
		}
	}

	function sample($id)
	{
		/*$this->load->helper('download');

		$data = file_get_contents(base_url()."sample/Nilai.csv");
		$name = 'Nilai.csv';

		force_download($name, $data); */
		$query = $this->mrumus->getExportSiswaKelas($id);
			 
		$this->load->helper('xls');
		query_to_xls($query, TRUE, 'daftar_nilai.xls');
	}

	function form($id,$jns,$kode,$tingkat)
	{
		$this->load->library('arey');
		$pecah = explode("_", $id);

		$ids = ($pecah[3] == "")?0:$pecah[3];		
		$kueri = ($jns == 2)?$this->mnilai->getAllKeterampilan($pecah[1],$ids):$this->mnilai->getAllSikap($ids);
		if($jns == 2)
		{
			$links = ($pecah[3] == "")?'simpan_keterampilan':'update_keterampilan/'.$pecah[3];
		}
		else
		{
			$links = ($pecah[3] == "")?'simpan_sikap':'update_sikap/'.$pecah[3];
		}

		$data = array(			
			'tingkat'		=> $kode,
			'nis'			=> $pecah[0],
			'id_guru_mapel'	=> $pecah[1],
			'id_kelas'		=> $pecah[2],
			'tingkat'		=> $tingkat,
			'kode'			=> $id,
			'kueri'			=> $kueri,
			'links'			=> $links,
			'jns'			=> $jns
		);

		$this->load->view('fguru/formNilaine',$data);		
	}

	function simpan_sikap()
	{
		$kode = $this->input->post('kode');
		$pecah = explode("_", $kode);		

		$this->mnilai->addSikap();
		if($this->db->affected_rows() > 0)
		{
			$data = array(
				'status'	=> 'ok',
				'id'		=> $this->db->insert_id(),
				'kode'		=> $kode,
				'nilai'		=> $this->mnilai->getNilaiSikap($this->db->insert_id()),
				'newK'		=> $pecah[0]."_".$pecah[1]."_".$pecah[2]."_".$this->db->insert_id()."_".$pecah[4]
			);	

			echo json_encode($data);
		}
		else
		{
			$data = array(
				'status'	=> 'gagal',				
			);	

			echo json_encode($data);
		}
	}

	function simpan_keterampilan()
	{
		$kode = $this->input->post('kode');
		$pecah = explode("_", $kode);

		$input = $this->input->post('buka',TRUE);
		$this->mnilai->addKeterampilan($input);
		if($this->db->affected_rows() > 0)
		{
			$data = array(
				'status'	=> 'ok',
				'id'		=> $this->db->insert_id(),
				'kode'		=> $kode,
				'nilai'		=> $this->mnilai->getNilaiTrampil($this->db->insert_id()),
				'newK'		=> $pecah[0]."_".$pecah[1]."_".$pecah[2]."_".$this->db->insert_id()."_".$pecah[4]
			);	

			echo json_encode($data);
		}
		else
		{
			$data = array(
				'status'	=> 'gagal',				
			);	

			echo json_encode($data);
		}
	}

	function update_sikap($id)
	{
		$this->mnilai->updateSikap($id);
		if($this->db->affected_rows() > 0)
		{
			$data = array(
				'status'	=> 'ok',
				'id'		=> $id,
				'kode'		=> $this->input->post('kode'),
				'nilai'		=> $this->mnilai->getNilaiSikap($id)
			);	

			echo json_encode($data);
		}
		else
		{
			$data = array(
				'status'	=> 'gagal',				
			);	

			echo json_encode($data);
		}
	}	

	function update_keterampilan($id)
	{
		$input = $this->input->post('buka',TRUE);
		$this->mnilai->updateKeterampilan($id,$input);		
		if($this->db->affected_rows() > 0)
		{
			$data = array(
				'status'	=> 'ok',
				'id'		=> $id,
				'kode'		=> $this->input->post('kode'),
				'nilai'		=> $this->mnilai->getNilaiTrampil($id)
			);	

			echo json_encode($data);
		}
		else
		{
			$data = array(
				'status'	=> 'gagal',				
			);	

			echo json_encode($data);
		}
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */